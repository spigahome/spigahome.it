<?php
/**
 * Media Functionality
 *
 * @package  Zoo_Theme\Core\Common\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Get all the registered image sizes along with their dimensions
 *
 * @global  array  $_wp_additional_image_sizes
 *
 * @link  http://core.trac.wordpress.org/ticket/18947 Reference ticket
 *
 * @return  array $image_sizes The image sizes
 */
function zoo_get_available_image_sizes()
{
    global $_wp_additional_image_sizes;
    $default_image_sizes = array('thumbnail', 'medium', 'large');

    foreach ($default_image_sizes as $size) {
        $image_sizes[$size]['width'] = intval(get_option("{$size}_size_w"));
        $image_sizes[$size]['height'] = intval(get_option("{$size}_size_h"));
        $image_sizes[$size]['crop'] = get_option("{$size}_crop") ? get_option("{$size}_crop") : false;
    }

    if (isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes)) {
        $image_sizes = array_merge($image_sizes, $_wp_additional_image_sizes);
    }

    $options = array();
    foreach( $image_sizes as $k => $option ) {
        $options[ $k ] = sprintf( '%1$s - (%2$s x %3$s)', $k, $option['width'], $option['height'] );
    }

    $options[ 'full' ] = 'Full';
    return $options;
}
