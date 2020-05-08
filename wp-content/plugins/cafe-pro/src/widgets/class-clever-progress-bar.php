<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverProgressBar
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverProgressBar extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-progress-bar';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return __('Clever Progress Bar', 'cafe-pro');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-slider-2';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('general_settings', [
            'label' => __('General', 'cafe-pro')
        ]);
        $this->add_control('title', [
            'label' => __('Title', 'cafe-pro'),
            'type' => Controls_Manager::TEXT,
            'default'=>esc_html__('Progress','cafe-pro'),
            'description' => __('Title for process bar.', 'cafe-pro'),
        ]);
        $this->add_control('title_tag',
            [
                'label' => __('Title HTML Tag', 'cafe-pro'),
                'description' => __('Select a heading tag for the title. Headings are defined with H1 to H6 tags.', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h4',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'p',
                    'div' => 'div',
                    'span' => 'span',
                ],
                'label_block' => true,
            ]);
        $this->add_control('percent', [
            'label' => __('Percentage loading', 'cafe-pro'),
            'type' => Controls_Manager::NUMBER,
            'max' => 100,
            'default' => 50,
        ]);
        $this->add_control('percentage_location',
            [
                'label' => __('Percentage Location', 'cafe-pro'),
                'description' => __('Location for display Percentage.', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' =>  __('Top', 'cafe-pro'),
                    'bottom' =>  __('Bottom', 'cafe-pro'),
                    'inner' =>  __('Inner', 'cafe-pro'),
                    'stuck-left' =>  __('Stuck Left', 'cafe-pro'),
                    'stuck-right' =>  __('Stuck Right', 'cafe-pro'),
                ],
            ]);
        $this->add_control('style',
            [
                'label' => __('Style', 'cafe-pro'),
                'description' => __('Select style.', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => 'Default',
                    'grouped' => 'Grouped',
                ],
            ]);
        $this->add_control('duration', [
            'label' => __('Duration (ms)', 'cafe-pro'),
            'type' => Controls_Manager::NUMBER,
            'default' => 2000,
        ]);
        $this->end_controls_section();
        $this->start_controls_section('title_style_settings', [
            'label' => __('Title', 'cafe-pro'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('title_color', [
            'label' => __('Color', 'cafe-pro'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-title' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_responsive_control(
            'title_align',
            [
                'label' => __('Align', 'cafe-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'cafe-pro'),
                        'icon' => 'fa fa-align-left',
                    ],'center' => [
                        'title' => __('Center', 'cafe-pro'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'cafe-pro'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cafe-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]

        );
        $this->add_control('title_spacing', [
            'label' => __('Spacing', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();
        $this->start_controls_section('progress_style_settings', [
            'label' => __('Progress bar', 'cafe-pro'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_control('bg_base', [
		    'label' => __('Background', 'cafe-pro'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .base-bg' => 'background: {{VALUE}};'
            ]
	    ]);
	    $this->add_control('progress_bg', [
		    'label' => __('Progress Background', 'cafe-pro'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-progress-bar' => 'background: {{VALUE}};'
            ]
	    ]);
	    $this->add_control('percentage_color', [
		    'label' => __('Percentage Color', 'cafe-pro'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .percent-count' => 'color: {{VALUE}};'
		    ]
	    ]);
        $this->add_control('progress_height', [
            'label' => __('Height', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .base-bg' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('progress_dot_height', [
            'label' => __('Dot Height', 'cafe-pro'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-progress-dot' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'percentage_typography',
                'selector' => '{{WRAPPER}} .percent-count',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]

        );
        $this->add_responsive_control('border_radius', [
            'label' => __('Border Radius', 'cafe-pro'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .base-bg,{{WRAPPER}} .cafe-progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'title' => esc_html__('Progress','cafe-pro'),
            'title_tag' => 'h4',
            'percent' => '50',
            'percentage_location' => 'top',
            'duration' => '2000',
            'style' => 'default',
        ], $this->get_settings_for_display());
        $this->add_inline_editing_attributes('title');
        $title_html_classes=['cafe-title'];
        $this->add_render_attribute('title', 'class', $title_html_classes);

        $this->getViewTemplate('template', 'progress-bar', $settings);
    }
}
