<?php
/**
 * Zoo_Demo_Importer
 *
 * @package  Zoo_Theme\Core\Admin\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 */
class Zoo_Demo_Importer
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if (!function_exists('post_exists')) {
            require ABSPATH . 'wp-admin/includes/post.php';
        }
        if (!function_exists('comment_exists')) {
            require ABSPATH . 'wp-admin/includes/comment.php';
        }
        if (!function_exists('wp_insert_category')) {
            require ABSPATH . 'wp-admin/includes/taxonomy.php';
        }
        if (!function_exists('wp_generate_attachment_metadata')) {
            require ABSPATH . 'wp-admin/includes/image.php';
        }
        if (!function_exists('wp_read_audio_metadata')) {
            require ABSPATH . 'wp-admin/includes/media.php';
        }
    }

    /**
     * Import content
     */
    public function importContent($content_file)
    {
        $wxr_importer = new Zoo_WXR_Importer();

        try {
            $imported = $wxr_importer->import($content_file);
        } catch (\Exception $e) {
            $imported = $e;
        }

        return $imported;
    }

    /**
     * Import widgets
     */
    public function importWidgets($file)
    {
        global $wp_filesystem, $wp_registered_widget_controls, $wp_registered_sidebars;

        $sidebars_widgets = [];
        $valid_sidebar = false;
        $widget_instances = [];
        $imported_content = (array)get_transient('_wxr_imported_content');

        WP_Filesystem();

        if (file_exists($file)) {
            $file_contents = $wp_filesystem->get_contents($file);
            $data = json_decode($file_contents, true);
            if (null === $data) {
                $data = maybe_unserialize($file_contents);
            }
        } else {
            $data = array();
        }

        foreach ($wp_registered_widget_controls as $widget_id => $widget) {
            $base_id = isset($widget['id_base']) ? $widget['id_base'] : null;
            if (!empty($base_id) && !isset($widget_instances[$base_id])) {
                $widget_instances[$base_id] = get_option('widget_' . $base_id);
            }
        }

        foreach ($data as $sidebar_id => $widgets) {
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }
            if (isset($wp_registered_sidebars[$sidebar_id])) {
                $valid_sidebar = true;
                $_sidebar_id = $sidebar_id;
            } else {
                $_sidebar_id = 'wp_inactive_widgets';
            }
            foreach ($widgets as $widget_instance_id => $widget) {
                if (false !== strpos($widget_instance_id, 'nav_menu') && !empty($widget['nav_menu'])) {
                    $widget['nav_menu'] = isset($imported_content['terms'][$widget['nav_menu']]) ? intval($imported_content['terms'][$widget['nav_menu']]) : 0;
                }
                $base_id = preg_replace('/-[0-9]+$/', '', $widget_instance_id);
                if (isset($widget_instances[$base_id])) {
                    $single_widget_instances = get_option('widget_' . $base_id);
                    $single_widget_instances = !empty($single_widget_instances) ? $single_widget_instances : array('_multiwidget' => 1);
                    $single_widget_instances[] = $widget;
                    end($single_widget_instances);
                    $new_instance_id_number = key($single_widget_instances);
                    if ('0' === strval($new_instance_id_number)) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset($single_widget_instances[0]);
                    }
                    if (isset($single_widget_instances['_multiwidget'])) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset($single_widget_instances['_multiwidget']);
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }
                    update_option('widget_' . $base_id, $single_widget_instances);
                    $sidebars_widgets[$_sidebar_id][] = $base_id . '-' . $new_instance_id_number;
                }
            }
        }

        update_option('sidebars_widgets', $sidebars_widgets);

        return true;
    }

    /**
     * Import revo sliders
     */
    public function importRevSliders($file)
    {
        if (!class_exists('RevSlider', false)) {
            return false;
        }

        $importer = new RevSlider();
        $response = $importer->importSliderFromPost(true, true, $file);

        return true;
    }

    /**
     * Import theme options
     */
    public function importThemeOptions($file)
    {
        global $wp_filesystem, $wp_customize;

        WP_Filesystem();

        if (is_dir(ZOO_THEME_DIR.'inc/customize/presets')) {
            $presets = new DirectoryIterator(ZOO_THEME_DIR.'inc/customize/presets');
            $saved_header_templates = $saved_footer_templates = [];
            foreach ($presets as $preset) {
                if ($preset->isFile()) {
                    $preset_name = $preset->getFilename();
                    if ('json' === $preset->getExtension()) {
                        $template_data = (array)json_decode($wp_filesystem->get_contents($preset->getPathname()), true);
                        if (false !== strpos($preset_name, 'header')) {
                            $saved_header_templates[rtrim($preset_name, '.json')] = $template_data;
                        } elseif (false !== strpos($preset_name, 'footer')) {
                            $saved_footer_templates[rtrim($preset_name, '.json')] = $template_data;
                        }
                    }
                }
            }
            if (!empty($saved_header_templates)) {
                update_option(get_option('template').'_header_saved_templates', $saved_header_templates);
            }
            if (!empty($saved_footer_templates)) {
                update_option(get_option('template').'_footer_saved_templates', $saved_footer_templates);
            }
        }

        if (file_exists($file)) {
            $file_contents = $wp_filesystem->get_contents($file);
            $customize_data = json_decode($file_contents, true);
            if (null === $customize_data) {
                $customize_data = maybe_unserialize($file_contents);
            }
        } else {
            $customize_data = [];
        }

        if (!empty($customize_data)) {
            $imported_content = (array)get_transient('_wxr_imported_content');
            if (!empty($customize_data['mods'])) {
                foreach ($customize_data['mods'] as $mod_key => $mod_value) {
                    if (is_string($mod_value) && preg_match('/\.(jpg|jpeg|png|gif)/i', $mod_value)) {
                        $attachment = $this->fetchCustomizeImage($mod_value);
                        if (!is_wp_error($attachment)) {
                            $mod_value = $attachment->url;
                            $index_key = $mod_key . '_data';
                            if (isset($customize_data['mods'][$index_key])) {
                                $customize_data['mods'][$index_key] = $attachment;
                                update_post_meta($attachment->attachment_id, '_wp_attachment_is_custom_header', get_option('stylesheet'));
                            }
                        }
                    }
                    if ('nav_menu_locations' === $mod_key) {
                        foreach ($mod_value as $menu_location => $menu_term_id) {
                            $mod_value[$menu_location] = isset($imported_content['terms'][$menu_term_id]) ? intval($imported_content['terms'][$menu_term_id]) : $menu_term_id;
                        }
                    }
                    if ('custom_logo' === $mod_key) {
                        $mod_value = $imported_content['posts'][(int)$mod_value];
                    }
                    if ('header_sticky_logo' === $mod_key) {
                        $mod_value['id'] = $imported_content['posts'][(int)$mod_value['id']];
                    }
                    set_theme_mod($mod_key, $mod_value);
                }
            }
            if (!empty($customize_data['options'])) {
                if (!class_exists('WP_Customize_Manager')) {
                    require ABSPATH . 'wp-includes/class-wp-customize-manager.php';
                }
                if (!$wp_customize instanceof WP_Customize_Manager) {
                    $wp_customize = new WP_Customize_Manager();
                }
                foreach ($customize_data['options'] as $option_key => $option_value) {
                    $option = new Zoo_Customize_Importer($wp_customize, $option_key, array('type' => 'option'));
                    $option->import($option_value);
                }
            }
        }

        return true;
    }

    /**
     * Import settings
     */
    public function importThemeSettings($base_path)
    {
        global $wp_filesystem;

        WP_Filesystem();

        if (file_exists($base_path . 'theme-settings.txt') && function_exists('zoo_base69_decode')) {
            $theme_settings = $wp_filesystem->get_contents($base_path . 'theme-settings.txt');
            if ($theme_settings) {
                $theme_settings = unserialize(zoo_base69_decode($theme_settings));
                update_option('zoo_theme_settings', $theme_settings, true);
            }
        }

        if (file_exists($base_path . 'sensei-settings.json') && function_exists('Sensei')) {
            $token = Sensei()->settings->token;
            $sensei_settings = $wp_filesystem->get_contents($base_path . 'sensei-settings.json');
            if ($sensei_settings) {
                $imported_content = (array)get_transient('_wxr_imported_content');
                $sensei_settings = json_decode($sensei_settings, true);
                if (isset($imported_content['posts'][$sensei_settings['course_page']])) {
                    $sensei_settings['course_page'] = intval($imported_content['posts'][$sensei_settings['course_page']]);
                }
                if (isset($imported_content['posts'][$sensei_settings['my_course_page']])) {
                    $sensei_settings['my_course_page'] = intval($imported_content['posts'][$sensei_settings['my_course_page']]);
                }
                update_option($token, $sensei_settings, true);
            }
        }

        return true;
    }


    /**
     * Sideload customizer image
     */
    protected function fetchCustomizeImage($file)
    {
        $data = new \stdClass();

        if (!function_exists('media_handle_sideload')) {
            require ABSPATH . 'wp-admin/includes/media.php';
            require ABSPATH . 'wp-admin/includes/file.php';
            require ABSPATH . 'wp-admin/includes/image.php';
        }

        if (!empty($file)) {
            preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches);
            $file_array = [];
            $file_array['name'] = basename($matches[0]);
            $file_array['tmp_name'] = download_url($file);
            if (is_wp_error($file_array['tmp_name'])) {
                return $file_array['tmp_name'];
            }
            $id = media_handle_sideload($file_array, 0);
            if (is_wp_error($id)) {
                unlink($file_array['tmp_name']);
                return $id;
            }
            $meta                = wp_get_attachment_metadata($id);
            $data->attachment_id = $id;
            $data->url           = wp_get_attachment_url($id);
            $data->thumbnail_url = wp_get_attachment_thumb_url($id);
            $data->height        = $meta['height'];
            $data->width         = $meta['width'];
        }

        return $data;
    }
}
