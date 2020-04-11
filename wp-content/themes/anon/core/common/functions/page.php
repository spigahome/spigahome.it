<?php
/**
 * Page Functionality
 *
 * @package  Zoo_Theme\Core\Common\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * List pages by slug
 *
 * @return  array
 */
function zoo_list_pages_by_slug()
{
    static $slugs = null;

    if (null === $slugs) {
        $slugs = [];
    } else {
        return $slugs;
    }

    $pages = get_pages();

    if (!$pages) {
        return $slugs;
    }

    foreach ($pages as $page) {
        $slugs[$page->post_name] = $page->post_title;
    }

    return $slugs;
}

/**
 * Get current page ID
 *
 * @return  int
 */
function zoo_get_current_page_id()
{
    global $wp_query;

    if (!$wp_query->is_main_query()) {
        return 0;
    }

    if ($wp_query->is_home() && $wp_query->is_front_page()) {
        return 0;
    } elseif ($wp_query->is_home() && !$wp_query->is_front_page()) {
        return (int)get_option('page_for_posts');
    } elseif (!$wp_query->is_home() && $wp_query->is_front_page()) {
        return (int)get_option('page_on_front');
    } elseif (function_exists('is_shop') && is_shop()) {
        return wc_get_page_id('shop');
    } elseif (isset($wp_query->post->ID)) {
        return (int)$wp_query->post->ID;
    } else {
        return 0;
    }
}
