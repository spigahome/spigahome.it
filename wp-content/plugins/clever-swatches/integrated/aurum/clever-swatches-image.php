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
        $variation_id = $product_id;
    } else {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
    }
}


$product_swatch_data_array = get_post_meta($product_id, 'zoo_cw_product_swatch_data', true);
if ($product_swatch_data_array == '') {
    $is_gallery_enabled = 0;
}

if ($product->is_type('variable') && $is_gallery_enabled != 0):
// Slick carousel
    aurum_enqueue_slick_carousel();

// Image options
    $autoswitch = get_data( 'shop_single_auto_rotate_image' );

    if ( ! is_numeric( $autoswitch ) ) {
        $autoswitch = 5;
    }

    $horizontal_gallery = aurum_woocommerce_get_single_product_thumbnails_placement() == 'horizontal';

    $images = array();
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);;

    if ( $post_thumbnail_id ) {
        $images[] = $post_thumbnail_id;
    }

    if ( ! empty( $attachment_ids ) ) {
        $images = array_merge( $images, $attachment_ids );
    }

// Thumbnail columns
    $thumbnail_columns = aurum_get_number_from_word( get_data( 'shop_product_thumbnails_columns' ) );
    $thumbnail_columns = apply_filters( 'woocommerce_product_thumbnails_columns', $thumbnail_columns ? $thumbnail_columns : 5 );

// Classes
    $product_images_classes = array( 'product-images' );

    if ( 1 == count( $images ) ) {
        $product_images_classes[] = 'product-images--single-image';
        $product_images_classes[] = 'product-images--single-image--on';
    }

    if ( apply_filters( 'aurum_woocommerce_single_variation_image_swap', true ) ) {
        $product_images_classes[] = 'product-images--single-variation-image-swap';
    }
    ?>
    <div <?php aurum_class_attr( $product_images_classes ); ?>>

        <div class="product-images--main">

            <?php
            if ( ! empty( $images ) ) {
                foreach ( $images as $attachment_id ) {
                    $html = aurum_woocommerce_get_gallery_image( $attachment_id, true );
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
                }
            } else {
                echo aurum_woocommerce_get_product_placeholder_image();
            }
            ?>

        </div>

        <?php if ( ! empty( $images ) ) : ?>
            <div class="product-images--thumbnails columns-<?php echo $thumbnail_columns; ?>">

                <?php
                if ( ! empty( $images ) ) {
                    foreach ( $images as $attachment_id ) {
                        $html = aurum_woocommerce_get_gallery_image( $attachment_id );
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
                    }
                }
                ?>

            </div>
        <?php endif; ?>

    </div>
<?php
endif;