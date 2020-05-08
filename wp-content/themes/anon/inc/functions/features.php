<?php
/**
 * Register theme Features
 *
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 * @des         All features of theme will register here. Dev can add or remove features.
 */
if (!function_exists('zoo_theme_setup')) :
    function zoo_theme_setup()
    {
        load_theme_textdomain('anon', get_template_directory() . '/languages');

        add_theme_support('title-tag');

        add_theme_support('cs-font');

        add_theme_support('post-thumbnails');

        add_theme_support('automatic-feed-links');

        add_theme_support('customize-selective-refresh-widgets');

        add_theme_support('post-formats', array('aside', 'gallery', 'image', 'quote', 'video', 'audio'));

        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

        add_theme_support('advanced-image-compression');

        //Add site support for customize
        add_theme_support('custom-logo', array(
            'height' => 100,
            'width' => 400,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('site-title', 'site-description'),
        ));
        add_theme_support("custom-header");
        add_theme_support("custom-background");
        add_editor_style();
        if (!isset($GLOBALS['content_width'])) $GLOBALS['content_width'] = 992;
        if (class_exists('WooCommerce', false)) {
            add_theme_support('woocommerce');
            add_theme_support( 'woocommerce', array(
                'gallery_thumbnail_image_width' => get_theme_mod('zoo_gallery_thumbnail_width','120'),
            ) );
        }
    }
endif;
add_action('after_setup_theme', 'zoo_theme_setup');
