<?php
/**
 * Breadcrumb functionality
 *
 * @package    Lib\Functions
 */

/**
* zoo_breadcrumbs
*/
if (!function_exists('zoo_breadcrumbs'))
{
    function zoo_breadcrumbs($home_title = '', $home_icon = '', $sep = '')
    {
        $breadcrumb = new Zoo_Breadcrumb($home_title, $home_icon, $sep);

        $breadcrumb->render($GLOBALS['wp_query']);
    }
}
