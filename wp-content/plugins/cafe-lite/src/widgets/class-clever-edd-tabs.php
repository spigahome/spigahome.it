<?php namespace Cafe\Widgets;

use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverEddTabs
 *
 * Show EDD user info with tabs
 */
final class CleverEddTabs extends CleverWidgetBase
{
    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'clever-edd-tabs';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('CAFE EDD Tabs', 'cafe-lite');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-tabs';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['edd', 'tabs', 'accordion', 'toggle'];
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_tabs',
            [
                'label' => esc_html__('Tabs', 'cafe-lite'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'tab_title',
            [
                'label' => esc_html__('Title', 'cafe-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'cafe-lite'),
                'placeholder' => esc_html__('Tab Title', 'cafe-lite'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'tab_content',
            [
                'label' => esc_html__('Tab Content', 'cafe-lite'),
                'default' => esc_html__('Tab Content', 'cafe-lite'),
                'placeholder' => esc_html__('Tab Content', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'download_history' => esc_html__('Download History', 'cafe-lite'),
                    'download_discounts' => esc_html__('Download Discounts', 'cafe-lite'),
                    'purchase_history' => esc_html__('Purchase History', 'cafe-lite'),
                    'edd_profile_editor' => esc_html__('Profile Editor', 'cafe-lite'),
                    'edd_subscriptions' => esc_html__('Subscriptions', 'cafe-lite'),
                    'edd_wish_lists' => esc_html__('Wishlist Items', 'cafe-lite'),
                    'edd_wish_lists_edit' => esc_html__('Edit Wishlist Items', 'cafe-lite'),
                    'edd_deposit' => esc_html__('Deposits', 'cafe-lite'),
                    'edd_license_keys' => esc_html__('License Keys', 'cafe-lite')
                ],
                'default' => 'purchase_history'
            ]
        );

        $this->add_control(
            'tabs',
            [
                'label' => esc_html__('Tabs Items', 'cafe-lite'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'tab_title' => esc_html__('Purchase History', 'cafe-lite'),
                        'tab_content' => 'purchase_history',
                    ],
                    [
                        'tab_title' => esc_html__('Download History', 'cafe-lite'),
                        'tab_content' => 'download_history',
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => esc_html__('View', 'cafe-lite'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'cafe-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'cafe-lite'),
                    'vertical' => esc_html__('Vertical', 'cafe-lite'),
                ],
                'prefix_class' => 'elementor-tabs-view-',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_tabs_style',
            [
                'label' => esc_html__('Tabs', 'cafe-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'navigation_width',
            [
                'label' => esc_html__('Navigation Width', 'cafe-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tabs-wrapper' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'type' => 'vertical',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label' => esc_html__('Border Width', 'cafe-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-title, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, {{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_color',
            [
                'label' => esc_html__('Border Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-mobile-title, {{WRAPPER}} .elementor-tab-desktop-title.elementor-active, {{WRAPPER}} .elementor-tab-title:before, {{WRAPPER}} .elementor-tab-title:after, {{WRAPPER}} .elementor-tab-content, {{WRAPPER}} .elementor-tabs-content-wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-desktop-title.elementor-active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-tabs-content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title', 'cafe-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tab_color',
            [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-title' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
            ]
        );

        $this->add_control(
            'tab_active_color',
            [
                'label' => esc_html__('Active Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-title.elementor-active' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_4,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .elementor-tab-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );

        $this->add_control(
            'heading_content',
            [
                'label' => esc_html__('Content', 'cafe-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'cafe-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-tab-content' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-tab-content',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $tabs = $this->get_settings_for_display('tabs');
        $id_int = substr($this->get_id_int(), 0, 3);

        if (!is_user_logged_in()) :
            echo do_shortcode('[edd_login]');
        else : ?>
		<div class="elementor-tabs clever-edd-tabs" role="tablist">
			<div class="elementor-tabs-wrapper">
				<?php
                foreach ($tabs as $index => $item) :
                    $tab_count = $index + 1;
                    $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                    $this->add_render_attribute($tab_title_setting_key, [
                        'id' => 'elementor-tab-title-' . $id_int . $tab_count,
                        'class' => ['elementor-tab-title', 'elementor-tab-desktop-title'],
                        'data-tab' => $tab_count,
                        'role' => 'tab',
                        'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
                    ]); ?>
					<div <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>><a href=""><?php echo $item['tab_title']; ?></a></div>
				<?php endforeach; ?>
			</div>
			<div class="elementor-tabs-content-wrapper">
				<?php
                foreach ($tabs as $index => $item) :
                    $tab_count = $index + 1;
                    $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);
                    $tab_title_mobile_setting_key = $this->get_repeater_setting_key('tab_title_mobile', 'tabs', $tab_count);
                    $this->add_render_attribute($tab_content_setting_key, [
                        'id' => 'elementor-tab-content-' . $id_int . $tab_count,
                        'class' => ['elementor-tab-content', 'elementor-clearfix'],
                        'data-tab' => $tab_count,
                        'role' => 'tabpanel',
                        'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
                    ]);
                    $this->add_render_attribute($tab_title_mobile_setting_key, [
                        'class' => ['elementor-tab-title', 'elementor-tab-mobile-title'],
                        'data-tab' => $tab_count,
                        'role' => 'tab',
                    ]);
                    ?>
					<div <?php echo $this->get_render_attribute_string($tab_title_mobile_setting_key); ?>><?php echo $item['tab_title']; ?></div>
					<div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>>
                        <?php echo do_shortcode('['.$item['tab_content'].']'); ?>
                    </div>
				<?php endforeach; ?>
			</div>
		</div>
        <?php endif;
    }
}
