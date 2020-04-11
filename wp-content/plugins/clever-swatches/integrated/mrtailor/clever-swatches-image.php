<?php
/**
 * Use for display image swatch.
 *
 * @version  2.0.0
 * @package  clever-swatches/templates
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
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
        $variation_id = $product_id;
    } else {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
    }
}

if ($product->is_type('variable')):
    global $mr_tailor_theme_options;

    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
    } else $product_id = $variation_id;

    $modal_class = "";
    $zoom_class = "";
    $plus_button = "";
    if (get_option('woocommerce_enable_lightbox') == "yes") {
        $modal_class = "fresco";
        $zoom_class = "";
        $plus_button = '<span class="product_image_zoom_button show-for-medium-up"><i class="fa fa-plus"></i></span>';
    }

    if ((isset($mr_tailor_theme_options['product_gallery_zoom'])) && ($mr_tailor_theme_options['product_gallery_zoom'] == "1")) {
        $modal_class = "zoom";
        $zoom_class = "easyzoom el_zoom";
        $plus_button = "";
    }
    $image_title = esc_attr(get_the_title(get_post_thumbnail_id($variation_id)));
    $image_src = wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'shop_thumbnail');
    $image_data_src = wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'shop_single');
    $image_data_src_original = wp_get_attachment_image_src(get_post_thumbnail_id($variation_id), 'full');
    $image_link = wp_get_attachment_url(get_post_thumbnail_id($variation_id));
    $image = get_the_post_thumbnail($variation_id, apply_filters('single_product_large_thumbnail_size', 'shop_single', array('class' => 'wp-post-image')));
    $image_original = get_the_post_thumbnail($variation_id, 'full');
    $attachment_count = count($attachment_ids);
    if (!isset($_POST['product_id'])) {
        echo apply_filters('woocommerce_single_product_image_html', sprintf('<div class="featured_img_temp">%s</div>', $image), $variation_id);
    }
    ?>
    <div class="images woocommerce-product-gallery__wrapper">
    <?php if (has_post_thumbnail($variation_id)) { ?>
    <div class="product_images">
        <div id="product-images-carousel" class="owl-carousel woocommerce-product-gallery__wrapper">
            <div class="<?php echo $zoom_class; ?> woocommerce-product-gallery__image">
                <a data-fresco-group="product-gallery" data-fresco-options="fit: 'width'"
                   class="<?php echo $modal_class; ?>" href="<?php echo esc_url($image_link); ?>">
                    <?php echo $image; ?>
                    <?php echo $plus_button; ?>
                </a>
            </div>
            <?php
            if ($attachment_ids) {
                foreach ($attachment_ids as $attachment_id) {
                    $image_link = wp_get_attachment_url($attachment_id);
                    if (!$image_link) continue;
                    $image_title = esc_attr(get_the_title($attachment_id));
                    $image_src = wp_get_attachment_image_src($attachment_id, 'shop_single_small_thumbnail');
                    $image_data_src = wp_get_attachment_image_src($attachment_id, 'shop_single');
                    $image_data_src_original = wp_get_attachment_image_src($attachment_id, 'full');
                    $image_link = wp_get_attachment_url($attachment_id);
                    $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_large_thumbnail_size', 'shop_single'));
                    ?>
                    <div class="<?php echo $zoom_class; ?>">
                        <a data-fresco-group="product-gallery" data-fresco-options="fit: 'width'"
                           class="<?php echo $modal_class; ?>" href="<?php echo esc_url($image_link); ?>">
                            <img src="<?php echo esc_url($image_src[0]); ?>"
                                 data-src="<?php echo esc_url($image_data_src[0]); ?>" class="lazyOwl wp-post-image"
                                 alt="<?php echo esc_html($image_title); ?>">
                            <?php echo $plus_button; ?>
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
} else {
    echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce')), $variation_id);
}
    ?>
    <?php
    if (isset($_POST['product_id'])) {
        $values = get_post_custom($product_id);
        $page_product_youtube = isset($values['page_product_youtube']) ? esc_attr($values['page_product_youtube'][0]) : '';
        if (has_post_thumbnail($variation_id)) {
            if ($mr_tailor_theme_options['products_layout'] == 1) {
                echo "<div class='product_thumbnails horizontal_product_thumbnails'>";
            } else {
                echo "<div class='product_thumbnails'>";
            }
            $image_title = esc_attr(get_the_title(get_post_thumbnail_id($variation_id)));
            $image_link = wp_get_attachment_url(get_post_thumbnail_id($variation_id));
            $image = get_the_post_thumbnail($variation_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), array(
                'title' => $image_title
            ));
            echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<div class="carousel-cell is-nav-selected">%s</div>', $image), $variation_id);
            //Thumbs
            if ($attachment_ids) {
                foreach ($attachment_ids as $attachment_id) {
                    $image_link = wp_get_attachment_url($attachment_id, 'thumbnail');
                    if (!$image_link)
                        continue;
                    $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'));
                    $image_title = esc_attr(get_the_title($attachment_id));
                    echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<div class="carousel-cell">%s</div>', $image), $attachment_id, $variation_id);
                }
                if ($page_product_youtube) {
                    echo '<div class="carousel-cell youtube"><i class="spk-icon-video-player"></i></div>';
                }
                ?>
                </div>
                <?php
            }
        }
    }
    ?>
    </div>
    <?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>