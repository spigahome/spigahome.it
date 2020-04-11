<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;

/**
 * Clever Revolution Slider
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('RevSlider')):
    final class CleverRevolutionSlider extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-revolution-slider';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return esc_html__('CAFE Revolution Slider', 'cafe-lite');
        }

        /**
         * @return string
         */
        function get_icon()
        {
            return 'cs-font clever-icon-slider';
        }

        /**
         * Register controls
         */
        protected function _register_controls()
        {
            $this->start_controls_section('settings', [
                'label' => esc_html__('Settings', 'cafe-lite')
            ]);
            $this->add_control('id', [
                'label' => esc_html__('Slider', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getListRevSlider(),
                'description' => esc_html__('Select slider you want display.', 'cafe-lite'),
            ]);

            $this->add_control('css_class', [
                'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
            ]);
            $this->end_controls_section();
        }

        /**
         * Render
         */
        protected function render()
        {
            $settings = array_merge([ // default settings
                'id' => '0',
                'css_class' => '',

            ], $this->get_settings_for_display());

            $this->getViewTemplate('template', 'revolution-slider', $settings);
        }
    }
endif;