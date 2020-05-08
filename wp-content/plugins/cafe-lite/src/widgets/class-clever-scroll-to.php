<?php namespace Cafe\Widgets;

use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

/**
 * CleverScrollTo
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
 final class CleverScrollTo extends CleverWidgetBase
 {
     public function get_name()
     {
         return 'clever-scroll-to';
     }

     public function get_title()
     {
         return esc_html__('CAFE Scroll To', 'cafe-lite');
     }

     public function get_icon()
     {
         return 'eicon-scroll';
     }

     /**
      * Register controls
      */
     protected function _register_controls()
     {
         $this->start_controls_section(
             'content_templates',
             [
                 'label'         => esc_html__('Sections', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'template_height_hint',
            [
                 'label'         => '<span style="line-height: 1.4em;"><b>NOTICE</b>:</span> You may need to refresh the page to see the changes.',
                 'type'          => Controls_Manager::RAW_HTML,

            ]
        );

         $this->add_control(
             'content_type',
             [
                 'label'         => esc_html__('Content Type', 'cafe-lite'),
                 'type'          => Controls_Manager::SELECT,
                 'options'       => [
                     'ids'           => esc_html__('HTML ID', 'cafe-lite'),
                     'templates'     => esc_html__('Templates', 'cafe-lite')
                 ],
                 'default'       => 'templates'
             ]
         );

         $temp_repeater = new REPEATER();

         $temp_repeater->add_control(
             'label',
            [
                 'label'	  	 => esc_html__('Label', 'cafe-lite'),
                 'type'          => Controls_Manager::TEXT,
                 'default'       => ''
            ]
        );

         $temp_repeater->add_control(
             'icon',
            [
                 'label'	  	 => esc_html__('Icon', 'cafe-lite'),
                 'type'          => 'clevericon',
                 'default'       => ''
            ]
        );

         $temp_repeater->add_control(
             'section_template',
            [
                 'label'			=> esc_html__('Template', 'cafe-lite'),
                 'type'          => Controls_Manager::SELECT,
                 'options'       => $this->getSavedElementorTemplates(),
                 'multiple'      => false,
            ]
        );

         $this->add_control(
             'section_repeater',
            [
                'label'          => esc_html__('Sections', 'cafe-lite'),
                'type'           => Controls_Manager::REPEATER,
                'fields'         => array_values($temp_repeater->get_controls()),
                'condition'      => [
                    'content_type'   => 'templates'
                ],
                'title_field'    => '{{{ label }}}'
            ]
         );

         $id_repeater = new REPEATER();

         $id_repeater->add_control(
             'section_id',
            [
                 'label'			=> esc_html__('Section ID', 'cafe-lite'),
                 'type'          => Controls_Manager::TEXT,
                 'dynamic'       => [ 'active' => true ],
            ]
        );

        $id_repeater->add_control(
            'label',
           [
                'label'	  	 => esc_html__('Label', 'cafe-lite'),
                'type'          => Controls_Manager::TEXT,
                'default'       => ''
           ]
       );

        $id_repeater->add_control(
            'icon',
           [
                'label'	  	 => esc_html__('Icon', 'cafe-lite'),
                'type'          => 'clevericon',
                'default'       => ''
           ]
       );

         $this->add_control(
             'id_repeater',
            [
                'label'          => esc_html__('Sections', 'cafe-lite'),
                'type'           => Controls_Manager::REPEATER,
                'fields'         => array_values($id_repeater->get_controls()),
                'condition'      => [
                    'content_type'   => 'ids'
                ],
                'title_field'    => '{{{ label }}}'
            ]
         );

         $this->end_controls_section();

         $this->start_controls_section(
             'nav_menu',
             [
                 'label'     => esc_html__('Navigation', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'navigation_layout',
             [
                 'label'         => esc_html__('Layout', 'cafe-lite'),
                 'type'          => Controls_Manager::SELECT,
                 'options'       => [
                     'dots'  => esc_html__('Dots', 'cafe-lite'),
                     'lines' => esc_html__('Lines', 'cafe-lite'),
                 ],
                 'default'       => 'dots'
             ]
         );
         $this->add_control(
              'dots_tooltips_switcher',
              [
                  'label'         => esc_html__('Show Tooltips', 'cafe-lite'),
                  'type'          => Controls_Manager::SWITCHER,
                  'default'       => 'yes'
              ]
          );

         $this->add_control(
             'navigation_dots_pos',
             [
                 'label'         => esc_html__('Position', 'cafe-lite'),
                 'type'          => Controls_Manager::SELECT,
                 'options'       => [
                     'left'  => esc_html__('Left', 'cafe-lite'),
                     'right' => esc_html__('Right', 'cafe-lite'),
                 ],
                 'default'       => 'right'
             ]
         );

         $this->end_controls_section();

         $this->start_controls_section(
             'advanced_settings',
             [
                 'label'         => esc_html__('Scroll Settings', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'scroll_speed',
             [
                 'label'         => esc_html__('Scroll Speed', 'cafe-lite'),
                 'type'          => Controls_Manager::NUMBER,
                 'description'   => esc_html__('Set scrolling speed in seconds, the default is 1', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'full_section',
             [
                 'label'         => esc_html__('Full Section Scroll', 'cafe-lite'),
                 'type'          => Controls_Manager::SWITCHER,
                 'default'       => 'yes',
             ]
         );

         $this->end_controls_section();

         $this->start_controls_section(
             'navigation_style',
             [
                 'label'         => esc_html__('Navigation', 'cafe-lite'),
                 'tab'           => CONTROLS_MANAGER::TAB_STYLE,
             ]
         );

         $this->start_controls_tabs('navigation_style_tabs');

         $this->start_controls_tab(
             'lines_style_tab',
             [
                 'label'         => esc_html__('Lines', 'cafe-lite'),
                 'condition' => [
                     'navigation_layout'    => 'lines',

                 ]
             ]
         );
         $this->add_control(
             'lines_color',
             [
                 'label'         => esc_html__('Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item'  => 'color: {{VALUE}}'
                 ]
             ]
         );
         $this->add_control(
             'lines_active_color',
             [
                 'label'         => esc_html__('Active/Hover Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item.active, {{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item:hover'  => 'color: {{VALUE}}'
                 ]
             ]
         );
         $this->add_group_control(
             Group_Control_Typography::get_type(),
             [
                 'name'          => 'lines_label_typo',
                 'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item',
             ]
         );
         $this->add_control(
             'lines_height',
             [
                 'label'         => esc_html__('Height', 'cafe-lite'),
                 'type'          => Controls_Manager::SLIDER,
                 'size_units'    => ['px'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item'  => 'border-width: {{SIZE}}{{UNIT}};',
                 ]
             ]
         );
         $this->add_control(
             'lines_width',
             [
                 'label'         => esc_html__('Width', 'cafe-lite'),
                 'type'          => Controls_Manager::SLIDER,
                 'size_units'    => ['px'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item'  => 'max-width: {{SIZE}}{{UNIT}};',
                 ]
             ]
         );
         $this->add_control(
             'line_margin',
             [
                 'label'         => esc_html__('Margin', 'cafe-lite'),
                 'type'          => Controls_Manager::DIMENSIONS,
                 'size_units'    => ['px', 'em', '%'],
                 'separator'   => 'before',
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item'  => 'margin:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ]
             ]
         );
         $this->add_control(
             'line_padding',
             [
                 'label'         => esc_html__('Padding', 'cafe-lite'),
                 'type'          => Controls_Manager::DIMENSIONS,
                 'size_units'    => ['px', 'em', '%'],
                 'separator'   => 'before',
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons-list.lines .cafe-scrollto-nav-item'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ]
             ]
         );

         $this->end_controls_tab();
         $this->start_controls_tab(
             'tooltips_style_tab',
             [
                 'label'         => esc_html__('Tooltips', 'cafe-lite'),
                 'condition' => [
                     'dots_tooltips_switcher'    => 'yes',
                     'navigation_layout'    => 'dots',

                 ]
             ]
         );

         $this->add_control(
             'tooltips_color',
             [
                 'label'         => esc_html__('Tooltips Text Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value' => Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip'  => 'color: {{VALUE}};',
                 ],
                 'condition' => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Typography::get_type(),
             [
                 'name'          => 'tooltips_typography',
                 'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip span',
                 'condition'     => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_control(
             'tooltips_background',
             [
                 'label'         => esc_html__('Tooltips Background', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value' => Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip'  => 'background-color: {{VALUE}};',
                     '{{WRAPPER}}  .cafe-scrollto-nav-icons.right .cafe-scrollto-nav-item-tooltip::after' => 'border-left-color: {{VALUE}}',
                     '{{WRAPPER}}  .cafe-scrollto-nav-icons.left .cafe-scrollto-nav-item-tooltip::after' => 'border-right-color: {{VALUE}}',
                 ],
                 'condition' => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Border::get_type(),
             [
                 'name'          =>  'tooltips_border',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip',
                 'condition'     => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_control(
             'tooltips_border_radius',
             [
                 'label'         => esc_html__('Border Radius', 'cafe-lite'),
                 'type'          => Controls_Manager::SLIDER,
                 'size_units'    => ['px', 'em', '%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip'  => 'border-radius: {{SIZE}}{{UNIT}};',
                 ],
                 'condition'     => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Box_Shadow::get_type(),
             [
                 'name'          => 'tooltips_shadow',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip',
                 'condition'     => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_responsive_control(
             'tooltips_margin',
             [
                 'label'         => esc_html__('Margin', 'cafe-lite'),
                 'type'          => Controls_Manager::DIMENSIONS,
                 'size_units'    => ['px', 'em', '%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
                 'condition' => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->add_responsive_control(
             'tooltips_padding',
             [
                 'label'         => esc_html__('Padding', 'cafe-lite'),
                 'type'          => Controls_Manager::DIMENSIONS,
                 'size_units'    => ['px', 'em', '%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-item-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
                 'condition' => [
                     'dots_tooltips_switcher'    => 'yes'
                 ]
             ]
         );

         $this->end_controls_tab();

         $this->start_controls_tab(
             'dots_style_tab',
             [
                 'label'         => esc_html__('Dots', 'cafe-lite'),
                 'condition' => [
                     'navigation_layout'    => 'dots',

                 ]

             ]
         );
	     $this->add_control(
		     'dot_size',
		     [
			     'label'         => esc_html__('Size', 'cafe-lite'),
			     'type'          => Controls_Manager::SLIDER,
			     'size_units'    => ['px'],
			     'selectors'     => [
				     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li'  => 'width:  {{SIZE}}{{UNIT}};height:  {{SIZE}}{{UNIT}};',
			     ]
		     ]
	     );
	     $this->add_control(
		     'dot_icon_size',
		     [
			     'label'         => esc_html__('Icon Size', 'cafe-lite'),
			     'type'          => Controls_Manager::SLIDER,
			     'size_units'    => ['px'],
			     'selectors'     => [
				     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li'  => 'font-size:  {{SIZE}}{{UNIT}};',
			     ]
		     ]
	     );
         $this->add_control(
             'dots_color',
             [
                 'label'         => esc_html__('Dots Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value' => Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li'  => 'background-color: {{VALUE}};',
                 ]
             ]
         );
         $this->add_control(
             'dots_bg_color',
             [
                 'label'         => esc_html__('Dots Background', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value' => Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li '  => 'background-color: {{VALUE}};',
                 ]
             ]
         );
         $this->add_control(
             'active_dot_color',
             [
                 'label'         => esc_html__('Active Dot Background Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value' => Scheme_Color::COLOR_2
                 ],
                 'selectors'     => [
                     '{{WRAPPER}}  .cafe-scrollto-nav-item.active .cafe-scrollto-nav-item-link span, {{WRAPPER}}  .cafe-scrollto-nav-item:hover .cafe-scrollto-nav-item-link span'  => 'background-color: {{VALUE}};',
                 ]
             ]
         );

         $this->add_control(
             'dots_border_color',
             [
                 'label'         => esc_html__('Dots Border Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_2
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons .cafe-scrollto-nav-item-link span'  => 'border-color: {{VALUE}};',
                 ]
             ]
         );
	     $this->add_control(
		     'dot_border_radius',
		     [
			     'label'         => esc_html__('Border Radius', 'cafe-lite'),
			     'type'          => Controls_Manager::DIMENSIONS,
			     'size_units'    => ['px', 'em', '%'],
			     'separator'   => 'before',
			     'selectors'     => [
				     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li, {{WRAPPER}}  ul.cafe-scrollto-nav-icons-list span'  => 'border-radius:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			     ]
		     ]
	     );
	     $this->add_control(
		     'dot_padding',
		     [
			     'label'         => esc_html__('Padding', 'cafe-lite'),
			     'type'          => Controls_Manager::DIMENSIONS,
			     'size_units'    => ['px', 'em', '%'],
			     'separator'   => 'before',
			     'selectors'     => [
				     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			     ]
		     ]
	     );
	     $this->add_control(
		     'dot_margin',
		     [
			     'label'         => esc_html__('Margin', 'cafe-lite'),
			     'type'          => Controls_Manager::DIMENSIONS,
			     'size_units'    => ['px', 'em', '%'],
			     'separator'   => 'before',
			     'selectors'     => [
				     '{{WRAPPER}}  ul.cafe-scrollto-nav-icons-list li'  => 'margin:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			     ]
		     ]
	     );

         $this->end_controls_tab();

         $this->start_controls_tab(
             'container_style_tab',
             [
                 'label'         => esc_html__('Container', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'navigation_background',
             [
                 'label'         => esc_html__('Background Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons'  => 'background-color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_control(
             'navigation_border_radius',
             [
                 'label'         => esc_html__('Border Radius', 'cafe-lite'),
                 'type'          => Controls_Manager::SLIDER,
                 'size_units'    => ['px', 'em', '%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-icons'  => 'border-radius: {{SIZE}}{{UNIT}};',
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Box_Shadow::get_type(),
             [
                 'label'         => esc_html__('Shadow', 'cafe-lite'),
                 'name'          => 'navigation_box_shadow',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-icons',
             ]
         );
	     $this->add_control(
		     'navigation_padding',
		     [
			     'label'         => esc_html__('Padding', 'cafe-lite'),
			     'type'          => Controls_Manager::DIMENSIONS,
			     'size_units'    => ['px', 'em', '%'],
			     'separator'   => 'before',
			     'selectors'     => [
				     '{{WRAPPER}} .cafe-scrollto-nav-icons'  => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			     ]
		     ]
	     );

         $this->end_controls_tab();

         $this->end_controls_tabs();

         $this->end_controls_section();

         $this->start_controls_section(
             'navigation_menu_style',
             [
                 'label'     => esc_html__('Navigation Menu', 'cafe-lite'),
                 'tab'       => CONTROLS_MANAGER::TAB_STYLE,
                 'condition' => [
                     'nav_menu_switch'   => 'yes'
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Typography::get_type(),
             [
                 'name'          => 'navigation_items_typography',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item .cafe-scrollto-nav-item-link'
             ]
         );

         $this->start_controls_tabs('navigation_menu_style_tabs');

         $this->start_controls_tab(
             'normal_style_tab',
             [
                 'label'         => esc_html__('Normal', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'normal_color',
             [
                 'label'         => esc_html__('Text Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item .cafe-scrollto-nav-item-link'  => 'color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_control(
             'normal_hover_color',
             [
                 'label'         => esc_html__('Text Hover Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item .cafe-scrollto-nav-item-link:hover'  => 'color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_control(
             'normal_background',
             [
                 'label'         => esc_html__('Background Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_2
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item'  => 'background-color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Box_Shadow::get_type(),
             [
                 'label'         => esc_html__('Shadow', 'cafe-lite'),
                 'name'          => 'normal_shadow',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item'
             ]
         );

         $this->end_controls_tab();

         $this->start_controls_tab(
             'active_style_tab',
             [
                 'label'         => esc_html__('Active', 'cafe-lite'),
             ]
         );

         $this->add_control(
             'active_color',
             [
                 'label'         => esc_html__('Text Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_2
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item.active .cafe-scrollto-nav-item-link'  => 'color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_control(
             'active_hover_color',
             [
                 'label'         => esc_html__('Text Hover Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_2
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item.active .cafe-scrollto-nav-item-link:hover'  => 'color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_control(
             'active_background',
             [
                 'label'         => esc_html__('Background Color', 'cafe-lite'),
                 'type'          => Controls_Manager::COLOR,
                 'scheme'        => [
                     'type'  => Scheme_Color::get_type(),
                     'value'=> Scheme_Color::COLOR_1
                 ],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item.active'  => 'background-color: {{VALUE}}'
                 ]
             ]
         );

         $this->add_group_control(
             Group_Control_Box_Shadow::get_type(),
             [
                 'label'         => esc_html__('Shadow', 'cafe-lite'),
                 'name'          => 'active_shadow',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item.active'
             ]
         );

         $this->end_controls_tabs();

         $this->add_group_control(
             Group_Control_Border::get_type(),
             [
                 'name'          => 'navigation_items_border',
                 'selector'      => '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item',
                 'separator'     => 'before'
             ]
         );

         $this->add_control(
             'navigation_items_border_radius',
             [
                 'label'         => esc_html__('Border Radius', 'cafe-lite'),
                 'type'          => Controls_Manager::SLIDER,
                 'size_units'    => ['px','em','%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item'  => 'border-radius: {{SIZE}}{{UNIT}};',
                 ]
             ]
         );

         $this->add_responsive_control(
             'navigation_items_margin',
             [
                 'label'         => esc_html__('Margin', 'cafe-lite'),
                 'type'          => Controls_Manager::DIMENSIONS,
                 'size_units'    => ['px', 'em', '%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
         );

         $this->add_responsive_control(
             'navigation_items_padding',
             [
                 'label'         => esc_html__('Padding', 'cafe-lite'),
                 'type'          => Controls_Manager::DIMENSIONS,
                 'size_units'    => ['px', 'em', '%'],
                 'selectors'     => [
                     '{{WRAPPER}} .cafe-scrollto-nav-menu .cafe-scrollto-nav-menu-item .cafe-scrollto-nav-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
         );

         $this->end_controls_section();
     }

     protected function render()
     {
         $settings = $this->get_settings_for_display();

         $id = $this->get_id();

         $this->add_render_attribute('vertical_scroll_wrapper', 'class', 'cafe-scrollto-section');

         $this->add_render_attribute('vertical_scroll_wrapper', 'id', 'cafe-scrollto-section--' . $id);

         $this->add_render_attribute('vertical_scroll_inner', 'class', array('cafe-scrollto-section-inner'));

         $this->add_render_attribute('vertical_scroll_inner', 'id', 'cafe-scrollto-' . $id);

         $this->add_render_attribute('vertical_scroll_dots', 'class', array('cafe-scrollto-nav-icons', $settings['navigation_dots_pos']));

         $this->add_render_attribute('vertical_scroll_dots_list', 'class', array('cafe-scrollto-nav-icons-list',$settings['navigation_layout']));

         $this->add_render_attribute('vertical_scroll_menu', 'id', 'cafe-scrollto-nav-menu-' . $id);

         $this->add_render_attribute('vertical_scroll_sections_wrap', 'id', 'cafe-scrollto-sections-wrap-' . $id);

         $this->add_render_attribute('vertical_scroll_sections_wrap', 'class', 'cafe-scrollto-sections-wrap');

         $scroll_settings = [
             'id'        => $id,
             'speed'     => !empty($settings['scroll_speed']) ? $settings['scroll_speed'] * 1000 : 700,
             'tooltips'  => 'yes' == $settings['dots_tooltips_switcher'] ? true : false,
             'dotsPos'   => $settings['navigation_dots_pos'],
             'fullSection'=> 'yes' == $settings['full_section'] ? true : false
         ];

         $templates = 'templates' === $settings['content_type'] ? $settings['section_repeater'] : $settings['id_repeater'];
         $checkType = 'templates' === $settings['content_type'] ? true : false;
         ?>

         <div <?php echo $this->get_render_attribute_string('vertical_scroll_wrapper'); ?> data-settings='<?php echo wp_json_encode($scroll_settings); ?>'>
             <div <?php echo $this->get_render_attribute_string('vertical_scroll_inner'); ?>>
                 <div <?php echo $this->get_render_attribute_string('vertical_scroll_dots'); ?>>
                     <ul <?php echo $this->get_render_attribute_string('vertical_scroll_dots_list'); ?>>
                         <?php foreach ($templates as $index => $section) : ?>
                             <li data-index="<?php echo $index; ?>" data-menuanchor="<?php echo $checkType ? 'section_' . $id . $index : $templates[$index]['section_id']; ?>" class="cafe-scrollto-nav-item" data-label="<?php echo $section['label'] ?>">
                                 <div class="cafe-scrollto-nav-item-link"><i class="<?php echo $section['icon'] ?>"></i>
                                     <?php if($settings['dots_tooltips_switcher']=='yes'){?><span class="label"><?php echo $section['label'] ?></span><?php }?>
                                 </div>
                             </li>
                         <?php endforeach; ?>
                     </ul>
                 </div>
                 <?php if ('templates' === $settings['content_type']) : ?>
                     <div <?php echo $this->get_render_attribute_string('vertical_scroll_sections_wrap'); ?>>

                         <?php foreach ($templates as $index => $section) :
                                 $this->add_render_attribute('section_' . $index, 'class', [ 'cafe-scrollto-temp', 'cafe-scrollto-temp-' . $id ]);
         $this->add_render_attribute('section_' . $index, 'id', 'section_' . $id . $index); ?>
                             <div <?php echo $this->get_render_attribute_string('section_' . $index); ?>>
                                 <?php
                                     $template_id = $section['section_template'];
         echo $this->getTemplateContent($template_id); ?>
                             </div>
                         <?php endforeach; ?>
                     </div>
                 <?php endif; ?>
             </div>
         </div>

     <?php
     }
 }
