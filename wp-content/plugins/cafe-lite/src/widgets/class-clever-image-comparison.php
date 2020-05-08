<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverImageComparison
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverImageComparison extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-image-comparison';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Image Comparison', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-banner';
    }

	/**
	 * @return script
	 */

	public function get_script_depends() {
		return [ 'event-move', 'twentytwenty' ];
	}

	/**
     * Register controls
     */
    protected function _register_controls()
    {
	    $this->start_controls_section( 'general', [
		    'label'       => esc_html__('General', 'cafe-lite' ),
	    ] );
	    $this->add_control('orientation', [
		    'label' => esc_html__('Orientation', 'cafe-lite'),
		    'type' => Controls_Manager::SELECT,
		    'default' => 'horizontal',
		    'options' => [
			    'horizontal' => esc_html__('Horizontal', 'cafe-lite'),
			    'vertical' => esc_html__('Vertical', 'cafe-lite')
		    ]
	    ]);
	    $this->add_control( 'before', [
		    'label'       => esc_html__('Before Image', 'cafe-lite' ),
		    'separator'   => 'before',
		    'type' => Controls_Manager::HEADING,
	    ] );
	    $this->start_controls_tabs( 'before_tabs' );
	    $this->start_controls_tab( 'before_content', [ 'label' => esc_html__('Content', 'cafe-lite' ) ] );
	    $this->add_control('before_image', [
		    'label' => esc_html__('Upload Image', 'cafe-lite'),
		    'description' => esc_html__('Select an image for compare.', 'cafe-lite'),
		    'type' => Controls_Manager::MEDIA,
		    'dynamic' => ['active' => true],
		    'show_external' => true,
		    'default' => [
			    'url' => CAFE_URI . 'assets/img/banner-placeholder.png'
		    ]
	    ]);
	    $this->add_control(
		    'before_title',
		    [
			    'label' => esc_html__('Title', 'cafe-lite'),
			    'type' => Controls_Manager::TEXT,
			    'label_block' => true,
		    ]
	    );
	    $this->end_controls_tab();

	    $this->start_controls_tab( 'before_imagestyle', [ 'label' => esc_html__('Style', 'cafe-lite' ) ] );
	    $this->add_control( 'before_title_color', [
		    'label'     => esc_html__('Color', 'cafe-lite' ),
		    'type'      => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .twentytwenty-before-label:before' => 'color: {{VALUE}}',
		    ],
	    ] );

	    $this->add_control( 'before_grayscale', [
		    'label'      => esc_html__('Grayscale', 'cafe-lite' ),
		    'type'       => Controls_Manager::SLIDER,
		    'size_units' => [ '%' ],
		    'range'      => [
			    '%' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors'  => [
			    '{{WRAPPER}} image.before-img' => 'filter: grayscale({{SIZE}});',
		    ],
	    ] );
	    $this->end_controls_tab();
	    $this->end_controls_tabs();
        $this->add_control( 'after', [
		    'label'       => esc_html__('After Image', 'cafe-lite' ),
		    'separator'   => 'before',
		    'type' => Controls_Manager::HEADING,
	    ] );
	    $this->start_controls_tabs( 'after_tabs' );
	    $this->start_controls_tab( 'after_content', [ 'label' => esc_html__('Content', 'cafe-lite' ) ] );
	    $this->add_control('after_image', [
		    'label' => esc_html__('Upload Image', 'cafe-lite'),
		    'description' => esc_html__('Select an image for compare.', 'cafe-lite'),
		    'type' => Controls_Manager::MEDIA,
		    'dynamic' => ['active' => true],
		    'show_external' => true,
		    'default' => [
			    'url' => CAFE_URI . 'assets/img/banner-placeholder.png'
		    ]
	    ]);
	    $this->add_control(
		    'after_title',
		    [
			    'label' => esc_html__('Title', 'cafe-lite'),
			    'type' => Controls_Manager::TEXT,
			    'label_block' => true,
		    ]
	    );
	    $this->end_controls_tab();

	    $this->start_controls_tab( 'after_imagestyle', [ 'label' => esc_html__('Style', 'cafe-lite' ) ] );
	    $this->add_control( 'after_title_color', [
		    'label'     => esc_html__('Color', 'cafe-lite' ),
		    'type'      => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .twentytwenty-after-label:before' => 'color: {{VALUE}}',
		    ],
	    ] );

	    $this->add_control( 'after_grayscale', [
		    'label'      => esc_html__('Grayscale', 'cafe-lite' ),
		    'type'       => Controls_Manager::SLIDER,
		    'size_units' => [ '%' ],
		    'range'      => [
			    '%' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors'  => [
			    '{{WRAPPER}} img.after-img' => 'filter: grayscale({{SIZE}});',
		    ],
	    ] );
	    $this->end_controls_tab();
	    $this->end_controls_tabs();

        $this->end_controls_section();
	    $this->start_controls_section( 'Style settings', [
		    'label' => esc_html__('Style', 'cafe-lite' ),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ] );
	    $this->add_control( 'overlay_bg', [
		    'label'     => esc_html__('Overlay Background', 'cafe-lite' ),
		    'type'      => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .twentytwenty-overlay' => 'background: {{COLOR}};',
		    ],
	    ] );
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'label_typography',
			    'selector' => '{{WRAPPER}} .twentytwenty-overlay>div:before',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
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
	        'orientation'=>'horizontal',
            'before_image' =>  CAFE_URI . 'assets/img/banner-placeholder.png',
	        'before_title' =>'',
            'after_image' =>  CAFE_URI . 'assets/img/banner-placeholder.png',
	        'after_title' =>'',
        ], $this->get_settings_for_display());


        $this->getViewTemplate('template', 'image-comparison', $settings);
    }
}
