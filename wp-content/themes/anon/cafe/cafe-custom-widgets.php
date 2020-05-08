<?php
/**
 * Anon Custom widgets.
 * Custom widget control for widget of Cafe plugins.
 */

add_action( 'elementor/element/clever-posts/layout_settings/before_section_end', function( $element, $args ) {
    $element->add_control('disable_border', [
        'label' => esc_html__('Disable border', 'anon'),
        'description' => esc_html__('', 'anon'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'return_value' => 'true',
        'default' => 'false',
    ]);
}, 10, 2 );
add_action( 'elementor/element/clever-services/layout_settings/before_section_end', function( $element, $args ) {
    $element->remove_control('style');
    $element->add_control('style', [
        'label' => esc_html__('Style', 'anon'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'default',
        'options' => [
            'default' => esc_html__('Default', 'anon'),
            'boxed' => esc_html__('Boxed', 'anon'),
            'inline' => esc_html__('Inline', 'anon'),
        ],
        'description' => esc_html__('Style of service.', 'anon'),
    ]);
    $element->add_responsive_control('height', [
        'label' => esc_html__('Min Height', 'anon'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units'    => ['px', 'vh'],
        'range' => [
            'px' => [
                'min' => 1,
                'max' => 2000,
            ],'vh' => [
                'min' => 1,
                'max' => 100,
            ],
        ],
        'selectors' => [
            '{{WRAPPER}} .cafe-services .cafe-wrap-service' => 'min-height: {{SIZE}}{{UNIT}};',
        ],
        'devices' => ['desktop', 'tablet', 'mobile'],
        'condition' => [
            'style' => 'inline'
        ],
    ]);
}, 10, 2 );