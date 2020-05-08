<?php
/**
 * Register theme sidebars
 *
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 * @des         All sidebar of theme register at here. Add/remove sidebars as you want.
 */

add_action('widgets_init', function () {
    register_sidebar(array(
        'name' => esc_html__('Blog Sidebar', 'anon'),
        'id' => 'sidebar',
        'description' => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'anon'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    register_sidebar(array(
        'name' => esc_html__('Blog Sidebar 2', 'anon'),
        'id' => 'sidebar-2',
        'description' => esc_html__('Secondary sidebar of blog page. User can set location display of this sidebar in Customize -> Blog -> Blog Archive -> Sidebar', 'anon'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
    if (class_exists('WooCommerce', false)) {
        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'anon'),
            'id' => 'shop',
            'description' => esc_html__('Add widgets here to appear in your sidebar of shop page.', 'anon'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Shop Top Sidebar', 'anon'),
            'id' => 'top-shop',
            'description' => esc_html__('Add widgets here to appear in your sidebar of top shop page.', 'anon'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ));
    } else {
        unregister_sidebar('shop');
        unregister_sidebar('top-shop');
    }
},10,0);