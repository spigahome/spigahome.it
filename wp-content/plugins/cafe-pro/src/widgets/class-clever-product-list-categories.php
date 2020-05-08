<?php

namespace Cafe\Widgets;

use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * CleverProductListCategories
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('WooCommerce')):
    final class CleverProductListCategories extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-product-list-categories';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return __('Clever Product Categories List', 'cafe-pro');
        }

        /**
         * @return string
         */
        function get_icon()
        {
            return 'cs-font clever-icon-cart-3';
        }

        /**
         * Register controls
         */
        protected function _register_controls()
        {
            $repeater = new \Elementor\Repeater();

            $this->start_controls_section(
                'section_title', [
                'label' => __('Title', 'cafe-pro')
            ]);

            $this->add_control('title', [
                'label' => __('Title', 'cafe-pro'),
                'type' => Controls_Manager::TEXT,
                'default' => __('CAFE Woo', 'cafe-pro'),
            ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'section_filter', [
                'label' => __('Filter', 'cafe-pro')
            ]);
            $this->add_control('layout', [
                'label' => __('Layout', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'cafe-pro'),
                    'carousel' => __('Carousel', 'cafe-pro'),
                ],
            ]);
            $this->add_control('children_cate', [
                'label' => __('Children Category', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sub_cate',
                'options' => [
                    'sub_cate' => __('Show children', 'cafe-pro'),
                    'list_cate' => __('Current category ', 'cafe-pro'),
                ],
            ]);
            $this->add_control('feature', [
                'label' => __('Get Category', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'multi',
                'options' => [
                    'repeat' => __('Repeat', 'cafe-pro'),
                    'multi' => __('Multi Select', 'cafe-pro'),
                ],
            ]);
            $this->add_control('filter_parent_categories', [
                'label' => __('Parent categories', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_parent_categories_for_cafe('product_cat', 2),
                'condition' => [
                    'children_cate' => 'sub_cate',
                    'feature' => 'multi',
                ],
            ]);
            $this->add_control('filter_categories', [
                'label' => __('Categories', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_categories_for_cafe('product_cat', 2),
                'condition' => [
                    'children_cate' => 'list_cate',
                    'feature' => 'multi',
                ],
            ]);

            $repeater->add_control('image', [
                'label' => __('Upload Image', 'cafe-lite'),
                'description' => __('Select an image for the banner.', 'cafe-lite'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => ['active' => true],
                'show_external' => true,
                'default' => [
                    'url' => CAFE_URI . '/assets/img/banner-placeholder.png'
                ]
            ]);
            $repeater->add_control( 'category', [
                'label'       => __( 'Category', 'cafe-pro' ),
                'description' => __( '', 'cafe-pro' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'options'     => $this->get_categories_for_cafe( 'product_cat' ),
            ] );
            $this->add_control('repeater',[
                'label' => __( 'Add Category', 'cafe-lite' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'condition' => [
                    'feature' => 'repeat',
                ],
            ]);

            $this->add_responsive_control('columns', [
                'label' => __('Columns for row', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'col' => [
                        'min' => 1,
                        'max' => 6,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 4,
                    'unit' => 'col',
                ],
                'tablet_default' => [
                    'size' => 3,
                    'unit' => 'col',
                ],
                'mobile_default' => [
                    'size' => 2,
                    'unit' => 'col',
                ],
            ]);
            $this->add_control('max_sub_cat', [
                'label' => __('Maximum sub cat', 'cafe-pro'),
                'description' => __('Maximum sub cat display, leave it 0 if want display all', 'cafe-pro'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'condition' => [
                    'children_cate' => 'sub_cate',
                ],
            ]);
            $this->add_control('show_view_more', [
                'label' => __('Show view more', 'cafe-pro'),
                'description' => __('Work with case number sub categories more than Maximum sub cat', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'children_cate' => 'sub_cate',
                ],
            ]);
            $this->add_control('show_view_more_text', [
                'label' => __('View more text', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::TEXT,
                'default'=>'View More',
                'condition' => [
                    'show_view_more' => 'true',
                    'children_cate' => 'sub_cate',
                ],
            ]);
            $this->add_responsive_control( 'image_position', [
                'label'                => esc_html__('Image Position', 'cafe-lite' ),
                'type'                 => Controls_Manager::CHOOSE,
                'label_block'          => false,
                'options'              => [
                    'left'   => [
                        'title' => esc_html__('Left', 'cafe-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__('Center', 'cafe-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'cafe-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'selectors'            => [
                    '{{WRAPPER}} .wrap-content-category-item' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left'      => '',
                    'top'       => 'flex-flow: column;',
                    'right'     => 'flex-direction: row-reverse;',
                ],
            ] );
            $this->add_control('vertical_align', [
                'label' => __('Vertical Alignment', 'cafe-pro'),
                'description' => __('', 'cafe-pro'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => __('Top', 'cafe-pro'),
                    'center' => __('Center', 'cafe-pro'),
                    'bottom' => __('Bottom', 'cafe-pro'),
                ],
                'selectors'            => [
                    '{{WRAPPER}} .wrap-content-category-item' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'top'      => 'align-items: flex-start;',
                    'center'   => 'align-items: center',
                    'bottom'   => 'align-items: flex-end;',
                ],
            ]);
            $this->add_responsive_control('slides_to_show',[
                'label'         => __( 'Slides to Show', 'cafe-pro' ),
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
                    'layout' => 'carousel',
                ],
                
            ]);

            $this->add_control('speed', [
                'label'         => __('Carousel: Speed to Scroll', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 5000,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_control('scroll', [
                'label'         => __('Carousel: Slide to Scroll', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::NUMBER,
                'default'       => 1,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('autoplay', [
                'label'         => __('Carousel: Auto Play', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'cafe-pro' ),
                'label_off' => __( 'Hide', 'cafe-pro' ),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('show_pag', [
                'label'         => __('Carousel: Pagination', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'cafe-pro' ),
                'label_off' => __( 'Hide', 'cafe-pro' ),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('show_nav', [
                'label'         => __('Carousel: Navigation', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'cafe-pro' ),
                'label_off' => __( 'Hide', 'cafe-pro' ),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_control('nav_position', [
                'label'         => __('Carousel: Navigation Position', 'cafe-pro'),
                'description'   => __('', 'cafe-pro'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'middle-nav',
                'options' => [
                    'top-nav'  => __( 'Top', 'cafe-pro' ),
                    'middle-nav' => __( 'Middle', 'cafe-pro' ),
                ],
                'condition'     => [
                    'layout' => 'carousel',
                    'show_nav' => 'true',
                ],
            ]);
            $this->add_responsive_control('nav_position_custom',[
                'label' => esc_html__('Custom Navigation Position', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' =>[
                        'min' => 0,
                        'max' => 300,
                    ],
                    '%' =>[
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap.top-nav span.cafe-carousel-btn' => 'top:  {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'layout' => 'carousel',
                    'show_nav' => 'true',
                    'nav_position' => 'top-nav',
                ],
            ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'normal_style_settings', [
                'label' => __('Heading', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);

            $this->add_control('title_color', [
                'label' => __('Title Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-title' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .cafe-title',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->add_control('title_background', [
                'label' => __('Title Background', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-title' => 'background: {{VALUE}};'
                ]
            ]);


            $this->end_controls_section();
            $this->start_controls_section(
                'content_style_settings', [
                'label' => __('Content Block', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('content_block_bg', [
                'label' => __('Background', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wrap-content-category-item' => 'background: {{VALUE}};'
                ]
            ]);
            $this->add_responsive_control('content_block_padding', [
                'label' => __('Padding', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wrap-content-category-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);
            $this->add_responsive_control('content_block_border_radius', [
                'label' => __('Border Radius', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wrap-content-category-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'content_block_border',
                    'label' => __('Border', 'cafe-pro'),
                    'placeholder' => '1px',
                    'default' => '1px',
                    'selector' => '{{WRAPPER}} .wrap-content-category-item',
                    'separator' => 'before',
                ]
            );
            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'content_block_shadow',
                    'separator' => 'before',
                    'selector' => '{{WRAPPER}} .wrap-content-category-item',
                ]
            );
            $this->end_controls_section();
            $this->start_controls_section(
                'image_style_settings', [
                'label' => __('Image Block', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_responsive_control('image_width',[
                'label' => esc_html__('Width', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wrap-content-category-item .category-image' => 'width:  {{SIZE}}{{UNIT}};',
                ],
            ]);
            $this->add_responsive_control('image_margin', [
                'label' => __('Margin', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wrap-content-category-item .category-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);
            $this->add_responsive_control('image_border_radius', [
                'label' => __('Border Radius', 'cafe-pro'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wrap-content-category-item .category-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);
            
            $this->end_controls_section();
            $this->start_controls_section(
                'normal_parent_style_settings', [
                'label' => __('Category Heading', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_responsive_control(
                'cat_heading_align',
                [
                    'label' => __('Align', 'cafe-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'cafe-pro'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'center' => __('Center', 'cafe-pro'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'cafe-pro'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .category-content .product-category-heading' => 'text-align: {{VALUE}};'
                    ],
                ]
            );
            $this->add_control('parent_category_color', [
                'label' => __('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category-content .product-category-heading a' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('parent_category_color_hover', [
                'label' => __('Color Hover', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category-content .product-category-heading a:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'parent_category_typography',
                    'selector' => '{{WRAPPER}} .category-content .product-category-heading',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->add_responsive_control('parent_category_space', [
                'label' => __('Space', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-content .product-category-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]);
            $this->end_controls_section();
            $this->start_controls_section(
                'cat_style_settings', [
                'label' => __('Sub Category item', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'children_cate' => 'sub_cate',
                ],
            ]);
            $this->add_responsive_control(
                'cat_align',
                [
                    'label' => __('Align', 'cafe-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'cafe-pro'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'center' => __('Center', 'cafe-pro'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'cafe-pro'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .category-content .category-item' => 'text-align: {{VALUE}};'
                    ]
                ]
            );
            $this->add_control('cat_color', [
                'label' => __('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category-content .category-item a' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('cat_color_hover', [
                'label' => __('Color Hover', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .category-content .category-item a:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cat_typography',
                    'selector' => '{{WRAPPER}} .category-content .category-item',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->add_responsive_control('cat_space', [
                'label' => __('Space', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}  .category-content .category-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]);
            $this->end_controls_section();
            $this->start_controls_section(
                'viewmore_style_settings', [
                'label' => __('View More', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_view_more' => 'true',
                ],
            ]);
            $this->add_responsive_control(
                'viewmore_align',
                [
                    'label' => __('Align', 'cafe-pro'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'cafe-pro'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'none' => [
                            'center' => __('Center', 'cafe-pro'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'right' => [
                            'title' => __('Right', 'cafe-pro'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .category-content' => 'text-align: {{VALUE}};'
                    ]
                ]
            );
            $this->add_control('viewmore_color', [
                'label' => __('Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category-content .view-more' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('viewmore_color_hover', [
                'label' => __('Color Hover', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .category-content .view-more:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'viewmore_typography',
                    'selector' => '{{WRAPPER}} .category-content .view-more',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->add_responsive_control('viewmore_space', [
                'label' => __('Space', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}  .category-content .view-more' => 'margin-top: {{SIZE}}{{UNIT}};',
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
	    public function get_script_depends()
	    {
		    return ['jquery-slick', 'cafe-script'];
	    }
        /**
         * Render
         */
        protected function render()
        {
            // default settings
            $settings = array_merge([
                'title' => '',
                'layout' => 'grid',
                'children_cate' => 'sub_cate',
                'filter_parent_categories' => '',
                'filter_categories' => '',
                'max_sub_cat' => '4',
                'show_view_more' => 'false',
                'columns' => '',

            ], $this->get_settings_for_display());

            $this->add_inline_editing_attributes('title');

            $this->add_render_attribute('title', 'class', 'cafe-title');

            $this->getViewTemplate('template', 'product-list-categories', $settings);
        }
    }
endif;