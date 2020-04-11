<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverProductAdvanced
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('WooCommerce')):
    final class CleverProductAdvanced extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-product-advanced';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return esc_html__('CAFE Product Advanced', 'cafe-lite');
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
            $this->start_controls_section(
                'section_setting', [
                'label' => esc_html__('Setting', 'cafe-lite')
            ]);

            $this->add_control('title', [
                'label' => esc_html__('Title', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('CAFE Woo', 'cafe-lite'),
            ]);

            $this->add_control('layout', [
                'label' => esc_html__('Layout', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'cafe-lite'),
                    'carousel' => esc_html__('Carousel', 'cafe-lite'),
                ],
            ]);

            $this->add_control('tabs_filter', [
                'label' => esc_html__('Filter', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'cafe-lite'),
                    'cate' => esc_html__('Category', 'cafe-lite'),
                    'asset' => esc_html__('Asset type', 'cafe-lite'),
                ],

            ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'section_options', [
                'label' => esc_html__('Options', 'cafe-lite')
            ]);
            //Cate
            $this->add_control('filter_categories', [
                'label' => esc_html__('Categories', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_categories_for_cafe('product_cat', 2),
                'condition' => [
                    'tabs_filter' => ['none', 'cate'],
                ],
            ]);
            $this->add_control('default_category', [
                'label' => esc_html__('Default categories', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_categories_for_cafe('product_cat'),
                'condition' => [
                    'tabs_filter' => 'cate',
                ],
            ]);
            $this->add_control('asset_type', [
                'label' => esc_html__('Asset type', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => $this->get_woo_asset_type_for_cafe(),
                'condition' => [
                    'tabs_filter' => ['none', 'cate'],
                ],
            ]);

            // Asset
            $this->add_control('filter_assets', [
                'label' => esc_html__('Asset type', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_woo_asset_type_for_cafe(2),
                'condition' => [
                    'tabs_filter' => 'asset',
                ],
            ]);
            $this->add_control('default_asset', [
                'label' => esc_html__('Default asset', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_woo_asset_type_for_cafe(),
                'condition' => [
                    'tabs_filter' => 'asset',
                ],
            ]);
            $this->add_control('filter_categories_for_asset', [
                'label' => esc_html__('Categories', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'options' => $this->get_categories_for_cafe('product_cat', 2),
                'condition' => [
                    'tabs_filter' => 'asset',
                ],
            ]);

            // Filter default
            $this->add_control('product_ids', [
                'label' => esc_html__('Exclude product IDs', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_list_posts('product'),
            ]);
            $this->add_control('orderby', [
                'label' => esc_html__('Order by', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => $this->get_woo_order_by_for_cafe(),
            ]);
            $this->add_control('order', [
                'label' => esc_html__('Order', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => $this->get_woo_order_for_cafe(),
            ]);

            $this->add_control('posts_per_page', [
                'label' => esc_html__('Products per pages', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]);
            // Grid
            $this->add_responsive_control('columns', [
                'label' => esc_html__('Columns for row', 'cafe-lite'),
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
                'condition' => [
                    'layout' => 'grid',
                ],

            ]);
            $this->add_control('pagination', [
                'label' => esc_html__('Pagination', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'cafe-lite'),
                    'numeric' => esc_html__('Numeric', 'cafe-lite'),
                    'ajaxload' => esc_html__('Ajax Load More', 'cafe-lite'),
                    'infinity' => esc_html__('Infinity Scroll', 'cafe-lite'),
                ],
                'condition' => [
                    'layout' => 'grid',
                    'tabs_filter' => 'none',
                ],
            ]);
            // Carousel
            $this->add_responsive_control('slides_to_show', [
                'label' => esc_html__('Slides to Show', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
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
                'label' => esc_html__('Carousel: Speed to Scroll', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'layout' => 'carousel',
                ],

            ]);
            $this->add_control('scroll', [
                'label' => esc_html__('Carousel: Slide to Scroll', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('autoplay', [
                'label' => esc_html__('Carousel: Auto Play', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'cafe-lite'),
                'label_off' => esc_html__('Hide', 'cafe-lite'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('show_pag', [
                'label' => esc_html__('Carousel: Pagination', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'cafe-lite'),
                'label_off' => esc_html__('Hide', 'cafe-lite'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_responsive_control('show_nav', [
                'label' => esc_html__('Carousel: Navigation', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'cafe-lite'),
                'label_off' => esc_html__('Hide', 'cafe-lite'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'layout' => 'carousel',
                ],
            ]);
            $this->add_control('nav_position', [
                'label' => esc_html__('Carousel: Navigation position', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'middle-nav',
                'options' => [
                    'top-nav' => esc_html__('Top', 'cafe-lite'),
                    'middle-nav' => esc_html__('Middle', 'cafe-lite'),
                ],
                'condition' => [
                    'show_nav' => 'true',
                    'layout' => 'carousel',
                ],

            ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'normal_style_settings', [
                'label' => esc_html__('Heading style', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);

            $this->add_control('title_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
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
                'label' => esc_html__('Background', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-title' => 'background: {{VALUE}};'
                ]
            ]);


            $this->end_controls_section();
            $this->start_controls_section(
                'filter_style_settings', [
                'label' => esc_html__('Filter style', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_responsive_control(
                'filter_align',
                [
                    'label' => esc_html__('Align', 'cafe-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__('Left', 'cafe-lite'),
                            'icon' => 'fa fa-align-left',
                        ], 'center' => [
                            'title' => esc_html__('Center', 'cafe-lite'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__('Right', 'cafe-lite'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .cafe-head-product-filter' => 'justify-content: {{VALUE}};'
                    ]
                ]
            );
            $this->add_control('filter_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-head-product-filter a' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('filter_active_color', [
                'label' => esc_html__('Active Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-head-product-filter a.active, {{WRAPPER}} .cafe-head-product-filter a:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'filter_typography',
                    'selector' => '{{WRAPPER}} .cafe-head-product-filter a',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->add_control('filter_space', [
                'label' => esc_html__('Space', 'cafe-lite'),
                'description' => esc_html__('Space between filter item', 'cafe-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-head-product-filter li' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]);
            $this->add_responsive_control('filter_padding', [
                'label' => esc_html__('Padding', 'cafe-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .cafe-head-product-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);
            $this->end_controls_section();
            $this->start_controls_section(
                'carousel_style_settings', [
                'label' => esc_html__('Carousel style', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('arrow_style', [
                'label' => esc_html__('Arrow', 'cafe-lite'),
                'type' => Controls_Manager::HEADING,
            ]);
            $this->add_control('arrow_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('arrow_hover_color', [
                'label' => esc_html__('Hover Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('arrow_size', [
                'label' => esc_html__('Size', 'cafe-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel .cafe-carousel-btn' => 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]);
            $this->add_control('dotted_style', [
                'label' => esc_html__('Dotted', 'cafe-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]);
            $this->add_control('dotted_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'background: {{VALUE}};'
                ]
            ]);
            $this->add_control('dotted_hover_color', [
                'label' => esc_html__('Hover & Active Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel ul.slick-dots li:hover, {{WRAPPER}} .cafe-carousel ul.slick-dots li.slick-active' => 'background: {{VALUE}};'
                ]
            ]);
            $this->add_control('dotted_size', [
                'label' => esc_html__('Size', 'cafe-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .cafe-carousel ul.slick-dots li.slick-active' => 'width: calc({{SIZE}}{{UNIT}} * 3);',
                ],
            ]);
            $this->add_control('dotted_radius', [
                'label' => esc_html__('Border radius', 'cafe-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-carousel ul.slick-dots li' => 'border-radius: {{SIZE}}{{UNIT}};'
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
            // default settings
            $settings = array_merge([
                'title' => '',
                'tabs_filter' => 'cate',
                'filter_categories' => '',
                'default_category' => '',
                'asset_type' => 'all',
                'filter_assets' => '',
                'default_asset' => '',
                'product_ids' => '',
                'orderby' => 'date',
                'order' => 'desc',
                'posts_per_page' => 6,
                'columns' => '',
                'pagination' => '',
                'slides_to_show' => 4,
                'speed' => 5000,
                'scroll' => 1,
                'autoplay' => 'true',
                'show_pag' => 'true',
                'show_nav' => 'true',
                'nav_position' => 'middle-nav',

            ], $this->get_settings_for_display());

            $this->add_inline_editing_attributes('title');

            $this->add_render_attribute('title', 'class', 'cafe-title');

            $this->getViewTemplate('template', 'product-advanced', $settings);
        }
    }
endif;