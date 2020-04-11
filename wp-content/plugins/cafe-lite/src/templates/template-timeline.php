<?php
/**
 * View template for TimeLine widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$cafe_content = $settings['timeline'];
$css_class = 'cafe-timeline ' . $settings['layout'];
$date_format = get_option('date_format');
$cafe_json_config = '';
if($settings['layout']=='carousel'){
    wp_enqueue_script('slick');
    $css_class.=' cafe-carousel';

    $settings['autoplay'] ? $settings['autoplay'] : $settings['autoplay'] = 'false';
    $settings['autoplay_tablet'] ? $settings['autoplay_tablet'] : $settings['autoplay_tablet'] = 'false';
    $settings['autoplay_mobile'] ? $settings['autoplay_mobile'] : $settings['autoplay_mobile'] = 'false';

    $settings['show_pag'] ? $settings['show_pag'] : $settings['show_pag'] = 'false';
    $settings['show_pag_tablet'] ? $settings['show_pag_tablet'] : $settings['show_pag_tablet'] = 'false';
    $settings['show_pag_mobile'] ? $settings['show_pag_mobile'] : $settings['show_pag_mobile'] = 'false';

    $settings['show_nav'] ? $settings['show_nav'] : $settings['show_nav'] = 'false';
    $settings['show_nav_tablet'] ? $settings['show_nav_tablet'] : $settings['show_nav_tablet'] = 'false';
    $settings['show_nav_mobile'] ? $settings['show_nav_mobile'] : $settings['show_nav_mobile'] = 'false';

    $settings['pag_arrow_left_icon'] ? $settings['pag_arrow_left_icon'] : $settings['pag_arrow_left_icon'] = 'cs-font clever-icon-arrow-left-1';
    $settings['pag_arrow_right_icon'] ? $settings['pag_arrow_right_icon'] : $settings['pag_arrow_right_icon'] = 'cs-font clever-icon-arrow-right-1';
    $settings['center_mode'] ? $settings['center_mode'] : $settings['center_mode'] = 'false';
    $settings['pag_arrow_left_icon'] ? $settings['pag_arrow_left_icon'] : $settings['pag_arrow_left_icon'] = '';
    $settings['pag_arrow_right_icon'] ? $settings['pag_arrow_right_icon'] : $settings['pag_arrow_right_icon'] = '';
    $settings['speed']?$settings['speed']:$settings['speed']=3000;

    $cafe_json_config        = '{
        "slides_to_show" : ' . $settings['cols']['size'] . ',
        "slides_to_show_tablet" : ' . $settings['cols_tablet']['size'] . ',
        "slides_to_show_mobile" : ' . $settings['cols_mobile']['size'] . ',
        "speed": ' . $settings['speed'] . ',
        "scroll": ' . $settings['scroll'] . ',

        "autoplay": ' . $settings['autoplay'] . ',
        "autoplay_tablet": ' . $settings['autoplay_tablet'] . ',
        "autoplay_mobile": ' . $settings['autoplay_mobile'] . ',

        "show_pag": ' . $settings['show_pag'] . ',
        "show_pag_tablet": ' . $settings['show_pag_tablet'] . ',
        "show_pag_mobile": ' . $settings['show_pag_mobile'] . ',

        "show_nav": ' . $settings['show_nav'] . ',
        "show_nav_tablet": ' . $settings['show_nav_tablet'] . ',
        "show_nav_mobile": ' . $settings['show_nav_mobile'] . ',
        
        "center_mode": ' . $settings['center_mode'] . ',
        "arrow_left": "' . $settings['pag_arrow_left_icon'] . '",
        "arrow_right": "' . $settings['pag_arrow_right_icon'] . '"
    }';
}
?>
<ul class="<?php echo esc_attr($css_class) ?>" <?php if($settings['layout']=='carousel'){?>
    data-cafe-config='<?php echo esc_attr($cafe_json_config) ?>'
    <?php }?>>
    <?php
    if($settings['layout']=='carousel'){
        foreach ($cafe_content as $content) {
            ?>
            <li class="cafe-timeline-item">
                <div class="cafe-head-timeline-item">
                    <div class="cafe-timeline-date">
                        <?php
                        echo esc_html($content['date']);
                        ?>
                    </div>
                    <h3 class="cafe-timeline-title">-
                        <?php
                        echo esc_html($content['title']);
                        ?>
                    </h3>
                </div>
                <div class="cafe-timeline-description">
                    <?php
                    echo esc_html($content['des']);
                    ?>
                </div>
            </li>
            <?php
        }
    }else{
        foreach ($cafe_content as $content) {
            ?>
            <li class="cafe-timeline-item">
                <div class="cafe-timeline-date">
                    <?php
                    echo esc_html($content['date']);
                    ?>
                </div>
                <h3 class="cafe-timeline-title">
                    <?php
                    echo esc_html($content['title']);
                    ?>
                </h3>
                <div class="cafe-timeline-description">
                    <?php
                    echo esc_html($content['des']);
                    ?>
                </div>
            </li>
            <?php
        }
    }
    ?>
</ul>

