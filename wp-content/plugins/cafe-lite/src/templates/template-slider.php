<?php
/**
 * Template for Slider widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ( empty( $settings['slides'] ) ) {
    return;
}

$button_class='cafe-button cafe-slide-btn ';
if ( ! empty( $settings['button_size'] ) ) {
	$button_class.='elementor-size-' . $settings['button_size'].' ';
}

$slides = [];
$slide_count = 0;

foreach ( $settings['slides'] as $slide ) {
    $slide_html = '';
    $slide_bg_overlay = '';
    $btn_attributes = '';
    $slide_attributes = '';
    $slide_element = 'div';
    $btn_element = 'span';
    $slide_url = $slide['link']['url'];

    if ( ! empty( $slide_url ) ) {
        $this->add_render_attribute('slide_link' . $slide_count, 'href', $slide_url );

        if ( $slide['link']['is_external'] ) {
            $this->add_render_attribute('slide_link' . $slide_count, 'target', '_blank');
        }

        if ('button' === $slide['link_click'] ) {
            $btn_element = 'a';
            $btn_attributes = $this->get_render_attribute_string('slide_link' . $slide_count );
        } else {
            $slide_element = 'a';
            $slide_attributes = $this->get_render_attribute_string('slide_link' . $slide_count );
        }
    }

    if ($slide['background_overlay']=='yes' ) {
	    $slide_bg_overlay .= '<div class="cafe-slide-bg-overlay"></div>';
    }

    $slide_html .= '<div class="cafe-slide-content"  data-animation="'.esc_attr($slide['content_animation']).'">';

    if ( $slide['heading'] ) {
        $slide_html .= '<div class="cafe-slide-heading">' . $slide['heading'] . '</div>';
    }if ( $slide['heading-2'] ) {
        $slide_html .= '<div class="cafe-slide-heading sec-heading">' . $slide['heading-2'] . '</div>';
    }

    if ( $slide['description'] ) {
        $slide_html .= '<div class="cafe-slide-desc">' . $slide['description'] . '</div>';
    }

    if ( $slide['button_text'] || $slide['button_icon']) {
        $slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' class="' .$button_class . $slide['button_style'].'">' . $slide['button_text'];
        if($slide['button_icon']){
            $slide_html.= '<i class="'.$slide['button_icon'].'"></i>';
        }
        $slide_html.= '</' . $btn_element . '>';
    }

    $ken_class = '';

    if ('' != $slide['background_ken_burns'] ) {
        $ken_class = ' elementor-ken-' . $slide['zoom_direction'];
    }
    if($slide['background_animation']=='inherit'){
        $ken_class .= ' inherit-effect';
    }

    $slide_html .= '</div>';
	$slider_arg ='{"sound":"'.($slide['background_video_sound']==''?0:$slide['background_video_sound']).'"}';
    $slide_html = '<div class="slick-slide-bg' . $ken_class . '"  data-animation="'.esc_attr($slide['background_animation']).'"></div>'.$this->cafe_viewer_video($slide['background_video'],$slider_arg).$slide_bg_overlay.'<' . $slide_element . ' ' . $slide_attributes . ' class="slick-slide-inner">' . $slide_html . '</' . $slide_element . '>';
    $slides[] = '<div class="elementor-repeater-item-' . $slide['_id'] . ' slick-slide">' . $slide_html . '</div>';
    $slide_count++;
}

$is_rtl = is_rtl();
$direction = $is_rtl ? 'rtl' : 'ltr';
$show_dots = $settings['show_dots']=='yes'?true:false;
$show_arrows = $settings['show_arrows']=='yes'?true:false;

$dots_style=!empty($settings['dots_style']) ? 'slick-' . esc_attr($settings['dots_style']) : 'slick-dots';
$slick_options = [
    'slidesToShow' => 1,
    'autoplaySpeed' => absint( $settings['autoplay_speed'] ),
    'autoplay' => ('yes' === $settings['autoplay'] ),
    'infinite' => ('yes' === $settings['infinite'] ),
    'pauseOnHover' => ('yes' === $settings['pause_on_hover'] ),
    'speed' => absint( $settings['transition_speed'] ),
    'arrows' => $show_arrows,
    'dots' => $show_dots,
    'dotsClass' => $dots_style,
    'rtl' => $is_rtl
];

if (!empty($settings['arrow_left_icon'])) {
    $this->add_render_attribute('arrow_left_icon', 'type', 'button');
    $this->add_render_attribute('arrow_left_icon', 'style','display:block');
    $this->add_render_attribute('arrow_left_icon', 'class', ['cafe-slider-prev', 'slick-arrow', esc_attr($settings['arrow_left_icon'])]);
    $slick_options['prevArrow'] = '<span ' .  $this->get_render_attribute_string('arrow_left_icon') . '></span>';
}

if (!empty($settings['arrow_right_icon'])) {
    $this->add_render_attribute('arrow_right_icon', 'type', 'button');
    $this->add_render_attribute('arrow_right_icon', 'style','display:block');
    $this->add_render_attribute('arrow_right_icon', 'class', ['cafe-slider-next', 'slick-arrow', esc_attr($settings['arrow_right_icon'])]);
    $slick_options['nextArrow'] = '<span ' . $this->get_render_attribute_string('arrow_right_icon') . '></span>';
}

if ('fade' === $settings['transition'] ) {
    $slick_options['fade'] = true;
}

$carousel_classes = ['cafe-slider-slides'];

if ( $show_arrows ) {
    $carousel_classes[] = 'slick-arrows-' . $settings['arrows_position'];
    if($settings['show_arrows_tablet']!='yes'){
	    $carousel_classes[] ='cafe-hide-arrows-on-tablet';
    }if($settings['show_arrows_mobile']!='yes'){
	    $carousel_classes[] ='cafe-hide-arrows-on-mobile';
    }
}

if ( $show_dots ) {
    $carousel_classes[] = 'slick-dots-' . $settings['dots_position'];
	if($settings['show_dots_tablet']!='yes'){
		$carousel_classes[] ='cafe-hide-dots-on-tablet';
	}if($settings['show_dots_mobile']!='yes'){
		$carousel_classes[] ='cafe-hide-dots-on-mobile';
	}
}

$this->add_render_attribute('slides', [
    'class' => $carousel_classes,
    'data-slider_options' => wp_json_encode( $slick_options ),
    'data-animation' => $settings['content_animation'],
]);

?>
<div class="cafe-slider-wrapper elementor-slick-slider" dir="<?php echo esc_attr( $direction ); ?>">
    <div <?php echo $this->get_render_attribute_string('slides'); ?>>
        <?php echo implode('', $slides ); ?>
    </div>
</div>
