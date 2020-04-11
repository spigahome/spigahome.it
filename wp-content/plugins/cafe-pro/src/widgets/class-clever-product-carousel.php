<?php 
namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
/**
 * CleverProductCarousel
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('WooCommerce')):
    final class CleverProductCarousel extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-product-carousel';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return __('Clever Product Carousel', 'cafe-pro');
        }

        /**
         * @return string
         */
        function get_icon()
        {
            return 'cs-font clever-icon-carousel';
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
         * Register controls
         */
        protected function _register_controls()
        {
            $this->start_controls_section(
                'section_title', [
                    'label' => __('Title', 'cafe-pro')
                ]);

                $this->add_control('title', [
                    'label'		    => __('Title', 'cafe-pro'),
                    'type'		    => Controls_Manager::TEXT,
                    'default'       => __( 'CAFE Woo', 'cafe-pro' ),
                ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'section_filter', [
                    'label' => __('Filter', 'cafe-pro')
                ]);

                $this->add_control('filter_categories', [
                    'label'         => __('Categories', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT2,
                    'default'       => '',
                    'multiple'      => true,
                    'options'       => $this->get_categories_for_cafe('product_cat', 2 ),
                ]);
                $this->add_control('product_ids', [
                    'label'         => __('Exclude product IDs', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT2,
                    'multiple'      => true,
                    'options'       => $this->get_list_posts('product'),
                ]);
                $this->add_control('asset_type', [
                    'label'         => __('Asset type', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'all',
                    'options'       => $this->get_woo_asset_type_for_cafe(),
                ]);
                $this->add_control('orderby', [
                    'label'         => __('Order by', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'date',
                    'options'       => $this->get_woo_order_by_for_cafe(),
                ]);
                $this->add_control('order', [
                    'label'         => __('Order', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'desc',
                    'options'       => $this->get_woo_order_for_cafe(),
                ]);
                $this->add_control('posts_per_page', [
                    'label'         => __('Products per pages', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 6,
                ]);
                
            $this->end_controls_section();

            $this->start_controls_section(
                'section_carousel', [
                    'label' => __('Options', 'cafe-pro')
                ]);
                $this->add_responsive_control('slides_to_show',[
                    'label'         => __( 'Slides to Show', 'elementor' ),
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
                    
                ]);

                $this->add_control('speed', [
                    'label'         => __('Carousel: Speed to Scroll', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 5000,
                ]);
                $this->add_control('scroll', [
                    'label'         => __('Carousel: Slide to Scroll', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::NUMBER,
                    'default'       => 1,
                ]);
                $this->add_responsive_control('autoplay', [
                    'label'         => __('Carousel: Auto Play', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'cafe-pro' ),
                    'label_off' => __( 'Hide', 'cafe-pro' ),
                    'return_value' => 'true',
                    'default' => 'true',
                ]);
                $this->add_responsive_control('show_pag', [
                    'label'         => __('Carousel: Pagination', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'cafe-pro' ),
                    'label_off' => __( 'Hide', 'cafe-pro' ),
                    'return_value' => 'true',
                    'default' => 'true',
                ]);
                $this->add_responsive_control('show_nav', [
                    'label'         => __('Carousel: Navigation', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SWITCHER,
                    'label_on' => __( 'Show', 'cafe-pro' ),
                    'label_off' => __( 'Hide', 'cafe-pro' ),
                    'return_value' => 'true',
                    'default' => 'true',
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
                        'show_nav' => 'true',
                    ],
                ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'normal_style_settings', [
                    'label' => __('Layout', 'cafe-pro'),
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
            
        }

        /**
         * Load style
         */
        public function get_style_depends()
        {
            return ['cafe-style'];
        }
        /**
         * Render
         */
        protected function render()
        {
            // default settings
            $settings = array_merge([ 
                'title'                 => '',
                'filter_categories'     => '',
                'product_ids'           => '',
                'asset_type'            => 'all',
                'orderby'               => 'date',
                'order'                 => 'desc',
                'posts_per_page'        => 6,
                'slides_to_show'        => 4,
                'speed'                 => 5000,
                'scroll'                => 1,
                'autoplay'              => 'true',
                'show_pag'              => 'true',
                'show_nav'              => 'true',
                'nav_position'          => 'middle-nav',
                
            ], $this->get_settings_for_display());

            $this->add_inline_editing_attributes('title');

            $this->add_render_attribute('title', 'class', 'cafe-title');

            $this->getViewTemplate('template', 'product-carousel', $settings);
        }
    }
endif;