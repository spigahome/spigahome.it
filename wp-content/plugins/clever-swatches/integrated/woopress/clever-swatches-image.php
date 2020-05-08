<?php
/**
 * CleverSwatches image
 * @description: use for display image swatch
 **/


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

if ($product->is_type('variable')):
    if (!defined('ABSPATH')) exit; // Exit if accessed directly

    global $post, $woocommerce, $etheme_global, $product;

    $zoom = etheme_get_option('zoom_effect');
    $lightbox = etheme_get_option('gallery_lightbox');
    $slider = etheme_get_option('images_slider');

    if (!empty($etheme_global['zoom'])) {
        $zoom = $etheme_global['zoom'];
    }

    $has_video = false;

    $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);

    $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
    $wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . $placeholder,
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    ));

    ?>
    <div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
         data-columns="<?php echo esc_attr($columns); ?>">

        <?php

        $data_rel = '';
        $image_title = get_post_field('post_title', $variation_id);
        $post_thumbnail_id = get_post_thumbnail_id($variation_id);
        $image_link = wp_get_attachment_url($post_thumbnail_id);
        $image_src = wp_get_attachment_image_src($post_thumbnail_id, apply_filters('single_product_large_thumbnail_size', 'shop_single'));
        $image_srcset = wp_get_attachment_image_srcset($post_thumbnail_id, apply_filters('single_product_large_thumbnail_size', 'shop_single'));
        $image = get_the_post_thumbnail($variation_id, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
            'title' => $image_title
        ));

        $attachment_count = count($attachment_ids);

        if ($attachment_count > 0) {
            $gallery = '[product-gallery]';
        } else {
            $gallery = '';
        }

        if ($lightbox) $data_rel = 'data-rel="gallery' . $gallery . '"';
        $thumb_html = '';
        ?>

        <figure class="woocommerce-product-gallery__wrapper <?php if ($slider): ?>product-images-slider<?php else: ?>product-images-gallery<?php endif ?> main-images images-popups-gallery <?php if ($zoom != 'disable') {
            echo 'zoom-enabled';
        } ?>">
            <?php if (has_post_thumbnail($variation_id)) { ?>
                <div>
                    <?php echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="product-main-image product-image" data-o_href="%s" data-o_srcset="%s" data-thumbnail-src = "%s" data-thumb-src = " " data-o_src="%s" title="%s">%s</a>', $image_link, $image_link, $image_srcset, $image_src[0], $image_src[0], $image_title, $image), $variation_id); ?>
                    <?php if ($lightbox): ?>
                        <a href="<?php echo $image_link; ?>" class="product-lightbox-btn" <?php echo $data_rel; ?>>lightbox</a>
                    <?php endif; ?>
                </div>
                <?php
                $attributes = array('title' => get_post_field('post_title', $variation_id));
                $thumb_html .= '<a href="' . $image_link . '" class="active-thumbnail" >';
                $thumb_html .= get_the_post_thumbnail($variation_id, 'shop_thumbnail', false, $attributes);
                $thumb_html .= '</a>';
            } else { ?>
                <div>
                    <?php echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="product-main-image product-image" data-o_href="%s"><img src="%s" /></a>', wc_placeholder_img_src(), wc_placeholder_img_src(), wc_placeholder_img_src()), $variation_id); ?>
                </div>
            <?php } ?>
            <?php
            $_i = 0;
            if ($attachment_count > 0) {
                foreach ($attachment_ids as $id) {
                    $_i++;
                    ?>
                    <div>
                        <?php

                        $image_title = esc_attr(get_the_title($id));
                        $image_link = wp_get_attachment_url($id);
                        $image = wp_get_attachment_image_src($id, 'shop_single');

                        echo sprintf('<a href="%s" itemprop="image" class="woocommerce-additional-image product-image" title="%s"><img src="%s" class="lazyOwl"/></a>', $image_link, $image_title, $image[0]);

                        if ($lightbox):
                            ?>
                            <a href="<?php echo $image_link; ?>" class="product-lightbox-btn" <?php echo $data_rel; ?>>lightbox</a>
                            <?php
                        endif;
                        ?>
                    </div>
                    <?php
                    $full_size_image = wp_get_attachment_image_src($id, 'full');
                    $thumbnail = wp_get_attachment_image_src($id, 'shop_thumbnail');
                    $attributes = array(
                        'title' => get_post_field('post_title', $id),
                        'data-caption' => get_post_field('post_excerpt', $id),
                        'data-src' => $full_size_image[0],
                        'data-large_image' => $full_size_image[0],
                        'data-large_image_width' => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                    );
                    $thumb_html .= '<a href="' . esc_url($full_size_image[0]) . '">';
                    $thumb_html .= wp_get_attachment_image($id, 'shop_thumbnail', false, $attributes);
                    $thumb_html .= '</a>';
                }

            }
            ?>
        </figure>

        <script type="text/javascript">
            <?php if ($slider): ?>
            jQuery('.main-images').owlCarousel({
                items: 1,
                navigation: true,
                lazyLoad: false,
                rewindNav: false,
                addClassActive: true,
                autoHeight: true,
                itemsCustom: [1600, 1],
                afterMove: function (args) {
                    var owlMain = jQuery(".main-images").data('owlCarousel');
                    var owlThumbs = jQuery(".product-thumbnails").data('owlCarousel');

                    jQuery('.active-thumbnail').removeClass('active-thumbnail')
                    jQuery(".product-thumbnails").find('.owl-item').eq(owlMain.currentItem).addClass('active-thumbnail');
                    if (typeof owlThumbs != 'undefined') {
                        owlThumbs.goTo(owlMain.currentItem - 1);
                    }
                }
            });
            <?php endif ?>
            <?php if ($zoom != 'disable') {
            ?>
            if (jQuery(window).width() > 768) {
                setTimeout(function () {
                    jQuery('.main-images .product-image').swinxyzoom({
                        mode: '<?php echo $zoom ?>',
                        controls: false,
                        size: '100%',
                        dock: {position: 'right'}
                    }); // dock window slippy lens
                }, 300);
            }
            <?php
            } ?>
            jQuery('.main-images a').click(function (e) {
                e.preventDefault();
            });
            <?php if ( $slider ) : ?>
            jQuery(window).load(function () {
                jQuery('.main-images').data('owlCarousel').reinit();
            });
            <?php endif; ?>
        </script>

        <?php if ($slider): ?>
            <div id="product-pager" class="product-thumbnails images-popups-gallery">
                <?php
                echo $thumb_html;
                ?>
            </div>
            <script type="text/javascript">
                jQuery('.product-thumbnails').owlCarousel({
                    items: 3,
                    transitionStyle: "fade",
                    navigation: true,
                    navigationText: ["", ""],
                    addClassActive: true,
                    itemsCustom: [[0, 2], [479, 2], [619, 3], [768, 3], [1200, 3], [1600, 3]],
                });

                jQuery('.product-thumbnails .owl-item').click(function (e) {
                    var owlMain = jQuery(".main-images").data('owlCarousel');
                    var owlThumbs = jQuery(".product-thumbnails").data('owlCarousel');
                    owlMain.goTo(jQuery(e.currentTarget).index());
                });

                jQuery('.product-thumbnails a').click(function (e) {
                    e.preventDefault();
                });

            </script>
        <?php endif ?>

    </div>
    <?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>