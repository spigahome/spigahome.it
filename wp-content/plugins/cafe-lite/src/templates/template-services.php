<?php
/**
 * View template for Services widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ($settings['content'] != ''):
    $layout = $settings['layout'];
    $css_class = 'cafe-services';
    $css_class .= ' ' . $layout . '-layout';
    if ($settings['layout_type'] != '') {
        $css_class .= ' ' . $settings['layout_type'] . '-position';
    }
    $cafe_json_config = '';
    $autoplay_speed = $settings['autoplay_speed'] ? $settings['autoplay_speed'] : '3000';
    if ($layout == 'grid') {
        $css_class .= '  cafe-grid-lg-' . $settings['col']['size'] . '-cols cafe-grid-md-' . $settings['col_tablet']['size'] . '-cols cafe-grid-' . $settings['col_mobile']['size'] . '-cols';
    } else {
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
        $settings['row']?$settings['row']:$settings['row']=0;
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
            "row" : ' . $settings['row'] . ',
            
            "wrap": ".cafe-row"
        }';
        $css_class .= ' cafe-carousel';
    }
    $css_class .= ' ' . $settings['style'];
    ?>
    <div class="<?php echo esc_attr($css_class) ?>"
       <?php if ($cafe_json_config != ''){ ?>data-cafe-config='<?php echo esc_attr($cafe_json_config); ?>'<?php } ?>>
       <div class="cafe-row">
        <?php
        foreach ($settings['content'] as $content):
            ?>
            <article class="cafe-service-item cafe-col <?php echo esc_attr('cafe-service-' . $content['_id']) ?>">
                <div class="cafe-wrap-service">
                    <div class="cafe-wrap-media">
                        <?php
                        $thumb = $content['thumb'];
                        if ($thumb['url'] != '') {
                            ?>
                            <img src="<?php echo esc_url($thumb['url']); ?>"
                            alt="<?php $content['title']; ?>" class="cafe-service-thumb"/>

                        <?php } elseif ($content['icon'] != '') {
                            ?>
                            <i class="cs-font <?php echo esc_attr($content['icon']); ?>"></i>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="cafe-wrap-service-content">
                        <h3 class="cafe-service-title">
                            <?php
                            if ($content['target_url']['url'] != '') {
                                $url = esc_url($content['target_url']['url']);
                                $target = $content['target_url']['is_external'] == 'on' ? 'target="_blank"' : '';
                                $follow = $content['target_url']['nofollow'] == 'on' ? 'rel="nofollow"' : '';
                                printf('<a href="%s" %s %s>%s</a>', $url, $target, $follow, $content['title']);
                            } else {
                                echo $content['title'];
                            }
                            ?>
                        </h3>
                        <?php
                        if ($content['des'] != '') { ?>
                            <div class="cafe-service-des">
                                <?php
                                echo wp_kses_post($content['des']);
                                ?>
                            </div>
                        <?php }
                        if ($content['button_label'] != '') {
                            ?>
                            <div class="cafe-wrap-button">
                                <?php
                                $button_class = 'cafe-button ' . $content['button_style'];
                                if ($content['button_icon'] != '') {
                                    $btn_icon = '<i class="' . $content['button_icon'] . '"></i>';
                                }
                                if ($content['target_url']['url'] != '') {
                                    $url = esc_url($content['target_url']['url']);
                                    $target = $content['target_url']['is_external'] == 'on' ? 'target="_blank"' : '';
                                    $follow = $content['target_url']['nofollow'] == 'on' ? 'rel="nofollow"' : '';
                                    printf('<a href="%s" class="%s" %s %s>%s %s</a>', $url, $button_class, $target, $follow, $content['button_label'], $btn_icon);
                                } else {
                                    printf('<span class="%s">%s %s</span>', $button_class, $content['button_label'], $btn_icon);
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </article>
            <?php
        endforeach;
        ?>
    </div>
</div>
<?php
endif;