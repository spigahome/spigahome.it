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
    $layout = flatsome_option('product_layout');
    $wrap_gallery_class = '';
    if ($layout == 'gallery-wide') {
        $wrap_gallery_class = 'gallery-wide';
    } else {
        $wrap_gallery_class = 'large-' . flatsome_option('product_image_width') . ' col';
    }
    if(get_theme_mod('product_layout') == 'custom') {
        ?>
        <div class="wrap-cw-images">
        <?php
    }
    $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
    $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
    $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
    $wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . $placeholder,
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    ));

    if ($layout != 'gallery-wide') {
        $slider_classes = array('product-gallery-slider', 'slider', 'slider-nav-small', 'mb-half');
    } else {
        $slider_classes = array('product-gallery-slider', 'slider', 'slider-nav-circle', 'mb-half', 'slider-style-container', 'slider-nav-light', 'slider-load-first', 'no-overflow');
    }
    // Image Zoom
    if (get_theme_mod('product_zoom', 0)) {
        $slider_classes[] = 'has-image-zoom';
    }

    $rtl = 'false';
    if (is_rtl()) $rtl = 'true';

    if (get_theme_mod('product_lightbox', 'default') == 'disabled') {
        $slider_classes[] = 'disable-lightbox';
    }
    if (flatsome_option('product_image_style') == 'vertical' && $layout != 'gallery-wide') {
        ?>
        <div class="row row-small">
        <div class="col large-10">
        <?php
    }
    do_action('flatsome_before_product_images'); ?>
    <div class="product-images relative mb-half has-hover <?php if ($layout == 'gallery-wide') {
        echo 'slider-wrapper relative ';
    }
    echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
         data-columns="<?php echo esc_attr($columns); ?>">
        <?php if ($layout != 'gallery-wide') { ?>
            <?php //do_action('flatsome_sale_flash'); ?>

            <div class="image-tools absolute top show-on-hover right z-3">
                <?php //do_action('flatsome_product_image_tools_top'); ?>
            </div>
        <?php } else { ?>
            <div class="absolute left right">
                <div class="container relative">
                    <?php //do_action('flatsome_sale_flash'); ?>
                </div>
            </div>
        <?php } ?>
        <figure class="woocommerce-product-gallery__wrapper <?php echo implode(' ', $slider_classes); ?>"
                data-flickity-options='{
                "cellAlign": "center",
                "wrapAround": true,
                "autoPlay": false,
                "prevNextButtons":true,
                "adaptiveHeight": true,
                "imagesLoaded": true,
                "lazyLoad": 1,
                "dragThreshold" : 15,
                "pageDots": false,
                "rightToLeft": <?php echo $rtl; ?>
       }'
        >
            <?php
            $attributes = array(
                'title' => get_post_field('post_title', $post_thumbnail_id),
                'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
                'data-src' => $full_size_image[0],
                'data-large_image' => $full_size_image[0],
                'data-large_image_width' => $full_size_image[1],
                'data-large_image_height' => $full_size_image[2],
            );
            $attributes_thumb = array(
                'title' => get_post_field('post_title', $post_thumbnail_id),
                'alt' => get_post_field('post_title', $post_thumbnail_id),
            );
            $html_thumb = '';
            if (has_post_thumbnail($variation_id)) {
                $html = '<div data-thumb="' . get_the_post_thumbnail_url($variation_id, 'shop_thumbnail') . '" class="first slide woocommerce-product-gallery__image woocommerce-product-gallery__image">';
                $html .= '<a href="' . esc_url($full_size_image[0]) . '">';
                $html .= get_the_post_thumbnail($variation_id, 'shop_single', $attributes);
                $html .= '</a>';
                $html .= '</div>';
                $html_thumb .= '<div class="col first is-nav-selected"><a title="' . $attributes_thumb['title'] . '" >' . get_the_post_thumbnail($variation_id, 'shop_thumbnail', $attributes) . '</a></div>';
            } elseif (has_post_thumbnail($product_id)) {
                $html = '<div data-thumb="' . get_the_post_thumbnail_url($product_id, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image"><a href="' . esc_url($full_size_image[0]) . '">';
                $html .= get_the_post_thumbnail($product_id, 'shop_single', $attributes);
                $html .= '</a></div>';
                $html_thumb .= '<div class="col first is-nav-selected"><a title="' . $attributes_thumb['title'] . '" >' . get_the_post_thumbnail($product_id, 'shop_thumbnail', $attributes) . '</a></div>';
            } else {
                $html = '<div class="woocommerce-product-gallery__image--placeholder">';
                $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce'));
                $html .= '</div>';
                $html_thumb .= '<div class="col first is-nav-selected">' . sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce')) . '</div>';

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
                $attributes_thumb = array(
                    'title' => get_post_field('post_title', $post_thumbnail_id),
                    'alt' => get_post_field('post_title', $post_thumbnail_id),
                );
                $html = '<div data-thumb="' . esc_url($thumbnail[0]) . '" class="woocommerce-product-gallery__image slide">';
                $html .= '<a href="' . esc_url($full_size_image[0]) . '">';
                $html .= wp_get_attachment_image($attachment_id, 'shop_single', false, $attributes);
                $html .= '</a>';
                $html .= '</div>';
                $html_thumb .= '<div class="col"><a title="' . $attributes_thumb['title'] . '" >' . wp_get_attachment_image($attachment_id, 'shop_thumbnail', false, $attributes) . '</a></div>';
                echo $html;
            }
            ?>
        </figure>
        <?php if ($layout != 'gallery-wide') { ?>
            <div class="image-tools absolute bottom left z-3">
                <?php do_action('flatsome_product_image_tools_bottom'); ?>
            </div>
        <?php } else { ?>
            <div class="absolute bottom left right">
                <div class="container relative image-tools">
                    <div class="image-tools absolute bottom right z-3">
                        <?php do_action('flatsome_product_image_tools_bottom'); ?>
                        <?php do_action('flatsome_product_image_tools_top'); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if (flatsome_option('product_image_style') == 'vertical' && $layout != 'gallery-wide') {
    ?>
    </div>
    <?php
}
    ?>
    <?php do_action('flatsome_after_product_images');
    if ($layout != 'gallery-wide') {
        $thumb_cell_align = "left";

        if ($attachment_ids) {
            $loop = 0;
            $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);

            $gallery_class = array('product-thumbnails', 'thumbnails');

            if ($thumb_count <= 5) {
                $gallery_class[] = 'slider-no-arrows';
            }

            $gallery_class[] = 'slider row row-small row-slider slider-nav-small small-columns-4';
            if (flatsome_option('product_image_style') == 'vertical' && $layout != 'gallery-wide') {
                ?>
                <div class="col large-2 large-col-first vertical-thumbnails pb-0">
                <?php
            }
            ?>

            <div class="<?php echo implode(' ', $gallery_class); ?>"
                 data-flickity-options='{
              "cellAlign": "<?php echo $thumb_cell_align; ?>",
              "wrapAround": false,
              "autoPlay": false,
              "prevNextButtons":true,
              "asNavFor": ".product-gallery-slider",
              "percentPosition": true,
              "imagesLoaded": true,
              "pageDots": false,
              "rightToLeft": <?php echo $rtl; ?>,
              "contain": true
          }'
            ><?php
                echo $html_thumb;
                ?>
            </div>
            <?php if (flatsome_option('product_image_style') == 'vertical' && $layout != 'gallery-wide') {
                ?>
                </div>
                <?php
            }
        }
    }
    if (flatsome_option('product_image_style') == 'vertical' && $layout != 'gallery-wide') {
        ?>
        </div>
        <?php
    }
    if(get_theme_mod('product_layout') == 'custom') {
        ?>
        </div>
        <?php
    }
    ?>
<?php
else:
    wc_get_template_part('single-product/product','image');
endif;
?>