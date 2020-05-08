<?php namespace Cafe\Widgets;

use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverHideThis
 */
final class CleverHideThis extends CleverWidgetBase
{

    /**
	 * Get widget name.
	 *
	 * Retrieve text editor widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name()
    {
        return 'clever-hide-this';
    }

    /**
	 * Get widget title.
	 *
	 * Retrieve text editor widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title()
    {
        return __('Clever Hide This', 'cafe-pro');
    }

    /**
	 * Get widget icon.
	 *
	 * Retrieve text editor widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon()
    {
        return 'eicon-eye';
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
        return ['text', 'editor', 'hide', 'role'];
    }

    /**
	 * Register text editor widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function _register_controls()
    {
        $roles = wp_roles()->roles;
        $options = ['role_anyone' => __('Anyone', 'cafe-pro')];

        foreach ($roles as $role => $cap) {
            $options['role_'.$role] = ucwords(str_replace('_', ' ', $role));
        }

        $this->start_controls_section(
            'section_editor',
            [
                'label' => __('Text Editor', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'viewers',
            [
                'label' => __('Who Can See This Content?', 'cafe-pro'),
                'default' => __('Anyone', 'cafe-pro'),
                'placeholder' => '',
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $options,
                'multiple' => true,
                'default' => 'anyone'
            ]
        );

        $this->add_control(
            'editor',
            [
                'label' => 'Content for Allowed Users',
                'label_block' => true,
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'cafe-pro'),
            ]
        );

        $this->add_control(
            'intruder',
            [
                'label' => 'Content for NOT Allowed Users',
                'label_block' => true,
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Sorry! You are not allowed to see this content.', 'cafe-pro'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Text Editor', 'cafe-pro'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'cafe-pro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'cafe-pro'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'cafe-pro'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'cafe-pro'),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'cafe-pro'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-text-editor' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}' => 'color: {{VALUE}};',
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
                'name' => 'typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();
    }

    /**
	 * Render text editor widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
    protected function render()
    {
        $allowed_viewers = $this->get_settings_for_display('viewers');

        if ($this->isViewableBy($allowed_viewers)) {
            $editor_content  = $this->get_settings_for_display('editor');
            $editor_content  = $this->parse_text_editor($editor_content);
            $this->add_render_attribute('editor', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ]);
            $this->add_inline_editing_attributes('editor', 'advanced');
            ?>
        	<div <?php echo $this->get_render_attribute_string('editor'); ?>><?php echo $editor_content; ?></div>
        	<?php
        } else {
            $editor_content  = $this->get_settings_for_display('intruder');
            $editor_content  = $this->parse_text_editor($editor_content);
            $this->add_render_attribute('intruder', 'class', [ 'elementor-text-editor', 'elementor-clearfix' ]);
            $this->add_inline_editing_attributes('intruder', 'advanced');
            ?>
        	<div <?php echo $this->get_render_attribute_string('intruder'); ?>><?php echo $editor_content; ?></div>
        	<?php
        }
    }

    /**
     * Check if a menu item is viewable
     *
     * @param    array    $settings    Item's settings.
     *
     * @return    bool
     */
    private function isViewableBy($viewers)
    {
        $valid_roles = [];
        $roles = wp_roles()->roles;

        foreach ($roles as $role => $cap) {
            $fake_role = 'role_'.$role;
            if (in_array($fake_role, $viewers)) {
                $valid_roles[] = $role;
            }
        }

        $user = wp_get_current_user();

        if ($user->exists()) {
            $minimal_role = $user->roles[0];
        } else {
            $minimal_role = 'role_anyone';
        }

        if (in_array($minimal_role, $valid_roles) || isset($viewers['role_anyone'])) {
            return true;
        }

        return false;
    }
}
