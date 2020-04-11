<?php
/**
 * Theme Common Functions
 *
 * @package  Zoo Theme
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * @return bool
 */
function zoo_is_elementor_theme_builder()
{
    static $exists = null;

    if (null === $exists) {
        $exists = function_exists('elementor_theme_do_location');
    }

    return $exists;
}

/**
 * Get current parent theme object
 *
 * @param  string  $template  Value of the `template` option in the options table.
 *
 * @return  object
 */
function zoo_get_parent_theme_object($template = null)
{
    static $theme = null;

    if (null === $theme)
        $theme = wp_get_theme($template ? : get_option('template'));

    return $theme;
}

/**
 * Get current child theme object
 *
 * @param  string  $stylesheet  Value of the `stylesheet` option in the options table.
 *
 * @return  object
 */
function zoo_get_child_theme_object($stylesheet = null)
{
    static $theme = null;

    if (null === $theme)
        $theme = wp_get_theme($stylesheet ? : get_option('stylesheet'));

    return $theme;
}
