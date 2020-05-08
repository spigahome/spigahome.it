<?php
/**
 * Admin Menu Functionality
 *
 * @package  Zoo_Theme\Core\Admin\Functions
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Mimic add_menu_page
 *
 * @see  https://developer.wordpress.org/reference/functions/add_menu_page/
 */
function zoo_add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null)
{
    global $menu, $admin_page_hooks, $_registered_pages, $_parent_pages;

    $menu_slug = plugin_basename($menu_slug);

    $admin_page_hooks[$menu_slug] = sanitize_title($menu_title);

    $hookname = get_plugin_page_hookname($menu_slug, '');

    if (!empty($function) && !empty($hookname) && current_user_can($capability)) {
        add_action($hookname, $function);
    }

    if (empty($icon_url)) {
        $icon_url = 'dashicons-admin-generic';
        $icon_class = 'menu-icon-generic ';
    } else {
        $icon_url = set_url_scheme($icon_url);
        $icon_class = '';
    }

    $new_menu = array( $menu_title, $capability, $menu_slug, $page_title, 'menu-top ' . $icon_class . $hookname, $hookname, $icon_url );

    if (null === $position) {
        $menu[] = $new_menu;
    } elseif (isset($menu[ "$position" ])) {
        $position = $position + substr(base_convert(md5($menu_slug . $menu_title), 16, 10), -5) * 0.00001;
        $menu[ "$position" ] = $new_menu;
    } else {
        $menu[ $position ] = $new_menu;
    }

    $_registered_pages[$hookname] = true;

    // No parent as top level
    $_parent_pages[$menu_slug] = false;

    return $hookname;
}


/**
 * Mimic add_submenu_page.
 *
 * @see  https://developer.wordpress.org/reference/functions/add_submenu_page/
 */
function zoo_add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '')
{
    global $submenu, $menu, $_wp_real_parent_file, $_wp_submenu_nopriv, $_registered_pages, $_parent_pages;

    $menu_slug = plugin_basename($menu_slug);
    $parent_slug = plugin_basename($parent_slug);

    if (isset($_wp_real_parent_file[$parent_slug])) {
        $parent_slug = $_wp_real_parent_file[$parent_slug];
    }

    if (!current_user_can($capability)) {
        $_wp_submenu_nopriv[$parent_slug][$menu_slug] = true;
        return false;
    }

    /*
     * If the parent doesn't already have a submenu, add a link to the parent
     * as the first item in the submenu. If the submenu file is the same as the
     * parent file someone is trying to link back to the parent manually. In
     * this case, don't automatically add a link back to avoid duplication.
     */
    if (!isset($submenu[$parent_slug]) && $menu_slug != $parent_slug) {
        foreach ((array)$menu as $parent_menu) {
            if ($parent_menu[2] == $parent_slug && current_user_can($parent_menu[1])) {
                $submenu[$parent_slug][] = array_slice($parent_menu, 0, 4);
            }
        }
    }

    $submenu[$parent_slug][] = array( $menu_title, $capability, $menu_slug, $page_title );

    $hookname = get_plugin_page_hookname($menu_slug, $parent_slug);
    if (!empty($function) && !empty($hookname)) {
        add_action($hookname, $function);
    }

    $_registered_pages[$hookname] = true;

    /*
     * Backward-compatibility for plugins using add_management page.
     * See wp-admin/admin.php for redirect from edit.php to tools.php
     */
    if ('tools.php' == $parent_slug) {
        $_registered_pages[get_plugin_page_hookname($menu_slug, 'edit.php')] = true;
    }

    // No parent as top level.
    $_parent_pages[$menu_slug] = $parent_slug;

    return $hookname;
}
