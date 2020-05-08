<?php
/**
 * View template for Clever Portfolios widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$clever_layout = !empty($settings['layout']) ? $settings['layout'] : 'masonry';
//Get Data
$args = array(
    'post_type' => 'portfolio',
    'posts_per_page' => !empty($settings['posts_per_page']) ? intval($settings['posts_per_page']) : get_option('posts_per_page')
);
$terms = array();
$catID = array();
if (!empty($settings['cats'])) {
    foreach ($settings['cats'] as $catslug) {
        $cat = get_term_by('slug', $catslug, 'portfolio_category');
        if ($cat) {
            $catID[] .= $cat->term_id;
        }
    }
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'portfolio_category',
            'field' => 'id',
            'terms' => $catID,
        )
    );
    if (count($catID) > 0) {
        foreach ($catID as $id) {
            $terms[] = get_term($id, 'portfolio_category');
        }
    }
} else {
    $terms = get_terms(array(
        'taxonomy' => 'portfolio_category',
        'hide_empty' => true
    ));
}
$args['orderby'] = $settings['order_by'];
$args['order'] = $settings['order'];
$args['paged'] = get_query_var('paged', 1);
$the_query = new WP_Query($args);
//Custom style
$cp_cols = $settings['cols'];
$padding = $settings['gutter'];
$cp_wrap_class = 'clever-portfolio clever-portfolio-shortcode cp-' . $clever_layout . '-layout ' . $settings['style'];
$cp_total = 0;
$cp_rows = 1;
if ($clever_layout == 'carousel') {
    $cp_rows = $settings['rows'];
    $cp_cols = $settings['columns']['size'];
    if ($cp_rows > 1) {
        $cp_items_rows = $cp_rows * $cp_cols;
        $cp_total = count($the_query->posts);
        $cp_wrap_class .= ' cp-multi-row';
    } else {
        $cp_wrap_class .= ' cp-single-row';
    }
}
$cp_wrap_class .= ' column-' . $cp_cols;
$wrapID = 'portfolio-block-' . uniqid();
if ($the_query->have_posts()):
    ?>
<div id="<?php echo esc_attr($wrapID); ?>" class="<?php echo esc_attr($cp_wrap_class); ?>"
     data-config='{"id":"<?php echo esc_attr($wrapID) ?>","columns":"<?php echo esc_attr($cp_rows > 1 ? '1' : $cp_cols) ?>"
     <?php if ($clever_layout == 'masonry') {
         ?>,"layout_mod":"<?php echo esc_attr($settings['layout_mod']) ?>"
     <?php } elseif ($clever_layout == 'grid') {
         ?>,"layout_mod":"fitRows"
     <?php }
     if ($clever_layout == 'carousel') {
         $auto_play = '';
         if ($settings['autoplay'] == 'true') {
             $auto_play = $settings['speed'];
         }
         ?>,"columns_tablet":"<?php echo esc_attr($settings['columns_tablet']['size']) ?>","columns_mobile":"<?php echo esc_attr($settings['columns_mobile']['size']) ?>","carousel_layout":"<?php echo esc_attr($settings['carousel_size']); ?>","scroll":"<?php echo esc_attr($settings['scroll']); ?>","show_pag":"<?php echo esc_attr(isset($settings['show_pag']) ? $settings['show_pag'] : ''); ?>","show_nav":"<?php echo esc_attr(isset($settings['show_nav']) ? $settings['show_nav'] : ''); ?>","autoplay":"<?php echo esc_attr($auto_play); ?>"
     <?php
     }
     ?>
     }'>
    <?php

    //Filter
    if ($settings['show_filter'] == 'true') {
        if (!empty($terms) && !is_wp_error($terms)): ?>
            <div class="wrap-portfolio-filter">
                <div class="cp-mobile-filter">
                    <span><?php echo esc_html__('All', 'cafe-lite') ?></span><i class="fa fa-angle-down"></i></div>
                <ul class="clever-portfolio-filter primary-font col-12">
                    <?php if (count($terms) > 1) : ?>
                        <li class="active" data-id="all"><span><?php echo esc_html__('All', 'cafe-lite') ?></span>
                        </li>
                        <?php foreach ($terms as $term) : ?>
                            <li data-id="<?php echo esc_attr('cp-' . $term->term_id) ?>">
                                <span><?php echo esc_html($term->name); ?></span></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif;
    }//End Filter
    //Content
if ($padding != 0) { ?>
    <div style="margin:0 <?php echo esc_attr('-' . $padding / 2) ?>px"><?php } ?>
    <div class="cp-wrap-block-item">
        <?php
        $i = 0;
        while ($the_query->have_posts()):
        $the_query->the_post();
        //get list catslug
        $catslug = 'clever-portfolio-item ';
        if (isset($settings['show_filter'])) {
            $catslug .= 'all ';
            $termspost = get_the_terms(get_the_ID(), 'portfolio_category');
            if ($termspost && !is_wp_error($termspost)) :
                foreach ($termspost as $term) :
                    $catslug .= 'cp-' . $term->term_id . ' ';
                endforeach;
            endif;
        }
        $clever_meta = clever_portfolio_single_meta();
        $portfolio_url = get_the_permalink();
        if (isset($clever_meta['extend_url'])) {
            if ($clever_meta['extend_url'] != '') {
                $portfolio_url = $clever_meta['extend_url'];
            }
        }
        $link_target="";
        if($settings['open_new_window']=='true'){
            $link_target="_blank";
        }
        if ($clever_layout != 'carousel') {
            ?>
            <article id="portfolio-<?php esc_attr(the_ID()); ?>" <?php echo post_class($catslug); ?>>
                <div class="wrap-portfolio-item">
                    <div class="wrap-portfolio-img"><a href="<?php echo esc_url($portfolio_url); ?>"
                                                       title="<?php the_title(); ?>" target="<?php echo esc_attr($link_target);?>">
                            <?php the_post_thumbnail($settings['img_size']); ?>
                        </a>
                        <?php
                        if ($settings['view_more'] == 'true' && $settings['style'] == 'style-7') { ?>
                            <a href="<?php echo esc_url($portfolio_url); ?>"
                               class="btn-readmore btn"
                               title="<?php the_title(); ?>" target="<?php echo esc_attr($link_target);?>"> <?php echo esc_html($settings['view_more_text']) ?> </a>
                        <?php } ?>
                    </div>
                    <div class="portfolio-info">
                        <h3 class="title title-post"><a href="<?php echo esc_url($portfolio_url); ?>"
                                                        title="<?php the_title(); ?>" target="<?php echo esc_attr($link_target);?>"><?php the_title(); ?></a>
                        </h3>
                        <?php if ($settings['show_date'] == 'true') { ?>
                            <span class="post-date"><?php echo esc_html(get_the_date('M, Y')); ?></span>
                        <?php }
                        if ($settings['show_cat'] == 'true') { ?>
                            <div
                                    class="list-cat body-font"><?php echo get_the_term_list(get_the_ID(), 'portfolio_category', '', '', ''); ?></div>
                        <?php }
                        if ($settings['view_more'] == 'true' && $settings['style'] != 'style-7') { ?>
                            <a href="<?php echo esc_url($portfolio_url); ?>"
                               class="btn-readmore btn <?php if ($settings['style'] == 'style-2') {
                                   echo esc_attr('btn-around');
                               } ?>"
                               title="<?php the_title(); ?>"> <?php echo esc_html($settings['view_more_text']) ?> </a>
                        <?php } ?>
                    </div>
                </div>
            </article>
            <?php
        } else {
        if ($cp_rows > 1 && $i == 0) {
        ?>
    <div class="cp-carousel-row">
    <?php
    }
    $i++;
    ?>
    <article id="portfolio-<?php esc_attr(the_ID()); ?>" <?php echo post_class($catslug);
    if ($settings['carousel_size'] != 'auto') {
        ?>
        style="background: url(<?php echo the_post_thumbnail_url($settings['img_size']); ?>) center center/cover;<?php
        if ($settings['carousel_size'] == 'custom') { ?>
                height:<?php echo esc_attr($settings['carousel_height'])?>px
            <?php
        }
        ?>"
    <?php } ?>>
        <div class="wrap-portfolio-item">
            <?php if ($settings['carousel_size'] == 'auto') { ?>
                <div class="wrap-portfolio-img"><a href="<?php echo esc_url($portfolio_url); ?>"
                                                   title="<?php the_title(); ?>" target="<?php echo esc_attr($link_target);?>">
                        <?php the_post_thumbnail($settings['img_size']); ?>
                    </a>
                </div>
            <?php } ?>
            <div class="portfolio-info">
                <h3 class="title title-post"><a href="<?php echo esc_url($portfolio_url); ?>"
                                                title="<?php the_title(); ?>" target="<?php echo esc_attr($link_target);?>"><?php the_title(); ?></a>
                </h3>
                <?php if ($settings['show_date'] == 'true') { ?>
                    <span class="post-date"><?php echo esc_html(get_the_date('M, Y')); ?></span>
                <?php }
                if ($settings['show_cat'] == 'true') { ?>
                    <div class="list-cat body-font"><?php echo get_the_term_list(get_the_ID(), 'portfolio_category', '', '', ''); ?></div>
                <?php }

                if ($settings['view_more'] == 'true') { ?>
                    <a href="<?php echo esc_url($portfolio_url); ?>"
                       class="btn-readmore btn <?php if ($settings['style'] == 'style-2') {
                           echo esc_attr('btn-around');
                       } ?>"
                       title="<?php the_title(); ?>" target="<?php echo esc_attr($link_target);?>"> <?php echo esc_html($settings['view_more_text']) ?> </a>
                <?php } ?>
            </div>
        </div>
    </article>
    <?php
    if (($cp_rows > 1 && ($i % $cp_items_rows == 0)) || $cp_total == $i) {
    ?>
    </div>
    <?php
    if ($cp_total != $i) {
    ?>
        <div class="cp-carousel-row">
            <?php
            }
            }
            }
            endwhile;
            ?>
        </div>
        <?php if ($padding) echo '</div>';
        //Content
        //Pagination Content
        if ($clever_layout != 'carousel') {
            $pagination_type = $settings['pagination'];
            $isotope = true;
            if ($pagination_type === 'standard' && $clever_layout != 'carousel') :
                echo '<div class="clever-wrap-pagination default">';
                clever_stardard_pagination($the_query, 3, '<i class="cs-font clever-icon-arrow-left-5"></i>', '<i class="cs-font clever-icon-arrow-right-5"></i>');
                echo '</div>';
            elseif ($pagination_type === 'infinite') :
                echo '<div class="clever-wrap-pagination">';
                clever_infinity_scroll_pagination($the_query, '#' . $wrapID, '.clever-portfolio-item', $isotope);
                echo '</div>';
            elseif ($pagination_type === 'loadmore') :
                echo '<div class="clever-wrap-pagination">';
                clever_ajax_load_pagination(basename(__FILE__, ".php"), $settings, $the_query, '', '#' . $wrapID, '.cp-wrap-block-item', $isotope);
                echo '</div>';
            endif;
        }
        //End Pagination Content
        wp_enqueue_style('clever-portfolio');
        ?>
    </div>
<?php
endif;
wp_reset_postdata(); ?>