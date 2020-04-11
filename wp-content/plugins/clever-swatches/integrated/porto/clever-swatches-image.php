<?php
/**
 * CleverSwatches image
 * @description: use for display image swatch
 **/
global $porto_settings;
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

if($product->is_type('variable')):
    $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
    ?>
    <div class="product-images images">
        <div class="product-image-slider owl-carousel show-nav-hover">
            <?php
            $attributes = array(
                'alt' => get_post_field('post_title', $post_thumbnail_id),
                'data-src' => $full_size_image[0],
                'content' => $full_size_image[0],
                'href' => $full_size_image[0],
                'class' => 'woocommerce-main-image img-responsive',
            );
            $attributes_thumb = array(
                'alt' => get_post_field('post_title', $post_thumbnail_id),
                'class' => 'woocommerce-main-thumb img-responsive',
            );
            $html_thumb = '';
            if (has_post_thumbnail($variation_id)) {
                $html = '<div class="img-thumbnail"><div class="inner">';
                $html .= get_the_post_thumbnail($variation_id, 'shop_single', $attributes);
                $html .= '</div></div>';
                $html_thumb .= '<div class="img-thumbnail">' . get_the_post_thumbnail($variation_id, 'shop_thumbnail', $attributes_thumb) . '</div>';
            } else {
                $image_link = wc_placeholder_img_src();
                $html = '<div class="img-thumbnail"><div class="inner">';
                $html .= '<img src="' . $image_link . '" alt="" href="' . $image_link . '" class="woocommerce-main-image img-responsive" itemprop="image" content="' . $image_link . '" />';
                $html .= '</div></div>';
                $html_thumb .= '<div class="img-thumbnail"><img src="' . $image_link . '" alt="" class="woocommerce-thumb-image img-responsive" /></div>';
            }
            echo $html;

            //thumb image
            foreach ($attachment_ids as $attachment_id) {
                $full_size_image = wp_get_attachment_image_src($attachment_id, 'full');
                $thumbnail = wp_get_attachment_image_src($attachment_id, 'shop_thumbnail');
                $image_title = get_post_field('post_title', $attachment_id);

                $attributes = array(
                    'alt' => $image_title,
                    'data-src' => $full_size_image[0],
                    'content' => $full_size_image[0],
                    'href' => $full_size_image[0],
                    'class' => 'img-responsive',
                );
                $attributes_thumb = array(
                    'alt' => get_post_field('post_title', $attachment_id),
                    'class' => 'woocommerce-main-thumb img-responsive',
                );
                echo '<div class="img-thumbnail"><div class="inner">';
                echo wp_get_attachment_image($attachment_id, 'shop_single', false, $attributes);
                echo '</div></div>';
                $html_thumb .= '<div class="img-thumbnail">' . wp_get_attachment_image($attachment_id, 'shop_thumbnail', false, $attributes_thumb) . '</div>';
            }
            ?>
        </div>
        <span class="zoom" data-index="0"><i class="fa fa-search"></i></span>
    </div>
    <?php
    if ($html_thumb != '') { ?>
        <div class="product-thumbnails thumbnails">
            <div class="product-thumbs-slider owl-carousel">
                <?php echo $html_thumb; ?>
            </div>
        </div>
        <?php
    }
    ?>
<?php
else:
    wc_get_template_part('single-product/product','image');
endif;
?>
