<?php namespace Cafe;

/**
 * CafeTemplatekitSourceRemote
 */
final class CafeTemplatekitSourceRemote extends CafeTemplatekitSourceBase
{
    /**
     * @var string
     */
    const SLUG = 'cafe-remote-template';

    /**
     * @var array
     */
    private $_object_cache = [];

    /**
     * @return string
    */
    public function get_slug()
    {
        return self::SLUG;
    }

    /**
     * @return string
    */
    public function get_version()
    {
        $version = get_transient(self::SLUG . '_version');

        if (!$version) {
            $version = '1.0.0'; // Maybe retrieve this from a REST server
            set_transient(self::SLUG . '_version', $version, DAY_IN_SECONDS);
        }

        return $version;
    }

    /**
     * @return array
    */
    public function get_items($tab = null)
    {
        if (!$tab) {
            return [];
        }

        $cached = $this->get_templates_cache();

        if (!empty($cached[$tab])) {
            return array_values($cached[$tab]);
        }

        $templates = $this->remote_get_templates($tab);

        if (!$templates) {
            return [];
        }

        if (empty($cached)) {
            $cached = [];
        }

        $cached[$tab] = $templates;

        $this->set_templates_cache($cached);

        return $templates;
    }

    /**
     * @return array
     */
    public function prepare_items_tab($tab = '')
    {
        if (!empty($this->_object_cache[$tab])) {
            return $this->_object_cache[$tab];
        }

        $result = array(
            'templates'  => [],
            'categories' => [],
            'keywords'   => [],
        );

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

        $result['templates'] = $this->remote_get_templates($tab);
        $result['templates'] = $this->remote_get_categories($tab);
        $result['templates'] = $this->remote_get_keywords($tab);

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
     * Get templates from remote server
     *
     * @param string $tab
     * @return bool|array
     */
    public function remote_get_templates($tab)
    {
        $api_url = 'https://rest.cleveraddon.com/wp-json/cs/v2/elementor-templates/';

        $response = wp_remote_get($api_url . $tab, array(
            'timeout'   => 60,
            'sslverify' => false
        ));

        $response = wp_remote_retrieve_body($response);

        if (!$response) {
            return false;
        }

        $body = json_decode($response, true);

        if (!isset($body['success']) || true !== $body['success']) {
            return false;
        }

        if (empty($body['templates'])) {
            return false;
        }

        return $body['templates'];
    }

    /**
     * Get categories from remote server
     *
     * @param string $tab
     * @return bool|array
     */
    public function remote_get_categories($tab)
    {
        $api_url = 'https://rest.cleveraddon.com/wp-json/cs/v2/elementor-template-cats/';

        $response = wp_remote_get($api_url . $tab, array(
            'timeout'   => 60,
            'sslverify' => false
        ));

        $body = wp_remote_retrieve_body($response);

        if (!$body) {
            return false;
        }

        $body = json_decode($body, true);

        if (!isset($body['success']) || true !== $body['success']) {
            return false;
        }

        if (empty($body['terms'])) {
            return false;
        }

        return $body['terms'];
    }

    /**
     * @return array
     */
    public function remote_get_keywords($tab)
    {
        $api_url = 'https://rest.cleveraddon.com/wp-json/cs/v2/elementor-template-tags/';

        $response = wp_remote_get($api_url . $tab, array(
            'timeout'   => 60,
            'sslverify' => false
        ));

        $body = wp_remote_retrieve_body($response);

        if (!$body) {
            return false;
        }

        $body = json_decode($body, true);

        if (!isset($body['success']) || true !== $body['success']) {
            return false;
        }

        if (empty($body['terms'])) {
            return false;
        }

        return $body['terms'];
    }

    /**
     * @return array
    */
    public function get_categories($tab = null)
    {
        if (!$tab) {
            return [];
        }

        $cached = $this->get_categories_cache();

        if (!empty($cached[$tab])) {
            return $this->prepare_categories($cached[$tab]);
        }

        $categories = $this->remote_get_categories($tab);

        if (!$categories) {
            return [];
        }

        if (empty($cached)) {
            $cached = [];
        }

        $cached[$tab] = $categories;

        $this->set_categories_cache($cached);

        return $this->prepare_categories($categories);
    }

    /**
     * Prepare categories for response
     *
     * @return array
     */
    public function prepare_categories($categories)
    {
        $result = [];

        foreach ($categories as $slug => $title) {
            $result[] = array(
                'slug'  => $slug,
                'title' => $title,
            );
        }

        return $result;
    }

    /**
     * Return source item list
     *
     * @since 1.0.0
     * @access public
    */
    public function get_keywords($tab = null)
    {
        if (!$tab) {
            return [];
        }

        $cached = $this->get_keywords_cache();

        if (!empty($cached[$tab])) {
            return $cached[$tab];
        }

        $keywords = $this->remote_get_keywords($tab);

        if (!$keywords) {
            return [];
        }

        if (empty($cached)) {
            $cached = [];
        }

        $cached[$tab] = $keywords;

        $this->set_keywords_cache($cached);

        return $keywords;
    }

    /**
     * @return array
    */
    public function get_item($template_id, $tab = false)
    {
        $id = str_replace($this->id_prefix(), '', $template_id);

        if (!$tab) {
           $tab = isset($_REQUEST['tab']) ? esc_attr($_REQUEST['tab']) : false;
        }

        if (!$tab) {
            return [];
        }

        $api_url = 'https://rest.cleveraddon.com/wp-json/cs/v2/elementor-template/';

        if (!$api_url) {
            wp_send_json_success(array(
                'licenseError' => true,
            ));
        }

        $request = $api_url . $id;

        $response = wp_remote_get($request, array(
            'timeout'   => 60,
            'sslverify' => false
        ));

        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);

        if (!isset($body['success'])) {
            wp_send_json_error(array(
                'message' => 'Internal Error',
            ));
        }

        if (false === $body['success'] && false === $body['license']) {
            wp_send_json_success(array(
                'licenseError' => true,
            ));
        }

        if (false === $body['success'] && true === $body['license']) {
            wp_send_json_error(array(
                'message' => $body['message'],
            ));
        }

        $content       = isset($body['content']) ? $body['content'] : '';
        $type          = isset($body['type']) ? $body['type'] : '';
        $page_settings = isset($body['page_settings']) ? $body['page_settings'] : [];

        if (!empty($content)) {
            $content = $this->replace_elements_ids($content);
            $content = $this->process_export_import_content($content, 'on_import');
        }

        return array(
            'page_settings' => $page_settings,
            'type'          => $type,
            'content'       => $content,
        );
    }
}
