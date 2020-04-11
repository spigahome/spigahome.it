<?php
/**
 * View template for Testimonial widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ($settings['content'] != ''):
    $layout = $settings['layout'];
    $css_class = 'cafe-testimonial';
    $css_class .= ' ' . $layout . '-layout';
    $css_class .= ' ' . $settings['style'];
    $data_config = '';
    $autoplay_speed = $settings['autoplay_speed'] ? $settings['autoplay_speed'] : '3000';
	$cafe_json_config='';
    if ($layout == 'grid') {
        $css_class .= '  cafe-grid-lg-' . $settings['columns']['size'] . '-cols cafe-grid-md-' . $settings['columns_tablet']['size'] . '-cols cafe-grid-' . $settings['columns_mobile']['size'] . '-cols';
    } else {
        $css_class .= '  cafe-grid-lg-' . $settings['slides_to_show']['size'] . '-cols cafe-grid-md-' . $settings['slides_to_show_tablet']['size'] . '-cols cafe-grid-' . $settings['slides_to_show_mobile']['size'] . '-cols';
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
            "wrap": ".cafe-row"
        }';
        $css_class .= ' cafe-carousel';
    }
    $css_class .= ' ' . $settings['css_class'];
    $quote = '';
    if ($settings['show_quotation'] == 'true') {
        $quote = '<span class="cafe-quotation"><i class="cs-font clever-icon-quote-1"></i></span>';
    }
    ?>
    <div class="<?php echo esc_attr($css_class) ?>"
         <?php if ($cafe_json_config != ''){ ?>data-cafe-config='<?php echo esc_attr($cafe_json_config); ?>'<?php } ?>>
        <div class="cafe-row">
            <?php
            if ($settings['style'] == 'default'||$settings['style'] == 'style-2') {
                
                foreach ($settings['content'] as $content):
                    ?>
                    <article class="cafe-testimonial-item cafe-col <?php echo esc_attr('cafe-testimonial-' . $content['_id']) ?>">
                        <div class="cafe-wrap-content">
                            <?php echo $quote; ?>
                            <div class="cafe-testimonial-content">
                                <?php echo $content['testimonial_content'];
                                if ($settings['show_star']=='true') {
                                    ?>
                                    <div class="cafe-testimonial-rate">
                                        <span class="cafe-rate-star <?php echo esc_attr($content['star']['size']) ?>-stars"></span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="cafe-wrap-testimonial-info">
                                <?php
                                $ava = $content['author_avatar'];
                                if ($ava['url'] != '' && $settings['show_avatar'] == 'true') {
                                    ?>
                                    <div class="cafe-wrap-avatar">
                                        <img src="<?php echo esc_url($ava['url']); ?>"
                                             alt="<?php $content['author']; ?>" class="cafe-testimonial-avatar"/>
                                    </div>
                                <?php } ?>
                                <div class="cafe-wrap-author-info">
                                    <?php
                                    if ($content['author'] != '') { ?>
                                        <h4 class="cafe-testimonial-author"><?php
                                            echo esc_html($content['author']); ?>
                                        </h4>
                                    <?php } ?>
                                    <?php
                                    if ($content['author_des'] != '' && $settings['show_des'] == 'true') { ?>
                                        <div class="cafe-testimonial-des"><?php
                                            echo esc_html($content['author_des']); ?>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php
                endforeach;
            }
            if ($settings['style'] == 'style-1') {
                foreach ($settings['content'] as $content):
                    ?>
                    <article
                            class="cafe-testimonial-item cafe-col <?php echo esc_attr('cafe-testimonial-' . $content['_id']) ?>">
                        <div class="cafe-wrap-content">
                            <div class="cafe-testimonial-content">
                                <?php echo $content['testimonial_content'];
                                if ($settings['show_star']=='true') {
                                    ?>
                                    <div class="cafe-testimonial-rate">
                                        <span class="cafe-rate-star <?php echo esc_attr($content['star']['size']) ?>-stars"></span>
                                    </div>
                                    <?php
                                }
                                echo $quote;
                                ?>
                            </div>
                            <div class="cafe-wrap-testimonial-info">
                                <?php
                                $ava = $content['author_avatar'];
                                if ($ava['url'] != '' && $settings['show_avatar'] == 'true') {
                                    ?>
                                    <div class="cafe-wrap-avatar">
                                        <img src="<?php echo esc_url($ava['url']); ?>"
                                             alt="<?php $content['author']; ?>" class="cafe-testimonial-avatar"/>
                                    </div>
                                <?php } ?>
                                <div class="cafe-wrap-author-info">
                                    <?php
                                    if ($content['author'] != '') { ?>
                                        <h4 class="cafe-testimonial-author"><?php
                                            echo esc_html($content['author']); ?>
                                        </h4>
                                    <?php } ?>
                                    <?php
                                    if ($content['author_des'] != '' && $settings['show_des'] == 'true') { ?>
                                        <div class="cafe-testimonial-des"><?php
                                            echo esc_html($content['author_des']); ?>
                                        </div>
                                        <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php
                endforeach;
            }
            if ($settings['style'] == 'style-3') {
                foreach ($settings['content'] as $content):
                    ?>
                    <article class="cafe-testimonial-item cafe-col <?php echo esc_attr('cafe-testimonial-' . $content['_id']) ?>">
                        <div class="cafe-wrap-content">
                            <div class="cafe-wrap-testimonial-info">
                                <?php
                                $ava = $content['author_avatar'];
                                if ($ava['url'] != '' && $settings['show_avatar'] == 'true') {
                                    ?>
                                    <div class="cafe-wrap-avatar">
                                        <img src="<?php echo esc_url($ava['url']); ?>"
                                             alt="<?php $content['author']; ?>" class="cafe-testimonial-avatar"/>
                                    </div>
                                <?php } ?>
                                <div class="cafe-wrap-author-info">
                                    <?php
                                    if ($content['author'] != '') { ?>
                                        <h4 class="cafe-testimonial-author"><?php
                                            echo esc_html($content['author']); ?>
                                        </h4>
                                    <?php } ?>
                                    <?php
                                    if ($content['author_des'] != '' && $settings['show_des'] == 'true') { ?>
                                        <div class="cafe-testimonial-des"><?php
                                            echo esc_html($content['author_des']); ?>
                                        </div>
                                        <?php
                                    } ?>
                                    <?php if ($settings['show_star']=='true') { ?>
                                        <div class="cafe-testimonial-rate">
                                            <span class="cafe-rate-star <?php echo esc_attr($content['star']['size']) ?>-stars"></span>
                                        </div>
                                    <?php
                                    }
                                    echo $quote; ?>
                                </div>
                            </div>
                            <div class="cafe-testimonial-content">
                                <?php echo $content['testimonial_content']; ?>
                            </div>
                            
                        </div>
                    </article>
                <?php
                endforeach;
            }
            ?>
        </div>
    </div>
<?php
endif;