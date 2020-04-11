<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

/**
 * CleverIcon
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverIcon extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-icon';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Icon', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-small-diamond';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('icon_settings', [
            'label' => esc_html__('Icon', 'cafe-lite')
        ]);
        $this->add_control('icon', [
            'label' => esc_html__('Icon', 'cafe-lite'),
            'default' => 'cs-font icon-plane-2',
            'type' => 'clevericon',
        ]);
	    $this->add_control('title', [
		    'label' => esc_html__('Title', 'cafe-lite'),
		    'type' => Controls_Manager::TEXT,
	    ]);
	    $this->add_control('link', [
		    'label' => esc_html__('Link', 'cafe-lite'),
		    'type' => Controls_Manager::URL,
		    'description' => esc_html__('Redirect link when click to icon.', 'cafe-lite'),
	    ]);
	    $this->add_control('flex_direction', [
		    'label' => esc_html__('Flex Direction', 'cafe-lite'),
		    'type' => Controls_Manager::SELECT,
		    'options' => [
			    'row'    => esc_html__('Row', 'cafe-lite' ),
			    'row-reverse' =>  esc_html__('Row reverse', 'cafe-lite' ),
			    'column' => esc_html__('Column', 'cafe-lite' ),
			    'column-reverse' => esc_html__('Column reverse', 'cafe-lite' ),
		    ],
		    'default' => 'row',
		    'selectors' => [
			    '{{WRAPPER}} .elementor-widget-container' => 'flex-direction: {{VALUE}};',
		    ],
	    ]);
	    $this->add_control('align', [
		    'label' => esc_html__('Alignment', 'cafe-lite'),
		    'type' => Controls_Manager::CHOOSE,
		    'options' => [
			    'flex-start'    => [
				    'title' => esc_html__('Left', 'cafe-lite' ),
				    'icon' => 'fa fa-align-left',
			    ],
			    'center' => [
				    'title' => esc_html__('Center', 'cafe-lite' ),
				    'icon' => 'fa fa-align-center',
			    ],
			    'flex-end' => [
				    'title' => esc_html__('Right', 'cafe-lite' ),
				    'icon' => 'fa fa-align-right',
			    ],
		    ],
		    'default' => 'center',
		    'selectors' => [
			    '{{WRAPPER}} .elementor-widget-container' => 'justify-content: {{VALUE}};',
		    ],
	    ]);
	    $this->add_control('css_class', [
		    'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
		    'type' => Controls_Manager::TEXT,
		    'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('normal_style_settings', [
            'label' => esc_html__('Icon', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
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
			    '{{WRAPPER}} .cafe-icon' => 'font-size: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
	    $this->add_control('icon_block_size', [
		    'label' => esc_html__('Size', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 300,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
	    $this->add_control('color', [
		    'label' => esc_html__('Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('icon_bg', [
		    'label' => esc_html__('Icon Background', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon' => 'background: {{VALUE}};'
		    ]
	    ]);
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_shadow',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .cafe-icon',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__('Border', 'cafe-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .cafe-icon',
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control('padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
	    $this->add_responsive_control('border_radius', [
		    'label' => esc_html__('Border Radius', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);

        $this->end_controls_section();

        $this->start_controls_section('hover_style_settings', [
            'label' => esc_html__('Icon Hover', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('color_hover', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-icon:hover' => 'color: {{VALUE}};'
            ]
        ]);
	    $this->add_control('border_color_hover', [
		    'label' => esc_html__('Border Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon:hover' => 'border-color: {{VALUE}};'
		    ]
	    ]);
        $this->add_control('icon_bg_hover', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-icon:hover' => 'background: {{VALUE}};'
            ]
        ]);
        $this->end_controls_section();
	    $this->start_controls_section('title_style_settings', [
		    'label' => esc_html__('Title', 'cafe-lite'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->add_control('title_color', [
		    'label' => esc_html__('Title Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon-title' => 'color: {{VALUE}};'
		    ]
	    ]);	    $this->add_control('title_color_hover', [
		    'label' => esc_html__('Title Hover Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .elementor-widget-container:hover .cafe-icon-title' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'title_typography',
			    'selector' => '{{WRAPPER}} .cafe-icon-title',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_responsive_control('title_padding', [
		    'label' => esc_html__('Padding', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);$this->add_responsive_control('title_margin', [
		    'label' => esc_html__('Margin', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-icon-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'link' => '',
            'title' => '',
            'css_class' => '',
            'icon' => '',
        ], $this->get_settings_for_display());

	    $this->add_inline_editing_attributes('title');
	    $title_html_classes=['cafe-icon-title'];
	    $this->add_render_attribute('title', 'class', $title_html_classes);
        $this->getViewTemplate('template', 'icon', $settings);
    }
}
