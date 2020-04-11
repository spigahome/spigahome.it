<?php
/**
 * Navigation Menu Functionality
 */


/**
 * @return object|string
 */
function zoo_get_walker_nav_menu()
{
    $theme_settings = get_option(ZOO_SETTINGS_KEY, true);

    if (!empty($theme_settings['enable_builtin_mega_menu'])) {
        return new Zoo_Mega_Menu_Walker();
    }

    return '';
}
