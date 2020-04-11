<?php namespace Cafe;

use DirectoryIterator;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Elementor\Widgets_Manager;
use Elementor\Controls_Manager;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Typography;

/**
 * CleverWidgetsManager
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 * @package CAFE
 */
final class WidgetsManager
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
        $this->settings = get_option(Plugin::OPTION_NAME) ? : [];

        add_action( 'elementor/element/image-carousel/section_style_image/after_section_start', function( $element, $args ) {
            /** @var \Elementor\Widgets\Widget_Image_Carousel $element */
            $element->add_group_control(
                \Elementor\Group_Control_Css_Filter::get_type(),
                [
                    'label' => esc_html__( 'Filter', 'cafe-lite' ),
                    'name' => 'css_filters',
                    'selector' => '{{WRAPPER}} img',
                ]
            );
            $element->add_group_control(
                \Elementor\Group_Control_Css_Filter::get_type(),
                [
                    'label' => esc_html__( 'Filter on Hover', 'cafe-lite' ),
                    'name' => 'hover_css_filters',
                    'selector' => '{{WRAPPER}} .slick-slide-inner:hover img',
                ]
            );
        }, 10, 2 );
        add_action( 'elementor/element/image-carousel/section_style_image/before_section_end', function( $element, $args ) {
            /** @var \Elementor\Widgets\Widget_Image_Carousel $element */
            $element->add_control(
                'padding_item',
                [
                    'label' => esc_html__('Padding Item', 'cafe-lite'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px'],
                    'separator'   => 'before',
                    'selectors' => [
                        '{{WRAPPER}} .slick-slide-inner' =>'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]
            );
            $element->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'label' => esc_html__( 'Box Shadow', 'cafe-lite' ),
                    'name' => 'box_shadow',
                    'selector' => '{{WRAPPER}} .slick-slide-inner img',
                ]
            );
            $element->add_control(
                'background_item',
                [
                    'label' => esc_html__('Background', 'cafe-lite'),
                    'type' => \Elementor\Controls_Manager::COLOR,

                    'selectors' => [
                        '{{WRAPPER}} .slick-slide-inner img' => 'background: {{VALUE}};'
                    ]
                ]
            );
        }, 10, 2 );

        add_action( 'elementor/element/counter/section_number/after_section_start', function( $element, $args ) {
            /** @var \Elementor\Widgets\Widget_Counter $element */

            $element->add_control(
                'counter_align',
                [
                    'label' => esc_html__('Align', 'cafe-lite'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__('Left', 'cafe-lite'),
                            'icon' => 'fa fa-align-left',
                        ],
                        'center' => [
                            'center' => esc_html__('Center', 'cafe-lite'),
                            'icon' => 'fa fa-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__('Right', 'cafe-lite'),
                            'icon' => 'fa fa-align-right',
                        ],
                    ],
                    'default'=>'center',
                    'selectors' => [
                        '{{WRAPPER}} .elementor-counter-number-wrapper' => 'justify-content: {{VALUE}};'
                    ]
                ]
            );
        }, 10, 2 );
        add_action( 'elementor/element/counter/section_title/after_section_start', function( $element, $args ) {
            /** @var \Elementor\Widgets\Widget_Counter $element */
            $element->add_control(
                'title_align',
                [
                    'label' => esc_html__('Align', 'cafe-lite'),
                    'type' => \Elementor\Controls_Manager::CHOOSE,
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
                    'default'=>'center',
                    'selectors' => [
                        '{{WRAPPER}} .elementor-counter-title' => 'text-align: {{VALUE}};'
                    ]
                ]
            );
        }, 10, 2 );

        add_action('elementor/element/section/section_layout/before_section_end', [$this, '_addFlexOrderControl'], PHP_INT_MAX, 2);
        add_action('elementor/element/column/layout/before_section_end', [$this, '_addFlexOrderControl'], PHP_INT_MAX, 2);
    }

    /**
     * Singleton
     */
    static function instance($return = false)
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
            add_action('elementor/widgets/widgets_registered', [$self, '_registerWidgets']);
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
    function _registerWidgets(Widgets_Manager $widget_manager)
    {
        $files = new DirectoryIterator(CAFE_DIR.'src/widgets');

        if (!class_exists('Cafe\CleverWidgetBase')) {
            require CAFE_DIR.'src/widgets/class-clever-widget-base.php';
        }

        foreach ($files as $file) {
            if ($file->isFile()) {
                $filename = $file->getFileName();
                if (false !== strpos($filename, '.php') && 'class-clever-widget-base.php' !== $filename) {
                    if (isset($this->settings[$filename]) && '1' === $this->settings[$filename]) {
                        continue;
                    }
                    require CAFE_DIR.'src/widgets/'.$filename;
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
     * Add extra controls
     *
     * @internal
     */
    function _addFlexOrderControl($el, $args)
    {
		$el->add_responsive_control(
			'cafe_element_grid_order',
			[
				'label' => __( 'Flex Order', 'cafe-lite' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
                        'min' => -10,
						'max' => 10,
						'step' => 1,
					],
				],
				'render_type' => 'ui',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}}' => 'order: {{SIZE}};'
                ]
			]
		);
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
}

// Initialize.
WidgetsManager::instance();
