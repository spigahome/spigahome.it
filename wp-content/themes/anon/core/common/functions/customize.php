<?php
/**
 * Customize Item Config Utilities
 *
 * @package  Zoo_Theme\Core\Customize\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Get all configs
 *
 * @param  object  $wp_customize  WP_Customize_Manager
 *
 * @return  array  $configs  All registered customize configs.
 */
function zoo_customize_get_all_config(WP_Customize_Manager $wp_customize = null)
{
    static $configs = null;

    if (null === $configs) {
        $filtered_configs = apply_filters('zoo/customizer/config', [], $wp_customize);
        if (file_exists(ZOO_THEME_DIR.'inc/customize/customize.php')) {
            $theme_configs = require ZOO_THEME_DIR.'inc/customize/customize.php';
            foreach ($theme_configs as $extra_configs) {
                $filtered_configs = array_merge($filtered_configs, $extra_configs);
            }
        }
        foreach ($filtered_configs as $config) {
            $config = array_merge([
                'priority'             => null,
                'title'                => null,
                'label'                => null,
                'name'                 => null,
                'type'                 => null,
                'description'          => null,
                'capability'           => null,
                'mod'                  => null,
                'settings'             => null,
                'active_callback'      => null,
                'sanitize_callback'    => 'Zoo_Customizer::sanitize',
                'sanitize_js_callback' => null,
                'theme_supports'       => null,
                'default'              => null,
                'selector'             => null,
                'render_callback'      => null,
                'css_format'           => null,
                'device'               => null,
                'device_settings'      => null,
                'field_class'          => null
            ], $config);

            if (!isset($config['type'])) {
                $config['type'] = null;
            }

            if (null === $config['device_settings']) {
                $config['device_settings'] = false;
            }

            switch ($config['type']) {
                case 'panel':
                    $configs['panel|'.$config['name']] = $config;
                    break;
                case 'section':
                    $configs['section|'.$config['name']] = $config;
                    break;
                default:
                    $configs['setting|'.$config['name']] = $config;
            }
        }
    }

    return $configs;
}

/**
 * Get panel config
 *
 * @param  string  $panel_id
 *
 * @return  array
 */
function zoo_customize_get_panel_setting($panel_id)
{
    $options = array_merge([
        'desktop' => '',
        'mobile'  => '',
    ], (array)zoo_customize_get_setting($panel_id, 'all'));

    foreach ($options as $k => $v) {
        $options[$k] = is_array($v) ? $v : [];
    }

    return $options;
}

/**
 * Get setting
 *
 * @param  string  $name          Config name.
 * @param  string  $key           Maybe an index for an array-value config.
 * @param  bool    $default_only  Get only default value or not.
 *
 * @return  mixed
 */
function zoo_customize_get_setting($name, $device = 'desktop', $key = '', $default_only = false)
{
    $get_value = $value = null;
    $page_id   = zoo_get_current_page_id();
    $configs   = zoo_customize_get_all_config();
    $default = isset($configs['setting|' . $name]['default']) ? $configs['setting|' . $name]['default'] : null;

    if ($default_only) {
        return $default;
    }

    if (null === $value && isset($configs['setting|'.$name])) {
         $value = get_theme_mod($name, $default);
        if (!$configs['setting|'.$name]['device_settings']) {
            return $value;
        }
    }

    if (null === $value) {
        $value = get_theme_mod($name, $default);
    }

    if (!$key) {
        if ($device != 'all') {
            if (is_array($value) && isset($value[$device])) {
                $get_value = $value[$device];
            } else {
                $get_value = $value;
            }
        } else {
            $get_value = $value;
        }
    } else {
        $value_by_key = isset($value[$key]) ? $value[$key] : false;
        if ($device != 'all' && is_array($value_by_key)) {
            if (is_array($value_by_key) && isset($value_by_key[$device])) {
                $get_value = $value_by_key[$device];
            } else {
                $get_value = $value_by_key;
            }
        } else {
            $get_value = $value_by_key;
        }
    }

    return $get_value;
}

/**
 * Get attachment url from data
 *
 * @param  string|array  $value  media data
 * @param  string  $size  WordPress image size name
 *
 * @return array|false  Media url or empty
 */
function zoo_customize_get_attachment($value, $size = 'full')
{
    if (empty($value)) {
        return false;
    }

    if (is_numeric($value)) {
        $image_attributes = wp_get_attachment_image_src($value, $size);
        if ($image_attributes) {
            return $image_attributes[0];
        } else {
            return false;
        }
    } elseif (is_string($value)) {
        $img_id = attachment_url_to_postid($value);
        if ($img_id) {
            $image_attributes = wp_get_attachment_image_src($img_id, $size);
            if ($image_attributes) {
                return $image_attributes[0];
            } else {
                return false;
            }
        }
        return $value;
    } elseif (is_array($value)) {
        $value = array_merge([
            'id'   => '',
            'url'  => '',
            'mime' => ''
        ], $value);
        if (empty($value['id']) && empty($value['url'])) {
            return false;
        }
        $url = '';
        if (strpos($value['mime'], 'image/') !== false) {
            $image_attributes = wp_get_attachment_image_src($value['id'], $size);
            if ($image_attributes) {
                $url = $image_attributes[0];
            }
        } else {
            $url = wp_get_attachment_url($value['id']);
        }
        if (!$url) {
            $url = $value['url'];
            if ($url) {
                $img_id = attachment_url_to_postid($url);
                if ($img_id) {
                    return wp_get_attachment_url($img_id);
                }
            }
        }
        return $url;
    }

    return false;
}
