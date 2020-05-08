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

if ($product->is_type('variable')):

    $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
    $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
    $featured_attachment_meta= wp_get_attachment($post_thumbnail_id);
    $modal_class = "fresco zoom";
    $zoom_class = "";
    global $shopkeeper_theme_options;
    if ((isset($shopkeeper_theme_options['product_gallery_zoom'])) && ($shopkeeper_theme_options['product_gallery_zoom'] == "1")) {
        $zoom_class = "easyzoom el_zoom";
    }
    $html_thumb = $html = '';
    ?>
    <div class="product-images-layout product-images-default images">
        <div class="product_images">
            <div class="swiper-container product-images-carousel hide-for-small woocommerce-product-gallery__wrapper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide product-image">
                        <div class="<?php echo $zoom_class; ?> woocommerce-product-gallery__image">
                            <a data-fresco-group="product-gallery"
                               data-fresco-options="ui: 'outside', thumbnail: '<?php echo esc_url($full_size_image[0]); ?>'"
                               data-fresco-group-options="overflow: true, thumbnails: 'vertical', onClick: 'close'"
                               data-fresco-caption="<?php echo esc_html($featured_attachment_meta['caption']); ?>"
                               class="<?php echo $modal_class; ?>"
                               href="<?php echo esc_url($full_size_image[0]); ?>">
                                <?php
                                $attributes = array(
                                    'title' => get_post_field('post_title', $post_thumbnail_id),
                                    'data-src' => $full_size_image[0],
                                    'class' => "swiper-lazy wp-post-image"
                                );
                                $attributes_thumb = array(
                                    'class' => "attachment-shop_thumbnail size-shop_thumbnail"
                                );
                                if (has_post_thumbnail($variation_id)) {
                                    $html .= get_the_post_thumbnail($variation_id, 'shop_single', $attributes);
                                    $html_thumb .= '<li class="carousel-cell is-nav-selected">' . get_the_post_thumbnail($variation_id, 'shop_thumbnail', $attributes_thumb) . '</li>';
                                } else {
                                    $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce'));
                                    $html_thumb .= '<li class="carousel-cell is-nav-selected"><img alt="Placeholder" src="' . esc_url(wc_placeholder_img_src()) . '" /></li>';
                                }
                                echo $html; ?>
                            </a>
                            <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                        </div>
                    </div>
                    <?php
                    //thumb image
                    foreach ($attachment_ids as $attachment_id) {
                        $gallery_image_title = esc_attr(get_the_title($attachment_id));
                        $gallery_image_src = wp_get_attachment_image_src($attachment_id, 'shop_thumbnail');
                        $gallery_image_data_src = wp_get_attachment_image_src($attachment_id, 'shop_single');
                        $gallery_image_data_src_original = wp_get_attachment_image_src($attachment_id, 'full');
                        $gallery_image_link = wp_get_attachment_url($attachment_id);

                        $gallery_attachment_meta = wp_get_attachment($attachment_id);
                        $html_thumb .= '<li class="carousel-cell">' . wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail')) . '</li>';
                        ?>

                        <div class="swiper-slide">

                            <div class="<?php echo $zoom_class; ?>">

                                <a

                                        data-fresco-group="product-gallery"
                                        data-fresco-options="thumbnail: '<?php echo esc_url($gallery_image_data_src[0]); ?>'"
                                        data-fresco-caption="<?php echo esc_html($gallery_attachment_meta['caption']); ?>"

                                        class="<?php echo $modal_class; ?>"

                                        href="<?php echo esc_url($gallery_image_link); ?>"

                                >

                                    <img src="<?php echo esc_url($gallery_image_src[0]); ?>"
                                         data-src="<?php echo esc_url($gallery_image_data_src[0]); ?>"
                                         alt="<?php echo esc_html($gallery_image_title); ?>" class="swiper-lazy">
                                    <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>

                                </a>

                            </div>

                        </div>

                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <ul class="product_thumbnails flex-control-nav show-for-medium-up" style="display: none !important;">
        <?php
        echo $html_thumb;
        ?>
    </ul>

    <div class="swiper-container mobile_gallery">

        <div class="swiper-wrapper">

            <div class="swiper-slide">

                <?php if ( has_post_thumbnail() ) { ?>
                    <img src="<?php echo esc_url($full_size_image[0]); ?>" data-src="<?php echo esc_url($full_size_image[0]); ?>" alt="<?php echo get_post_field('post_title', $post_thumbnail_id); ?>" class=" wp-post-image">
                <?php } else { ?>
                    <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" data-src="<?php echo esc_url(wc_placeholder_img_src()); ?>" class="swiper-lazy">
                <?php } ?>

            </div><!-- /.swiper-slide -->

            <?php

            if ( $attachment_ids ) {

                foreach ( $attachment_ids as $attachment_id ) {

                    $gallery_image_title       			= esc_attr( get_the_title( $attachment_id ) );
                    $gallery_image_src         			= wp_get_attachment_image_src( $attachment_id, 'medium' );
                    $gallery_image_data_src    			= wp_get_attachment_image_src( $attachment_id, 'shop_single' );
                    $gallery_image_data_src_original 	= wp_get_attachment_image_src( $attachment_id, 'full' );
                    $gallery_image_link        			= wp_get_attachment_url( $attachment_id );

                    $gallery_attachment_meta			= wp_get_attachment($attachment_id);

                    ?>

                    <div class="swiper-slide">

                        <img src="<?php echo esc_url($gallery_image_data_src[0]); ?>" data-src="<?php echo esc_url($gallery_image_data_src[0]); ?>" alt="<?php echo esc_html($gallery_image_title); ?>">

                    </div><!-- /.swiper-slide -->

                    <?php

                }

            }
?>

        </div>

    </div>


    <div class="swiper-container mobile_gallery_thumbs">

        <div class="swiper-wrapper">

            <?php if ( $attachment_ids ) {

                if ( has_post_thumbnail() ) {

                    $featured_image_src    = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' );
                    $bg_img = '<div class="swiper-slide" style="background-image: url('.$featured_image_src[0] . '); "></div> ';
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $bg_img,$variation_id );

                } else {

                    $bg_placeholder = '<div class="swiper-slide" style="background-image: url('.wc_placeholder_img_src() . '); "></div> ';
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $bg_placeholder, $variation_id );

                }

                if ( $attachment_ids ) {

                    foreach ( $attachment_ids as $attachment_id ) {

                        $image          = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
                        $image_src      = wp_get_attachment_image_src( $attachment_id, 'thumbnail')  ;

                        $bg_img = '<div class="swiper-slide" style="background-image: url('.$image_src[0] . '); "></div> ';

                        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $bg_img, $variation_id );

                    }

                }
            }

            ?>

        </div> <!-- end mobile gallery -->

    </div> <!-- mobile_gallery_thumbs -->
    <?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>