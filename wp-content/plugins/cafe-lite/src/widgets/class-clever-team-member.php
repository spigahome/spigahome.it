<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

/**
 * Clever Team Member
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverTeamMember extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-team-member';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return esc_html__('CAFE Team Member', 'cafe-lite');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-team';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $repeater = new \Elementor\Repeater();
        $repeater->add_control(
            'social_title',
            [
                'label' => esc_html__('Title', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'social',
            [
                'label' => esc_html__('Icon', 'elementor' ),
                'type' => Controls_Manager::ICON,
                'label_block' => true,
                'default' => 'fa fa-wordpress',
                'include' => [
                    'fa fa-android',
                    'fa fa-apple',
                    'fa fa-behance',
                    'fa fa-bitbucket',
                    'fa fa-codepen',
                    'fa fa-delicious',
                    'fa fa-deviantart',
                    'fa fa-digg',
                    'fa fa-dribbble',
                    'fa fa-envelope',
                    'fa fa-facebook',
                    'fa fa-flickr',
                    'fa fa-foursquare',
                    'fa fa-free-code-camp',
                    'fa fa-github',
                    'fa fa-gitlab',
                    'fa fa-houzz',
                    'fa fa-instagram',
                    'fa fa-jsfiddle',
                    'fa fa-linkedin',
                    'fa fa-medium',
                    'fa fa-meetup',
                    'fa fa-mixcloud',
                    'fa fa-odnoklassniki',
                    'fa fa-pinterest',
                    'fa fa-product-hunt',
                    'fa fa-reddit',
                    'fa fa-rss',
                    'fa fa-shopping-cart',
                    'fa fa-skype',
                    'fa fa-slideshare',
                    'fa fa-snapchat',
                    'fa fa-soundcloud',
                    'fa fa-spotify',
                    'fa fa-stack-overflow',
                    'fa fa-steam',
                    'fa fa-stumbleupon',
                    'fa fa-telegram',
                    'fa fa-thumb-tack',
                    'fa fa-tripadvisor',
                    'fa fa-tumblr',
                    'fa fa-twitch',
                    'fa fa-twitter',
                    'fa fa-vimeo',
                    'fa fa-vk',
                    'fa fa-weibo',
                    'fa fa-weixin',
                    'fa fa-whatsapp',
                    'fa fa-wordpress',
                    'fa fa-xing',
                    'fa fa-yelp',
                    'fa fa-youtube',
                    'fa fa-500px',
                ],
            ]
        );
        $repeater->add_control(
            'url',
            [
                'label' => esc_html__('Link', 'cafe-lite'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->start_controls_section('settings', [
            'label' => esc_html__('Settings', 'cafe-lite')
        ]);
        $this->add_control('member_ava', [
            'label' => esc_html__('Member Avatar', 'cafe-lite'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $this->add_control('member_name', [
            'label' => esc_html__('Member Name', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'dynamic'       => ['active' => true],
            'label_block'	=> false
        ]);
        $this->add_control('member_des', [
            'label' => esc_html__('Member Description', 'cafe-lite'),
            'type' => Controls_Manager::TEXT,
            'dynamic'       => ['active' => true],
            'label_block'	=> false
        ]);
        $this->add_control('member_bio', [
            'label' => esc_html__('Biographical Info', 'cafe-lite'),
            'type' => Controls_Manager::TEXTAREA,
            'dynamic'       => ['active' => true],
            'label_block'	=> false
        ]);
        $this->add_control('style', [
            'label'         => esc_html__('Layout Style', 'cafe-lite'),
            'description'   => esc_html__('', 'cafe-lite'),
            'type'          => Controls_Manager::SELECT,
            'default'       => 'style-1',
            'options' => [
                'style-1'  => esc_html__('Style 1', 'cafe-lite' ),
                'style-2' => esc_html__('Style 2', 'cafe-lite' ),
                'style-3' => esc_html__('Style 3', 'cafe-lite' ),
                'style-4' => esc_html__('Style 4', 'cafe-lite' ),
            ],
        ]);
        $this->add_control('member_social', [
            'label' => esc_html__('Social account', 'cafe-lite'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'description' => esc_html__('Link to social account of member.', 'cafe-lite'),
            'title_field' => '{{{ social_title }}}',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('block_style', [
            'label' => esc_html__('Style', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_responsive_control(
		    'text_align',
		    [
			    'label' => esc_html__('Content Align', 'cafe-lite'),
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
				    '{{WRAPPER}} .cafe-team-member' => 'text-align: {{VALUE}};'
			    ]
		    ]
	    );
        $this->add_responsive_control('content_padding', [
            'label' => esc_html__('Content Padding', 'cafe-lite'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator'   => 'before',
            'selectors' => [
                '{{WRAPPER}} .cafe-wrap-team-member, {{WRAPPER}} .cafe-team-member:not(.style-1)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->end_controls_section();

        $this->start_controls_section('member_ava_styling_block', [
            'label' => esc_html__('Avatar', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_control('background_content', [
		    'label' => esc_html__('Mask Background', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-team-member .mask-color' => 'background-color: {{COLOR}}',
		    ],
	    ]);
	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'avatar_shadow',
			    'selector' => '{{WRAPPER}} .cafe-member-ava',
		    ]
	    );
        $this->add_control( 'ava_width', [
            'label'     => esc_html__('Width', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-member-ava' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ] );
        $this->add_responsive_control( 'ava_height', [
            'label'     => esc_html__('Height', 'cafe-lite' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-member-ava' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ] );
	    $this->add_responsive_control('ava_border_radius', [
		    'label' => esc_html__('Border Radius', 'cafe-lite'),
		    'type' => Controls_Manager::DIMENSIONS,
		    'size_units' => ['px', '%'],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-member-ava' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();

        $this->start_controls_section('styling_member_name_block', [
            'label' => esc_html__('Member Name', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_control('color_member_name', [
		    'label' => esc_html__('Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-team-member .cafe-member-name' => 'color: {{COLOR}}',
		    ],
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'typo_member_name',
			    'selector' => '{{WRAPPER}} .cafe-team-member .cafe-member-name',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );
	    $this->add_control('space_member_name', [
		    'label' => esc_html__('Space', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();

        $this->start_controls_section('styling_member_des_block', [
            'label' => esc_html__('Member Description', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_control('color_member_des', [
		    'label' => esc_html__('Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-team-member .cafe-member-des' => 'color: {{COLOR}}',
		    ],
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'typo_member_des',
			    'selector' => '{{WRAPPER}} .cafe-team-member .cafe-member-des',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );

	    $this->add_control('space_member_des', [
		    'label' => esc_html__('Space', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-member-des' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();
        $this->start_controls_section('styling_member_bio_block', [
            'label' => esc_html__('Member Bio', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
	    $this->add_control('color_member_bio', [
		    'label' => esc_html__('Color', 'cafe-lite'),
		    'type' => Controls_Manager::COLOR,
		    'selectors' => [
			    '{{WRAPPER}} .cafe-team-member .cafe-member-bio' => 'color: {{COLOR}}',
		    ],
	    ]);
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'typo_member_bio',
			    'selector' => '{{WRAPPER}} .cafe-team-member .cafe-member-bio',
			    'scheme' => Scheme_Typography::TYPOGRAPHY_1,
		    ]
	    );

	    $this->add_control('space_member_bio', [
		    'label' => esc_html__('Space', 'cafe-lite'),
		    'type' => Controls_Manager::SLIDER,
		    'range' => [
			    'px' => [
				    'min' => 0,
				    'max' => 100,
			    ],
		    ],
		    'selectors' => [
			    '{{WRAPPER}} .cafe-member-bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
		    ],
	    ]);
        $this->end_controls_section();

        $this->start_controls_section('styling_member_social_block', [
            'label' => esc_html__('Member Social', 'cafe-lite'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);
        $this->add_control('font_size_member_social', [
            'label' => esc_html__('Font Size', 'cafe-lite'),
            'separator'   => 'before',
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-team-member .cafe-member-social-item a' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('size_member_social', [
            'label' => esc_html__('Size', 'cafe-lite'),
            'separator'   => 'before',
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .cafe-team-member .cafe-member-social-item a' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('color_member_social', [
            'label' => esc_html__('Color', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .cafe-team-member .cafe-member-social-item a' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('color_member_social_hover', [
            'label' => esc_html__('Color Hover', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .cafe-team-member .cafe-member-social-item a:hover' => 'color: {{COLOR}}',
            ],
        ]);
        $this->add_control('bg_member_social', [
            'label' => esc_html__('Background', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .cafe-team-member .cafe-member-social-item a' => 'background: {{COLOR}}',
            ],
        ]);
        $this->add_control('bg_member_social_hover', [
            'label' => esc_html__('Background Hover', 'cafe-lite'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}  .cafe-team-member .cafe-member-social-item a:hover' => 'background: {{COLOR}}',
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
        return ['typed', 'cafe-script'];
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'member_ava' => '',
            'member_name' => '',
            'member_des' => '',
            'member_bio' => '',
            'member_social' => '',

        ], $this->get_settings_for_display());

        $name_class='cafe-member-name';
        $this->add_inline_editing_attributes('member_name');
        $this->add_inline_editing_attributes('member_des');

        $this->add_render_attribute('member_name', 'class', $name_class);

        $this->getViewTemplate('template', 'team-member', $settings);
    }
}
