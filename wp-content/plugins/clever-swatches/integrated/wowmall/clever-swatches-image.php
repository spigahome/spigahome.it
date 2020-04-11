<?php
/**
 * CleverSwatches image
 * @description: use for display image swatch
**/
global $wowmall_options;
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
if($wowmall_options['product_page_layout']!=1):
?>
<div class="images product_page_layout_<?php echo $wowmall_options['product_page_layout']?>">
    <div class=woocommerce-product-gallery__image>
        <?php
        if ( has_post_thumbnail($variation_id) ) {
            $attachment_count = count($attachment_ids);
            $gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
            $post_thumbnail_id = get_post_thumbnail_id( $variation_id );
            $props            = wc_get_product_attachment_props($post_thumbnail_id);
            $image            = get_the_post_thumbnail( $variation_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
                'title' => $props['title'],
                'alt'   => $props['alt'],
            ) );

            $classes = array(
                'woocommerce-main-image',
            );

            if ( ( ! isset( $wowmall_options['product_zoom'] ) || $wowmall_options['product_zoom'] ) && ! wp_is_mobile() ) {

                $classes[] = 'zoom';
            }

            $image_class = join( ' ', $classes );
            $link        = ( ! isset( $wowmall_options['product_lightbox'] ) || $wowmall_options['product_lightbox'] ) ? '<a href="%1$s" class="%2$s" title="%3$s" data-rel="prettyPhoto%4$s">%5$s</a>' : '<span data-url="%1$s" class="%2$s">%5$s</span>';
            $html = sprintf( $link, esc_url( $props['url'] ), $image_class, esc_attr( $props['caption'] ), $gallery, $image );
        }
        else {
            $html = sprintf( '<img src="%s" alt="%s" class="wp-post-image">', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
        }
        echo $html;
        if ( $attachment_ids ) {
            ?>
        <div class="thumbnails"><?php
            foreach ( $attachment_ids as $attachment_id ) {
                $full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
                $thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                $attributes      = array(
                    'title'                   => get_post_field( 'post_title', $attachment_id ),
                    'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
                    'data-src'                => $full_size_image[0],
                    'data-large_image'        => $full_size_image[0],
                    'data-large_image_width'  => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                );

                $html  = '<a href="' . esc_url( $full_size_image[0] ) . '" class='.$image_class.'>';
                $html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
                $html .= '</a>';

                echo $html;
            }
            ?>
            </div><?php
        }
        ?>
    </div>
</div>
<?php else:
    $thumb_id       = get_post_thumbnail_id($variation_id);
    if ( ! empty( $thumb_id ) ) {
    array_unshift( $attachment_ids, $thumb_id );
    }
    $lightbox = ! isset( $wowmall_options['product_lightbox'] ) || $wowmall_options['product_lightbox'];
    if ( wp_is_mobile() || empty( $wowmall_options['product_page_layout'] ) || '2' === $wowmall_options['product_page_layout'] ) {
        woocommerce_show_product_images();

        return;
    }
    ?>
    <div class="images product_page_layout_1">
        <?php
        if ( $lightbox ) {
            $thumbs = array();

            if ( ! wp_is_mobile() && ! empty( $attachment_ids ) && 0 < count( $attachment_ids ) ) {

                foreach ( $attachment_ids as $attachment_id ) {

                    $thumbs[] = wp_get_attachment_image_src( $attachment_id, 'gallery_img_size_lightbox_thumb', 0 );
                }
            }
            ?>
            <script>
                var cw_singleProductLightbox={"thumbs": <?php echo json_encode($thumbs);?>};
            </script>
            <?php
        }
        $gallery = 1 < count( $attachment_ids ) ? '[product-gallery]' : ''; ?>
        <div class=thumbs-wrapper>
            <div id=gallery-thumbs class="swiper-container swiper-container-vertical">
                <div class=swiper-wrapper>
                    <?php
                    if ( ! empty( $attachment_ids ) ) {
                        foreach ( $attachment_ids as $attachment_id ) {
                            echo sprintf( '<div class=swiper-slide><span>%s</span></div>', wp_get_attachment_image( $attachment_id, 'woo_img_size_single_thumb', 0 ) );
                        }
                    }
                    else {
                        echo sprintf( '<div class=swiper-slide><span>%s</span></div>', wc_placeholder_img( 'woo_img_size_single_thumb' ) );
                    } ?>
                </div>
                <?php if ( $gallery ) { ?>
                    <div class=swiper-button-prev id=prev-thumbs></div>
                    <div class=swiper-button-next id=next-thumbs></div>
                <?php } ?>
            </div>
        </div>
        <div id=gallery-images>
            <div class=swiper-container>
                <div class=swiper-wrapper>
                    <?php

                    if ( ! empty( $attachment_ids ) ) {

                        $image_class = '';

                        if ( ! isset( $wowmall_options['product_zoom'] ) || $wowmall_options['product_zoom'] ) {

                            $image_class = 'zoom';
                        }
                        foreach ( $attachment_ids as $attachment_id ) {

                            $props = wc_get_product_attachment_props( $attachment_id, $post );

                            if ( ! $props['url'] ) {
                                continue;
                            }

                            $link = $lightbox ? '<a href="%1$s" class="%2$s" title="%3$s" data-rel=prettyPhoto%4$s>%5$s</a>' : '<span data-url="%1$s" class="%2$s">%5$s</span>';

                            printf( '<div class=swiper-slide>' . $link . '</div>', esc_url( $props['url'] ), esc_attr( $image_class ), esc_attr( $props['caption'] ), $gallery, wp_get_attachment_image( $attachment_id, 'woo_img_size_single_1', 0 ) );
                        }
                    }
                    else {
                        printf( '<div class=swiper-slide>%s</div>', wc_placeholder_img( 'woo_img_size_single_1' ) );
                    } ?>
                </div>
                <?php if ( $gallery ) { ?>
                    <div class=swiper-button-prev id=prev-images></div>
                    <div class=swiper-button-next id=next-images></div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php endif;
else:
    wc_get_template_part('single-product/product', 'image');
endif;