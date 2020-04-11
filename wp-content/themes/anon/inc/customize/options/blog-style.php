<?php
/**
 * Customize for General Style
 */
return [ 
    [
        'name' => 'zoo_blog_style',
        'type' => 'section',
        'label' => esc_html__('Blog Style', 'anon'),
        'panel' => 'zoo_style',
    ],
    [
        'name' => 'zoo_blog_archive_heading_color',
        'type' => 'heading',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Blog Archive', 'anon'),
    ],
    [
        'name' => 'zoo_blog_title_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Title Color', 'anon'),
        'selector' => ".post-loop-item .entry-title",
        'css_format' => 'color: {{value}};',
    ],    [
        'name' => 'zoo_site_title_color_hover',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Title Color Hover', 'anon'),
        'selector' => ".post-loop-item .entry-title:hover",
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_blog_date_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Post date color', 'anon'),
        'selector' => ".post-loop-item .post-date",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_excerpt_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Excerpt color', 'anon'),
        'selector' => ".post-loop-item .entry-content",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_readmore_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Read More color', 'anon'),
        'selector' => ".post-loop-item .readmore",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_readmore_color_hover',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Link color hover', 'anon'),
        'selector' => ".post-loop-item .readmore:hover",
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_blog_single_heading_color',
        'type' => 'heading',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Blog Single', 'anon'),
    ],
    [
        'name' => 'zoo_blog_single_title_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Title post', 'anon'),
        'selector' => ".post-loop-item .readmore:hover",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_single_info_color',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Post Information', 'anon'),
        'description' => esc_html__('Color of block date post, categories, author.', 'anon'),
        'selector' => "post-detail .post-info",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_blog_single_info_color_hover',
        'type' => 'color',
        'section' => 'zoo_blog_style',
        'title' => esc_html__('Post Information Link hover', 'anon'),
        'description' => esc_html__('Color hover link of block date post, categories, author.', 'anon'),
        'selector' => ".post-detail .post-info a:hover",
        'css_format' => 'color: {{value}};',
    ],
];
