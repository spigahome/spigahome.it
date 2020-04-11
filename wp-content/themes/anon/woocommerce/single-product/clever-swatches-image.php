<?php
if ( ! defined( 'ABSPATH' ) ) {
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

$general_settings   = get_option( 'zoo-cw-settings', true );
$is_gallery_enabled = isset( $general_settings['product_gallery'] ) ? intval( $general_settings['product_gallery'] ) : 1;

if ( isset( $_POST['product_id'] ) ) {
    $product_id = intval( $_POST['product_id'] );
} else {
    $product_id = get_the_ID();
}

if ( isset ( $variation_id ) ) {
    if ( $is_gallery_enabled ) {
        $gallery_images_id = get_post_meta( $variation_id, 'zoo-cw-variation-gallery', true );
        $attachment_ids    = array_filter( explode( ',', $gallery_images_id ) );
    } else {
        $attachment_ids = [];
    }
} else {
    global $post, $product;

    $default_active     = [];
    $variation_id       = 0;

    $default_attributes = $product->get_default_attributes();
    $attributes=$product->get_attributes();
    foreach ($attributes as $attribute){
        $attribute_name=$attribute['name'];
        if(isset($_REQUEST['attribute_' . sanitize_title($attribute_name)])){
            $default_active['attribute_' . sanitize_title($attribute_name)]=wc_clean(stripslashes(urldecode($_REQUEST['attribute_' . sanitize_title($attribute_name)])));
        }else{
            break;
        }
    }

    if($is_gallery_enabled) {
        if(count($default_active)){
            $data_store = WC_Data_Store::load('product');
            $variation_id = $data_store->find_matching_product_variation($product, $default_active);
        }
        elseif (count($default_attributes)) {
            foreach ($default_attributes as $key => $value) {
                $default_active['attribute_' . $key] = $value;
            }
            $data_store = WC_Data_Store::load('product');
            $variation_id = $data_store->find_matching_product_variation($product, $default_active);
        }
    }
    if ( $variation_id == 0 ) {
        $attachment_ids = $product->get_gallery_image_ids();
        $variation_id   = $product_id;
    } else {
        $gallery_images_id = get_post_meta( $variation_id, 'zoo-cw-variation-gallery', true );
        $attachment_ids    = array_filter( explode( ',', $gallery_images_id ) );
    }
}


$product_swatch_data_array = get_post_meta( $product_id, 'zoo_cw_product_swatch_data', true );
if ( $product_swatch_data_array == '' ) {
    $is_gallery_enabled = 0;
}

if ( $product->is_type( 'variable' ) && $is_gallery_enabled != 0 ):
    $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
    $post_thumbnail_id     = get_post_thumbnail_id( $variation_id );
    $wrapper_classes       = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ( has_post_thumbnail( $variation_id ) ? 'with-images' : 'without-images' ),
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
    ) );
    if ( ! $attachment_ids ) {
        $wrapper_classes[] = 'gallery-without-thumb';
    }
    ?>
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"
         data-columns="<?php echo esc_attr( $columns ); ?>">
        <figure class="woocommerce-product-gallery__wrapper">
            <div class="wrap-main-product-gallery">
                <?php
                $thumb_html = '';
                if ( has_post_thumbnail( $variation_id ) ) {
                    $html       = wc_get_gallery_image_html( $post_thumbnail_id, true );
                    $thumb_html .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $post_thumbnail_id ), $post_thumbnail_id );
                } else {
                    $html = '<div class="woocommerce-product-gallery__image--placeholder">';
                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'anon' ) );
                    $html .= '</div>';
                }

                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
                if ( $attachment_ids ) {
                    foreach ( $attachment_ids as $attachment_id ) {
                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id, true ), $attachment_id );
                        $thumb_html .= apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id );
                    }
                }
                ?>
            </div>
            <?php
            if ( $attachment_ids ) {
                $gallery_layout = zoo_product_gallery_layout( $product_id );
                if ( $gallery_layout != 'grid' && $gallery_layout != 'slider' && $gallery_layout != 'sticky' ) {
                    ?>
                    <div class="wrap-list-thumbnail">
                        <?php
                        echo ent2ncr( $thumb_html );
                        ?>
                    </div>
                    <?php
                }
            }
            ?>
        </figure>
    </div>
<?php
else:
    wc_get_template_part( 'single-product/product', 'image' );
endif;