<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Plugin as ElementorPlugin;

/**
 * CleverBanner
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverBanner extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-banner';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Banner', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-banner';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('clever_banner_image__settings', [
            'label' => esc_html__('Image', 'cafe-lite')
        ]);

        $this->add_control('image', [
            'label' => esc_html__('Upload Image', 'cafe-lite'),
            'description' => esc_html__('Select an image for the banner.', 'cafe-lite'),
            'type' => Controls_Manager::MEDIA,
            'dynamic' => ['active' => true],
            'show_external' => true,
            'default' => [
                'url' => ''
            ]
        ]);
        $this->add_control('auto_size', [
            'label' => esc_html__('Auto Size', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'description' => esc_html__('Size of banner will apply follow image size.', 'cafe-lite'),
            'return_value' => 'true',
            'default' => 'true',
        ]);
        $this->add_responsive_control('custom_height', [
            'label' => esc_html__('Height', 'cafe-lite'),
            'type' => Controls_Manager::NUMBER,
            'desktop_default' => '300',
            'tablet_default' => '300',
            'mobile_default' => '300',
            'description' => esc_html__('Custom height of banner.', 'cafe-lite'),
            'condition' => [
                'auto_size' => ''
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-image' => 'height: {{VALUE}}px'
            ]
        ]);
        $this->add_control('link', [
            'label' => esc_html__('Link', 'cafe-lite'),
            'type' => Controls_Manager::URL,
            'description' => esc_html__('Redirect link when click to banner.', 'cafe-lite'),
        ]);

        $this->add_control('css_class', [
            'label' => esc_html__('Custom HTML Class', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'description' => esc_html__('You may add a custom HTML class to style element later.', 'cafe-lite'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('content_settings', [
            'label' => esc_html__('Content', 'cafe-lite')
        ]);

        $this->add_control('title', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'placeholder' => esc_html__('What is the title of this banner.', 'cafe-lite'),
            'description' => esc_html__('What is the title of this banner.', 'cafe-lite'),
            'type' => Controls_Manager::TEXTAREA,
            'dynamic' => ['active' => true],
            'default' => esc_html__('CAFE Banner', 'cafe-lite'),
            'label_block' => true
        ]);

        $this->add_control('title_tag',
            [
                'label' => esc_html__('HTML Tag', 'cafe-lite'),
                'description' => esc_html__('Select a heading tag for the title. Headings are defined with H1 to H6 tags.', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6'
                ],
                'label_block' => true,
            ]);
        $this->add_control('des', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'description' => esc_html__('Give a description to this banner.', 'cafe-lite'),
            'type' => Controls_Manager::WYSIWYG,
            'dynamic' => ['active' => true],
            'default' => esc_html__('A web banner or banner ad is a form of advertising. It is intended to attract traffic to a website by linking to the website of the advertiser. - Wikipedia', 'cafe-lite'),
            'label_block' => true
        ]);
        $this->add_control('button_heading', [
            'label' => esc_html__('Button', 'cafe-lite'),
            'type' => Controls_Manager::HEADING,
            'separator'   => 'after',
        ]);
        $this->add_control('button_label', [
            'label' => esc_html__('Button Label', 'cafe-lite'),
            'placeholder' => esc_html__('Button', 'cafe-lite'),
            'description' => esc_html__('Leave it blank if don\'t use it', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'default' => esc_html__('Button', 'cafe-lite'),
            'label_block' => false
        ]);
        $this->add_control('button_icon', [
            'label' => esc_html__('Icon', 'cafe-lite'),
            'type' => 'clevericon'
        ]);
        $this->add_control('button_icon_pos', [
            'label' => esc_html__('Icon position', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'after',
            'options' => [
                'before' => esc_html__('Before', 'cafe-lite'),
                'after' => esc_html__('After', 'cafe-lite'),
            ]
        ]);
        $this->add_control('button_style', [
            'label' => esc_html__('Button style', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'normal',
            'options' => [
                'normal' => esc_html__('Normal', 'cafe-lite'),
                'underline' => esc_html__('Underline', 'cafe-lite'),
                'outline' => esc_html__('Outline', 'cafe-lite'),
                'flat' => esc_html__('Flat', 'cafe-lite'),
                'line-through' => esc_html__('Line Through', 'cafe-lite'),
            ]
        ]);
        $this->end_controls_section();
        $this->start_controls_section('normal_style_settings', [
            'label' => esc_html__('Normal', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('overlay_banner', [
            'label' => esc_html__('Overlay banner', 'cafe-lite'),
            'type' => Controls_Manager::SWITCHER,
            'description' => esc_html__('Content will show up on image banner.', 'cafe-lite'),
            'return_value' => 'true',
            'default' => 'true',
        ]);
        $this->add_control('effect', [
            'label' => esc_html__('Hover Effect', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'normal',
            'condition' => [
                'overlay_banner' => 'true'
            ],
            'options' => [
                'normal' => esc_html__('Normal', 'cafe-lite'),
                'lily' => esc_html__('Lily', 'cafe-lite'),
                'layla' => esc_html__('Layla', 'cafe-lite'),
                'sadie' => esc_html__('Sadie', 'cafe-lite'),
                'oscar' => esc_html__('Oscar', 'cafe-lite'),
                'oscar-2' => esc_html__('Oscar 2', 'cafe-lite'),
                'chico' => esc_html__('Chico', 'cafe-lite'),
                'ruby' => esc_html__('Ruby', 'cafe-lite'),
                'roxy' => esc_html__('Roxy', 'cafe-lite'),
                'marley' => esc_html__('Marley', 'cafe-lite'),
                'sarah' => esc_html__('Sarah', 'cafe-lite'),
                'milo' => esc_html__('Milo', 'cafe-lite'),
                'overlay-box' => esc_html__('Overlay box', 'cafe-lite'),
            ]
        ]);
        $this->add_control('normal_effect', [
            'label' => esc_html__('Hover Effect', 'cafe-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'style-1',
            'condition' => [
                'overlay_banner!' => 'true'
            ],
            'options' => [
                'style-1' => esc_html__('Normal', 'cafe-lite'),
                'style-2' => esc_html__('Style 2', 'cafe-lite'),
            ]
        ]);
        $this->add_responsive_control('content_width', [
            'label' => esc_html__('Content Width', 'cafe-lite'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px','%'],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 2000,
                ],'%' => [
                    'min' => 0,
                    'max' => 2000,
                ],
            ],
            'condition' => [
                'overlay_banner' => 'true'
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Text Align', 'cafe-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'cafe-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'center' => esc_html__('Center', 'cafe-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'cafe-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cafe-wrap-content' => 'text-align: {{VALUE}};'
                ],
                'separator'   => 'before',
            ]
        );
        $this->add_responsive_control( 'horizontal_content_align', [
            'label'                => esc_html__('Horizontal Align', 'cafe-lite' ),
            'type'                 => Controls_Manager::CHOOSE,
            'label_block'          => false,
            'options'              => [
                'left'    => [
                    'title' => esc_html__('Left', 'cafe-lite' ),
                    'icon'  => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'cafe-lite' ),
                    'icon'  => 'eicon-h-align-center',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'cafe-lite' ),
                    'icon'  => 'eicon-h-align-right',
                ],
            ],
            'condition' => [
                'overlay_banner' => 'true'
            ],
            'selectors'            => [
                '{{WRAPPER}} .cafe-wrap-content' => '{{VALUE}};'
            ],
            'selectors_dictionary' => [
                'left'    => 'left:0',
                'center' => 'left:50%;transform:translateX(-50%)',
                'right' => 'left:auto;right:0',
            ],
        ] );
        $this->add_responsive_control( 'content_align', [
            'label'                => esc_html__('Vertical Align', 'cafe-lite' ),
            'type'                 => Controls_Manager::CHOOSE,
            'label_block'          => false,
            'options'              => [
                'top'    => [
                    'title' => esc_html__('Top', 'cafe-lite' ),
                    'icon'  => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Middle', 'cafe-lite' ),
                    'icon'  => 'eicon-v-align-middle',
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'cafe-lite' ),
                    'icon'  => 'eicon-v-align-bottom',
                ],
            ],
            'condition' => [
                'overlay_banner' => 'true'
            ],
            'selectors'            => [
                '{{WRAPPER}} .cafe-wrap-content' => 'justify-content: {{VALUE}};'
            ],
            'selectors_dictionary' => [
                'top'    => 'flex-start',
                'center' => 'center',
                'bottom' => 'flex-end',
            ],
        ] );
        $this->add_responsive_control('dimensions', [
            'label' => esc_html__('Content Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator'   => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_responsive_control('content_margin', [
            'label' => esc_html__('Content Margin', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
	    $this->add_responsive_control('border_radius', [
		    'label' => esc_html__('Border Radius', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-wrap-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
        $this->add_control(
            'title_color_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control('title_color_heading', [
            'label' => esc_html__('Title', 'cafe-lite'),
            'type' => Controls_Manager::HEADING
        ]);
        $this->add_control('title_color', [
            'label' => esc_html__('Title Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner-title, {{WRAPPER}} .cafe-wrap-content' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .cafe-banner-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
	    $this->add_control( 'title_space', [
		    'label'     => esc_html__('Space', 'cafe-lite' ),
		    'type'      => Controls_Manager::SLIDER,
		    'range'     => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-banner .cafe-banner-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ] );
        $this->add_control(
            'des_color_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control('des_color_heading', [
            'label' => esc_html__('Description', 'cafe-lite'),
            'type' => Controls_Manager::HEADING
        ]);
        $this->add_control('des_color', [
            'label' => esc_html__('Description Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-banner-description' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'des_typography',
                'selector' => '{{WRAPPER}} .cafe-banner .cafe-banner-description',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
	    $this->add_control( 'des_space', [
		    'label'     => esc_html__('Space', 'cafe-lite' ),
		    'type'      => Controls_Manager::SLIDER,
		    'range'     => [
			    'px' => [
				    'min' => 0,
				    'max' => 500,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-banner .cafe-banner-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ] );
        $this->add_control(
            'button_style_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control('button_style_heading', [
            'label' => esc_html__('Button', 'cafe-lite'),
            'type' => Controls_Manager::HEADING
        ]);
        $this->add_control('button_color', [
            'label' => esc_html__('Button Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('button_bg', [
            'label' => esc_html__('Button Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button.line-through::after,{{WRAPPER}} .cafe-banner .cafe-button.outline::after,{{WRAPPER}}  .cafe-banner .cafe-button.normal,{{WRAPPER}}  .cafe-banner .cafe-button.flat' => 'background: {{VALUE}};'
            ]
        ]);
        $this->add_control('button_border_color', [
            'label' => esc_html__('Border Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button:before,{{WRAPPER}} .cafe-banner .cafe-button:after' => 'border-color: {{VALUE}};'
            ]
        ]);
        $this->add_control( 'button_border_width', [
            'label'     => esc_html__('Border Width', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button:before,{{WRAPPER}} .cafe-banner .cafe-button:after' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ] );
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'button_typography',
			    'selector' => '{{WRAPPER}} .cafe-banner .cafe-button',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_responsive_control('button_spacing', [
		    'label' => esc_html__('Spacing', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%', 'em'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-banner .cafe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
	    $this->add_responsive_control('button_border_radius', [
		    'label' => esc_html__('Border Radius', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-banner .cafe-button,{{WRAPPER}} .cafe-banner .cafe-button:before,{{WRAPPER}} .cafe-banner .cafe-button:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);

        $this->add_control('overlay_bg_heading', [
            'label' => esc_html__('Background Overlay', 'cafe-lite'),
            'type' => Controls_Manager::HEADING
        ]);
        $this->add_control(
            'overlay_bg_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_bg',
                'label' => esc_html__('Background Overlay', 'cafe-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cafe-wrap-content',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('hover_style_settings', [
            'label' => esc_html__('Hover', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('title_color_hover', [
            'label' => esc_html__('Title Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner:hover .cafe-wrap-content,{{WRAPPER}} .cafe-banner.cafe-overlay-content:hover .cafe-banner-title, {{WRAPPER}} .cafe-banner:not(.cafe-overlay-content) .cafe-banner-title:hover' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('des_color_hover', [
            'label' => esc_html__('Description Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner.cafe-overlay-content:hover .cafe-banner-description,{{WRAPPER}} .cafe-banner:not(.cafe-overlay-content) .cafe-banner-description:hover' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('button_color_hover', [
            'label' => esc_html__('Button Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button:hover' => 'color: {{VALUE}};'
            ]
        ]);
        $this->add_control('button_bg_hover', [
            'label' => esc_html__('Button Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button.line-though:hover:after, {{WRAPPER}} .cafe-banner .cafe-button.outline:hover:after,{{WRAPPER}}  .cafe-banner .cafe-button.normal:hover, {{WRAPPER}}  .cafe-banner .cafe-button.flat:hover' => 'background: {{VALUE}};'
            ]
        ]);$this->add_control('button_border_hover', [
            'label' => esc_html__('Button Border Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .cafe-banner .cafe-button.outline:hover:after,{{WRAPPER}}  .cafe-banner .cafe-button.normal:hover, {{WRAPPER}}  .cafe-banner .cafe-button:hover:before, {{WRAPPER}}  .cafe-banner .cafe-button:hover:after' => 'border-color: {{VALUE}};'
            ]
        ]);
        $this->add_control(
            'overlay_bg_hover_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );
        $this->add_control('overlay_bg_hover_heading', [
            'label' => esc_html__('Background Overlay', 'cafe-lite'),
            'type' => Controls_Manager::HEADING
        ]);
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_bg_hover',
                'label' => esc_html__('Background Overlay', 'cafe-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cafe-banner.cafe-overlay-content:hover .cafe-wrap-content',
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'image' => CAFE_URI . 'assets/img/banner-placeholder.png',
            'auto_size' => 'true',
            'link' => '',
            'css_class' => '',
            'title' => '',
            'title_tag' => 'h3',
            'des' => '',
            'button_label' => '',
            'button_icon' => '',
            'button_icon_pos' => 'after',
            'button_style' => 'normal',
            'overlay_banner' => 'true',
            'effect' => 'normal',
        ], $this->get_settings_for_display());

        $this->add_inline_editing_attributes('title');
        $this->add_inline_editing_attributes('description');
        $this->add_inline_editing_attributes('button_label');

        $des_html_classes = ['cafe-banner-description'];
        $title_html_classes = ['cafe-banner-title'];
        $button_html_classes = [ 'cafe-button ' . $settings['button_style']];

        if (ElementorPlugin::$instance->editor->is_edit_mode()) {
            $des_html_classes[] = 'cafe-draggable-element';
            $title_html_classes[] = 'cafe-draggable-element';
            $button_html_classes[] = 'cafe-draggable-element';
        }

        $this->add_render_attribute('title', 'class', $title_html_classes);
        $this->add_render_attribute('des', 'class', $des_html_classes);
        $this->add_render_attribute('button_label', 'class', $button_html_classes);

        $this->getViewTemplate('template', 'banner', $settings);
    }
}
