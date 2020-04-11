<?php

namespace Cafe\Widgets;

use Elementor\Controls_Manager;

/**
 * Clever Single Product
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('WooCommerce')):
    final class CleverSingleProduct extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-single-product';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return esc_html__('CAFE Single Product', 'cafe-lite');
        }

        /**
         * @return string
         */
        function get_icon()
        {
            return 'cs-font clever-icon-online-shopping';
        }

        /**
         * Register controls
         */
        protected function _register_controls()
        {
            $this->start_controls_section('content_settings', [
                'label' => esc_html__('Settings', 'cafe-lite')
            ]);
            $this->add_control('product_id', [
                'label' => esc_html__('Product', 'cafe-lite'),
                'default' => '',
                'description' => esc_html__('Select product you want display.', 'cafe-lite'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_list_posts('product'),
            ]);
            $this->add_control('css_class', [
                'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
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
                'product_id' => '',
                'css_class' => '',

            ], $this->get_settings_for_display());

            $this->getViewTemplate('template', 'single-product', $settings);
        }
    }
endif;