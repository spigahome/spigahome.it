<?php namespace Cafe\Widgets;

use Elementor\Controls_Manager;

/**
 * Clever Image 360 View
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class CleverImage360View extends CleverWidgetBase
{
    /**
     * @return string
     */
    function get_name()
    {
        return 'clever-image-360-view';
    }

    /**
     * @return string
     */
    function get_title()
    {
        return __('Clever Image 360 View', 'cafe-pro');
    }

    /**
     * @return string
     */
    function get_icon()
    {
        return 'cs-font clever-icon-360-2';
    }

    /**
     * Register controls
     */
    protected function _register_controls()
    {
        $this->start_controls_section('settings', [
            'label' => __('Settings', 'cafe-pro')
        ]);
        $this->add_control(
            'images',
            [
                'label' => __( 'Add Images', 'elementor' ),
                'type' => Controls_Manager::GALLERY,
                'default' => [],
                'show_label' => false,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $this->add_control('width', [
            'label' => __('Width', 'cafe-pro'),
            'type' => Controls_Manager::NUMBER,
            'description' => __('Set a width value in px.', 'cafe-pro'),
        ]);
        $this->add_control('height', [
            'label' => __('Height', 'cafe-pro'),
            'type' => Controls_Manager::NUMBER,
            'description' => __('Set a height value in px.', 'cafe-pro'),
        ]);
        $this->add_control('des', [
            'label' => __('Description', 'cafe-pro'),
            'placeholder' => __('Short description of block.', 'cafe-pro'),
            'description' => __('Short description of block.', 'cafe-pro'),
            'type' => Controls_Manager::TEXT,
            'dynamic' => ['active' => true],
            'label_block' => false
        ]);
        $this->add_control('css_class', [
            'label'			=> __('Custom HTML Class', 'cafe-pro'),
            'type'			=> Controls_Manager::TEXT,
            'description'	=> __('You may add a custom HTML class to style element later.', 'cafe-pro'),
        ]);
        $this->end_controls_section();
    }
    /**
     * Load style
     */
    public function get_style_depends() {
        return [ 'cafe-style' ];
    }    /**
     * Retrieve the list of scripts the image carousel widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return [ 'spritespin','cafe-script' ];
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = array_merge([ // default settings
            'images' => '',
            'width'=>'',
            'height'=>'',
            'des'=>'',
            'css_class'=>'',

        ], $this->get_settings_for_display());

        $this->add_inline_editing_attributes('des');

        $this->getViewTemplate('template', 'image-360-view', $settings);
    }
}
