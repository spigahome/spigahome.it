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
    $is_gallery_enabled=0;
}

if ($product->is_type('variable')&&$is_gallery_enabled!=0):
    $is_quick_view = basel_loop_prop( 'is_quick_view' );

    $thums_position = basel_get_opt('thums_position');
    $product_design = basel_product_design();

    $image_action = basel_get_opt( 'image_action' );

    $columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
    $thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
    $placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
    $wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . $placeholder,
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
    ) );
    if ( $product_design == 'sticky' ) $attachment_ids = false;
    ?>
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?> images row thumbs-position-<?php echo esc_attr( $thums_position ); ?> image-action-<?php echo esc_attr( $image_action ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
        <div class="<?php if ( $attachment_ids && $thums_position == 'left' && ! $is_quick_view ): ?>col-md-9 col-md-push-3<?php else: ?>col-sm-12<?php endif ?>">
            <figure class="woocommerce-product-gallery__wrapper <?php if( $product_design != 'sticky' ) echo 'owl-carousel'; ?>">
                <?php
                $attributes = array(
                    'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
                    'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
                    'data-src'                => $full_size_image[0],
                    'data-large_image'        => $full_size_image[0],
                    'data-large_image_width'  => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                );


                if ( has_post_thumbnail($variation_id) ) {
                    $html  = '<figure data-thumb="' . get_the_post_thumbnail_url($variation_id, 'woocommerce_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                    $html .= get_the_post_thumbnail( $variation_id, 'woocommerce_single', $attributes );
                    $html .= '</a></figure>';
                } else {
                    $html  = '<figure class="woocommerce-product-gallery__image--placeholder">';
                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
                    $html .= '</figure>';
                }

                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );


                // Full size images for sticky product design
                if( $product_design == 'sticky' ) {
                    $thums_position = 'bottom';
                }
                if ( $attachment_ids) {
                    foreach ( $attachment_ids as $attachment_id ) {
                        $full_size_image  = wp_get_attachment_image_src( $attachment_id, 'full' );
                        $thumbnail        = wp_get_attachment_image_src( $attachment_id, 'woocommerce_thumbnail' );

                        $attributes = array(
                            'title'                   => get_post_field( 'post_title', $attachment_id ),
                            'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
                            'data-src'                => $full_size_image[0],
                            'data-large_image'        => $full_size_image[0],
                            'data-large_image_width'  => $full_size_image[1],
                            'data-large_image_height' => $full_size_image[2],
                        );

                        $html  = '<figure data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
                        $html .= wp_get_attachment_image( $attachment_id, 'woocommerce_single', false, $attributes );
                        $html .= '</a></figure>';

                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );
                    }
                }

                ?>
            </figure>
            <?php if ( $image_action != 'popup' && basel_get_opt('photoswipe_icon')): ?>
                <div class="basel-show-product-gallery-wrap"><a href="#" class="basel-show-product-gallery basel-tooltip"><?php _e('Click to enlarge', 'basel'); ?></a></div>
            <?php endif ?>
        </div>

        <?php if ( $attachment_ids ): ?>
            <div class="<?php if ( $thums_position == 'left' && ! $is_quick_view ): ?>col-md-3 col-md-pull-9<?php else: ?>col-sm-12<?php endif ?>"><div class="thumbnails"></div></div>
        <?php endif; ?>
    </div>
<?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>