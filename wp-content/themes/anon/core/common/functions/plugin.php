<?php
/**
 * Plugin Functionality
 *
 * @package  Zoo Theme
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

/**
 * Check if a plugin is active
 *
 * !NOTICE: Haven't supported multisite yet.
 *
 * @param  string  $plugin  Path to the main plugin file from plugins directory.
 *
 * @return  bool
 */
function zoo_plugin__is_active($plugin)
{
    static $active_plugins = null;

    if (null === $active_plugins) {
        $active_plugins = (array)get_option('active_plugins', []);
    }

    return in_array($plugin, $active_plugins); // || is_plugin_active_for_network($plugin);
}
