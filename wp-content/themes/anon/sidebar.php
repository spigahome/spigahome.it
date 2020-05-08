<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

if (is_active_sidebar(get_theme_mod('zoo_blog_sidebar', 'sidebar')) && get_theme_mod('zoo_blog_sidebar_config', 'right') != '') {
    ?>
    <aside id="sidebar"
           class="sidebar widget-area col-12 col-md-3">
        <?php dynamic_sidebar(get_theme_mod('zoo_blog_sidebar', 'sidebar')); ?>
    </aside>
<?php }