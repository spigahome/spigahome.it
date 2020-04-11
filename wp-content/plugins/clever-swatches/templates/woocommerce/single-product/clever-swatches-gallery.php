<?php
/**
 * Use for display image swatch.
 *
 * @version  2.0.0
 * @package  clever-swatches/templates
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;

$general_settings = get_option('zoo-cw-settings', true);
$is_gallery_enabled = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
} else $product_id = get_the_ID();

if(isset($_GET['cart_item_key'])){
    $cart_item_key=$_GET['cart_item_key'];
    $cart_session=WC()->session->get('cart');
    if(isset($cart_session[$cart_item_key]['variation_id'])){
        $variation_id=$cart_session[$cart_item_key]['variation_id'];
    }
}

if (isset ($variation_id)) {
    if ($is_gallery_enabled) {
        $gallery_images_id = get_post_meta($variation_id, 'zoo-cw-variation-gallery', true);
        $attachment_ids = array_filter(explode(',', $gallery_images_id));
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


$product_swatch_data_array = get_post_meta($product_id, 'zoo_cw_product_swatch_data', true);
if ($product_swatch_data_array == '') {
    $is_gallery_enabled = 0;
}

if ($product->is_type('variable') && $is_gallery_enabled != 0):
    wp_enqueue_style('slick');
    wp_enqueue_script('slick');
    $gallery_layout='horizontal';
    if(isset($general_settings['cw_gallery_layout'])){
        $gallery_layout=$general_settings['cw_gallery_layout'];
    }
    $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
    $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
    $wrapper_classes = apply_filters('woocommerce_single_product_image_gallery_classes', array(
        'woocommerce-product-gallery',
        'images',
        'cw-product-gallery',
        $gallery_layout
    ));
    ?>
    <div id="cw-product-gallery"
         class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>">
        <figure class="woocommerce-product-gallery__wrapper cw-product-gallery-main">
            <?php
            $thumb_html = '';
            $attributes = array(
                'title' => get_post_field('post_title', $post_thumbnail_id),
                'data-caption' => get_post_field('post_excerpt', $post_thumbnail_id),
                'data-src' => $full_size_image[0],
                'data-large_image' => $full_size_image[0],
                'data-large_image_width' => $full_size_image[1],
                'data-large_image_height' => $full_size_image[2],
            );

            if (has_post_thumbnail($variation_id)) {
                $html = '<div data-thumb="' . get_the_post_thumbnail_url($variation_id, 'shop_thumbnail') . '" class="woocommerce-product-gallery__image cw-product-gallery-item">';
                $html .= '<a href="' . esc_url($full_size_image[0]) . '">';
                $html .= get_the_post_thumbnail($variation_id, 'shop_single', $attributes);
                $html .= '</a>';
                $html .= '</div>';
                $thumb_html = '<div class="cw-gallery-thumb-item">' . get_the_post_thumbnail($variation_id, 'shop_thumbnail', $attributes) . '</div>';
            } else {
                $html = '<div class="woocommerce-product-gallery__image--placeholder cw-product-gallery-item">';
                $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce'));
                $html .= '</div>';
                $thumb_html = '<div class="cw-gallery-thumb-item">' . sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src()), esc_html__('Awaiting product image', 'woocommerce')) . '</div>';

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

                $html = '<div data-thumb="' . esc_url($thumbnail[0]) . '" class="woocommerce-product-gallery__image cw-product-gallery-item">';
                $html .= '<a href="' . esc_url($full_size_image[0]) . '">';
                $html .= wp_get_attachment_image($attachment_id, 'shop_single', false, $attributes);
                $html .= '</a>';
                $html .= '</div>';
                $thumb_html .= '<div class="cw-gallery-thumb-item">' . wp_get_attachment_image($attachment_id, 'shop_thumbnail', false, $attributes) . '</div>';
                echo $html;
            }
            ?>
        </figure>
        <?php if ($thumb_html != '') { ?>
            <figure class="cw-product-gallery-thumbs" data-thumb-columns="<?php echo apply_filters('woocommerce_product_thumbnails_columns', 4);?>">
                <?php echo $thumb_html;?>
            </figure>
        <?php } ?>
    </div>
<?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>