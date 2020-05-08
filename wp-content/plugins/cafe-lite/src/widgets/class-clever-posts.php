<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
/**
 * Clever Posts
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverPosts extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-posts';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Posts', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-blog';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('content_settings', [
            'label' => esc_html__('Content Settings', 'cafe-lite')
        ]);
        $this->add_control('cat', [
            'label' => esc_html__('Categories', 'cafe-lite'),
            'type' => Controls_Manager::SELECT2,
            'default' => '',
            'multiple' => true,
            'description' => esc_html__('Select categories want display.', 'cafe-lite'),
            'options' => $this->get_categories_for_cafe('category', 0),
        ]);
        $this->add_control('ignore_sticky_posts', [
            'label' => esc_html__('Ignore sticky post', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'true',
        ]);
        $this->add_control('posts_per_page', [
            'label' => esc_html__('Number of items', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '3',
            'description' => esc_html__('Number post display.', 'cafe-lite'),
        ]);
        $this->add_control('output_type', [
            'label' => esc_html__('Content display', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'excerpt',
            'options' => [
                'excerpt' => esc_html__('Excerpt', 'cafe-lite'),
                'full' => esc_html__('Full Content', 'cafe-lite'),
                'none' => esc_html__('None', 'cafe-lite'),
            ],
            'description' => esc_html__('Number testimonial display.', 'cafe-lite'),
        ]);
        $this->add_control('excerpt_length', [
            'label' => esc_html__('Excerpt Length', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '30',
            'condition' => [
                'output_type' => 'excerpt'
            ],
        ]);
        $this->add_control('pagination', [
            'label' => esc_html__('Pagination', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'none' => esc_html__('none', 'cafe-lite'),
                'numeric' => esc_html__('Numeric', 'cafe-lite'),
            ],
            'condition' => [
                'layout' => ['grid','list'],
            ],
            'description' => esc_html__('Not work with carousel layout. Numeric pagination work only with page have single widget posts. ', 'cafe-lite'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('layout_settings', [
            'label' => esc_html__('Layout', 'cafe-lite')
        ]);
        $this->add_control('layout', [
            'label' => esc_html__('Layout', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid' => esc_html__('Grid', 'cafe-lite'),
                'list' => esc_html__('List', 'cafe-lite'),
                'carousel' => esc_html__('Carousel', 'cafe-lite'),
            ],
            'description' => esc_html__('Layout of blog.', 'cafe-lite'),
        ]);
        $this->add_control('style', [
            'label' => esc_html__('Style', 'cafe-lite'),
            'type' =>  Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => esc_html__('Default', 'cafe-lite'),
                'basic' => esc_html__('Basic', 'cafe-lite'),
                'boxed' => esc_html__('Boxed', 'cafe-lite'),
                'image-shadow' => esc_html__('Image Shadow', 'cafe-lite'),
                'img-left' => esc_html__('Image Left', 'cafe-lite'),
                'first-large' => esc_html__('First Large (work ony with Grid)', 'cafe-lite'),
                'content-overlay' => esc_html__('Content Overlay', 'cafe-lite'),
            ],
            'condition' => [
                'layout' =>  ['carousel','grid']
            ],
        ]);
        $this->add_responsive_control('col', [
            'label' => esc_html__('Columns', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'desktop_default' => '3',
            'tablet_default' =>  '1',
            'mobile_default' => '1',
            'description' => esc_html__('Columns post display per row. With grid layout, min column is 1, max is 6. Unlimited with carousel.', 'cafe-lite'),
            'condition' => [
                'layout' => 'grid'
            ],
        ]);
        $this->add_control('image_size', [
            'label' => esc_html__('Image size', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'medium',
            'options' => $this->getImgSizes(),
            'description' => esc_html__('Select image size fit to layout you want use.', 'cafe-lite'),
        ]);
        $this->add_control('show_date_post', [
            'label' => esc_html__('Show date post', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('show_cat_post', [
            'label' => esc_html__('Show post categories', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('show_author_post', [
            'label' => esc_html__('Show author post', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_control('show_read_more', [
            'label' => esc_html__('Show read more', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
        ]);
        $this->add_responsive_control('slides_to_show',[
            'label'         => esc_html__('Slides to Show', 'elementor' ),
            'type'          => Controls_Manager::SLIDER,
            'range' => [
                'px' =>[
                    'min' => 1,
                    'max' => 10,
                ]
            ],
            'devices' => [ 'desktop', 'tablet', 'mobile' ],
            'desktop_default' => [
                'size' => 4,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => 3,
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => 2,
                'unit' => 'px',
            ],
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_control('scroll', [
            'label' => esc_html__('Slider to scroll', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '1',
            'description' => esc_html__('Number testimonial scroll per time.', 'cafe-lite'),
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('show_nav', [
            'label' => esc_html__('Navigation', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('show_pag', [
            'label' => esc_html__('Pagination', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_responsive_control('autoplay', [
            'label' => esc_html__('Auto Play', 'cafe-lite'),
            'description' => esc_html__('', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'true',
            'default' => 'false',
            'condition' => [
                'layout' => 'carousel'
            ],
        ]);
        $this->add_control('speed', [
            'label' => esc_html__('Auto play speed', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '3000',
            'condition' => [
                'layout' => 'carousel',
                'autoplay' => 'true'
            ],
            'description' => esc_html__('Time(ms) to next slider.', 'cafe-lite'),
        ]);
        $this->add_control('css_class', [
            'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
        ]);
	    $this->end_controls_section();
	    $this->start_controls_section('blog_item_style', [
		    'label' => esc_html__('Blog Item', 'cafe'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->add_control('blog_item_overlay_color_heading', [
		    'label' => esc_html__('Overlay background', 'cafe'),
		    'type' => Controls_Manager::HEADING,
		    'separator'=>'after',
		    'condition' => [
			    'style'   => 'content-overlay'
		    ]
	    ]);
	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'blog_item_overlay_color',
			    'label' => esc_html__('Background Overlay', 'cafe'),
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .post  .wrap-media::before',
			    'condition' => [
				    'style'   => 'content-overlay'
			    ]
		    ]
	    );
	    $this->add_control('blog_item_overlay_color_hv_heading', [
		    'label' => esc_html__('Overlay hover background', 'cafe'),
		    'type' => Controls_Manager::HEADING,
		    'separator'=>'after',
		    'condition' => [
			    'style'   => 'content-overlay'
		    ]
	    ]);
	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'blog_item_overlay_color_hv',
			    'label' => esc_html__('Background Overlay', 'cafe'),
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .post .wrap-media::after',
			    'condition' => [
				    'style'   => 'content-overlay'
			    ]
		    ]
	    );
	    $this->add_control(
		    'content_padding',
		    [
			    'label'         => esc_html__('Content Padding', 'cafe'),
			    'type'          => Controls_Manager::DIMENSIONS,
			    'size_units'    => ['px', 'em', '%'],
			    'selectors'     => [
				    '{{WRAPPER}} .post .wrap-post-item-content'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ]
		    ]
	    );
	    $this->end_controls_section();
	    $this->start_controls_section('title_style', [
		    'label' => esc_html__('Title', 'cafe'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->add_control('title_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .entry-title a' => 'color: {{VALUE}};'
		    ]
	    ]);$this->add_control('title_color_hv', [
		    'label' => esc_html__('Color Hover', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .entry-title a:hover' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'title_typography',
			    'selector' => '{{WRAPPER}} .post .entry-title',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_control('title_space', [
		    'label' => esc_html__('Space', 'cafe'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .post .entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('excerpt_style', [
		    'label' => esc_html__('Excerpt', 'cafe'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->add_control('excerpt_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .entry-content' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'excerpt_typography',
			    'selector' => '{{WRAPPER}} .post .entry-content',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_control('excerpt_space', [
		    'label' => esc_html__('Space', 'cafe'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .post .entry-content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('post_info_style', [
		    'label' => esc_html__('Post Info', 'cafe'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->add_control('post_info_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .post-info' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('post_info_url_color', [
		    'label' => esc_html__('Link Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .post-info a' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('post_info_url_color_hv', [
		    'label' => esc_html__('Link Hover Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .post-info a:hover' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'post_info_typography',
			    'selector' => '{{WRAPPER}} .post .post-info',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_control('post_info_space', [
		    'label' => esc_html__('Space', 'cafe'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .post .post-info' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('cat_style', [
		    'label' => esc_html__('Categories Label', 'cafe'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->start_controls_tabs('cat_color_tabs');

	    $this->start_controls_tab(
		    'cat_color_normal_tab',
		    [
			    'label'         => esc_html__('Normal', 'cafe'),
		    ]
	    );
	    $this->add_control('cat_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .list-cat a' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('cat_bg_color', [
		    'label' => esc_html__('Background Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .list-cat a' => 'background-color: {{VALUE}};'
		    ]
	    ]);
	    $this->end_controls_tab();
	    $this->start_controls_tab(
		    'cat_color_hv_tab',
		    [
			    'label'         => esc_html__('Hover', 'cafe'),
		    ]
	    );
	    $this->add_control('cat_hv_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .list-cat a:hover' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('cat_bg_hv_color', [
		    'label' => esc_html__('Background Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .list-cat a:hover' => 'background-color: {{VALUE}};'
		    ]
	    ]);
	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->add_control(
		    'cat_padding',
		    [
			    'label'         => esc_html__('Padding', 'cafe'),
			    'type'          => Controls_Manager::DIMENSIONS,
			    'size_units'    => ['px', 'em', '%'],
			    'separator'   => 'before',
			    'selectors'     => [
				    '{{WRAPPER}} .post .list-cat a'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ]
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'cat_typography',
			    'selector' => '{{WRAPPER}} .post .list-cat a',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_control('cat_space', [
		    'label' => esc_html__('Space', 'cafe'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .post .list-cat' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('readmore_style', [
		    'label' => esc_html__('Read more button', 'cafe'),
		    'tab' => Controls_Manager::TAB_STYLE,
	    ]);
	    $this->start_controls_tabs('readmore_color_tabs');

	    $this->start_controls_tab(
		    'readmore_color_normal_tab',
		    [
			    'label'         => esc_html__('Normal', 'cafe'),
		    ]
	    );
	    $this->add_control('readmore_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .readmore' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('readmore_bg_color', [
		    'label' => esc_html__('Background Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .readmore' => 'background-color: {{VALUE}};'
		    ]
	    ]);
	    $this->end_controls_tab();
	    $this->start_controls_tab(
		    'readmore_color_hv_tab',
		    [
			    'label'         => esc_html__('Hover', 'cafe'),
		    ]
	    );
	    $this->add_control('readmore_hv_color', [
		    'label' => esc_html__('Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .readmore:hover' => 'color: {{VALUE}};'
		    ]
	    ]);
	    $this->add_control('readmore_bg_hv_color', [
		    'label' => esc_html__('Background Color', 'cafe'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .post .readmore:hover' => 'background-color: {{VALUE}};'
		    ]
	    ]);
	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'readmore_typography',
			    'selector' => '{{WRAPPER}} .post .readmore',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_control('readmore_space', [
		    'label' => esc_html__('Space', 'cafe'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .post .readmore' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
        return ['jquery-slick', 'cafe-script'];
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'cat' => '',
            'ignore_sticky_posts' => 'true',
            'posts_per_page' => '3',
            'output_type' => 'excerpt',
            'excerpt_length' => '30',
            'pagination' => 'none',
            'layout' => 'grid',
            'col' => '3',
            'col_table' => '1',
            'col_mobile' => '1',
            'image_size' => 'medium',
            'show_date_post' => 'false',
            'show_cat_post' => 'false',
            'show_author_post' => 'false',
            'show_read_more' => 'false',
            'show_nav' => 'false',
            'show_pag' => 'false',
            'show_avatar' => 'false',
            'autoplay' => 'false',
            'speed' => '3000',
            'css_class' => '',

        ], $this->get_settings_for_display());


        $this->getViewTemplate('template', 'posts', $settings);
    }
}