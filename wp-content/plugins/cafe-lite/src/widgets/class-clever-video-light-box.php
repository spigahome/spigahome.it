<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * Clever Video Light box
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverVideoLightBox extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-video-light-box';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Video Light Box', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-play-1';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('settings', [
            'label' => esc_html__('Settings', 'cafe-lite')
        ]);
        $this->add_control('button_type', [
            'label' => esc_html__('Button Type', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'round',
            'options' => [
                'round' => esc_html__('Round', 'cafe-lite'),
                'flat' => esc_html__('Flat', 'cafe-lite'),
                'custom-icon' => esc_html__('Custom Icon', 'cafe-lite'),
            ]
        ]);
        $this->add_control('source_url', [
            'label' => esc_html__('Video Emb URL', 'cafe-lite'),
            'type' => Controls_Manager::URL,
        ]);
        $this->add_control('button_icon', [
            'label' => esc_html__('Icon', 'cafe-lite'),
            'type' => 'clevericon',
            'condition' => [
                'button_type' => 'custom-icon'
            ],
        ]);
        $this->add_control('title', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'label_block' => false
        ]);
        $this->add_control('des', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'label_block' => false
        ]);
        $this->add_control('image', [
            'label' => esc_html__('Video thumb', 'cafe-lite'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $this->add_control('width', [
            'label' => esc_html__('Video Width', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '800',
            'description' => esc_html__('Video Width in desktop', 'cafe-lite'),
        ]);
        $this->add_control('height', [
            'label' => esc_html__('Video Height', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => '450',
            'description' => esc_html__('Video height in desktop', 'cafe-lite'),
        ]);

        $this->add_control('css_class', [
            'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
        ]);
        $this->end_controls_section();

        $this->start_controls_section('block_style', [
            'label' => esc_html__('Style', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_responsive_control('button-align', [
            'label' => esc_html__('Align', 'cafe-lite'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => false,
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'cafe-lite'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'cafe-lite'),
                    'icon' => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'cafe-lite'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-video-light-box .cafe-wrap-content-video' => '{{VALUE}}',
            ],
            'selectors_dictionary' => [
                'left' => 'align-items: flex-start',
                'center' => 'align-items: center',
                'right' => 'align-items: flex-end',
            ],
            'condition' => [
                'button_type!' => 'custom-icon'
            ]
        ]);
        $this->add_responsive_control('custom-icon-button-align', [
            'label' => esc_html__('Align', 'cafe-lite'),
            'type' => Controls_Manager::CHOOSE,
            'label_block' => false,
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'cafe-lite'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'cafe-lite'),
                    'icon' => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'cafe-lite'),
                    'icon' => 'eicon-h-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-video-light-box .cafe-wrap-content-video' => '{{VALUE}}',
            ],
            'selectors_dictionary' => [
                'left' => 'justify-content: flex-start',
                'center' => 'justify-content: center',
                'right' => 'justify-content: flex-end',
            ],
            'condition' => [
                'button_type' => 'custom-icon'
            ]
        ]);
        $this->add_responsive_control('block_padding', [
            'label' => esc_html__('Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content-video' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('button_heading', [
            'label' => esc_html__('Button', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_responsive_control('button_size', [
            'label' => esc_html__('Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-video-button.custom-icon' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}',
                '{{WRAPPER}} .cafe-video-button .play' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}}',
            ],
        ]);
        $this->add_responsive_control('icon_size', [
            'label' => esc_html__('Icon Size', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-video-button.custom-icon' => 'font-size: {{SIZE}}{{UNIT}}',
            ],
            'condition' => [
                'button_type' => 'custom-icon'
            ]
        ]);
        $this->add_control('color', [
            'label' => esc_html__('Color Button', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'selectors' => [
                '{{WRAPPER}} .cafe-video-button.custom-icon' => 'color: {{COLOR}}',
                '{{WRAPPER}} .cafe-video-button path.icon' => 'fill: {{COLOR}}',
                '{{WRAPPER}} .cafe-video-button path.stroke-solid, {{WRAPPER}} .cafe-video-button path.stroke-dotted' => 'stroke: {{COLOR}}',
            ],
        ]);
        $this->add_control('background', [
            'label' => esc_html__('Color Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .cafe-video-button.custom-icon, {{WRAPPER}} .cafe-video-button .play, {{WRAPPER}} .cafe-video-button:before' => 'background: {{COLOR}}',
            ],
        ]);

        $this->add_responsive_control('border-radius', [
            'label' => esc_html__('Border Radius', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .cafe-video-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
                'button_type' => 'custom-icon'
            ]
        ]);
        $this->add_responsive_control('button_margin', [
            'label' => esc_html__('Button Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .cafe-video-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('title_style_heading', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_control('color_title', [
            'label' => esc_html__('Color Title', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-video-light-box .cafe-title' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cafe-video-light-box .cafe-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_responsive_control('title_margin', [
            'label' => esc_html__('Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors' => [
                '{{WRAPPER}} .cafe-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('des_style_heading', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);
        $this->add_control('color_des', [
            'label' => esc_html__('Color Description', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-video-light-box .cafe-description' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'des_typography',
                'selector' => '{{WRAPPER}} .cafe-video-light-box .cafe-description',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
        $this->add_responsive_control('block_height', [
            'label' => esc_html__('Height', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                ],
                'vh' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'separator' => 'before',
            'description' => esc_html__('Height for block', 'cafe-lite'),
            'selectors' => [
                '{{WRAPPER}} .cafe-video-light-box' => 'height: {{SIZE}}{{UNIT}}',
            ],
        ]);
        $this->add_control('overlay_bg', [
            'label' => esc_html__('Overlay Background Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,

            'selectors' => [
                '{{WRAPPER}} .cafe-video-light-box .cafe-wrap-content-video' => 'background-color: {{COLOR}}',
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
        return ['cafe-script'];
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'source_url' => '',
            'title' => '',
            'des' => '',
            'width' => '800',
            'height' => '450',
            'image' => '',
            'color' => '#fff',
            'button_type' => 'round',
            'css_class' => '',

        ], $this->get_settings_for_display());

        $this->add_inline_editing_attributes('title');
        $this->add_inline_editing_attributes('des');

        $this->getViewTemplate('template', 'video-light-box', $settings);
    }
}
