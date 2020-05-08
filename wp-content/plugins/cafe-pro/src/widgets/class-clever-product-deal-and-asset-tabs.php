<?php 
namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
/**
 * CleverProductDealandAssetTabs
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('WooCommerce')):
    final class CleverProductDealandAssetTabs extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-product-deal-and-asset-tabs';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return __('Clever Product Deal And Asset Tabs', 'cafe-pro');
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
                'section_title_deal', [
                    'label' => __('Deal', 'cafe-pro')
                ]);

                $this->add_control('title_deal', [
                    'label'         => __('Title deal', 'cafe-pro'),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => __( 'CAFE Woo', 'cafe-pro' ),
                ]);
                $this->add_control('deal_id', [
                    'label'         => __('Deal product', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $this->get_list_product_deal(),
                ]);
                $this->add_control('show_deal_position', [
                    'label'         => __('Show deal position', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'left',
                    'options' => [
                        'left'      => __( 'Left', 'cafe-pro' ),
                        'right'     => __( 'Right', 'cafe-pro' ),
                    ],
                ]);

            $this->end_controls_section();

            $this->start_controls_section(
                'section_filter', [
                    'label' => __('Filter', 'cafe-pro')
                ]);
                
                // Asset
                $this->add_control('filter_assets', [
                    'label'         => __('Asset type', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT2,
                    'default'       => '',
                    'multiple'      => true,
                    'options'       => $this->get_woo_asset_type_for_cafe(2),
                ]);
                $this->add_control('default_asset', [
                    'label'         => __('Default asset', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => '',
                    'options'       => $this->get_woo_asset_type_for_cafe(),
                ]);
                $this->add_control('product_ids', [
                    'label'         => __('Exclude product IDs', 'cafe-pro'),
                    'description'   => __('', 'cafe-pro'),
                    'type'          => Controls_Manager::SELECT2,
                    'multiple'      => true,
                    'options'       => $this->get_list_posts('product'),
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
                $this->add_responsive_control('columns',[
                    'label'         => __( 'Columns for row', 'cafe-pro' ),
                    'type'          => Controls_Manager::SLIDER,
                    'range' => [
                        'col' =>[
                            'min' => 1,
                            'max' => 6,
                        ]
                    ],
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
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
            return ['cafe-script'];
        }
        /**
         * Render
         */
        protected function render()
        {
            // default settings
            $settings = array_merge([ 
                'title'                 => '',

                'title_deal'            => '',
                'deal_id'               => '',
                'filter_assets'         => '',
                'default_asset'         => '',
                'columns'               => '',
                
                'product_ids'           => '',
                'orderby'               => 'date',
                'order'                 => 'desc',
                'posts_per_page'        => 6,

                
                
            ], $this->get_settings_for_display());

            $this->add_inline_editing_attributes('title');

            $this->add_render_attribute('title', 'class', 'cafe-title');

            $this->getViewTemplate('template', 'product-deal-and-asset-tabs', $settings);
        }
    }
endif;