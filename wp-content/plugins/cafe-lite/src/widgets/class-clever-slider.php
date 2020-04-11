<?php namespace Cafe\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

/**
 * CleverSlider
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverSlider extends CleverWidgetBase {
	public function get_name() {
		return 'clever-slider';
	}

	public function get_title() {
		return esc_html__('CAFE Slider', 'cafe-lite' );
	}

	public function get_icon() {
		return 'cs-font clever-icon-slider';
	}

	public function get_keywords() {
		return [ 'slides', 'slider' ];
	}

	public function get_style_depends() {
		return [ 'cleverfont' ];
	}

	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-slick' ];
	}

	public static function get_button_sizes() {
		return [
			'xs' => esc_html__('Extra Small', 'cafe-lite' ),
			'sm' => esc_html__('Small', 'cafe-lite' ),
			'md' => esc_html__('Medium', 'cafe-lite' ),
			'lg' => esc_html__('Large', 'cafe-lite' ),
			'xl' => esc_html__('Extra Large', 'cafe-lite' ),
		];
	}

	protected function _register_controls() {
		$repeater = new Repeater();

		$this->start_controls_section( 'section_slides', [
			'label' => esc_html__('Slides', 'cafe-lite' ),
		] );

		$repeater->start_controls_tabs( 'slides_repeater' );

		$repeater->start_controls_tab( 'background', [ 'label' => esc_html__('Background', 'cafe-lite' ) ] );

		$repeater->add_control( 'background_color', [
			'label'     => esc_html__('Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#bbbbbb',
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
			],
		] );

		$repeater->add_control( 'background_image', [
			'label'     => _x( 'Image', 'Background Control', 'cafe-lite' ),
			'type'      => Controls_Manager::MEDIA,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-image: url({{URL}})',
			],
		] );
		$repeater->add_control( 'background_video', [
			'label'      => esc_html__('Video url', 'cafe-lite' ),
			'description'      => esc_html__('Support Video and Youtube only.', 'cafe-lite' ),
			'placeholder'      => esc_html__('https://www.youtube.com/watch?v=xxxx', 'cafe-lite' ),
			'type'       => Controls_Manager::TEXT,
			'default'    => '',
			'input_type'=>'url',
			'separator'  => 'before',
		] );
		$repeater->add_control( 'background_video_sound', [
			'label'      => esc_html__('Enable Sound', 'cafe-lite' ),
			'type'       => Controls_Manager::SWITCHER,
			'default'    => '',
			'separator'  => 'before',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'background_video',
						'operator' => '!=',
						'value'    => '',
					],
				],
			],
		] );
		$repeater->add_control( 'background_size', [
			'label'      => _x( 'Size', 'Background Control', 'cafe-lite' ),
			'type'       => Controls_Manager::SELECT,
			'default'    => 'cover',
			'options'    => [
				'cover'   => _x( 'Cover', 'Background Control', 'cafe-lite' ),
				'contain' => _x( 'Contain', 'Background Control', 'cafe-lite' ),
				'auto'    => _x( 'Auto', 'Background Control', 'cafe-lite' ),
			],
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'background-size: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'     => 'background_image[url]',
						'operator' => '!=',
						'value'    => '',
					],
				],
			],
		] );

		$repeater->add_control( 'background_ken_burns', [
			'label'      => esc_html__('Ken Burns Effect', 'cafe-lite' ),
			'type'       => Controls_Manager::SWITCHER,
			'default'    => '',
			'separator'  => 'before',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'background_image[url]',
						'operator' => '!=',
						'value'    => '',
					],
				],
			],
		] );

		$repeater->add_control( 'zoom_direction', [
			'label'      => esc_html__('Zoom Direction', 'cafe-lite' ),
			'type'       => Controls_Manager::SELECT,
			'default'    => 'in',
			'options'    => [
				'in'  => esc_html__('In', 'cafe-lite' ),
				'out' => esc_html__('Out', 'cafe-lite' ),
			],
			'conditions' => [
				'terms' => [
					[
						'name'     => 'background_ken_burns',
						'operator' => '!=',
						'value'    => '',
					],
				],
			],
		] );

		$repeater->add_control( 'background_overlay', [
			'label'      => esc_html__('Background Overlay', 'cafe-lite' ),
			'type'       => Controls_Manager::SWITCHER,
			'default'    => 'yes',
			'separator'  => 'before',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'background_image[url]',
						'operator' => '!=',
						'value'    => '',
					],
				],
			],
		] );

		$repeater->add_control( 'background_overlay_color', [
			'label'      => esc_html__('Color', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'default'    => '',
			'conditions' => [
				'terms' => [
					[
						'name'  => 'background_overlay',
						'value' => 'yes',
					],
				],
			],
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-bg-overlay' => 'background-color: {{VALUE}}',
			],
		] );

		$repeater->add_control( 'background_overlay_blend_mode', [
			'label'      => esc_html__('Blend Mode', 'cafe-lite' ),
			'type'       => Controls_Manager::SELECT,
			'options'    => [
				''            => esc_html__('Normal', 'cafe-lite' ),
				'multiply'    => 'Multiply',
				'screen'      => 'Screen',
				'overlay'     => 'Overlay',
				'darken'      => 'Darken',
				'lighten'     => 'Lighten',
				'color-dodge' => 'Color Dodge',
				'color-burn'  => 'Color Burn',
				'hue'         => 'Hue',
				'saturation'  => 'Saturation',
				'color'       => 'Color',
				'exclusion'   => 'Exclusion',
				'luminosity'  => 'Luminosity',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'background_overlay',
						'value' => 'yes',
					],
				],
			],
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
			],
		] );
		$repeater->add_control( 'background_animation', [
			'label'      => esc_html__('Background Animation', 'cafe-lite' ),
			'type'       => Controls_Manager::SELECT,
			'default'    => 'inherit',
			'separator'  => 'before',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'background_ken_burns',
						'operator' => '==',
						'value'    => '',
					],
				],
			],
			'options'    => [
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
		$repeater->add_control( 'background_transition_duration', [
			'label'     => esc_html__('Transition Duration(ms)', 'cafe-lite' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 1000,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-bg' => 'animation-duration: {{VALUE}}ms; transition-duration: {{VALUE}}ms',
			],
		] );
		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'content', [ 'label' => esc_html__('Content', 'cafe-lite' ) ] );

		$repeater->add_control( 'heading', [
			'label'       => esc_html__('Title & Description', 'cafe-lite' ),
			'type'        => Controls_Manager::TEXT,
            'placeholder'     => esc_html__('Text Line 1', 'cafe-lite' ),
			'default'     => esc_html__('Slide Heading', 'cafe-lite' ),
			'label_block' => true,
		] );
		$repeater->add_control( 'heading-2', [
			'label'       => esc_html__('Text Line 2', 'cafe-lite' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder'     => esc_html__('Text Line 2', 'cafe-lite' ),
            'label_block' => true,
            'show_label' => false,
		] );

		$repeater->add_control( 'description', [
			'label'      => esc_html__('Description', 'cafe-lite' ),
			'placeholder'      => esc_html__('Text Line 3', 'cafe-lite' ),
			'type'       => Controls_Manager::TEXTAREA,
			'default'    => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'cafe-lite' ),
			'show_label' => false,
		] );

		$repeater->add_control( 'button_text', [
			'label'   => esc_html__('Button Text', 'cafe-lite' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__('Click Here', 'cafe-lite' ),
		] );
        $repeater->add_control('button_icon', [
            'label' => esc_html__('Button Icon', 'cafe-lite'),
            'type' => 'clevericon'
        ]);
		$repeater->add_control( 'button_style', [
			'label'   => esc_html__('Button style', 'cafe-lite' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'normal',
			'options' => [
				'normal'    => esc_html__('Normal', 'cafe-lite' ),
				'underline' => esc_html__('Underline', 'cafe-lite' ),
				'outline'   => esc_html__('Outline', 'cafe-lite' ),
				'line-through'   => esc_html__('Line Through', 'cafe-lite' ),
			]
		] );
		$repeater->add_control( 'link', [
			'label'       => esc_html__('Link', 'cafe-lite' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => esc_html__('https://yoursite.com', 'cafe-lite' ),
		] );

		$repeater->add_control( 'link_click', [
			'label'      => esc_html__('Apply Link On', 'cafe-lite' ),
			'type'       => Controls_Manager::SELECT,
			'options'    => [
				'slide'  => esc_html__('Whole Slide', 'cafe-lite' ),
				'button' => esc_html__('Button Only', 'cafe-lite' ),
			],
			'default'    => 'slide',
			'conditions' => [
				'terms' => [
					[
						'name'     => 'link[url]',
						'operator' => '!=',
						'value'    => '',
					],
				],
			],
		] );
		$repeater->add_control( 'content_animation', [
			'label'     => esc_html__('Content Animation', 'cafe-lite' ),
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
		$repeater->add_control( 'content_transition_duration', [
			'label'     => esc_html__('Transition Duration(ms)', 'cafe-lite' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 500,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-content' => 'animation-duration: calc({{VALUE}}ms); transition-duration: calc({{VALUE}}ms)',
			],
		] );
		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', [ 'label' => esc_html__('Style', 'cafe-lite' ) ] );

		$repeater->add_control( 'custom_style', [
			'label'       => esc_html__('Custom', 'cafe-lite' ),
			'type'        => Controls_Manager::SWITCHER,
			'description' => esc_html__('Set custom style that will only affect this specific slide.', 'cafe-lite' ),
		] );

		$repeater->add_responsive_control( 'content_max_width', [
			'label'          => esc_html__('Content Width', 'cafe-lite' ),
			'type'           => Controls_Manager::SLIDER,
			'range'          => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'size_units'     => [ '%', 'px' ],
			'default'        => [
				'size' => '100',
				'unit' => '%',
			],
			'tablet_default' => [
				'unit' => '%',
			],
			'mobile_default' => [
				'unit' => '%',
			],
			'selectors'      => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-content' => 'width: {{SIZE}}{{UNIT}};',
			],
			'conditions'     => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$repeater->add_responsive_control( 'slides_padding', [
			'label'      => esc_html__('Padding', 'cafe-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );
		$repeater->add_control(
			'align_divider',
			[
				'type'       => Controls_Manager::DIVIDER,
				'style'      => 'thick',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);
		$repeater->add_responsive_control( 'horizontal_position', [
			'label'                => esc_html__('Horizontal Position', 'cafe-lite' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => [
				'left'   => [
					'title' => esc_html__('Left', 'cafe-lite' ),
					'icon'  => 'eicon-h-align-left',
				],
				'center' => [
					'title' => esc_html__('Center', 'cafe-lite' ),
					'icon'  => 'eicon-h-align-center',
				],
				'right'  => [
					'title' => esc_html__('Right', 'cafe-lite' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'selectors'            => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-content' => '{{VALUE}}',
			],
			'selectors_dictionary' => [
				'left'   => 'margin-right: auto',
				'center' => 'margin: 0 auto',
				'right'  => 'margin-left: auto',
			],
			'conditions'           => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$repeater->add_responsive_control( 'vertical_position', [
			'label'                => esc_html__('Vertical Position', 'cafe-lite' ),
			'type'                 => Controls_Manager::CHOOSE,
			'label_block'          => false,
			'options'              => [
				'top'    => [
					'title' => esc_html__('Top', 'cafe-lite' ),
					'icon'  => 'eicon-v-align-top',
				],
				'middle' => [
					'title' => esc_html__('Middle', 'cafe-lite' ),
					'icon'  => 'eicon-v-align-middle',
				],
				'bottom' => [
					'title' => esc_html__('Bottom', 'cafe-lite' ),
					'icon'  => 'eicon-v-align-bottom',
				],
			],
			'selectors'            => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner' => 'align-items: {{VALUE}}',
			],
			'selectors_dictionary' => [
				'top'    => 'flex-start',
				'middle' => 'center',
				'bottom' => 'flex-end',
			],
			'conditions'           => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$repeater->add_responsive_control( 'text_align', [
			'label'       => esc_html__('Text Align', 'cafe-lite' ),
			'type'        => Controls_Manager::CHOOSE,
			'label_block' => false,
			'options'     => [
				'left'   => [
					'title' => esc_html__('Left', 'cafe-lite' ),
					'icon'  => 'fa fa-align-left',
				],
				'center' => [
					'title' => esc_html__('Center', 'cafe-lite' ),
					'icon'  => 'fa fa-align-center',
				],
				'right'  => [
					'title' => esc_html__('Right', 'cafe-lite' ),
					'icon'  => 'fa fa-align-right',
				],
			],
			'selectors'   => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner' => 'text-align: {{VALUE}}',
			],
			'conditions'  => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control(
			'color_divider',
			[
				'type'       => Controls_Manager::DIVIDER,
				'style'      => 'thick',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);
		$repeater->add_control( 'heading_color', [
			'label'      => esc_html__('Text Line 1 Color', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-heading' => 'color: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control( 'heading_2_color', [
			'label'      => esc_html__('Text Line 2 Color', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-heading.sec-heading' => 'color: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );

		$repeater->add_control( 'des_color', [
			'label'      => esc_html__('Text Line 3 Color', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-desc' => 'color: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control(
			'button_divider',
			[
				'type'       => Controls_Manager::DIVIDER,
				'style'      => 'thick',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);
		$repeater->add_control( 'button_heading', [
			'label'      => esc_html__('Button', 'cafe-lite' ),
			'type'       => Controls_Manager::HEADING,
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control( 'button_color', [
			'label'      => esc_html__('Color', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-btn' => 'color: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control( 'button_bg', [
			'label'      => esc_html__('Background', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-btn.outline::after,{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-btn.line-through::after,{{WRAPPER}} {{CURRENT_ITEM}}  .cafe-slide-btn.normal::after' => 'background: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control(
			'button_hover_divider',
			[
				'type'       => Controls_Manager::DIVIDER,
				'style'      => 'thick',
				'conditions' => [
					'terms' => [
						[
							'name'  => 'custom_style',
							'value' => 'yes',
						],
					],
				],
			]
		);
		$repeater->add_control( 'button_hover_heading', [
			'label'      => esc_html__('Button Hover', 'cafe-lite' ),
			'type'       => Controls_Manager::HEADING,
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control( 'button_color_hover', [
			'label'      => esc_html__('Color', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-btn:hover' => 'color: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->add_control( 'button_bg_hover', [
			'label'      => esc_html__('Background', 'cafe-lite' ),
			'type'       => Controls_Manager::COLOR,
			'selectors'  => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-btn.outline:hover:after,{{WRAPPER}} {{CURRENT_ITEM}} .cafe-slide-btn.line-through:hover:after,{{WRAPPER}} {{CURRENT_ITEM}}  .cafe-slide-btn.normal:hover:after' => 'background: {{VALUE}}',
			],
			'conditions' => [
				'terms' => [
					[
						'name'  => 'custom_style',
						'value' => 'yes',
					],
				],
			],
		] );
		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control( 'slides', [
			'label'       => esc_html__('Slides', 'cafe-lite' ),
			'type'        => Controls_Manager::REPEATER,
			'show_label'  => true,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'heading'          => esc_html__('Slide 1', 'cafe-lite' ),
					'heading-2'          => esc_html__('', 'cafe-lite' ),
					'description'      => esc_html__('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'cafe-lite' ),
					'button_text'      => esc_html__('Click Here', 'cafe-lite' ),
					'background_color' => '#3d70b2',
				],
				[
					'heading'          => esc_html__('Slide 2', 'cafe-lite' ),
					'heading-2'          => esc_html__('', 'cafe-lite' ),
					'description'      => esc_html__('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'cafe-lite' ),
					'button_text'      => esc_html__('Click Here', 'cafe-lite' ),
					'background_color' => '#6596e6',
				],
				[
					'heading'          => esc_html__('Slide 3', 'cafe-lite' ),
					'heading-2'          => esc_html__('', 'cafe-lite' ),
					'description'      => esc_html__('Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'cafe-lite' ),
					'button_text'      => esc_html__('Click Here', 'cafe-lite' ),
					'background_color' => '#41d6c3',
				],
			],
			'title_field' => '{{{ heading }}}',
		] );

		$this->add_responsive_control( 'slides_height', [
			'label'      => esc_html__('Height', 'cafe-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 1000,
				],
				'vh' => [
					'min' => 10,
					'max' => 100,
				],
			],
			'default'    => [
				'size' => 400,
			],
			'size_units' => [ 'px', 'vh'],
			'selectors'  => [
				'{{WRAPPER}} .slick-slide' => 'height: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );
		$this->add_responsive_control( 'slides_width', [
			'label'      => esc_html__('Container width', 'cafe-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [
					'min' => 100,
					'max' => 2000,
				],
				'vw' => [
					'min' => 10,
					'max' => 100,
				],'%' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'size' => 1400,
			],
			'size_units' => [ 'px', 'vw','%' ],
			'selectors'  => [
				'{{WRAPPER}} .slick-slide-inner' => 'width: {{SIZE}}{{UNIT}};',
			]
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_slider_options', [
			'label' => esc_html__('Slider Settings', 'cafe-lite' ),
			'type'  => Controls_Manager::SECTION,
		] );
		$this->add_responsive_control( 'show_arrows', [
			'label'          => esc_html__('Show Arrows', 'cafe-lite' ),
			'type'           => Controls_Manager::SWITCHER,
			'default'        => 'yes',
			'tablet_default' => 'yes',
			'mobile_default' => 'no'
		] );
		$this->add_responsive_control( 'show_dots', [
			'label'   => esc_html__('Show Dots', 'cafe-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );
		$this->add_control( 'pause_on_hover', [
			'label'   => esc_html__('Pause on Hover', 'cafe-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'autoplay', [
			'label'   => esc_html__('Autoplay', 'cafe-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'autoplay_speed', [
			'label'     => esc_html__('Autoplay Speed', 'cafe-lite' ),
			'type'      => Controls_Manager::NUMBER,
			'default'   => 5000,
			'condition' => [
				'autoplay' => 'yes',
			],
			'selectors' => [
				'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
			],
		] );

		$this->add_control( 'infinite', [
			'label'   => esc_html__('Infinite Loop', 'cafe-lite' ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->add_control( 'transition', [
			'label'   => esc_html__('Slide Effect', 'cafe-lite' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'slide',
			'options' => [
				'slide' => esc_html__('Slide', 'cafe-lite' ),
				'fade'  => esc_html__('Fade', 'cafe-lite' ),
			],
		] );

		$this->add_control( 'transition_speed', [
			'label'   => esc_html__('Transition Speed (ms)', 'cafe-lite' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 500,
		] );

		$this->add_control( 'content_animation', [
			'label'   => esc_html__('Content Animation', 'cafe-lite' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'fadeInUp',
			'options' => [
				''                  => esc_html__('None', 'cafe-lite' ),
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

		$this->end_controls_section();
		$this->start_controls_section( 'section_style_title', [
			'label' => esc_html__('Text Line 1', 'cafe-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'heading_spacing', [
			'label'     => esc_html__('Spacing', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .slick-slide-inner .cafe-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'heading_color', [
			'label'     => esc_html__('Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-heading' => 'color: {{VALUE}}',

			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_typography',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .cafe-slide-heading',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_title_2', [
			'label' => esc_html__('Text Line 2', 'cafe-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'heading_2_spacing', [
			'label'     => esc_html__('Spacing', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .slick-slide-inner .cafe-slide-heading.sec-heading' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'heading_2_color', [
			'label'     => esc_html__('Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-heading.sec-heading' => 'color: {{VALUE}}',

			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'heading_2_typography',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			'selector' => '{{WRAPPER}} .cafe-slide-heading.sec-heading',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_description', [
			'label' => esc_html__('Text Line 3', 'cafe-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'description_spacing', [
			'label'     => esc_html__('Spacing', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .slick-slide-inner .cafe-slide-desc:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
			],
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__('Text Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-desc' => 'color: {{VALUE}}',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_2,
			'selector' => '{{WRAPPER}} .cafe-slide-desc',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_button', [
			'label' => esc_html__('Button', 'cafe-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'button_size', [
			'label'   => esc_html__('Size', 'cafe-lite' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'sm',
			'options' => self::get_button_sizes(),
		] );


		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_typography',
			'selector' => '{{WRAPPER}} .cafe-slide-btn',
			'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
		] );

		$this->add_control( 'button_border_width', [
			'label'     => esc_html__('Border Width', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 20,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn.normal::before, {{WRAPPER}} .cafe-slide-btn.outline::before' => 'border-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'button_border_radius', [
			'label'     => esc_html__('Border Radius', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );
        $this->add_responsive_control( 'button_padding', [
            'label'      => esc_html__('Padding', 'cafe-lite' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .cafe-slide-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'separator' => 'after',
        ] );
		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__('Text Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_background_color', [
			'label'     => esc_html__('Background Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn.normal::after,{{WRAPPER}} .cafe-slide-btn.line-through::after, {{WRAPPER}} .cafe-slide-btn.outline::after' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_border_color', [
			'label'     => esc_html__('Border Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn.normal::before, {{WRAPPER}} .cafe-slide-btn.outline::before' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [ 'label' => esc_html__('Hover', 'cafe-lite' ) ] );

		$this->add_control( 'button_hover_text_color', [
			'label'     => esc_html__('Text Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_background_color', [
			'label'     => esc_html__('Background Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn.normal:hover:after, {{WRAPPER}} .cafe-slide-btn.outline:hover:after, {{WRAPPER}} .cafe-slide-btn.line-through:hover:after' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_control( 'button_hover_border_color', [
			'label'     => esc_html__('Border Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slide-btn.normal:hover:before, {{WRAPPER}} .cafe-slide-btn.outline:hover:before' => 'border-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section( 'section_style_navigation', [
			'label' => esc_html__('Navigation', 'cafe-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'heading_style_arrows', [
			'label'     => esc_html__('Arrows', 'cafe-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );
		$this->add_control( 'arrow_left_icon', [
			'label'     => esc_html__('Arrow Left', 'cafe-lite' ),
			'type'      => 'clevericon',
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );
		$this->add_control( 'arrow_right_icon', [
			'label'     => esc_html__('Arrow Right', 'cafe-lite' ),
			'type'      => 'clevericon',
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );
		$this->add_control( 'arrows_position', [
			'label'     => esc_html__('Arrows Position', 'cafe-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'inside',
			'options'   => [
				'inside'  => esc_html__('Inside', 'cafe-lite' ),
				'outside' => esc_html__('Outside', 'cafe-lite' ),
			],
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );

		$this->add_control( 'arrows_size', [
			'label'     => esc_html__('Arrows Size', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 20,
					'max' => 60,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .cafe-slider-wrapper .slick-slider .slick-prev:before, {{WRAPPER}} .cafe-slider-wrapper .slick-slider .slick-next:before' => 'font-size: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );

		$this->add_control( 'arrows_color', [
			'label'     => esc_html__('Arrows Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slider-wrapper .slick-arrow:before' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );
		$this->add_control( 'arrows_color_hover', [
			'label'     => esc_html__('Arrows Color Hover', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slider-wrapper .slick-arrow:hover:before' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_arrows' => 'yes',
			],
		] );

		$this->add_control( 'heading_style_dots', [
			'label'     => esc_html__('Dots', 'cafe-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_dots' => 'yes',
			],
		] );

		$this->add_control( 'dots_style', [
			'label'     => esc_html__('Dots Style', 'cafe-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'dots',
			'options'   => [
				'dots'  => esc_html__('Dots', 'cafe-lite' ),
				'line'  => esc_html__('Line', 'cafe-lite' ),
				'group' => esc_html__('Group', 'cafe-lite' ),
			],
			'condition' => [
				'show_dots' => 'yes',
			],
		] );

		$this->add_control( 'dots_position', [
			'label'     => esc_html__('Dots Position', 'cafe-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'inside',
			'options'   => [
				'outside' => esc_html__('Outside', 'cafe-lite' ),
				'inside'  => esc_html__('Inside', 'cafe-lite' ),
			],
			'condition' => [
				'show_dots' => 'yes',
			],
		] );

		$this->add_control( 'dots_size', [
			'label'     => esc_html__('Dots Size', 'cafe-lite' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 5,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-dots li button'  => 'width: {{SIZE}}{{UNIT}}, height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-group li button' => 'width: {{SIZE}}{{UNIT}}, height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides > ul.slick-line button' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition' => [
				'show_dots' => 'yes',
			],
		] );

		$this->add_control( 'dots_color', [
			'label'     => esc_html__('Dots Color', 'cafe-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides > ul.slick-group li.slick-active button, {{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides > ul.slick-group li:hover button'=> 'color: {{VALUE}};background-color: {{VALUE}}',
				'{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-dots li button, {{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-line li button'                      => 'background-color: {{VALUE}}',
				'{{WRAPPER}} .cafe-slider-wrapper .cafe-slider-slides .slick-group li button'                                                                                                 => 'color: {{VALUE}}',
				],
			'condition' => [
				'show_dots' => 'yes',
			],
		] );

		$this->end_controls_section();
        $this->start_controls_section( 'section_style_background', [
            'label' => esc_html__('Background Overlay', 'cafe-lite' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( Group_Control_Background ::get_type(), [
            'name'     => 'bg_overlay_style',
            'selector' => '{{WRAPPER}} .cafe-slide-bg-overlay',
        ] );
        $this->add_control( 'bg_overlay_blend_mode_style', [
            'label'      => esc_html__('Blend Mode', 'cafe-lite' ),
            'type'       => Controls_Manager::SELECT,
            'options'    => [
                ''            => esc_html__('Normal', 'cafe-lite' ),
                'multiply'    => 'Multiply',
                'screen'      => 'Screen',
                'overlay'     => 'Overlay',
                'darken'      => 'Darken',
                'lighten'     => 'Lighten',
                'color-dodge' => 'Color Dodge',
                'color-burn'  => 'Color Burn',
                'hue'         => 'Hue',
                'saturation'  => 'Saturation',
                'color'       => 'Color',
                'exclusion'   => 'Exclusion',
                'luminosity'  => 'Luminosity',
            ],
            'selectors'  => [
                '{{WRAPPER}} .cafe-slide-bg-overlay' => 'mix-blend-mode: {{VALUE}}',
            ],
        ] );
        $this->end_controls_section();
	}

	/**
	 * Render
	 */
	protected function render() {
		$this->getViewTemplate( 'template', 'slider', $this->get_settings() );
	}
}
