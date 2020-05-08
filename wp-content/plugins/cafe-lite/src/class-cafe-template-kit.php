<?php namespace Cafe;

/**
 * TemplateKit
 */
final class TemplateKit
{
    /**
     * Default sources
     *
     * @var array
     */
    private $sources = [];

    /**
     * Constructor
     */
    private function __construct()
    {
        require CAFE_DIR . 'src/templatekit/sources/class-templatekit-source-base.php';
        require CAFE_DIR . 'src/templatekit/sources/class-templatekit-source-local.php';
        require CAFE_DIR . 'src/templatekit/sources/class-templatekit-source-remote.php';

        $this->sources = [
            'cafe-remote-template' => new CafeTemplatekitSourceRemote(),
            'cafe-local-template' => new CafeTemplatekitSourceLocal()
        ];

        add_action('wp_ajax_templatekit_get_templates', [$this, 'get_templates']);
        add_action('elementor/ajax/register_actions', [$this, 'register_ajax_actions'], 20);
    }

    /**
     * Get available template tabs
     */
    public function get_template_tabs()
    {
        return array(
			'page'    => array(
				'title'    => esc_html__('HomePages', 'cafe-lite'),
				'data'     => [],
				'sources'  => array('cafe-local-template', 'cafe-remote-template'),
				'settings' => array(
					'show_title'    => true,
					'show_keywords' => true,
				),
			),
			'subpage'    => array(
				'title'    => esc_html__('SubPages', 'cafe-lite'),
				'data'     => [],
				'sources'  => array('cafe-local-template', 'cafe-remote-template'),
				'settings' => array(
					'show_title'    => true,
					'show_keywords' => true,
				),
			),
			'section' => array(
				'title'    => esc_html__('Sections', 'cafe-lite'),
				'data'     => [],
				'sources'  => array('cafe-local-template', 'cafe-remote-template'),
				'settings' => array(
					'show_title'    => true,
					'show_keywords' => true,
				),
			),
		);
    }

    /**
     * Get source by slug
     *
     * @return bool|object
     */
    public function get_source($slug = null)
    {
        return isset($this->sources[$slug]) ? $this->sources[$slug] : false;
    }

    /**
     * Get templates
     *
     * @internal Used as a callback
     */
    public function get_templates()
    {
        if (!current_user_can('edit_posts')) {
            wp_send_json_error();
        }

        $tab     = $_GET['tab'];
        $tabs    = $this->get_template_tabs();
        $sources = $tabs[$tab]['sources'];

        $result = array(
            'templates'  => [],
            'categories' => [],
            'keywords'   => [],
        );

        foreach ($sources as $source_slug) {
            $source = isset($this->sources[$source_slug]) ? $this->sources[$source_slug] : false;
            if ($source) {
                $result['templates']  = array_merge($result['templates'], $source->get_items($tab));
                $result['categories'] = array_merge($result['categories'], $source->get_categories($tab));
                $result['keywords']   = array_merge($result['keywords'], $source->get_keywords($tab));
            }
        }

        $all_cats = array(
            array(
                'slug' => '',
                'title' => esc_html__('All', 'cafe-lite'),
            )
        );

        if (!empty($result['categories'])) {
            $result['categories'] = array_merge($all_cats, $result['categories']);
        }

        wp_send_json_success($result);
    }

    /**
     * Register AJAX actions
     *
     * @internal Used as a callback
     */
    public function register_ajax_actions($ajax)
    {
        if (! isset($_REQUEST['actions'])) {
            return;
        }

        $actions = json_decode(stripslashes($_REQUEST['actions']), true);
        $data    = false;

        foreach ($actions as $id => $action_data) {
            if (!isset($action_data['get_template_data'])) {
                $data = $action_data;
            }
        }

        if (! $data) {
            return;
        }

        if (! isset($data['data'])) {
            return;
        }

        if (! isset($data['data']['source'])) {
            return;
        }

        $source = $data['data']['source'];

        if (! isset($this->sources[ $source ])) {
            return;
        }

        $ajax->register_ajax_action('get_template_data', function ($data) {
            return $this->get_template_data_array($data);
        });
    }

    /**
     * Returns template data as an array
     */
    protected function get_template_data_array($data)
    {
        if (! current_user_can('edit_posts')) {
            return false;
        }

        if (empty($data['template_id'])) {
            return false;
        }

        $source_name = isset($data['source']) ? esc_attr($data['source']) : 'cafe-remote-template';

        if (! $source_name) {
            return false;
        }

        $source = isset($this->sources[ $source_name ]) ? $this->sources[ $source_name ] : false;

        if (! $source) {
            return false;
        }

        if (empty($data['tab'])) {
            return false;
        }

        $template = $source->get_item($data['template_id'], $data['tab']);

        return $template;
    }

    /**
     * Singleton
     */
    public static function get_instance($return = true)
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
        }

        return $return ? $self : null;
    }
}
TemplateKit::get_instance();
