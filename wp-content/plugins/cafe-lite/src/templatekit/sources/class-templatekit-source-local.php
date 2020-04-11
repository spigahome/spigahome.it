<?php namespace Cafe;

/**
 * CafeTemplatekitSourceLocal
 */
class CafeTemplatekitSourceLocal extends CafeTemplatekitSourceBase
{
    /**
     * @var string
     */
    const SLUG = 'cafe-local-template';

    /**
     * @var array
     */
    private $_object_cache = [];

    /**
     * @var array
     */
    private $config = [
		'version' => '1.0.0',
		'tabs'    => [
			'page' => CAFE_DIR . 'src/templatekit/templates/home-pages',
			'subpage' => CAFE_DIR . 'src/templatekit/templates/sub-pages',
			'section' => CAFE_DIR . 'src/templatekit/templates/sections'
        ],
		'keywords' => [],
	];

    /**
     * @return string
    */
    public function get_slug()
    {
        return self::SLUG;
    }

    /**
     * Return source version.
     *
     * @since 1.0.0
     * @access public
     */
    public function get_version()
    {
        return $this->config['version'];
    }

    /**
     * @return array
    */
    public function get_items($tab = null)
    {
        if (!$tab) {
            return [];
        }

        $items = $this->get_templates_cache();

        if (!empty($items[$tab])) {
            return array_values($items[$tab]);
        }

        $result = $this->prepare_items_tab($tab);

        return isset($result['templates']) ? array_values($result['templates']) : [];
    }

    /**
     * @return bool|array
     */
    public function prepare_items_tab($tab = '')
    {
        if (!empty($this->_object_cache[$tab])) {
            return $this->_object_cache[$tab];
        }

        $templates_path = isset($this->config['tabs'][$tab]) ? $this->config['tabs'][$tab] : false;

        if (!$templates_path) {
            return false;
        }

        $result = array(
            'templates'  => [],
            'categories' => [],
            'keywords'   => [],
        );

        $tpl_cats = glob(trailingslashit($templates_path) . '*', GLOB_ONLYDIR);

        if (!$tpl_cats) {
            return $result;
        }

        foreach ($tpl_cats as $category) {
            $cat_slug = basename($category);

            $result['categories'][$cat_slug] = [
                'slug'  => $cat_slug,
                'title' => ucwords(str_replace('-', ' ', $cat_slug))
            ];

            $templates = glob(trailingslashit($category) . '*', GLOB_ONLYDIR);

            if (!$templates) {
                continue;
            }

            foreach ($templates as $template) {
                $template_slug = basename($template);
                $template_url  = CAFE_URI . 'src/templatekit/templates/' . $tab . '/' . $cat_slug . '/' . $template_slug . '/';
                $template_data = get_file_data(
                    trailingslashit($template) . 'meta.txt',
                    array(
                        'title'    => 'Template Name',
                        'keywords' => 'Keywords',
                    )
                );

                if (file_exists($template . '/thumb.png')) {
                    $thumb = $template_url . 'thumb.png';
                } else {
                    $thumb = $template_url . 'thumb.jpg';
                }

                $preview = '';

                if (file_exists($template . '/preview.png')) {
                    $preview = $template_url . 'preview.png';
                }

                if (file_exists($template . '/preview.jpg')) {
                    $preview = $template_url . 'preview.jpg';
                }

                $template_id = sprintf('%1$s%2$s/%3$s', $this->id_prefix(), $cat_slug, $template_slug);

                $result['templates'][$template_slug] = array(
                    'categories'      => [$cat_slug],
                    'hasPageSettings' => false,
                    'source'          => $this->get_slug(),
                    'template_id'     => $template_id,
                    'thumbnail'       => $thumb,
                    'title'           => $template_data['title'],
                    'preview'         => $preview,
                    'type'            => $tab,
                    'keywords'        => $this->get_keywords_from_string($template_data['keywords']),
                );
            }
        }

        $config             = $this->config;
        $keywords           = isset($config['keywords']) ? $config['keywords'] : [];
        $result['keywords'] = $keywords;

        $templates_cache  = $this->get_templates_cache();
        $categories_cache = $this->get_categories_cache();
        $keywords_cache   = $this->get_keywords_cache();

        if (empty($templates_cache)) {
            $templates_cache = [];
        }

        if (empty($categories_cache)) {
            $categories_cache = [];
        }

        if (empty($keywords_cache)) {
            $keywords_cache = [];
        }

        $templates_cache[$tab]  = $result['templates'];
        $categories_cache[$tab] = $result['categories'];
        $keywords_cache[$tab]   = $result['keywords'];

        $this->set_templates_cache($templates_cache);
        $this->set_categories_cache($categories_cache);
        $this->set_keywords_cache($keywords_cache);

        $this->_object_cache[$tab] = $result;

        return $result;
    }

    /**
     * @return array
     */
    public function get_keywords_from_string($string)
    {
        if (empty($string)) {
            return $string;
        }

        $string = str_replace(' ', '', $string);
        $array  = explode(',', $string);

        if (empty($array)) {
            return [];
        }

        return $array;
    }

    /**
     * @return array
    */
    public function get_categories($tab = '')
    {
        if (!$tab) {
            return [];
        }

        $categories = $this->get_categories_cache();

        if (!empty($categories[$tab])) {
            return array_values($categories[$tab]);
        }

        $result = $this->prepare_items_tab($tab);

        return isset($result['categories']) ? array_values($result['categories']) : [];
    }

    /**
     * @return array
    */
    public function get_keywords($tab = '')
    {
        if (!$tab) {
            return [];
        }

        $keywords = $this->get_keywords_cache();

        if (!empty($keywords[$tab])) {
            return $keywords[$tab];
        }

        $result = $this->prepare_items_tab($tab);

        return isset($result['keywords']) ? $result['keywords'] : [];
    }

    /**
     * @return array
    */
    public function get_item($template_id,$tab= false)
    {
        $id = str_replace($this->id_prefix(), '', $template_id);

        if (!$tab) {
           $tab= isset($_REQUEST['tab']) ? esc_attr($_REQUEST['tab']) : false;
        }

        if (!$tab) {
            return [];
        }

        $templates_path = isset($this->config['tabs'][$tab]) ? $this->config['tabs'][$tab] : false;

        if (!$templates_path) {
            return [];
        }

        $template_dir  = trailingslashit($templates_path) . $id;
        $template_file = trailingslashit($template_dir) . 'content.json';

        if (!file_exists($template_file)) {
            return [];
        }

        ob_start();
        include $template_file;
        $content = ob_get_clean();
        $content = json_decode($content, true);

        if (!empty($content)) {
            $content = $this->replace_elements_ids($content);
            $content = $this->process_export_import_content($content, 'on_import');
        }

        return array(
            'page_settings' => [],
            'type'          => $tab,
            'content'       => $content,
        );
    }
}
