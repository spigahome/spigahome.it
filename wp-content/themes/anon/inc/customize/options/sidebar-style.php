<?php
/**
 * Customize for General Style
 */
return [ 
    [
        'name' => 'zoo_sidebar_style',
        'type' => 'section',
        'label' => esc_html__('Sidebar Style', 'anon'),
        'panel' => 'zoo_style',
        'description' => esc_html__('Leave option blank if you want use default style of theme.', 'anon'),
    ],
    [
        'name' => 'zoo_sidebar_heading_color',
        'type' => 'heading',
        'section' => 'zoo_sidebar_style',
        'title' => esc_html__('Color', 'anon'),
    ],
    [
        'name' => 'zoo_sidebar_title_color',
        'type' => 'color',
        'section' => 'zoo_sidebar_style',
        'title' => esc_html__('Title Sidebar Color', 'anon'),
        'selector' => ".sidebar .widget-title",
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_sidebar_color',
        'type' => 'color',
        'section' => 'zoo_sidebar_style',
        'title' => esc_html__('Text color', 'anon'),
        'selector' => ".sidebar",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_sidebar_link_color',
        'type' => 'color',
        'section' => 'zoo_sidebar_style',
        'title' => esc_html__('Link color', 'anon'),
        'selector' => ".sidebar a, .widget > ul li a",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_sidebar_link_color_hover',
        'type' => 'color',
        'section' => 'zoo_sidebar_style',
        'title' => esc_html__('Link color hover', 'anon'),
        'selector' => ".sidebar a:hover, .widget > ul li a:hover",
        'css_format' => 'color: {{value}};',
    ],
];
