<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

/**
 * CleverButton
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverButton extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-button';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Button', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-button';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('button_settings', [
            'label' => esc_html__('Button', 'cafe-lite')
        ]);
        $this->add_control('button_label', [
            'label' => esc_html__('Button Label', 'cafe-lite'),
            'placeholder' => esc_html__('Button', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => esc_html__('Button', 'cafe-lite'),
            'label_block' => false
        ]);
        $this->add_control('button_icon', [
            'label' => esc_html__('Icon', 'cafe-lite'),
            'type' => 'clevericon'
        ]);
        $this->add_control('button_icon_pos', [
            'label' => esc_html__('Icon position', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'after',
            'options' => [
                'before' => esc_html__('Before', 'cafe-lite'),
                'after' => esc_html__('After', 'cafe-lite'),
            ]
        ]);
	    $this->add_control('link', [
		    'label' => esc_html__('Link', 'cafe-lite'),
		    'type' => Controls_Manager::URL,
		    'description' => esc_html__('Redirect link when click to banner.', 'cafe-lite'),
		    'separator'   => 'after',
	    ]);
	    $this->add_control('css_class', [
		    'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
		    'type' => Controls_Manager::TEXT,
		    'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('normal_style_settings', [
            'label' => esc_html__('Normal', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_control('button_style', [
		    'label' => esc_html__('Button style', 'cafe-lite'),
		    'type' => Controls_Manager::SELECT,
		    'default' => 'normal',
		    'options' => [
			    'normal' => esc_html__('Normal', 'cafe-lite'),
			    'underline' => esc_html__('Underline', 'cafe-lite'),
			    'outline' => esc_html__('Outline', 'cafe-lite'),
			    'flat' => esc_html__('Flat', 'cafe-lite'),
			    'slide' => esc_html__('Slide', 'cafe-lite'),
			    'slide-up' => esc_html__('Slide Up', 'cafe-lite'),
			    'line-through' => esc_html__('Line Through', 'cafe-lite'),
		    ]
	    ]);
	    $this->add_responsive_control(
		    'text_align',
		    [
			    'label' => esc_html__('Button Align', 'cafe-lite'),
			    'type' => Controls_Manager::CHOOSE,
			    'options' => [
				    'left' => [
					    'title' => esc_html__('Left', 'cafe-lite'),
					    'icon' => 'fa fa-align-left',
				    ],'center' => [
					    'title' => esc_html__('Center', 'cafe-lite'),
					    'icon' => 'fa fa-align-center',
				    ],
				    'right' => [
					    'title' => esc_html__('Right', 'cafe-lite'),
					    'icon' => 'fa fa-align-right',
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}}' => 'text-align: {{VALUE}};'
			    ]
		    ]
	    );
	    $this->add_control('button_color', [
		    'label' => esc_html__('Button Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-button' => 'color: {{VALUE}};'
		    ]
	    ]);
        $this->start_controls_tabs( 'button_background_tabs' );

        $this->start_controls_tab( 'button_bg_normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );
	    $this->add_control('button_bg', [
		    'label' => esc_html__('Button Background', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-button:after,{{WRAPPER}} .cafe-button:before' => 'background: {{VALUE}};'
		    ]
	    ]);
        $this->end_controls_tab();
        $this->start_controls_tab( 'button_bg_gradient_tab', [ 'label' => esc_html__('Gradient', 'cafe-lite' ) ] );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_bg_gradient',
                'label' => esc_html__('Background', 'cafe-lite'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .cafe-button:after,{{WRAPPER}} .cafe-button:before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control('button_typography_heading', [
            'separator'   => 'before',
            'label' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
        ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'button_typography',
			    'selector' => '{{WRAPPER}} .cafe-button',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
        $this->add_control('button_border_width', [
            'separator'   => 'before',
            'label' => esc_html__('Border Width', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors' => [
                '{{WRAPPER}} .cafe-button:before,{{WRAPPER}} .cafe-button:after,{{WRAPPER}} .cafe-button.line-through' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);
	    $this->add_control('button_border', [
		    'label' => esc_html__('Button Border', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-button:before,{{WRAPPER}} .cafe-button:after, {{WRAPPER}} .cafe-button.line-through' => 'border-color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_responsive_control('border_radius', [
		    'label' => esc_html__('Border Radius', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-button,{{WRAPPER}} .cafe-button:after,{{WRAPPER}} .cafe-button:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'button_box_shadow',
			    'selector' => '{{WRAPPER}} .cafe-button',
		    ]
	    );
	    $this->add_responsive_control('dimensions', [
		    'label' => esc_html__('Padding', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'separator'   => 'before',
		    'selectors' => [
			    '{{WRAPPER}} .cafe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();

        $this->start_controls_section('hover_style_settings', [
            'label' => esc_html__('Hover', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('button_color_hover', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:hover' => 'color: {{VALUE}};'
            ]
        ]);
        $this->start_controls_tabs( 'button_background_hover_tabs' );

        $this->start_controls_tab( 'button_bg_hover_normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );
        $this->add_control('button_bg_hover', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-button:hover:after,{{WRAPPER}} .cafe-button:hover:before'  => 'background-color: {{VALUE}};',
            ]
        ]);
        $this->end_controls_tab();
        $this->start_controls_tab( 'button_bg_hover_gradient_tab', [ 'label' => esc_html__('Gradient', 'cafe-lite' ) ] );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_bg_hover_gradient',
                'label' => esc_html__('Background', 'cafe-lite'),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .cafe-button:hover:after,{{WRAPPER}} .cafe-button:hover:before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control('button_border_hover', [
            'label' => esc_html__('Border Color', 'cafe-lite'),
            'separator'   => 'before',
            'type' => Controls_Manager::COLOR,
            'selectors' => [
	            '{{WRAPPER}} .cafe-button:hover:before,{{WRAPPER}} .cafe-button:hover:after'  => 'border-color: {{VALUE}};'
	            ]
        ]);
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'button_box_shadow_hover',
			    'selector' => '{{WRAPPER}} .cafe-button:hover',
		    ]
	    );
        $this->end_controls_section();
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'link' => '',
            'css_class' => '',
            'button_label' => '',
            'button_icon' => '',
            'button_icon_pos' => 'after',
            'button_style' => 'normal',
        ], $this->get_settings_for_display());

        $this->add_inline_editing_attributes('button_label');

        $button_class = 'cafe-button ' . $settings['button_style'].' '.$settings['css_class'];
        $this->add_render_attribute('button_label', 'class', $button_class);

        $this->getViewTemplate('template', 'button', $settings);
    }
}
