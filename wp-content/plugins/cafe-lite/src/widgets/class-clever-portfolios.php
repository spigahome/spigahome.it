<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

/**
 * Clever Portfolios
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('Clever_Portfolio')):
    final class CleverPortfolios extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-portfolios';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return esc_html__('CAFE Portfolios', 'cafe-lite');
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
            $this->start_controls_section('general_settings', [
                'label' => esc_html__('General Settings', 'cafe-lite')
            ]);
            $this->add_control('cats', [
                'label' => esc_html__('Categories', 'cafe-lite'),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => true,
                'description' => esc_html__('Select categories want display.', 'cafe-lite'),
                'options' => $this->get_categories_for_cafe('portfolio_category', 0),
            ]);
            $this->add_control('posts_per_page', [
                'label' => esc_html__('Number of items', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
                'description' => esc_html__('Number post display.', 'cafe-lite'),
            ]);
            $this->add_control('order_by', [
                'label' => esc_html__('Order By', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__('Date', 'cafe-lite'),
                    'id' => esc_html__('ID', 'cafe-lite'),
                    'title' => esc_html__('Title', 'cafe-lite'),
                    'comment-count' => esc_html__('Comment Count', 'cafe-lite'),
                    'rand' => esc_html__('Random', 'cafe-lite'),
                ],
            ]);
            $this->add_control('order', [
                'label' => esc_html__('Order', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC' => esc_html__('Ascending', 'cafe-lite'),
                    'DESC' => esc_html__('Descending', 'cafe-lite'),
                ],
            ]);
            $this->add_control('pagination', [
                'label' => esc_html__('Pagination', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('none', 'cafe-lite'),
                    'standard' => esc_html__('Standard', 'cafe-lite'),
                    'infinite' => esc_html__('Infinite', 'cafe-lite'),
                    'loadmore' => esc_html__('Loadmore', 'cafe-lite'),
                ],
                'condition' => [
                    'layout' => ['grid', 'metro', 'masonry'],
                ],
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
                    'metro' => esc_html__('Metro', 'cafe-lite'),
                    'masonry' => esc_html__('Masonry', 'cafe-lite'),
                    'carousel' => esc_html__('Carousel', 'cafe-lite'),
                ],
            ]);
            $this->add_control('style', [
                'label' => esc_html__('Style', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'cafe-lite'),
                    'style-1' => esc_html__('Style 1', 'cafe-lite'),
                    'style-2' => esc_html__('Style 2', 'cafe-lite'),
                    'style-3' => esc_html__('Style 3', 'cafe-lite'),
                    'style-4' => esc_html__('Style 4', 'cafe-lite'),
                    'style-5' => esc_html__('Style 5', 'cafe-lite'),
                    'style-6' => esc_html__('Style 6', 'cafe-lite'),
                    'style-7' => esc_html__('Style 7', 'cafe-lite'),
                ],
                'separator' => 'after',
            ]);
            $this->add_control('layout_mod', [
                'label' => esc_html__('Layout Mod', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'masonry' => esc_html__('Masonry', 'cafe-lite'),
                    'fitRows' => esc_html__('Fit Rows', 'cafe-lite'),
                    'vertical' => esc_html__('Vertical', 'cafe-lite'),
                ],
                'condition' => [
                    'layout' => 'masonry'
                ],
            ]);
            $this->add_control('carousel_size', [
                'label' => esc_html__('Carousel Size', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => esc_html__('Auto', 'cafe-lite'),
                    'full-screen' => esc_html__('Full Screen', 'cafe-lite'),
                    'custom' => esc_html__('Custom', 'cafe-lite'),
                ],
                'condition' => [
                    'layout' => 'carousel'
                ],
            ]);

            $this->add_control('carousel_height', [
                'label' => esc_html__('Carousel Height', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '768',
                'condition' => [
                    'layout' => 'carousel',
                    'carousel_size' => 'custom'
                ],
            ]);
            $this->add_responsive_control('columns', [
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
                    'layout' => 'carousel'
                ],
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-item' => 'width: calc(100% / {{SIZE}});'
                ],
            ]);
            $this->add_control('rows', [
                'label' => esc_html__('Rows', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'desktop_default' => '2',
                'condition' => [
                    'layout' => ['carousel'],
                ],
            ]);
            $this->add_control('scroll', [
                'label' => esc_html__('Slider to scroll', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1',
                'description' => esc_html__('Number item scroll per time.', 'cafe-lite'),
                'condition' => [
                    'layout' => 'carousel'
                ],
            ]);
            $this->add_control('show_nav', [
                'label' => esc_html__('Navigation', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'layout' => 'carousel'
                ],
            ]);
            $this->add_control('show_pag', [
                'label' => esc_html__('Pagination', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'layout' => 'carousel'
                ],
            ]);
            $this->add_control('autoplay', [
                'label' => esc_html__('Auto Play', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => '',
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
            $this->add_control('cols', [
                'label' => esc_html__('Columns', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
                'condition' => [
                    'layout' => ['grid', 'metro', 'masonry'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-item' => 'width: calc(100% / {{VALUE}});'
                ],
                'description' => esc_html__('Columns post display per row. With grid layout, min column is 1, max is 6. Unlimited with carousel.', 'cafe-lite'),
            ]);

            $this->add_control('show_filter', [
                'label' => esc_html__('Show Filter', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'false',
                'separator' => 'before',
                'condition' => [
                    'layout' => ['grid', 'metro', 'masonry'],
                ],
            ]);
            $this->add_control('img_size', [
                'label' => esc_html__('Image size', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => $this->getImgSizes(),
                'description' => esc_html__('Select image size fit to layout you want use.', 'cafe-lite'),
            ]);
            $this->add_control('gutter', [
                'label' => esc_html__('Gutter', 'cafe-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'description' => esc_html__('Space between each portfolio item.', 'cafe-lite'),
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-item' => 'padding: calc({{VALUE}}px / 2);'
                ]
            ]);
            $this->add_control('show_cat', [
                'label' => esc_html__('Show categories', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'false',
            ]);
            $this->add_control('show_date', [
                'label' => esc_html__('Show Date', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'false',
            ]);
            $this->add_control('view_more', [
                'label' => esc_html__('Show Read more', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'false',
            ]);
            $this->add_control('view_more_text', [
                'label' => esc_html__('Read more label', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Read more', 'cafe-lite'),
                'condition' => [
                    'view_more' => 'true',
                ],
            ]);
            $this->add_control('open_new_window', [
                'label' => esc_html__('Open in new window', 'cafe-lite'),
                'description' => esc_html__('', 'cafe-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'default' => 'false',
            ]);
            $this->end_controls_section();
            $this->start_controls_section('style_settings', [
                'label' => esc_html__('General Style', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('overlay_color_heading', [
                'label' => esc_html__('Overlay Color', 'cafe-lite'),
                'type' => Controls_Manager::HEADING,
            ]);
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'overlay_color',
                    'label' => esc_html__('Background Overlay', 'cafe-lite'),
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .clever-portfolio-item .wrap-portfolio-item::after',
                ]
            );
            $this->add_control('content_color', [
                'label' => esc_html__('Content Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-item .portfolio-info' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('content_background_color', [
                'label' => esc_html__('Content Background Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-item' => 'background-color: {{VALUE}};'
                ]
            ]);

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'item_box_shadow',
                    'selector' => '{{WRAPPER}} .wrap-portfolio-item',
                ]
            );
            $this->add_responsive_control('content_padding', [
                'label' => esc_html__('Content Padding', 'cafe-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);
            $this->end_controls_section();
            $this->start_controls_section('title_style_settings', [
                'label' => esc_html__('Title', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('title_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title a' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('title_color_hv', [
                'label' => esc_html__('Hover Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title a:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .title a',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->end_controls_section();
            $this->start_controls_section('cat_style_settings', [
                'label' => esc_html__('Categories', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('cat_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-info .list-cat a' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('cat_bg_color', [
                'label' => esc_html__('Background Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-info .list-cat a' => 'background-color: {{VALUE}};'
                ]
            ]);
            $this->add_control('cat_color_hv', [
                'label' => esc_html__('Hover Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-info .list-cat a:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('cat_bg_color_hv', [
                'label' => esc_html__('Hover Background Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-info .list-cat a:hover' => 'background-color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'cat_typography',
                    'selector' => '{{WRAPPER}} .portfolio-info .list-cat a',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->end_controls_section();
            $this->start_controls_section('date_style_settings', [
                'label' => esc_html__('Date', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('date_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-date' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'date_typography',
                    'selector' => '{{WRAPPER}} .post-date',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->end_controls_section();
            $this->start_controls_section('readmore_style_settings', [
                'label' => esc_html__('Button', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_control('read_more_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .btn-readmore' => 'color: {{VALUE}};'
                ]
            ]);


            $this->start_controls_tabs( 'button_readmore_tabs' );

            $this->start_controls_tab( 'button_readmore_normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );
            $this->add_control('read_more_bg_color', [
                'label' => esc_html__('Background Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .btn-readmore' => 'background-color: {{VALUE}};'
                ]
            ]);
            $this->end_controls_tab();
            $this->start_controls_tab( 'button_readmore_bg_gradient_tab', [ 'label' => esc_html__('Gradient', 'cafe-lite' ) ] );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'read_more_bg_gradient',
                    'label' => esc_html__('Background', 'cafe-lite'),
                    'types' => ['gradient'],
                    'selector' => '{{WRAPPER}} .btn-readmore',
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();


            $this->add_control('read_more_color_hv', [
                'label' => esc_html__('Hover Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .btn-readmore:hover' => 'color: {{VALUE}};'
                ]
            ]);

            $this->start_controls_tabs( 'button_readmore_hv_tabs' );

            $this->start_controls_tab( 'button_readmore_hv_normal', [ 'label' => esc_html__('Normal', 'cafe-lite' ) ] );
            $this->add_control('read_more_bg_color_hv', [
                'label' => esc_html__('Hover Background Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .btn-readmore:hover' => 'background-color: {{VALUE}};'
                ]
            ]);
            $this->end_controls_tab();
            $this->start_controls_tab( 'button_readmore_bg_hv_gradient_tab', [ 'label' => esc_html__('Gradient', 'cafe-lite' ) ] );
            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'read_more_bg_color_hv_gradient',
                    'label' => esc_html__('Background', 'cafe-lite'),
                    'types' => ['gradient'],
                    'selector' => '{{WRAPPER}} .btn-readmore:hover',
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'read_more_typography',
                    'selector' => '{{WRAPPER}} .btn-readmore',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->end_controls_section();
            $this->start_controls_section('filter_style_settings', [
                'label' => esc_html__('Filter Style', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]);
            $this->add_responsive_control(
                'filter_align',
                [
                    'label' => esc_html__('Align', 'cafe-lite'),
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
                        '{{WRAPPER}} .clever-portfolio-filter' => 'text-align: {{VALUE}};'
                    ]
                ]
            );
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'filter_typography',
                    'selector' => '{{WRAPPER}} .clever-portfolio-filter li',
                    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                ]
            );
            $this->add_control('filter_color', [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-filter li' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_control('filter_color_hv', [
                'label' => esc_html__('Hover/Active Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-filter li.active,{{WRAPPER}} .clever-portfolio-filter li:hover' => 'color: {{VALUE}};'
                ]
            ]);
            $this->add_responsive_control('filter_space', [
                'label' => esc_html__('Space', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ]
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' =>30,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .clever-portfolio-filter' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                ],
            ]);
            $this->end_controls_section();
        }

        /**
         * Load style
         */
        public function get_style_depends()
        {
            return ['cafe-style','clever-portfolio','slick-theme'];
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
            return ['jquery-slick', 'cafe-script','isotope','clever-portfolio-js'];
        }

        /**
         * Render
         */
        protected function render()
        {
            $settings = array_merge([ // default settings
                'cats' => '',
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

            ], $this->get_settings_for_display());


            $this->getViewTemplate('template', 'portfolios', $settings);
        }
    }
endif;