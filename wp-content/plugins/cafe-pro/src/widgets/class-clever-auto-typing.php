<?php
namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Cafe\Clever_Custom_Control;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * Clever Auto Typing
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverAutoTyping extends CleverWidgetBase {
	/**
	 * @return string
	 */
	function get_name() {
		return 'clever-auto-typing';
	}

	/**
	 * @return string
	 */
	function get_title() {
		return __( 'Clever Auto Typing', 'cafe-pro' );
	}

	/**
	 * @return string
	 */
	function get_icon() {
		return 'cs-font clever-icon-keyboard-and-hands';
	}

	/**
	 * Register controls
	 */
	protected function _register_controls() {
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'text_item',
			[
				'label'       => __( 'Content', 'cafe-pro' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->start_controls_section( 'settings', [
			'label' => __( 'Settings', 'cafe-pro' )
		] );
		$this->add_control( 'fixed_text', [
			'label'       => __( 'Fixed Text', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'This text is fixed, not has effect', 'cafe-pro' ),
		] );
		$this->add_control( 'text', [
			'label'       => __( 'Text Typing', 'cafe-pro' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'description' => __( 'Set text for typing.', 'cafe-pro' ),
			'title_field' => '{{{ text_item }}}',
			'default'     => [
				[
					'text_item'          => __( 'This is auto typing 1', 'cafe-pro' ),
				],
				[
					'text_item'          => __( 'This is auto typing 2', 'cafe-pro' ),
				],
				[
					'text_item'          => __( 'This is auto typing 3', 'cafe-pro' ),
				],
			],
			'separator'   => 'before',

		] );
		$this->add_control( 'typeSpeed', [
			'label'       => __( 'Typing speed', 'cafe-pro' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => '100',
			'description' => __( 'Apply only number.', 'cafe-pro' ),
			'separator'   => 'before',
		] );
		$this->add_control( 'delay_time', [
			'label'       => __( 'Delay time', 'cafe-pro' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => '0',
			'description' => __( 'Apply only number.', 'cafe-pro' ),
		] );
		$this->add_control( 'show_cursor', [
			'label'       => __( 'Show Cursor', 'cafe-pro' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
			'description' => __( 'Show Cursor when typing or not.', 'cafe-pro' ),
		] );
		$this->add_control( 'loop', [
			'label'   => __( 'Enable Loop', 'cafe-pro' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'no',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'Style settings', [
			'label' => __( 'Style', 'cafe-pro' ),
            'tab'   => Controls_Manager::TAB_STYLE,
		] );
        $this->add_responsive_control(
            'text_align',
            [
                'label'     => __( 'Text Align', 'cafe-pro' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'cafe-pro' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'cafe-pro' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'cafe-pro' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-auto-typing' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .cafe-auto-typing',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
		$this->add_control( 'color_fix_text', [
			'label'     => __( 'Fixed text Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-fixed-text' => 'color: {{COLOR}};',
			],
		] );
		$this->add_control( 'color', [
			'label'     => __( 'Color', 'cafe-pro' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-auto-typing' => 'color: {{COLOR}}',
			],
		] );
		$this->add_control( 'css_class', [
			'label'       => __( 'Custom HTML Class', 'cafe-pro' ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'You may add a custom HTML class to style element later.', 'cafe-pro' ),
		] );
		$this->end_controls_section();
	}

	/**
	 * Load style
	 */
	public function get_style_depends() {
		return [ 'cafe-style' ];
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
	public function get_script_depends() {
		return [ 'typed', 'cafe-script' ];
	}

	/**
	 * Render
	 */
	protected function render() {
		$settings = array_merge( [ // default settings
			'fixed_text'  => '',
			'text'        => '',
			'typeSpeed'   => '100',
			'delay_time'  => '0',
			'show_cursor' => 'yes',
			'loop'        => 'no',
			'css_class'   => '',

		], $this->get_settings_for_display() );

		$this->add_inline_editing_attributes( 'fixed_text' );
		$this->add_render_attribute( 'fixed_text', 'class', 'cafe-fixed-text' );

		$this->getViewTemplate( 'template', 'auto-typing', $settings );
	}
}
