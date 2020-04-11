<?php
/**
 * View template for Clever Instagram
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$cafe_json_config = $target = $limit = $img_size = $count = '';
if ( !empty( $settings['link_target'] ) && $settings['link_target'] == '_blank' ) {
    $target = 'target="_blank"';
}
$limit = !empty( $settings['number'] ) ? $settings['number'] : 8;
$img_size = !empty( $settings['img_size'] ) ? $settings['img_size'] : 'large';

$cafe_wrap_class = "woocommerce cafe-products-wrap";
$cafe_json_config = '';
if($settings['layout'] == 'carousel'){
    $class                  = 'grid-layout carousel';
    $class                  .= ' ' . $settings['nav_position'];
    $cafe_wrap_class         .= ' cafe-carousel';

    $settings['autoplay'] ? $settings['autoplay'] : $settings['autoplay'] = 'false';
    $settings['autoplay_tablet'] ? $settings['autoplay_tablet'] : $settings['autoplay_tablet'] = 'false';
    $settings['autoplay_mobile'] ? $settings['autoplay_mobile'] : $settings['autoplay_mobile'] = 'false';

    $settings['show_pag'] ? $settings['show_pag'] : $settings['show_pag'] = 'false';
    $settings['show_pag_tablet'] ? $settings['show_pag_tablet'] : $settings['show_pag_tablet'] = 'false';
    $settings['show_pag_mobile'] ? $settings['show_pag_mobile'] : $settings['show_pag_mobile'] = 'false';

    $settings['show_nav'] ? $settings['show_nav'] : $settings['show_nav'] = 'false';
    $settings['show_nav_tablet'] ? $settings['show_nav_tablet'] : $settings['show_nav_tablet'] = 'false';
    $settings['show_nav_mobile'] ? $settings['show_nav_mobile'] : $settings['show_nav_mobile'] = 'false';
    $settings['speed']?$settings['speed']:$settings['speed']=3000;
    $cafe_json_config = '{
        "slides_to_show" : ' . $settings['slides_to_show']['size'] . ',
        "slides_to_show_tablet" : ' . $settings['slides_to_show_tablet']['size'] . ',
        "slides_to_show_mobile" : ' . $settings['slides_to_show_mobile']['size'] . ',

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
        "wrap": ".wrap-instagram"
    }';
}else{
    $cafe_wrap_class         = "woocommerce cafe-products-wrap";
    $class                  = 'grid-layout';
    $grid_class = '  cafe-grid-lg-' . $settings['columns']['size'] . '-cols cafe-grid-md-' . $settings['columns_tablet']['size'] . '-cols cafe-grid-' . $settings['columns_mobile']['size'] .'-cols';
    $cafe_wrap_class .= $grid_class;
}

if ( !empty( $settings['username'] ) ) : ?>
    <?php $media_array = cafe_scrape_instagram( $settings['username'] ); ?>

    <?php if ( is_wp_error( $media_array ) ) : ?>

        <?php echo wp_kses_post( $media_array->get_error_message() ); ?>

        <?php else : ?>
            <?php
            if ( $images_only = apply_filters( 'cafe_instagram_images_only', FALSE ) ) {
                $media_array = array_filter( $media_array, 'cafe_images_only' );
            }
            ?>
            <div class="<?php echo esc_attr($cafe_wrap_class) ?> " data-cafe-config='<?php echo esc_attr($cafe_json_config) ?>'>
                <?php if (isset($settings['title']) && $settings['title'] != '') : 
                    printf('<h3 %s>%s</h3>',$this->get_render_attribute_string('title'), $settings['title']); 
                endif; ?>
                <div class="wrap-instagram">
                    <?php foreach ( $media_array as $item ) : $count++; if($count > $limit){break;} ?>
                        <div class="instagram-item post">
                            <div class="instagram-item-inner">
                                <?php
                            $type =  $item['type']; // image, video
                            $comments = cafe_abbreviate_total_count( $item['comments'], 10000 );
                            $likes = cafe_abbreviate_total_count( $item['likes'], 10000 );
                            $time =  cafe_time_elapsed_string( '@' . $item['time'] );

                            $gmt = get_option('gmt_offset');;
                            $time_zone = get_option( 'timezone_string' );
                            if ( !empty( $settings['date_format'] ) ) {
                                $date_format = $settings['date_format'];
                            } else {
                                $date_format = get_option( 'date_format' );
                            }
                            ?>
                            <a href="<?php echo esc_url( $item['link'] ); ?>"<?php echo $target; ?>>
                                <?php if ( !empty( $settings['show_type'] ) && $settings['show_type'] == '1' ) : ?>
                                    <?php if ( $type == 'video' ) : ?>
                                        <span class="type type-video"><i class="cs-font clever-icon-triangle"></i></span>
                                        <?php else : ?>
                                            <span class="type type-image"><i class="cs-font clever-icon-compare-6"></i></span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <span class="group-items">
                                        <?php if ( !empty( $settings['show_likes'] ) && $settings['show_likes'] ) : ?>
                                            <span class="likes"><i class="cs-font clever-icon-heart-o"></i><?php echo $likes; ?></span>
                                        <?php endif; ?>

                                        <?php if ( !empty( $settings['show_comments'] ) && $settings['show_comments'] ) : ?>
                                            <span class="comments"><i class="cs-font clever-icon-consulting-message"></i><?php echo $comments; ?></span>
                                        <?php endif; ?>
                                    </span>

                                    <?php if ( !empty( $settings['show_time'] ) && $settings['show_time'] ) : ?>
                                        <?php if ( !empty( $settings['time_layout'] ) && $settings['time_layout'] == 'elapsed' ) : ?>
                                            <span class="time elapsed-time"><?php echo $time; ?></span>
                                        <?php endif; ?>

                                        <?php if ( !empty( $settings['time_layout'] ) && $settings['time_layout'] == 'date' ) : ?>
                                            <span class="time date-time"><?php echo date_i18n( $date_format, $item['time'], $gmt ); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <img src="<?php echo esc_url( $item[$img_size] ); ?>"  alt="<?php echo esc_attr( $item['description'] ); ?>"  class="instagram-photo"/>
                                </a>
                            </div>
                        </div>
                        
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php endif; ?>