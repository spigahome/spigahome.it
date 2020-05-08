<?php
/**
 * Template for Slider2 widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if (empty($settings['slides'])) {
    return;
}

$slides = [];
$slide_count = 0;
$elementor_fontend_builder = Elementor\Plugin::$instance->frontend;

foreach ($settings['slides'] as $slide) {
    $template = get_page_by_path($slide['slide_template'], OBJECT, 'elementor_library');
    if ($template) {
        $slides[] = $elementor_fontend_builder->get_builder_content_for_display($template->ID, false);
    }
}

$is_rtl = is_rtl();
$direction = $is_rtl ? 'rtl' : 'ltr';
$show_dots = $settings['show_dots']=='yes'?true:false;
$show_arrows = $settings['show_arrows']=='yes'?true:false;

$dots_style=!empty($settings['dots_style']) ? 'slick-' . esc_attr($settings['dots_style']) : 'slick-dots';
$slick_options = [
    'rows' => 0,
    'slide' => 'div[data-elementor-type="section"]',
    'slidesToShow' => 1,
    'autoplaySpeed' => absint($settings['autoplay_speed']),
    'autoplay' => ('yes' === $settings['autoplay']),
    'infinite' => ('yes' === $settings['infinite']),
    'pauseOnHover' => ('yes' === $settings['pause_on_hover']),
    'speed' => absint($settings['transition_speed']),
    'arrows' => $show_arrows,
    'dots' => $show_dots,
    'dotsClass' => $dots_style,
    'rtl' => $is_rtl
];

if (!empty($settings['arrow_left_icon'])) {
    $this->add_render_attribute('arrow_left_icon', 'type', 'button');
    $this->add_render_attribute('arrow_left_icon', 'style', 'display:block');
    $this->add_render_attribute('arrow_left_icon', 'class', ['cafe-slider-prev', 'slick-arrow', esc_attr($settings['arrow_left_icon'])]);
    $slick_options['prevArrow'] = '<span ' .  $this->get_render_attribute_string('arrow_left_icon') . '></span>';
}

if (!empty($settings['arrow_right_icon'])) {
    $this->add_render_attribute('arrow_right_icon', 'type', 'button');
    $this->add_render_attribute('arrow_right_icon', 'style', 'display:block');
    $this->add_render_attribute('arrow_right_icon', 'class', ['cafe-slider-next', 'slick-arrow', esc_attr($settings['arrow_right_icon'])]);
    $slick_options['nextArrow'] = '<span ' . $this->get_render_attribute_string('arrow_right_icon') . '></span>';
}

$carousel_classes = ['cafe-slider-pro-slides'];

if ($show_arrows) {
    $carousel_classes[] = 'slick-arrows-' . $settings['arrows_position'];
    if ($settings['show_arrows_tablet']!='yes') {
        $carousel_classes[] ='cafe-hide-arrows-on-tablet';
    }
    if ($settings['show_arrows_mobile']!='yes') {
        $carousel_classes[] ='cafe-hide-arrows-on-mobile';
    }
}

if ($show_dots) {
    $carousel_classes[] = 'slick-dots-' . $settings['dots_position'];
    if ($settings['show_dots_tablet']!='yes') {
        $carousel_classes[] ='cafe-hide-dots-on-tablet';
    }
    if ($settings['show_dots_mobile']!='yes') {
        $carousel_classes[] ='cafe-hide-dots-on-mobile';
    }
}

$this->add_render_attribute('slides', [
    'class' => $carousel_classes,
    'data-slider_options' => wp_json_encode($slick_options)
]);

?>
<div class="cafe-slider-pro cafe-slider-wrapper elementor-slick-slider" dir="<?php echo esc_attr($direction); ?>">
    <div <?php echo $this->get_render_attribute_string('slides'); ?>>
        <?php echo implode('', $slides); ?>
    </div>
</div>
