<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

$general_settings = get_option('zoo-cw-settings', true);
$is_gallery_enabled = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
} else $product_id = get_the_ID();

if (isset ($variation_id)) {
    if ($is_gallery_enabled) {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $product_attach_ids = array_filter(explode(',', $gallery_images_id));
    } else {
        $product_attach_ids = [];
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
        $product_attach_ids = $product->get_gallery_image_ids();
        $variation_id = $product_id;
    } else {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $product_attach_ids = array_filter(explode(',', $gallery_images_id));
    }
}

if ($product->is_type('variable')):
    global $post, $product, $woocommerce, $nectar_options;

    $product_gallery_style = (!empty($nectar_options['single_product_gallery_type'])) ? $nectar_options['single_product_gallery_type'] : 'default';

    if ($product_gallery_style == 'left_thumb_sticky') {
        wp_enqueue_script('stickykit');
    }

    if ($product_gallery_style == 'ios_slider' || $product_gallery_style == 'left_thumb_sticky') {
        wp_enqueue_script('flickity');
    }
    wp_enqueue_script('nectar_single_product');


    if ($product_gallery_style == 'ios_slider' || $product_gallery_style == 'left_thumb_sticky') {


        $has_gallery_imgs = ($product_attach_ids) ? 'true' : 'false';
        ?>


        <div class="images" data-has-gallery-imgs="<?php echo esc_attr($has_gallery_imgs); ?>">

            <div class="flickity product-slider woocommerce-product-gallery">

                <div class="slider generate-markup">

                    <?php if (has_post_thumbnail($variation_id)) {

                        $img_link = wp_get_attachment_url(get_post_thumbnail_id($variation_id));

                        $post_thumbnail_id = get_post_thumbnail_id($variation_id);
                        $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
                        $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
                        $thumbnail_post = get_post($post_thumbnail_id);
                        $image_title = get_post_field('post_excerpt', $post_thumbnail_id);

                        $attributes = array(
                            'title' => get_post_field('post_title', $post_thumbnail_id),
                            'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
                            'data-src' => $full_size_image[0],
                            'data-large_image' => $full_size_image[0],
                            'data-large_image_width' => $full_size_image[1],
                            'data-large_image_height' => $full_size_image[2],
                        );

                        ?>

                        <div class="slide">
                            <div data-thumb="<?php echo get_the_post_thumbnail_url($variation_id, 'shop_thumbnail'); ?>"
                                 class="woocommerce-product-gallery__image easyzoom">
                                <a href="<?php echo esc_url($img_link); ?>" class="no-ajaxy">
                                    <?php echo get_the_post_thumbnail($variation_id, 'shop_single', $attributes); ?>
                                </a>
                            </div>
                        </div>

                    <?php } else {
                        echo '<div class="slide">' . apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src('woocommerce_single'), __('Placeholder', 'woocommerce')), $variation_id) . '</div>';
                    }

                    if ($variation_id) {

                        foreach ($product_attach_ids as $product_attach_id) {

                            $img_link = wp_get_attachment_url($product_attach_id);

                            if (!$img_link)
                                continue;


                            $full_size_image = wp_get_attachment_image_src($product_attach_id, 'full');
                            $attributes = array(
                                'data-caption' => get_post_field('post_excerpt', $product_attach_id),
                                'data-src' => $full_size_image[0],
                                'data-large_image' => $full_size_image[0],
                                'data-large_image_width' => $full_size_image[1],
                                'data-large_image_height' => $full_size_image[2],
                            );

                            echo '<div class="slide"><div class="woocommerce-product-gallery__image easyzoom" data-thumb="' . get_the_post_thumbnail_url($variation_id, 'shop_thumbnail') . '"><a href="' . wp_get_attachment_url($product_attach_id) . '" class="no-ajaxy">';
                            echo wp_get_attachment_image($product_attach_id, 'shop_single', false, $attributes);
                            echo '</a></div></div>';

                        }
                    }
                    ?>

                </div>


            </div>

        </div>


        <?php if ($product_attach_ids) { ?>

            <div class="flickity product-thumbs">
                <div class="slider generate-markup">

                    <?php
                    if (has_post_thumbnail($variation_id)) { ?>
                        <div class="thumb active">
                            <div class="thumb-inner"><?php echo get_the_post_thumbnail($variation_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail')) ?></div>
                        </div>
                    <?php }

                    foreach ($product_attach_ids as $product_attach_id) {

                        $img_link = wp_get_attachment_url($product_attach_id);

                        if (!$img_link)
                            continue;

                        $img_size = wp_get_attachment_image($product_attach_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'));
                        $classes = array();
                        $image_class = esc_attr(implode(' ', $classes));

                        echo apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<div class="thumb"><div class="thumb-inner">%s</div></div>', $img_size), $product_attach_id, $variation_id, $image_class);


                    } ?>
                </div>


            </div>

        <?php }


    } //default lightbox functionality
    else {

            global $post, $product;
            $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
            $post_thumbnail_id = get_post_thumbnail_id($variation_id);
            $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
            $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
            $image_title = get_post_field('post_excerpt', $post_thumbnail_id);
            $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
            $wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
                'woocommerce-product-gallery',
                'woocommerce-product-gallery--' . $placeholder,
                'woocommerce-product-gallery--columns-' . absint($columns),
                'images',
            ));
            ?>
            <div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
                 data-columns="<?php echo esc_attr($columns); ?>"
                 style="opacity: 0; transition: opacity .25s ease-in-out;">
                <figure class="woocommerce-product-gallery__wrapper">
                    <?php
                    $attributes = array(
                        'title' => get_post_field('post_title', $post_thumbnail_id),
                        'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
                        'data-src' => $full_size_image[0],
                        'data-large_image' => $full_size_image[0],
                        'data-large_image_width' => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                    );

                    if (has_post_thumbnail($variation_id)) {
                        $html = '<div data-thumb="' . get_the_post_thumbnail_url($variation_id, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
                        $html .= get_the_post_thumbnail($variation_id, 'shop_single', $attributes);
                        $html .= '</a></div>';
                    } else {
                        $html = '<div class="woocommerce-product-gallery__image--placeholder">';
                        $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'woocommerce'));
                        $html .= '</div>';
                    }

                    echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id($variation_id));

                    do_action('woocommerce_product_thumbnails');
                    ?>
                </figure>
            </div>
        <?php }
endif;
 
