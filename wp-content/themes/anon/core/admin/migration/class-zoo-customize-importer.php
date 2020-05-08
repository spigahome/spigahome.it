<?php
/**
 * Zoo_Customize_Importer
 *
 * @package  Zoo_Theme\Core\Admin\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 */

if (!class_exists('WP_Customize_Setting', false)) {
    require ABSPATH.'wp-includes/class-wp-customize-setting.php';
}

class Zoo_Customize_Importer extends WP_Customize_Setting
{
    /**
     * Do import
     */
    function import($value)
    {
        return $this->update($value);
    }

    /**
     * Handle attachments
     *
     * @param  array  $mods  Importing thememods.
     */
    protected function migrateAttachments(array $mods)
    {
        foreach ($mods as $key => $mod) {
            $mod = (string)$mod;
            if (!preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $mod, $matches)) continue;
            $data = $this->sideLoadAttachment($mod);
            if (!is_wp_error($data)) {
                $mods[$key] = $data->url;
                if (isset($mods[$key.'_data'])) {
                    $mods[$key.'_data'] = $data;
                    update_post_meta($data->attachment_id, '_wp_attachment_is_custom_header', ZOO_CHILD_THEME_SLUG);
                }
            }
        }

        return $mods;
    }

    /**
     * Sideload customizer image
     *
     * @param  string  $file  Attachment file.
     */
    protected function sideLoadAttachment($file)
    {
        $data = new \stdClass();

        if (!function_exists('media_handle_sideload')) {
            require ABSPATH.'wp-admin/includes/media.php';
            require ABSPATH.'wp-admin/includes/file.php';
            require ABSPATH.'wp-admin/includes/image.php';
        }

        if (!empty($file)) {
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
            $meta = wp_get_attachment_metadata($id);
            $data->attachment_id = $id;
            $data->url           = wp_get_attachment_url($id);
            $data->thumbnail_url = wp_get_attachment_thumb_url($id);
            $data->height        = $meta['height'];
            $data->width         = $meta['width'];
        }

        return $data;
    }
}
