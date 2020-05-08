<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
/**
 * Clever Count Down
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverCountDown extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-count-down';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Count Down', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-clock-3';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {

        $this->start_controls_section('settings', [
            'label' => esc_html__('Settings', 'cafe-lite')
        ]);
        $this->add_control('date', [
            'label' => esc_html__('Time Ended', 'cafe-lite'),
            'type' => Controls_Manager::DATE_TIME,
            'description' => esc_html__('Date and time ended count down.', 'cafe-lite'),
        ]);
        $this->add_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => esc_html__('Left', 'elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .cafe-countdown' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('css_class', [
            'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
        ]);
        $this->end_controls_section();
	    $this->start_controls_section('normal_style_settings', [
		    'label' => esc_html__('Style', 'cafe-lite'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->add_control('width', [
		    'label' => esc_html__('Item Width', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 200,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div' => 'min-width:{{SIZE}}{{UNIT}};',
		    ],
		    'description' => esc_html__('Width for each item.', 'cafe-lite'),
	    ]);
	    $this->add_control('space', [
		    'label' => esc_html__('Space', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div' => 'margin:0 {{SIZE}}{{UNIT}};',
		    ],
		    'description' => esc_html__('Space with each countdown elements.', 'cafe-lite'),
	    ]);
	    $this->add_control('inner_space', [
		    'label' => esc_html__('Inner Space', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times b' => 'margin-bottom:{{SIZE}}{{UNIT}};',
		    ],
		    'description' => esc_html__('Space with of countdown number and text.', 'cafe-lite'),
	    ]);
	    $this->add_control( 'heading_count_typo', [
		    'label' => esc_html__('Count Typography', 'cafe-lite'),
		    'type' => Controls_Manager::HEADING,
		    'separator'   => 'after',
	    ] );
	    $this->add_group_control( Group_Control_Typography::get_type(), [
		    'name'     => 'count_typo',
		    'selector' => '{{WRAPPER}} .cafe-countdown .countdown-times > div b',
		    'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
	    ] );
	    $this->add_control( 'timer_typo', [
		    'label' => esc_html__('Text Typography', 'cafe-lite'),
		    'type' => Controls_Manager::HEADING,
		    'separator'   => 'after',
	    ] );
	    $this->add_group_control( Group_Control_Typography::get_type(), [
		    'name'     => 'typo',
		    'selector' => '{{WRAPPER}} .cafe-countdown .countdown-times > div',
		    'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
	    ] );
	    $this->add_control('color', [
		    'label' => esc_html__('Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div' => 'color: {{COLOR}};',
		    ],
	    ]);
	    $this->add_control('color_count', [
		    'label' => esc_html__('Color count', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div b' => 'color: {{COLOR}};',
		    ],
		    'description' => esc_html__('Color of number count.', 'cafe-lite'),
	    ]);
	    $this->add_control('bg_color', [
		    'label' => esc_html__('Background Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div' => 'background-color: {{COLOR}};',
		    ],
	    ]);
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'          =>  'border',
			    'selector'      => '{{WRAPPER}} .cafe-countdown .countdown-times > div',
		    ]
	    );
	    $this->add_responsive_control('border_radius', [
		    'label' => esc_html__('Border Radius', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'separator'   => 'before',
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
	    $this->add_responsive_control('padding', [
		    'label' => esc_html__('Padding', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'separator'   => 'before',
		    'selectors' => [
			    '{{WRAPPER}} .cafe-countdown .countdown-times > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
    }

    /**
     * Load style
     */
    public function get_style_depends()
    {
        return ['cafe-style'];
    }

    /**
     * Retrieve the list of scripts the image carousel widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['countdown', 'cafe-script'];
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'date' => '',
            'css_class' => '',
        ], $this->get_settings_for_display());

        $this->getViewTemplate('template', 'count-down', $settings);
    }
}
