<?php
/**
 * Customize for General Style
 */
return [
    [
        'name' => 'zoo_style',
        'type' => 'panel',
        'label' => esc_html__('Style', 'anon'),
    ], [
        'name' => 'zoo_general_style',
        'type' => 'section',
        'label' => esc_html__('General Style', 'anon'),
        'panel' => 'zoo_style',
        'description' => esc_html__('Leave option blank if you want use default style of theme.', 'anon'),
    ],
    [
        'name' => 'zoo_general_style_heading_color',
        'type' => 'heading',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Color', 'anon'),
    ],
    [
        'name' => 'zoo_color_preset',
        'type' => 'select',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Preset', 'anon'),
        'description' => esc_html__('Predefined color scheme to Style', 'anon'),
        'default' => 'default',
        'choices' => [
            'default' => esc_html__('Default', 'anon'),
            'black' => esc_html__('Black', 'anon'),
            'blue' => esc_html__('Blue', 'anon'),
            'red' => esc_html__('Red', 'anon'),
            'yellow' => esc_html__('Yellow', 'anon'),
            'green' => esc_html__('Green', 'anon'),
            'grey' => esc_html__('Grey', 'anon'),
            'custom' => esc_html__('Custom', 'anon'),
        ]
    ],
    [
        'name' => 'zoo_primary_color',
        'type' => 'color',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Primary color', 'anon'),
        'selector' => ".accent-color",
        'css_format' => 'color: {{value}};',
        'description' => esc_html__('Primary color of theme apply only when preset is custom.', 'anon'),
        'required'=>['zoo_color_preset','==','custom']
    ],[
        'name' => 'zoo_site_color',
        'type' => 'color',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Text color', 'anon'),
        'selector' => "body",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_site_link_color',
        'type' => 'color',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Link color', 'anon'),
        'selector' => "a",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_site_link_color_hover',
        'type' => 'color',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Link color hover', 'anon'),
        'selector' => "a:hover",
        'css_format' => 'color: {{value}};',
    ],[
        'name' => 'zoo_site_heading_color',
        'type' => 'color',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Heading color', 'anon'),
        'selector' => "h1, h2, h3, h4, h5, h6",
        'css_format' => 'color: {{value}};',
    ],
    [
        'name' => 'zoo_general_heading_bg',
        'type' => 'heading',
        'section' => 'zoo_general_style',
        'title' => esc_html__('Background', 'anon'),
    ],
    [
        'name' => 'zoo_general_bg',
        'type' => 'styling',
        'section' => 'zoo_general_style',
        'title'  => esc_html__('Background', 'anon'),
        'selector' => [
            'normal' => "body",
        ],
        'field_class'=>'no-hide no-heading',
        'css_format' => 'styling', // styling
        'fields' => [
            'normal_fields' => [
                'text_color' => false,
                'link_color' => false,
                'link_hover_color' => false,
                'padding' => false,
                'box_shadow' => false,
                'border_radius' => false,
                'border_style' => false,
                'border_heading' => false,
                'bg_heading' => false,
                'margin' => false
            ],
            'hover_fields' => false
        ]
    ],
];
