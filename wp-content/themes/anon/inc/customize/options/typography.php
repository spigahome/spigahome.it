<?php
/**
 * Typography for theme
 * @core: 3.0
 * @version : 1.0.0
 * @package : zootemplate
 */
return[
    [
        'name' => 'zoo_typography',
        'type' => 'section',
        'label' => esc_html__('Typography', 'anon'),
        'panel' => 'zoo_style',
    ],

    array(
        'name'  => 'zoo_use_font',
        'type'  => 'select',
        'label' => esc_html__('Choose Use Font', 'anon'),
        'section' => 'zoo_typography',
        'default' => 'google',
        'choices' => [
            'google'        => esc_html__( 'Google Font', 'anon' ),
            'custom'        => esc_html__( 'Custom Font', 'anon' ),
        ]
    ),
    //Add new font
    [
        'name' => 'zoo_typo_new_font_heading',
        'type' => 'heading',
        'label' => esc_html__('Add New Font', 'anon'),
        'section' => 'zoo_typography',
        'required'=>['zoo_use_font','==','custom']
    ],
    [
        'name'        => 'zoo_typo_new_font_family',
        'type'        => 'text',
        'section'     => 'zoo_typography',
        'title'       => esc_html__('Add Family Name', 'anon'),
        'description' => esc_html__('Ex: Roboto, Larsseit...', 'anon'),
        'required'=>['zoo_use_font','==','custom']
    ],
    [
        'name' => 'zoo_typo_cf_body_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'html,body',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('Body Font Size', 'anon'),
        'required'=>['zoo_use_font','==','custom']
    ],
    [
        'name'             => 'zoo_font_items',
        'type'             => 'repeater',
        'section'          => 'zoo_typography',
        'title'            => esc_html__('Add Font', 'anon'),
        'live_title_field' => 'font',
        'default'          => array(
            array(
                'weight' =>'normal',
                'style' =>'normal',
                'woff' =>'#',
                'woff2' =>'#',
                'ttf' =>'#',
                'svg' =>'#',
            ),
        ),
        'fields'           => array(
            array(
                'name'  => 'weight',
                'type'  => 'select',
                'label' => esc_html__('Weight', 'anon'),
                'default' => 'normal',
                'choices' => [
                    'normal'        => esc_html__( 'Normal', 'anon' ),
                    'bold'        => esc_html__( 'Bold', 'anon' ),
                    '100'       => esc_html__( '100', 'anon' ),
                    '200'       => esc_html__( '200', 'anon' ),
                    '300'       => esc_html__( '300', 'anon' ),
                    '400'       => esc_html__( '400', 'anon' ),
                    '500'       => esc_html__( '500', 'anon' ),
                    '600'       => esc_html__( '600', 'anon' ),
                    '700'       => esc_html__( '700', 'anon' ),
                    '800'       => esc_html__( '800', 'anon' ),
                    '900'       => esc_html__( '900', 'anon' ),
                ]
            ),
            array(
                'name'  => 'style',
                'type'  => 'select',
                'label' => esc_html__('Style', 'anon'),
                'default' => 'normal',
                'choices' => [
                    'normal'        => esc_html__( 'Normal', 'anon' ),
                    'italic'        => esc_html__( 'Italic', 'anon' ),
                    'oblique'        => esc_html__( 'Oblique', 'anon' ),
                ]
            ),

            array(
                'name'  => 'woff',
                'type'  => 'text',
                'label' => esc_html__('URL WOFF File', 'anon'),
            ),
            array(
                'name'  => 'woff2',
                'type'  => 'text',
                'label' => esc_html__('URL WOFF2 File', 'anon'),
            ),
            array(
                'name'  => 'ttf',
                'type'  => 'text',
                'label' => esc_html__('URL TTF File', 'anon'),
            ),
            array(
                'name'  => 'svg',
                'type'  => 'text',
                'label' => esc_html__('URL SVG File', 'anon'),
            ),

        ),
        'required'=>['zoo_use_font','==','custom']
    ],
    // Google font
    [
        'name' => 'zoo_typo_base_heading',
        'type' => 'heading',
        'label' => esc_html__('Base', 'anon'),
        'section' => 'zoo_typography',
        'required'=>['zoo_use_font','==','google']
    ],
    [
        'name'        => 'zoo_typo_base',
        'type'        => 'typography',
        'section'     => 'zoo_typography',
        'title'       => esc_html__('Base Typography', 'anon'),
        'description' => esc_html__('Typography for site', 'anon'),
        'selector'    => "body",
        'css_format'  => 'typography',
        'field_class'=>'no-hide',
        'fields' => array(
            'style' => false,
            'text_decoration' => false,
            'text_transform' => false,
        ),
        'required'=>['zoo_use_font','==','google']
    ],
    [
        'name' => 'zoo_typo_heading_heading',
        'type' => 'heading',
        'label' => esc_html__('Heading & Title', 'anon'),
        'section' => 'zoo_typography',
    ],
    [
        'name'        => 'zoo_typo_heading',
        'type'        => 'typography',
        'section'     => 'zoo_typography',
        'title'       => esc_html__('Heading & Title Typography', 'anon'),
        'description' => esc_html__('Typography for heading', 'anon'),
        'selector'    => "h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6",
        'css_format'  => 'typography',
        'field_class'=>'no-hide',
        'fields' => array(
            'style' => false,
            'text_decoration' => false,
            'text_transform' => false,
            'font_size' => false,
        ),
        'required'=>['zoo_use_font','==','google']
    ],

    [
        'name' => 'zoo_typo_h1_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'h1,.h1',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('H1 Font Size', 'anon'),
    ],[
        'name' => 'zoo_typo_h2_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'h2,.h2',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('H2 Font Size', 'anon'),
    ],[
        'name' => 'zoo_typo_h3_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'h3,.h3',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('H3 Font Size', 'anon'),
    ],[
        'name' => 'zoo_typo_h4_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'h4,.h4',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('H4 Font Size', 'anon'),
    ],[
        'name' => 'zoo_typo_h5_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'h5,.h5',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('H5 Font Size', 'anon'),
    ],[
        'name' => 'zoo_typo_h6_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => 'h6,.h6',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('H6 Font Size', 'anon'),
    ],[
        'name' => 'zoo_typo_title_loop_blog_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => '.post-loop-item .entry-title',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('Post Loop Title Font Size', 'anon'),
        'description' => esc_html__('Font size title of post in loop', 'anon'),
    ],[
        'name' => 'zoo_typo_title_single_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => '.title-detail',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('Post Title Font Size', 'anon'),
        'description' => esc_html__('Font size title of single post', 'anon'),
    ],[
        'name' => 'zoo_typo_widget_title_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector'  => '.widget .widget-title',
        'css_format' => 'font-size: {{value}};',
        'label' => esc_html__('Widget Title Font Size', 'anon'),
        'description' => esc_html__('Font size title of widget', 'anon'),
    ],
    [
        'name' => 'zoo_typo_woo_heading',
        'type' => 'heading',
        'label' => esc_html__('WooCommerce Typography', 'anon'),
        'section' => 'zoo_typography',
    ],
    [
        'name'        => 'zoo_typo_woo',
        'type'        => 'typography',
        'section'     => 'zoo_typography',
        'title'       => esc_html__('WooCommerce Title Typography', 'anon'),
        'description' => esc_html__('Typography for title product', 'anon'),
        'selector'    => ".product-loop-title,  .product_title",
        'css_format'  => 'typography',
        'field_class'=>'no-hide',
        'fields' => array(
            'style' => false,
            'text_decoration' => false,
            'text_transform' => false,
            'font_size' => false,
        ),
        'required'=>['zoo_use_font','==','google']
    ],
    [
        'name' => 'zoo_typo_product_loop_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'selector' => ".woocommerce ul.products li.product h3.product-loop-title a, .woocommerce ul.products li.product .woocommerce-loop-category__title a",
        'css_format' => 'font-size: {{value}};',
        'theme_supports' => 'woocommerce',
        'label' => esc_html__('Product Font Size', 'anon'),
        'description' => esc_html__('Font size title of product in loop', 'anon'),
    ],[
        'name' => 'zoo_typo_product_single_size',
        'type' => 'slider',
        'section' => 'zoo_typography',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'css_format' => 'font-size: {{value}};',
        'selector' => ".woocommerce div.product .product_title",
        'theme_supports' => 'woocommerce',
        'label' => esc_html__('Single Product Font Size', 'anon'),
        'description' => esc_html__('Font size title of Single Product', 'anon'),
    ],
];
