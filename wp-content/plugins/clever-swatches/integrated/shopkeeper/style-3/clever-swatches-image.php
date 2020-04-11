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

$post_thumbnail_id = get_post_thumbnail_id($variation_id);
$full_size_image = wp_get_attachment_image_src($post_thumbnail_id,'full');
$featured_attachment_meta 			= wp_get_attachment($post_thumbnail_id);

$modal_class = "fresco zoom";
$zoom_class = "";
global $shopkeeper_theme_options;
if ((isset($shopkeeper_theme_options['product_gallery_zoom'])) && ($shopkeeper_theme_options['product_gallery_zoom'] == "1")) {
    $zoom_class = "easyzoom el_zoom";
}
$html_thumb = '';
$current_img=1;
?>
<div class="product-images-style-3  woocommerce-product-gallery__wrapper images">
    <div class="product_images">
        <div class="product-image featured woocommerce-product-gallery__image" id="controller-navigation-image-1">
            <div class="<?php echo $zoom_class; ?>">
                    <a data-fresco-group="product-gallery"
                       data-fresco-options="ui: 'outside', thumbnail: '<?php echo esc_url($full_size_image[0]); ?>'"
                       data-fresco-group-options="overflow: true, thumbnails: 'vertical', onClick: 'close'"
                       data-fresco-caption="<?php echo esc_html($featured_attachment_meta['caption']); ?>"
                       class="<?php echo $modal_class; ?>"
                       href="<?php echo esc_url($full_size_image[0]); ?>">

                    <?php if ( has_post_thumbnail($variation_id) ) { ?>
                        <img src="<?php echo esc_url($full_size_image[0]); ?>" data-src="<?php echo esc_url($full_size_image[0]); ?>" alt="<?php echo get_post_field('post_title', $post_thumbnail_id); ?>" class="wp-post-image">
                    <?php } else { ?>
                        <img src="<?php echo esc_url(wc_placeholder_img_src()); ?>" data-src="<?php echo esc_url(wc_placeholder_img_src()); ?>" >
                    <?php } ?>

                </a>

            </div>

            <?php if ($featured_attachment_meta['caption'] && has_post_thumbnail($variation_id) ) : ?>

                <p class="caption">
                    <?php echo $featured_attachment_meta['caption']; ?>
                </p>

            <?php endif; ?>

        </div> <!-- product-image featured -->
        <?php
        if ( $attachment_ids ) : ?>
            <?php

            foreach ( $attachment_ids as $attachment_id ) {

                $current_img++; // get the current img for controller navigation
                $gallery_image_title       			= esc_attr( get_the_title( $attachment_id ) );
                $gallery_image_src         			= wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                $gallery_image_data_src    			= wp_get_attachment_image_src( $attachment_id, 'shop_single' );
                $gallery_image_data_src_original 	= wp_get_attachment_image_src( $attachment_id, 'full' );
                $gallery_image_link        			= wp_get_attachment_url( $attachment_id );
                $gallery_attachment_meta			= wp_get_attachment($attachment_id);
                ?>

                <div class="product-image" id="controller-navigation-image-<?php echo $current_img; ?>">
                    <div class="<?php echo $zoom_class; ?>">
                        <a data-fresco-group="product-gallery"
                                data-fresco-options="thumbnail: '<?php echo esc_url($gallery_image_data_src[0]); ?>'"
                                data-fresco-caption="<?php echo esc_html($gallery_attachment_meta['caption']); ?>"
                                class="<?php echo $modal_class; ?>"
                                href="<?php echo esc_url($gallery_image_link); ?>">

                            <img src="<?php echo esc_url($gallery_image_data_src[0]); ?>" alt="<?php echo esc_html($gallery_image_title); ?>">

                        </a>
                    </div>

                    <?php if ($gallery_attachment_meta['caption']) : ?>

                        <p class="caption">
                            <?php echo $gallery_attachment_meta['caption']; ?>
                        </p>

                    <?php endif; ?>

                </div>

                <?php

            }

            ?>

        <?php endif; ?>
        <ul class="product-images-controller">
            <?php
            $current_img = 1;
            ?>
            <li>
                <a href="#controller-navigation-image-1" data-number="1">
                    <span class="dot current"></span>
                </a>
            </li>
            <?php foreach ( $attachment_ids as $attachment_id ) {
                $current_img++;
                echo '<li><a href="#controller-navigation-image-'.$current_img.'" data-number="'.$current_img.'">
					<span class="dot"></span>
        		</a></li>';
            }
            ?>

        </ul>
    </div>
</div>
<?php
else:
wc_get_template_part('single-product/product','image');
endif;
?>