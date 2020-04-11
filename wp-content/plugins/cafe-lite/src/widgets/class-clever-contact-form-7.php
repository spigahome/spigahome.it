<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;

/**
 * Clever Contact Form 7
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
if (class_exists('WPCF7')):
    final class CleverContactForm7 extends CleverWidgetBase
    {
        /**
         * @return string
         */
        function get_name()
        {
            return 'clever-contact-form-7';
        }

        /**
         * @return string
         */
        function get_title()
        {
            return esc_html__('CAFE Contact Form 7', 'cafe-lite');
        }

        /**
         * @return string
         */
        function get_icon()
        {
            return 'cs-font clever-icon-horizontal-tablet-with-pencil';
        }

        /**
         * Register controls
         */
        protected function _register_controls()
        {
            //Get list contact form
            $cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
            $contact_forms = array();
            if ( $cf7 ) {
                foreach ( $cf7 as $cform ) {
                    $contact_forms[$cform->ID] = $cform->post_title;
                }
            } else {
                $contact_forms[0] = esc_html__( 'No forms found', 'cafe-lite' );
            }

            $this->start_controls_section('settings', [
                'label' => esc_html__('Settings', 'cafe-lite')
            ]);
            $this->add_control('ct7_form_id', [
                'label' => esc_html__('Contact form', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => $contact_forms,
                'description' => esc_html__('Select contact from 7 you want display', 'cafe-lite'),
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
                'ct7_form_id' => 0,
                'css_class' => '',

            ], $this->get_settings_for_display());

            $this->getViewTemplate('template', 'contact-form-7', $settings);
        }
    }
endif;