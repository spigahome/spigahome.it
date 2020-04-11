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
        $variation_id = $product_id;
    } else {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
    }
}
if($product->is_type('variable')):
$columns           = 5;
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $variation_id );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters('woocommerce_single_product_image_gallery_classes', array(
'woocommerce-product-gallery',
'woocommerce-product-gallery--' . $placeholder,
'woocommerce-product-gallery--columns-' . absint( $columns ),
'images',
));
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>"
     style="opacity: 1; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper">
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
            $html = '<div data-thumb="' . get_the_post_thumbnail_url( $variation_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image">';
            $html .= '<a href="' . esc_url( $full_size_image[0] ) . '">';
            $html .= get_the_post_thumbnail( $variation_id, 'shop_single', $attributes );
            $html .= '</a>';
            $html .= '</div>';
        } elseif (has_post_thumbnail($product_id)) {
            $html = '<div data-thumb="' . get_the_post_thumbnail_url($product_id, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
            $html .= get_the_post_thumbnail($product_id, 'shop_single', $attributes);
            $html .= '</a></div>';
        } else {
            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
            $html .= '</div>';
        }
        echo $html;

        //thumb image
        foreach ($attachment_ids as $attachment_id) {
            $full_size_image = wp_get_attachment_image_src($attachment_id, 'full');
            $thumbnail = wp_get_attachment_image_src($attachment_id, 'shop_thumbnail');
            $image_title = get_post_field('post_excerpt', $attachment_id);

            $attributes = array(
                'title' => $image_title,
                'data-src' => $full_size_image[0],
                'data-large_image' => $full_size_image[0],
                'data-large_image_width' => $full_size_image[1],
                'data-large_image_height' => $full_size_image[2],
            );

            $html = '<div data-thumb="' . esc_url($thumbnail[0]) . '" class="woocommerce-product-gallery__image">';
            $html .= '<a href="' . esc_url($full_size_image[0]) . '">';
            $html .= wp_get_attachment_image($attachment_id, 'shop_single', false, $attributes);
            $html .= '</a>';
            $html .= '</div>';

            echo $html;
        }
        ?>
    </figure>
</div>
    <?php
else:
    wc_get_template_part('single-product/product','image');
endif;
?>