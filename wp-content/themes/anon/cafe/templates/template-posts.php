<?php
/**
 * View template for Posts widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */
global $wp_query;

$args = array(
    'post_type' => 'post',
    'post_status' => array('publish'),
    'posts_per_page' => ($settings['posts_per_page'] > 0) ? $settings['posts_per_page'] : get_option('posts_per_page')
);
if ($settings['ignore_sticky_posts']=='true') {
    $args['ignore_sticky_posts']=1;
}
if ($settings['cat']) {
    $catid = array();
    foreach ($settings['cat'] as $catslug) {
        $catid[] .= get_category_by_slug($catslug)->term_id;
    }
    if(!empty($catid)) {
        $args['category__in'] = $catid;
    }
}
if ($settings['pagination'] != 'none') {
    $args['paged'] = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if (is_front_page() && !empty($wp_query->query['paged'])) {
        $args['paged'] = $wp_query->query['paged'];
    }
}

$the_query = new WP_Query($args);
if ($the_query->have_posts()):
    $layout = $settings['layout'];
    $css_class = 'cafe-posts';
    $css_class .= ' ' . $layout . '-layout';
    $css_class_item[] = 'cafe-post-item';
    $css_class_item[] = 'post-loop-item';
    $css_class_item[] = $layout . '-layout-item';
    $css_class_item[] = 'cafe-col';
    $cafe_json_config = '';
    if($settings['disable_border']=='true'){
        $css_class .= ' no-border';
    }
    if ($layout == 'grid') {
        $css_class .= ' cafe-row cafe-grid-lg-' . $settings['col'] . '-cols cafe-grid-md-' . $settings['col_tablet'] . '-cols cafe-grid-' . $settings['col_mobile'] . '-cols';
    } elseif ($layout == 'carousel') {
        $css_class .= ' cafe-carousel grid-layout';

        $settings['autoplay'] ? $settings['autoplay'] : $settings['autoplay'] = 'false';
        $settings['autoplay_tablet'] ? $settings['autoplay_tablet'] : $settings['autoplay_tablet'] = 'false';
        $settings['autoplay_mobile'] ? $settings['autoplay_mobile'] : $settings['autoplay_mobile'] = 'false';

        $settings['show_pag'] ? $settings['show_pag'] : $settings['show_pag'] = 'false';
        $settings['show_pag_tablet'] ? $settings['show_pag_tablet'] : $settings['show_pag_tablet'] = 'false';
        $settings['show_pag_mobile'] ? $settings['show_pag_mobile'] : $settings['show_pag_mobile'] = 'false';

        $settings['show_nav'] ? $settings['show_nav'] : $settings['show_nav'] = 'false';
        $settings['show_nav_tablet'] ? $settings['show_nav_tablet'] : $settings['show_nav_tablet'] = 'false';
        $settings['show_nav_mobile'] ? $settings['show_nav_mobile'] : $settings['show_nav_mobile'] = 'false';

        $settings['speed'] ? $settings['speed'] : $settings['speed'] = 3000;

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
        "show_nav_mobile": ' . $settings['show_nav_mobile'] . '
        }';
    }
    $style = '';
    if (isset($settings['style'])) {
        $style = $settings['style'];
        if ($style == 'first-large' && $layout == 'carousel') {
            $style = 'default';
        }
        $css_class .= ' ' . $style;
    }
    $css_class .= ' ' . $settings['css_class'];
    ?>
    <div class="<?php echo esc_attr($css_class) ?>"
         <?php if ($cafe_json_config != '') {
         ?>data-cafe-config='<?php echo esc_attr($cafe_json_config); ?>'<?php
    } ?>>
        <?php
        $i = 1;
        $total_post = count($the_query->posts);
        while ($the_query->have_posts()):
            $the_query->the_post();
            if ($style == 'first-large' && $layout == 'grid'):
                if ($i == 2) {
                    ?>
                    <div class="wrap-right-col">
                    <?php
                }
                ?>
                <article id="post-<?php the_ID(); ?>" <?php echo post_class($css_class_item) ?>>
                    <?php
                    if (has_post_thumbnail() && $i == 1) {
                        ?>
                        <div class="wrap-media">
                            <?php
                            if (is_sticky()) {
                                ?>
                                <span class="sticky-post-label"><i
                                            class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                <?php
                            } ?>
                            <a href="<?php echo esc_url(get_permalink()); ?>"
                               title="<?php the_title_attribute() ?>">
                                <?php
                                the_post_thumbnail($settings['image_size']); ?>
                            </a>
                        </div>
                        <?php
                    } else {
                        if (is_sticky()) {
                            ?>
                            <span class="sticky-post-label"><i
                                        class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                            <?php
                        }
                    }
                    ?>
                    <div class="wrap-post-item-content">
                        <?php
                        if ($settings['show_cat_post'] == 'true') {
                            ?>
                            <div class="list-cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ' ', ''); ?></div>
                            <?php
                        }
                        the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                        ?>
                        <ul class="post-info">
                            <?php
                            if ($settings['show_date_post'] == 'true') {
                                ?>
                                <li class="post-date"><?php esc_html_e('Posted ', 'anon');
                                    echo esc_html(get_the_date()); ?></li>
                                <?php
                            }
                            if ($settings['show_author_post'] == 'true') {
                                ?>
                                <li class="author-post"><?php esc_html_e('by', 'anon'); ?>
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                                       title="<?php echo esc_attr(get_the_author()) ?>">
                                        <?php echo esc_html(get_the_author()) ?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                        <?php
                        if ($settings['output_type'] != 'none') {
                            ?>
                            <div class="entry-content">
                                <?php
                                if ($settings['output_type'] == 'excerpt') {
                                    echo cafe_get_excerpt($settings['excerpt_length']);
                                } else {
                                    the_content();
                                } ?>
                            </div>
                            <?php
                        }
                        if ($settings['show_read_more'] == 'true') {
                            ?>
                            <a href="<?php the_permalink(); ?>"
                               class="readmore"><?php

                                echo esc_html__('Read more', 'anon'); ?></a>
                            <?php
                        }
                        ?>
                    </div>
                </article>
                <?php if ($i == $total_post) {
                ?>
                </div>
                <?php
            }
                $i++;
            else:
                ?>
                <article id="post-<?php the_ID(); ?>" <?php echo post_class($css_class_item) ?>>
                    <?php
                    if ($layout == 'list'):
                        if (has_post_thumbnail()) {
                            ?>
                            <div class="wrap-media">
                                <?php
                                if (is_sticky()) {
                                    ?>
                                    <span class="sticky-post-label"><i
                                                class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                    <?php
                                } ?>
                                <a href="<?php echo esc_url(get_permalink()); ?>"
                                   title="<?php the_title_attribute() ?>">
                                    <?php
                                    the_post_thumbnail($settings['image_size']); ?>
                                </a>
                            </div>
                            <?php
                        } else {
                            if (is_sticky()) {
                                ?>
                                <span class="sticky-post-label"><i
                                            class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                <?php
                            }
                        } ?>
                        <div class="wrap-post-item-content">
                            <?php
                            the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                            ?>
                            <ul class="post-info">
                                <?php
                                if ($settings['show_author_post'] == 'true') {
                                    ?>
                                    <li class="author-post"><i class="cs-font clever-icon-user-2"></i>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                                           title="<?php echo esc_attr(get_the_author()) ?>">
                                            <?php echo esc_html(get_the_author()) ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                if ($settings['show_date_post'] == 'true') {
                                    ?>
                                    <li class="post-date"><i
                                                class="cs-font clever-icon-clock-4"></i> <?php echo esc_html(get_the_date()); ?>
                                    </li>
                                    <?php
                                }
                                if ($settings['show_cat_post'] == 'true') {
                                    ?>
                                    <li class="list-cat"><i
                                                class="cs-font clever-icon-document"></i><?php echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                            if ($settings['output_type'] != 'none') {
                                ?>
                                <div class="entry-content">
                                    <?php
                                    if ($settings['output_type'] == 'excerpt') {
                                        echo cafe_get_excerpt($settings['excerpt_length']);
                                    } else {
                                        the_content();
                                    } ?>
                                </div>
                                <?php
                            }
                            if ($settings['show_read_more'] == 'true') {
                                ?>
                                <a href="<?php the_permalink(); ?>"
                                   class="readmore"><?php echo esc_html__('Read more', 'anon'); ?></a>
                                <?php
                            }
                            ?>
                        </div>
                    <?php
                    else:
                        //IMG Left IMG Style
                        if ($style == 'img-left'):
                            if (has_post_thumbnail()) {
                                ?>
                                <div class="wrap-media">
                                    <?php
                                    if (is_sticky()) {
                                        ?>
                                        <span class="sticky-post-label"><i
                                                    class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                        <?php
                                    } ?>
                                    <a href="<?php echo esc_url(get_permalink()); ?>"
                                       title="<?php the_title_attribute() ?>">
                                        <?php
                                        the_post_thumbnail($settings['image_size']); ?>
                                    </a>
                                </div>
                                <?php
                            } ?>
                            <div class="wrap-post-item-content">
                                <?php
                                if (is_sticky() && !has_post_thumbnail()) {
                                    ?>
                                    <span class="sticky-post-label"><i
                                                class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                    <?php
                                }

                                if ($settings['show_date_post'] == 'true') {
                                    ?>
                                    <div class="post-date">
                                        <span class="date"><?php echo esc_html(get_the_date('d')); ?></span>
                                        <span class="month">/<?php echo esc_html(get_the_date('M')); ?></span>
                                    </div>
                                    <?php
                                }
                                the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                                ?>
                                <ul class="post-info">
                                    <?php
                                    if ($settings['show_author_post'] == 'true') {
                                        ?>
                                        <li class="author-post">
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                                               title="<?php echo esc_attr(get_the_author()) ?>">
                                                <?php echo esc_html(get_the_author()) ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    if ($settings['show_cat_post'] == 'true') {
                                        ?>
                                        <li class="list-cat">
                                            <?php echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                                if ($settings['output_type'] != 'none') {
                                    ?>
                                    <div class="entry-content">
                                        <?php
                                        if ($settings['output_type'] == 'excerpt') {
                                            echo cafe_get_excerpt($settings['excerpt_length']);
                                        } else {
                                            the_content();
                                        } ?>
                                    </div>
                                    <?php
                                }
                                if ($settings['show_read_more'] == 'true') {
                                    ?>
                                    <a href="<?php the_permalink(); ?>"
                                       class="readmore"><?php echo esc_html__('Read more', 'anon'); ?> &#187;</a>
                                    <?php
                                }
                                ?>
                            </div>
                        <?php
                        //End IMG Left IMG Style
                        else:
                            if ($style == 'boxed') {
                                ?>
                                <div class="inner-post-content">
                                <?php
                            }
                            if (has_post_thumbnail()) {
                                ?>
                                <div class="wrap-media">
                                    <?php
                                    if (is_sticky()) {
                                        ?>
                                        <span class="sticky-post-label"><i
                                                    class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                        <?php
                                    } ?>
                                    <a href="<?php echo esc_url(get_permalink()); ?>"
                                       title="<?php the_title_attribute() ?>">
                                        <?php
                                        the_post_thumbnail($settings['image_size']); ?>
                                    </a>
                                </div>
                                <?php
                            } else {
                                if (is_sticky()) {
                                    ?>
                                    <span class="sticky-post-label"><i
                                                class="cs-font clever-icon-light"></i> <?php echo esc_html__('Featured', 'anon') ?></span>
                                    <?php
                                }
                            }
                            ?>
                            <div class="wrap-post-item-content">
                                <?php
                                if ($settings['show_cat_post'] == 'true' && $style != 'basic') {
                                    ?>
                                    <div class="list-cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ' ', ''); ?></div>
                                    <?php
                                }
                                the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h3>');
                                if ($settings['output_type'] != 'none') {
                                    ?>
                                    <div class="entry-content">
                                        <?php
                                        if ($settings['output_type'] == 'excerpt') {
                                            echo cafe_get_excerpt($settings['excerpt_length']);
                                        } else {
                                            the_content();
                                        } ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <ul class="post-info">
                                    <?php
                                    if ($settings['show_date_post'] == 'true') {
                                        ?>
                                    <li class="post-date"><?php esc_html_e('Posted ', 'anon');
                                        echo esc_html(get_the_date()); ?></li>
                                    <?php
                                    }
                                    if ($settings['show_author_post'] == 'true') {
                                    ?>
                                    <li class="author-post"><?php esc_html_e('by', 'anon'); ?>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                                           title="<?php echo esc_attr(get_the_author()) ?>">
                                            <?php echo esc_html(get_the_author()) ?>
                                        </a>
                                    </li>
                                    <?php
                                    }
                                    if ($settings['show_cat_post'] == 'true' && $style == 'basic') {
                                        ?>
                                        <li class="list-cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ' ', ''); ?></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <?php
                                if ($settings['show_read_more'] == 'true') {
                                    ?>
                                    <a href="<?php the_permalink(); ?>"
                                       class="readmore"><?php
                                        if ($style == 'image-shadow') {
                                            ?>
                                            <i class="cs-font clever-icon-arrow-bold"></i>
                                            <?php
                                        } else {
                                            echo esc_html__('Read more', 'anon');
                                        } ?></a>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                            if ($style == 'boxed') {
                                ?>
                                </div>
                                <?php
                            }
                        endif;
                    endif;
                    ?>
                </article>
            <?php
            endif;
        endwhile;
        ?>
    </div>
    <?php
    if ($settings['pagination'] != 'none' && $layout != 'carousel') :
        cafe_pagination(3, $the_query, '');
    endif;
endif;
wp_reset_postdata();
