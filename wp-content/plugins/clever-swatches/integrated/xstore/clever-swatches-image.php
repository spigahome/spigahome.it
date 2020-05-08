<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Use for display image swatch.
 *
 * @version  2.0.0
 * @package  clever-swatches/templates
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

$general_settings = get_option('zoo-cw-settings', true);
$is_gallery_enabled = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
} else $product_id = get_the_ID();

if (isset ($variation_id)) {
    if ($is_gallery_enabled) {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
    } else {
        $attachment_ids = [];
    }
} else {
    global $post, $product;

    $default_active = [];
    $default_attributes = $product->get_default_attributes();
    $variation_id = 0;
    if (count($default_attributes) && $is_gallery_enabled) {
        foreach ($default_attributes as $key => $value) {
            $default_active['attribute_' . $key] = $value;
        }
        $data_store = WC_Data_Store::load('product');
        $variation_id = $data_store->find_matching_product_variation($product, $default_active);
    }

    if ($variation_id == 0) {
        $attachment_ids = $product->get_gallery_image_ids();
        $variation_id=$product_id;
    } else {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
    }
}

if ($product->is_type('variable')) {
?>
    <div class="swiper-entry swipers-couple-wrapper images-wrapper cw-wrap-images">
    <?php

    global $post, $etheme_global, $is_IE, $main_slider_on;
    $main_attachment_id = get_post_thumbnail_id($variation_id);

    $has_video = false;

    $gallery_slider = etheme_get_option('thumbs_slider');

    if (etheme_get_custom_field('disable_gallery', $product_id)) {
        $gallery_slider = false;
    }

    $video_attachments = array();
    $videos = etheme_get_attach_video($product_id);
    if (isset($videos[0]) && $videos[0] != '') {
        $video_attachments = get_posts(array(
            'post_type' => 'attachment',
            'include' => $videos[0]
        ));
    }

    if (count($video_attachments) > 0 || etheme_get_external_video($product_id) != '') {
        $has_video = true;
    }

    $main_slider_on = (count($attachment_ids) > 0 || $has_video);

    $class = '';
    if ($main_slider_on) {
        $class .= ' main-slider-on';
    }

    $product_photoswipe = etheme_get_option('product_photoswipe');

    if ($product_photoswipe) {
        $class .= ' photoswipe-on';
    } else {
        $class .= ' photoswipe-off';
    }


    $class .= ($gallery_slider) ? ' gallery-slider-on' : ' gallery-slider-off';

    if ($is_IE) {
        $class .= ' ie';
    }

    $et_zoom = etheme_get_option('product_zoom');
    $et_zoom_class = 'woocommerce-product-gallery__image';

    if ($et_zoom) {
        $class .= ' zoom-on';
    }

    if (!$gallery_slider) {
        $wrapper_classes = array();
    }

    $swiper_container = '';
    $swiper_wrapper = '';
    if ($gallery_slider && count($attachment_ids) > 0) {
        $swiper_container = 'swiper-container';
        $swiper_wrapper = 'swiper-wrapper';
    }
    ?>
    <div class="<?php echo esc_attr($swiper_container); ?> images<?php echo esc_attr($class); ?> swiper-control-top"
         data-space="10">
        <div class="<?php echo esc_attr($swiper_wrapper); ?> main-images">
            <?php if (has_post_thumbnail($variation_id)) {

                $index = 1;
                $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
                $post_thumbnail_id = get_post_thumbnail_id($variation_id);
                $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, 'full');
                $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';


                // **********************************************************************************************
                // ! Main product image
                // **********************************************************************************************

                $attributes = array(
                    'title' => get_post_field('post_title', $post_thumbnail_id),
                    'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
                    'data-src' => $full_size_image[0],
                    'data-large_image' => $full_size_image[0],
                    'data-large_image_width' => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                );

                $gallery_slider = ($gallery_slider) ? 'swiper-slide' : '';
                $html = '<div class="' . $gallery_slider . ' images woocommerce-product-gallery woocommerce-product-gallery__wrapper"><div data-thumb="' . get_the_post_thumbnail_url($variation_id, 'shop_thumbnail') . '" class="' . $et_zoom_class . '"><a class="woocommerce-main-image pswp-main-image zoom" href="' . esc_url($full_size_image[0]) . '" data-width="' . esc_attr($full_size_image[1]) . '" data-height="' . esc_attr($full_size_image[2]) . '">';
                $html .= get_the_post_thumbnail($variation_id, 'shop_single', $attributes);
                $html .= '</a></div></div>';

                echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id($variation_id));

                if ($main_slider_on) {

                    if (count($attachment_ids) > 0) {
                        foreach ($attachment_ids as $attachment_id) {

                            $full_size_image = wp_get_attachment_image_src($attachment_id, 'full');

                            $attributes = array(
                                'title' => esc_attr(get_the_title($attachment_id)),
                                'data-src' => $full_size_image[0],
                                'data-large_image' => $full_size_image[0],
                                'data-large_image_width' => $full_size_image[1],
                                'data-large_image_height' => $full_size_image[2],
                            );

                            $html = '<div class="' . $gallery_slider . ' images woocommerce-product-gallery woocommerce-product-gallery__wrapper"><div data-thumb="' . get_the_post_thumbnail_url($attachment_id, 'shop_thumbnail') . '" class="' . $et_zoom_class . '"><a href="' . esc_url($full_size_image[0]) . '"  data-large="' . esc_url($full_size_image[0]) . '" data-width="' . esc_attr($full_size_image[1]) . '"  data-height="' . esc_attr($full_size_image[2]) . '" data-index="' . $index . '" itemprop="image" class="woocommerce-main-image zoom" >';


                            $html .= wp_get_attachment_image($attachment_id, apply_filters('single_product_large_thumbnail_size', 'shop_single'), false, $attributes);
                            $html .= '</a></div></div>';

                            echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id($attachment_id));

                            $index++;

                        }
                    }
                }

            } else {
                echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__('Placeholder', 'xstore')), $variation_id);
            }
            ?>
        </div>
        <?php if (count($attachment_ids) > 1) { ?>
            <div class="swiper-pagination"></div>
        <?php } ?>

        <?php if ($product_photoswipe) { ?>
            <a href="#" class="zoom-images-button" data-index="0"><?php esc_html_e('Zoom images', 'xstore'); ?></a>
        <?php } ?>

        <?php if (etheme_get_external_video($product_id)) { ?>
            <a href="#product-video-popup" class="open-video-popup"><?php esc_html_e('Open Video', 'xstore'); ?></a>
            <div id="product-video-popup" class="product-video-popup mfp-hide">
                <?php echo etheme_get_external_video($product_id); ?>
            </div>
        <?php } ?>

        <?php etheme_360_view_block(); ?>

        <?php if (count($video_attachments) > 0) { ?>
            <a href="#product-video-popup" class="open-video-popup"><?php esc_html_e('Open Video', 'xstore'); ?></a>
            <div id="product-video-popup" class="product-video-popup mfp-hide">
                <video controls="controls">
                    <?php foreach ($video_attachments as $video): ?>
                        <?php $video_ogg = $video_mp4 = $video_webm = false; ?>
                        <?php if ($video->post_mime_type == 'video/mp4' && !$video_mp4): $video_mp4 = true; ?>
                            <source src="<?php echo $video->guid; ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                        <?php endif; ?>
                        <?php if ($video->post_mime_type == 'video/webm' && !$video_webm): $video_webm = true; ?>
                            <source src="<?php echo $video->guid; ?>" type='video/webm; codecs="vp8, vorbis"'>
                        <?php endif; ?>
                        <?php if ($video->post_mime_type == 'video/ogg' && !$video_ogg): $video_ogg = true; ?>
                            <source src="<?php echo $video->guid; ?>" type='video/ogg; codecs="theora, vorbis"'>
                            <?php esc_html_e('Video is not supporting by your browser', 'xstore'); ?>
                            <a href="<?php echo $video->guid; ?>"><?php esc_html_e('Download Video', 'xstore'); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </video>
            </div>
        <?php } ?>

    </div>
    <?php
    if ($gallery_slider && count($attachment_ids) > 0) {
        echo '<div class="swiper-custom-left"></div>
              <div class="swiper-custom-right"></div>
        ';
    } ?>

    <div class="empty-space col-xs-b15 col-sm-b30"></div>
    <?php
    if ($gallery_slider) {
        global  $main_slider_on;
        $main_attachment_id = get_post_thumbnail_id($variation_id);
        $zoom_plugin = etheme_is_zoom_activated();

        $gallery_slider = etheme_get_option('thumbs_slider');

        if (etheme_get_custom_field('disable_gallery', $product_id)) {
            $gallery_slider = false;
        }
        $thums_size = apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail');

        $ul_class = 'swiper-wrapper right thumbnails-list';

        if ($zoom_plugin) {
            $ul_class .= ' yith_magnifier_gallery';
        }

        if (empty($attachment_ids)) {
            $attachment_ids = $product->get_gallery_image_ids();
        }

        if ($attachment_ids) {
            $loop = 0;
            $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
            ?>
            <div class="swiper-container swiper-control-bottom thumbnails <?php echo 'columns-' . $columns; ?> <?php echo $gallery_slider ? 'slider' : 'noslider' ?>"
                 data-breakpoints="1" data-xs-slides="3" data-sm-slides="3" data-md-slides="4" data-lt-slides="4"
                 data-slides-per-view="4" data-clickedslide="1" data-space="10">
                <?php etheme_loader(); ?>
                <ul class="<?php echo esc_attr($ul_class); ?>">
                    <?php

                    if (count($attachment_ids) > 0) {

                        $classes = array('zoom');

                        $image = wp_get_attachment_image($main_attachment_id, $thums_size);
                        $image_class = esc_attr(implode(' ', $classes));
                        $image_title = esc_attr(get_the_title($main_attachment_id));

                        list($thumbnail_url, $thumbnail_width, $thumbnail_height) = wp_get_attachment_image_src($main_attachment_id, "shop_single");
                        list($magnifier_url, $magnifier_width, $magnifier_height) = wp_get_attachment_image_src($main_attachment_id, "shop_magnifier");
                        echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<li class="swiper-slide thumbnail-item %s"><a href="%s" class="%s" title="%s" data-small="%s">%s</a></li>', $image_class, $magnifier_url, $image_class, $image_title, $thumbnail_url, $image), $variation_id, $variation_id, $image_class);

                    }

                    foreach ($attachment_ids as $attachment_id) {

                        $classes = array('zoom');

                        $image_link = wp_get_attachment_url($attachment_id);

                        if (!$image_link)
                            continue;

                        $image = wp_get_attachment_image($attachment_id, $thums_size);
                        $image_class = esc_attr(implode(' ', $classes));
                        $image_title = esc_attr(get_the_title($attachment_id));
                        $image_link = wp_get_attachment_image_src($attachment_id, 'full');

                        list($thumbnail_url, $thumbnail_width, $thumbnail_height) = wp_get_attachment_image_src($attachment_id, "shop_single");
                        list($magnifier_url, $magnifier_width, $magnifier_height) = wp_get_attachment_image_src($attachment_id, "shop_magnifier");

                        echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<li class="swiper-slide thumbnail-item %s"><a href="%s" data-large="%s" data-width="%s" data-height="%s" class="pswp-additional %s" title="%s" data-small="%s">%s</a></li>', $image_class, $magnifier_url, $image_link[0], $image_link[1], $image_link[2], $image_class, $image_title, $thumbnail_url, $image), $attachment_id, $variation_id, $image_class);
                        $loop++;
                    }
                    ?>
                </ul>
                <div class="swiper-custom-left thumbnails-bottom"></div>
                <div class="swiper-custom-right thumbnails-bottom"></div>
            </div>
            <?php
        }
    }
    ?>
    </div>
        <?php
}else {
    wc_get_template_part('single-product/product', 'image');
}
