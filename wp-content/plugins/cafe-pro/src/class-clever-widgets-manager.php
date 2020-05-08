<?php namespace Cafe;

use DirectoryIterator;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Elementor\Widgets_Manager;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

/**
 * CleverWidgetsManager
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class WidgetsManagerPro
{
    /**
     * Plugin settings
     */
    private $settings;

    /**
     * Nope constructor
     */
    private function __construct()
    {
        $this->settings = get_option('cafe_plugin_settings') ? : [];
    }

    /**
     * Singleton
     */
    public static function instance($return = false)
    {
        static $self = null;
        if (null === $self) {
            $self = new self;
            if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && is_admin() ) {
                add_action( 'init', [ $self, 'register_wc_hooks' ], 5 );
            }
            add_action('elementor/widgets/widgets_registered', [$self, '_registerWidgets']);
            add_action('elementor/element/after_section_end', [$self, '_addCustomMediaQueries'], PHP_INT_MAX, 3);
            add_action('elementor/widget/before_render_content', [$self, '_renderCustomMediaQueriesForContent'], PHP_INT_MAX);
            add_action('elementor/frontend/column/before_render', [$self, '_renderCustomMediaQueriesForColumn'], PHP_INT_MAX);
            add_action('elementor/frontend/section/before_render', [$self, '_renderCustomMediaQueriesForSection'], PHP_INT_MAX);
            add_action('elementor/element/section/section_effects/after_section_end', [$self, '_addStickyEffect']);
            add_action('elementor/element/common/section_effects/after_section_end', [$self, '_addStickyEffect']);
            add_action('elementor/frontend/section/before_render', [$self, '_startStickyContainer']);
            add_action('elementor/frontend/section/after_render', [$self, '_endStickyContainer']);
        }

        if ($return) {
            return $self;
        }
    }

    /**
     * Register widgets
     *
     * @internal Used as a callback.
     */
    public function _registerWidgets(Widgets_Manager $widget_manager)
    {
        $files = new DirectoryIterator(CAFE_PRO_DIR.'src/widgets');

        foreach ($files as $file) {
            if ($file->isFile()) {
                $filename = $file->getFileName();
                if (false !== strpos($filename, '.php') && 'class-clever-widget-base.php' !== $filename) {
                    if (isset($this->settings[$filename]) && '1' === $this->settings[$filename]) {
                        continue;
                    }
                    require CAFE_PRO_DIR.'src/widgets/'.$filename;
                    $classname = $this->getWidgetClassName($filename);
                    $widget = class_exists($classname) ? new $classname() : false;
                    if ($widget && $widget instanceof Widget_Base) {
                        $widget_manager->register_widget_type($widget);
                    }
                }
            }
        }
    }

    /**
     * Add more controls to the built-in widgets of Elementor
     *
     * @internal Used as a callback.
     */
    public function _addCustomMediaQueries($widget, $section, $args)
    {
        if ('_section_responsive' === $section) {
            $widget->start_controls_section(
                'cafe_custom_media_queries',
                [
                    'label' => __('Custom Media Queries', 'cafe-pro'),
                    'tab' => Controls_Manager::TAB_ADVANCED,
                ]
            );

            $repeater = new Repeater();

            $repeater->add_control(
                'enable',
                [
                    'label' => __('Enable', 'cafe-pro'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => ''
                ]
            );

            $repeater->add_control(
                'min_width',
                [
                    'label' => __('Min Width', 'cafe-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1024
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 767,
                    ],
                    'condition' => [
                        'enable!' => '',
                    ]
                ]
            );

            $repeater->add_control(
                'max_width',
                [
                    'label' => __('Max Width', 'cafe-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1920
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 1024,
                    ],
                    'condition' => [
                        'enable!' => '',
                    ]
                ]
            );

            $repeater->add_control(
                'hidden',
                [
                    'label' => __('Hide', 'cafe-pro'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => '',
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $repeater->add_control(
                'width',
                [
                    'label' => __('Width', 'cafe-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ '%', 'px' ],
                    'range' => [
                        '%' => [
                            'min' => 10,
                            'max' => 100
                        ],
                        'px' => [
                            'min' => 768,
                            'max' => 1920
                        ]
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 100,
                    ],
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $repeater->add_control(
                'font_size',
                [
                    'label' => __('Font Size', 'cafe-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', 'em', 'rem' ],
                    'range' => [
                        'em' => [
                            'min' => 0.5,
                            'max' => 5,
                            'step' => 0.1
                        ],
                        'rem' => [
                            'min' => 0.5,
                            'max' => 5,
                            'step' => 0.1
                        ],
                        'px' => [
                            'min' => 10,
                            'max' => 64
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 16,
                    ],
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $repeater->add_control(
                'line_height',
                [
                    'label' => __('Line Height', 'cafe-pro'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 64
                        ]
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 16,
                    ],
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $repeater->add_control(
                'margin',
                [
                    'label' => __('Margin', 'cafe-pro'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100
                        ]
                    ],
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $repeater->add_control(
                'padding',
                [
                    'label' => __('Padding', 'cafe-pro'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100
                        ]
                    ],
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $repeater->add_control(
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
                    ],
                    'condition' => [
                        'enable!' => '',
                    ],
                ]
            );

            $widget->add_control(
                'cafe_custom_queries_items',
                [
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ min_width.size }}}px - {{{ max_width.size }}}px',
                ]
            );

            $widget->end_controls_section();
        }
    }

    /**
     * Render custom media queries
     *
     * @internal Used as a callback
     */
    public function _renderCustomMediaQueriesForSection($section)
    {
        $settings = $section->get_settings();

        if (!empty($settings['cafe_custom_queries_items'])) {
            $_id = $section->get_id();
            $style = '';
            foreach ($settings['cafe_custom_queries_items'] as $item) {
                if ('yes' !== $item['enable']) {
                    continue;
                }
                $style .= '@media ';
                if (!empty($item['min_width']['size'])) {
                    $style .= '(min-width:'. intval($item['min_width']['size']) .'px)';
                }
                if (!empty($item['min_width']['size']) && !empty($item['max_width']['size'])) {
                    $style .= ' and ';
                }
                if (!empty($item['max_width']['size'])) {
                    $style .= '(max-width:'. intval($item['max_width']['size']) . 'px)';
                }
                $style .= '{.elementor-element.elementor-element-' . $_id . '{';
                if ('yes' === $item['hidden']) {
                    $style .= 'display:none;';
                }
                if (!empty($item['width']['size'])) {
                    $style .= 'width:'. intval($item['width']['size']) . $item['width']['unit'] . ';';
                }
                if (!empty($item['font_size']['size'])) {
                    $style .= 'font-size:'. intval($item['font_size']['size']) . $item['font_size']['unit'] . ';';
                }
                if (!empty($item['line_height']['size'])) {
                    $style .= 'line-height:'. intval($item['line_height']['size']) . $item['line_height']['unit'] . ';';
                }
                if (is_numeric($item['margin']['top'])) {
                    $style .= 'margin-top:'. intval($item['margin']['top']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['right'])) {
                    $style .= 'margin-right:'. intval($item['margin']['right']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['bottom'])) {
                    $style .= 'margin-bottom:'. intval($item['margin']['bottom']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['left'])) {
                    $style .= 'margin-left:'. intval($item['margin']['left']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['padding']['top'])) {
                    $style .= 'padding-top:'. intval($item['padding']['top']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['right'])) {
                    $style .= 'padding-right:'. intval($item['padding']['right']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['bottom'])) {
                    $style .= 'padding-bottom:'. intval($item['padding']['bottom']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['left'])) {
                    $style .= 'padding-left:'. intval($item['padding']['left']) . $item['padding']['unit'] . ';';
                }
                if (!empty($item['align'])) {
                    $style .= 'text-align:'. $item['align'] . ';';
                }
                $style .= '}}';
            }
            add_action('wp_footer', function () use ($style) {
                echo '<style>' . $style . '</style>';
            }, 10, 0);
        }
    }

    /**
     * Render custom media queries
     *
     * @internal Used as a callback
     */
    public function _renderCustomMediaQueriesForColumn($col)
    {
        $settings = $col->get_settings();

        if (!empty($settings['cafe_custom_queries_items'])) {
            $_id = $col->get_id();
            $style = '';
            foreach ($settings['cafe_custom_queries_items'] as $item) {
                if ('yes' !== $item['enable']) {
                    continue;
                }
                $style .= '@media ';
                if (!empty($item['min_width']['size'])) {
                    $style .= '(min-width:'. intval($item['min_width']['size']) .'px)';
                }
                if (!empty($item['min_width']['size']) && !empty($item['max_width']['size'])) {
                    $style .= ' and ';
                }
                if (!empty($item['max_width']['size'])) {
                    $style .= '(max-width:'. intval($item['max_width']['size']) . 'px)';
                }
                $style .= '{.elementor-element.elementor-section .elementor-element.elementor-element-' . $_id . '>.elementor-element-populated{';
                if ('yes' === $item['hidden']) {
                    $style .= 'display:none;';
                }
                if (!empty($item['width']['size'])) {
                    $style .= 'width:'. intval($item['width']['size']) . $item['width']['unit'] . ';';
                }
                if (!empty($item['font_size']['size'])) {
                    $style .= 'font-size:'. intval($item['font_size']['size']) . $item['font_size']['unit'] . ';';
                }
                if (!empty($item['line_height']['size'])) {
                    $style .= 'line-height:'. intval($item['line_height']['size']) . $item['line_height']['unit'] . ';';
                }
                if (is_numeric($item['margin']['top'])) {
                    $style .= 'margin-top:'. intval($item['margin']['top']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['right'])) {
                    $style .= 'margin-right:'. intval($item['margin']['right']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['bottom'])) {
                    $style .= 'margin-bottom:'. intval($item['margin']['bottom']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['left'])) {
                    $style .= 'margin-left:'. intval($item['margin']['left']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['padding']['top'])) {
                    $style .= 'padding-top:'. intval($item['padding']['top']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['right'])) {
                    $style .= 'padding-right:'. intval($item['padding']['right']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['bottom'])) {
                    $style .= 'padding-bottom:'. intval($item['padding']['bottom']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['left'])) {
                    $style .= 'padding-left:'. intval($item['padding']['left']) . $item['padding']['unit'] . ';';
                }
                if (!empty($item['align'])) {
                    $style .= 'text-align:'. $item['align'] . ';';
                }
                $style .= '}}';
            }
            add_action('wp_footer', function () use ($style) {
                echo '<style>' . $style . '</style>';
            }, 11, 0);
        }
    }

    /**
     * Render custom media queries
     *
     * @internal Used as a callback
     */
    public function _renderCustomMediaQueriesForContent($el)
    {
        $settings = $el->get_settings();

        if (!empty($settings['cafe_custom_queries_items'])) {
            $_id = $el->get_id();
            $style = '';
            foreach ($settings['cafe_custom_queries_items'] as $item) {
                if ('yes' !== $item['enable']) {
                    continue;
                }
                $style .= '@media ';
                if (!empty($item['min_width']['size'])) {
                    $style .= '(min-width:'. intval($item['min_width']['size']) .'px)';
                }
                if (!empty($item['min_width']['size']) && !empty($item['max_width']['size'])) {
                    $style .= ' and ';
                }
                if (!empty($item['max_width']['size'])) {
                    $style .= '(max-width:'. intval($item['max_width']['size']) . 'px)';
                }
                $style .= '{.elementor-element.elementor-element-' . $_id . '{';
                if ('yes' === $item['hidden']) {
                    $style .= 'display:none;';
                }
                if (!empty($item['width']['size'])) {
                    $style .= 'width:'. intval($item['width']['size']) . $item['width']['unit'] . ';';
                }
                if (!empty($item['font_size']['size'])) {
                    $style .= 'font-size:'. intval($item['font_size']['size']) . $item['font_size']['unit'] . ';';
                }
                if (!empty($item['line_height']['size'])) {
                    $style .= 'line-height:'. intval($item['line_height']['size']) . $item['line_height']['unit'] . ';';
                }
                if (is_numeric($item['margin']['top'])) {
                    $style .= 'margin-top:'. intval($item['margin']['top']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['right'])) {
                    $style .= 'margin-right:'. intval($item['margin']['right']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['bottom'])) {
                    $style .= 'margin-bottom:'. intval($item['margin']['bottom']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['margin']['left'])) {
                    $style .= 'margin-left:'. intval($item['margin']['left']) . $item['margin']['unit'] . ';';
                }
                if (is_numeric($item['padding']['top'])) {
                    $style .= 'padding-top:'. intval($item['padding']['top']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['right'])) {
                    $style .= 'padding-right:'. intval($item['padding']['right']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['bottom'])) {
                    $style .= 'padding-bottom:'. intval($item['padding']['bottom']) . $item['padding']['unit'] . ';';
                }
                if (is_numeric($item['padding']['left'])) {
                    $style .= 'padding-left:'. intval($item['padding']['left']) . $item['padding']['unit'] . ';';
                }
                if (!empty($item['align'])) {
                    $style .= 'text-align:'. $item['align'] . ';';
                }
                $style .= '}}';
            }
            add_action('wp_footer', function () use ($style) {
                echo '<style>' . $style . '</style>';
            }, 13, 0);
        }
    }

    /**
     * Add sticky effect for every section.
     *
     * @internal Used as a callback
     */
    public function _addStickyEffect($el)
    {
		$el->start_controls_section(
			'section_sticky_effect',
			[
				'label' => __( 'Row Sticky', 'cafe-pro' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

        $el->add_control(
            'enable_sticky_effect',
            [
                'label' => __('Enable Sticky', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'cafe-pro'),
                'label_off' => __('No', 'cafe-pro'),
                'default' => '',
                'return_value' => 'sticky',
                'prefix_class' => 'cafe-row-',
                'separator'   => 'after',
            ]
        );
        $el->add_control(
            'enable_desktop_sticky_effect',
            [
                'label' => __('Enable Desktop Sticky', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'cafe-pro'),
                'label_off' => __('No', 'cafe-pro'),
                'default' => '',
                'return_value' => 'sticky',
                'prefix_class' => 'desktop-',
                'condition' => [
                    'enable_sticky_effect!' => ''
                ]
            ]
        );
        $el->add_control(
            'enable_tablet_sticky_effect',
            [
                'label' => __('Enable Tablet Sticky', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'cafe-pro'),
                'label_off' => __('No', 'cafe-pro'),
                'default' => '',
                'return_value' => 'sticky',
                'prefix_class' => 'tablet-',
                'condition' => [
                    'enable_sticky_effect!' => ''
                ]
            ]
        );
        $el->add_control(
            'enable_mobile_sticky_effect',
            [
                'label' => __('Enable Mobile Sticky', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'cafe-pro'),
                'label_off' => __('No', 'cafe-pro'),
                'default' => '',
                'return_value' => 'sticky',
                'prefix_class' => 'mobile-',
                'separator'   => 'after',
                'condition' => [
                    'enable_sticky_effect!' => ''
                ]
            ]
        );
        $el->add_control(
            'enable_scroll_up_sticky',
            [
                'label' => __('Enable Scroll Up Sticky', 'cafe-pro'),
                'description' => __('Header sticky only when scroll up', 'cafe-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'cafe-pro'),
                'label_off' => __('No', 'cafe-pro'),
                'default' => '',
                'return_value' => 'sticky',
                'prefix_class' => 'cafe-scroll-up-',
                'condition' => [
                    'enable_sticky_effect!' => ''
                ],
            ]
        );
        $el->add_responsive_control(
            'sticky_effect_offset_top',
            [
                'label' => __('Sticky Offset Top', 'cafe-pro'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ]
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}}.is-sticky' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_sticky_effect!' => ''
                ],

			'separator'   => 'after',
            ]
        );

        $el->add_control(
            'sticky_effect_color',
            [
                'label' => __('Sticky Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'description' => __('For Clever Header&#8217;s Widgets only.', 'cafe-pro'),
                'selectors' => [
                    '{{WRAPPER}}.is-sticky, {{WRAPPER}}.is-sticky .cafe-site-menu:not(.hamburger) .cafe-menu > .menu-item > a,
                    {{WRAPPER}}.is-sticky .cafe-wlcp-label,
                    {{WRAPPER}}.is-sticky .cafe-wlcp-icon i,
                    {{WRAPPER}}.is-sticky .cafe-account-btn,
                    {{WRAPPER}}.is-sticky .cafe-account-btn i,
                    {{WRAPPER}}.is-sticky .cafe-search-toggle-button,
                    {{WRAPPER}}.is-sticky .cafe-wrap-right-cart,
                    {{WRAPPER}}.is-sticky .cafe-wrap-right-cart .amount,
                    {{WRAPPER}}.is-sticky .cafe-wrap-icon-cart,
                    {{WRAPPER}}.is-sticky .cafe-hamburger-button'
                    => '--sticky-color: {{VALUE}};color: {{VALUE}};--menu-icon-color:{{VALUE}}',
                ],
                'condition' => [
                    'enable_sticky_effect!' => ''
                ],
            ]
        );
        $el->add_control(
            'sticky_effect_bg_color',
            [
                'label' => __('Sticky Background Color', 'cafe-pro'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.is-sticky' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'enable_sticky_effect!' => ''
                ],
            ]
        );

		$el->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sticky_effect_border',
				'selector' => '{{WRAPPER}}.is-sticky',
                'condition' => [
                    'enable_sticky_effect!' => ''
                ]
			]
		);

        $el->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label' => esc_html__('Sticky Box Shadow', 'cafe-pro'),
                'name'          => 'sticky_effect_shadow',
                'selector'      => '{{WRAPPER}}.is-sticky',
                'condition' => [
                    'enable_sticky_effect!' => ''
                ]
            ]
        );

        if ($el instanceof Widget_Base) {
            $el->add_control(
                'sticky_parent_container',
                [
                    'label' => __('Stay In Column', 'cafe-pro'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [
                        'enable_sticky_effect!' => '',
                    ],
                    'render_type' => 'none',
                    'frontend_available' => true,
                ]
            );
        }

        $el->end_controls_section();
    }

    /**
     * Maybe start sticky container
     */
    function _startStickyContainer($el)
    {
        $settings = $el->get_settings();

        if (isset($settings['enable_sticky_effect']) && 'sticky' === $settings['enable_sticky_effect']) {
            echo '<div class="cafe-sticky-row-container">';
        }
    }

    /**
     * Maybe end sticky container
     */
    function _endStickyContainer($el)
    {
        $settings = $el->get_settings();

        if (isset($settings['enable_sticky_effect']) && 'sticky' === $settings['enable_sticky_effect']) {
            echo '</div>';
        }
    }

    /**
     * Get classname from filename
     *
     * @param string $filename
     *
     * @return string
     */
    private function getWidgetClassName($filename)
    {
        $_filename = trim(str_replace(['class', '-', '.php'], ['', ' ', ''], $filename));

        return sprintf('%s\Widgets\%s', __NAMESPACE__, str_replace(' ', '', ucwords($_filename)));
    }

    /**
     * Get WC instance
     *
     * @param string $filename
     *
     * @return string
     */
    public function register_wc_hooks() {
        if(class_exists('WooCommerce')) {
            wc()->frontend_includes();
        }
    }
}

// Initialize.
WidgetsManagerPro::instance();
