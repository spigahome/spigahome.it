<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;

/**
 * CleverSingleScrollTo
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverSingleScrollTo extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-single-scroll-to';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Single Scroll To', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-arrow-up';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('general_settings', [
            'label' => esc_html__('General', 'cafe-lite')
        ]);
        $this->add_control('target_id', [
            'label' => esc_html__('Target ID', 'cafe-lite'),
            'default' => '',
            'description' => esc_html__('ID of block want scroll to when click.', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
        ]);
        $this->add_control('icon',
            [
                'label' => esc_html__('Icon', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'down',
                'options' => [
                    'up' => esc_html__('Up','cafe-lite'),
                    'down' =>  esc_html__('Down','cafe-lite'),
                    'font-icon' =>  esc_html__('Font Icon','cafe-lite'),
                ],
            ]);
        $this->add_control('font_icon', [
            'label' => esc_html__('Font Icon', 'cafe-lite'),
            'default' => 'cs-font icon-plane-2',
            'type' => 'clevericon',
            'condition' => [
                'icon' => 'font-icon'
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('animation_settings', [
            'label' => esc_html__('Animation', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control( 'button_animation', [
            'label'     => esc_html__('Animation', 'cafe-lite' ),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'inherit',
            'separator' => 'before',
            'options'   => [
                'inherit'           => esc_html__('Inherit', 'cafe-lite' ),
                'bounce'            => esc_html__('Bounce', 'cafe-lite' ),
                'bounceIn'          => esc_html__('Bounce In', 'cafe-lite' ),
                'bounceInDown'      => esc_html__('Bounce In Down', 'cafe-lite' ),
                'bounceInLeft'      => esc_html__('Bounce In Left', 'cafe-lite' ),
                'bounceInRight'     => esc_html__('Bounce In Right', 'cafe-lite' ),
                'bounceInUp'        => esc_html__('Bounce In Up', 'cafe-lite' ),
                'fadeIn'            => esc_html__('Fade In', 'cafe-lite' ),
                'fadeInDown'        => esc_html__('Fade In Down', 'cafe-lite' ),
                'fadeInLeft'        => esc_html__('Fade In Left', 'cafe-lite' ),
                'fadeInRight'       => esc_html__('Fade In Right', 'cafe-lite' ),
                'fadeInUp'          => esc_html__('Fade In Up', 'cafe-lite' ),
                'flash'             => esc_html__('Flash', 'cafe-lite' ),
                'headShake'         => esc_html__('Head Shake', 'cafe-lite' ),
                'lightSpeedIn'      => esc_html__('Light Speed In', 'cafe-lite' ),
                'jello'             => esc_html__('Jello', 'cafe-lite' ),
                'pulse'             => esc_html__('Pulse', 'cafe-lite' ),
                'rotateIn'          => esc_html__('Rotate In', 'cafe-lite' ),
                'rotateInDownLeft'  => esc_html__('Rotate Down Left', 'cafe-lite' ),
                'rotateInDownRight' => esc_html__('Rotate Down Right', 'cafe-lite' ),
                'rotateInUpLeft'    => esc_html__('Rotate Up Left', 'cafe-lite' ),
                'rotateInUpRight'   => esc_html__('Rotate Up Right', 'cafe-lite' ),
                'rollIn'            => esc_html__('Roll In', 'cafe-lite' ),
                'zoomIn'            => esc_html__('Zoom In', 'cafe-lite' ),
                'rubberBand'        => esc_html__('Rubber Band', 'cafe-lite' ),
                'shake'             => esc_html__('Shake', 'cafe-lite' ),
                'swing'             => esc_html__('Swing', 'cafe-lite' ),
                'slideInDown'       => esc_html__('Slide In Down', 'cafe-lite' ),
                'slideInLeft'       => esc_html__('Slide In Left', 'cafe-lite' ),
                'slideInRight'      => esc_html__('Slide In Right', 'cafe-lite' ),
                'slideInUp'         => esc_html__('Slide In Up', 'cafe-lite' ),
                'tada'              => esc_html__('Tada', 'cafe-lite' ),
                'wobble'            => esc_html__('Wobble', 'cafe-lite' ),
                'zoomInDown'        => esc_html__('Zoom In Down', 'cafe-lite' ),
                'zoomInLeft'        => esc_html__('Zoom In Left', 'cafe-lite' ),
                'zoomInRight'       => esc_html__('Zoom In Right', 'cafe-lite' ),
                'zoomInUp'          => esc_html__('Zoom In Up', 'cafe-lite' )
            ],
        ] );
        $this->add_control('duration', [
            'label' => esc_html__('Animation Duration', 'cafe-lite'),
            'default' => '1000',
            'description' => esc_html__('ID of block want scroll to when click.', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'selectors' => [
                '{{WRAPPER}} .cafe-single-scroll-button .cafe-scroll-icon' => 'animation-duration: {{VALUE}}ms;',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('normal_style_settings', [
            'label' => esc_html__('Normal', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('align', [
            'label' => esc_html__('Alignment', 'cafe-lite'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start'    => [
                    'title' => esc_html__('Left', 'elementor' ),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'elementor' ),
                    'icon' => 'fa fa-align-center',
                ],
                'flex-end' => [
                    'title' => esc_html__('Right', 'elementor' ),
                    'icon' => 'fa fa-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-single-scroll-button' => 'justify-content: {{VALUE}};',
            ],
        ]);
	    $this->add_control('icon_size', [
		    'label' => esc_html__('Icon size', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-scroll-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
	    $this->add_control('color', [
		    'label' => esc_html__('Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-scroll-icon i' => 'color: {{VALUE}};'
		    ]
	    ]);
        $this->add_control('icon_bg', [
            'label' => esc_html__('Icon Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-single-scroll-button .bg-box' => 'background: {{VALUE}};'
            ]
        ]);
        $this->end_controls_section();

        $this->start_controls_section('hover_style_settings', [
            'label' => esc_html__('Hover', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('color_hover', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-scroll-icon:hover i' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('icon_bg_hover', [
            'label' => esc_html__('Button Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-single-scroll-button:hover .bg-box' => 'background: {{VALUE}};'
            ]
        ]);
        $this->end_controls_section();
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'target_id' => '',
            'icon' => 'down',
            'font_icon' => '',
            'button_animation' => 'inherit',
        ], $this->get_settings_for_display());

        $this->getViewTemplate('template', 'single-scroll-to', $settings);
    }
}
