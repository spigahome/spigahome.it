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
        $variation_id =$product_id;
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
    $columns = apply_filters('woocommerce_product_thumbnails_columns', 4);
    $thumbnail_size = apply_filters('woocommerce_product_thumbnails_large_size', 'full');
    $post_thumbnail_id = get_post_thumbnail_id($variation_id);
    $full_size_image = wp_get_attachment_image_src($post_thumbnail_id, $thumbnail_size);
    $image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
    $placeholder = has_post_thumbnail() ? 'with-images' : 'without-images';
    $wrapper_classes = apply_filters(
        'woocommerce_single_product_image_gallery_classes', array(
            'woocommerce-product-gallery',
            'woocommerce-product-gallery--' . $placeholder,
            'woocommerce-product-gallery--columns-' . absint($columns),
            'images',
            'unero-images'
        )
    );
    ?>
    <div id="product-images-content" class="product-images-content">
        <div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
             data-columns="<?php echo esc_attr($columns); ?>">
            <figure class="woocommerce-product-gallery__wrapper" id="product-images">
                <?php


                $attributes = array(
                    'title'                   => $image_title,
                    'data-src'                => $full_size_image[0],
                    'data-large_image'        => $full_size_image[0],
                    'data-large_image_width'  => $full_size_image[1],
                    'data-large_image_height' => $full_size_image[2],
                );

                if ( has_post_thumbnail($variation_id) ) {
                    $html = '<div data-thumb="' . get_the_post_thumbnail_url( $variation_id, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a class="photoswipe" href="' . esc_url( $full_size_image[0] ) . '">';
                    $html .= get_the_post_thumbnail( $variation_id, 'shop_single', $attributes );
                    $html .= '</a></div>';
                } else {
                    $html = '<div class="woocommerce-product-gallery__image--placeholder">';
                    $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'unero' ) );
                    $html .= '</div>';
                }
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id($variation_id) );
               // echo $html;
                //thumb image
                $small_thumb_size = apply_filters( 'single_product_small_thumbnail_size', 'shop_single' );
                $video_position = intval( get_post_meta( $product_id, 'video_position', true ) );
                $video_width    = intval( get_post_meta( $product_id, 'video_width', true ) );
                $video_height   = intval( get_post_meta( $product_id, 'video_height', true ) );
                $video_thumb    = get_post_meta( $product_id, 'video_thumbnail', true );
                $video_url      = get_post_meta( $product_id, 'video_url', true );
                $video_html     = '';
                if ( $video_thumb ) {
                    $video_thumb = wp_get_attachment_image( $video_thumb, $small_thumb_size );
                    // If URL: show oEmbed HTML
                    if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
                        $atts = array(
                            'width'  => $video_width,
                            'height' => $video_height
                        );
                        if ( $oembed = @wp_oembed_get( $video_url, $atts ) ) {
                            $video_html = $oembed;
                        } else {
                            $atts = array(
                                'src'    => $video_url,
                                'width'  => $video_width,
                                'height' => $video_height
                            );

                            $video_html = wp_video_shortcode( $atts );
                        }
                    }
                    if ( $video_html ) {
                        $video_html = '<div class="video-wrapper">' . $video_html . '</div>';
                    }

                    if ( $video_thumb ) {
                        $video_html = sprintf(
                            '<div><a href="#" data-href="%s" class="photoswipe video">%s</a></div>',
                            esc_attr( $video_html ),
                            $video_thumb
                        );
                    }
                }

                if ( ( $attachment_ids && has_post_thumbnail($variation_id) ) || $video_thumb ) {
                    $loop = 1;
                    foreach ( $attachment_ids as $attachment_id ) {
                        $full_size_image  = wp_get_attachment_image_src( $attachment_id, 'full' );
                        $thumbnail        = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );

                        if ( intval( $video_position ) == $loop + 1 ) {
                            echo $video_html;
                        }

                        $attributes = array(
                            'data-src'                => $full_size_image[0],
                            'data-large_image'        => $full_size_image[0],
                            'data-large_image_width'  => $full_size_image[1],
                            'data-large_image_height' => $full_size_image[2],
                        );

                        $html  = '<div data-thumb="' . esc_url( $thumbnail[0] ) . '" class="woocommerce-product-gallery__image"><a class="photoswipe" href="' . esc_url( $full_size_image[0] ) . '">';
                        $css_class='';
                        if ( intval( unero_get_option( 'lazyload' ) ) ) {
                            $props = wc_get_product_attachment_props( $attachment_id);
                            $image = wp_get_attachment_image_src( $attachment_id, 'shop_single' );

                            if ( $image ) {
                                $image_trans = get_template_directory_uri() . '/images/transparent.png';

                                if ( $attributes ) {
                                    $html .= sprintf(
                                        '<img src="%s" data-original="%s" data-lazy="%s" alt="%s" class="lazy %s" width="%s" height="%s" data-large_image_width="%s" data-large_image_height="%s">',
                                        esc_url( $image_trans ),
                                        esc_url( $image[0] ),
                                        esc_url( $image[0] ),
                                        esc_attr( $props['alt'] ),
                                        esc_attr( $css_class ),
                                        esc_attr( $image[1] ),
                                        esc_attr( $image[2] ),
                                        esc_attr( $attributes['data-large_image_width'] ),
                                        esc_attr( $attributes['data-large_image_height'] )
                                    );
                                } else {
                                    $html .= sprintf(
                                        '<img src="%s" data-original="%s" data-lazy="%s" alt="%s" class="lazy %s" width="%s" height="%s">',
                                        esc_url( $image_trans ),
                                        esc_url( $image[0] ),
                                        esc_url( $image[0] ),
                                        esc_attr( $props['alt'] ),
                                        esc_attr( $css_class ),
                                        esc_attr( $image[1] ),
                                        esc_attr( $image[2] )
                                    );
                                }

                            }
                        } else {
                            $attributes['class'] = $css_class;
                            $output              = wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
                        }

                        //$html .= unero_get_image_html($attachment_id, 'shop_single', '', $attributes );
                        $html .= '</a></div>';

                        echo $html;

                        $loop ++;
                    }

                    if ( $video_position > count( $attachment_ids ) ) {
                        echo $video_html;
                    }
                }
                ?>
            </figure>
        </div>
        <?php
        if ( $video_thumb ) {
            $video_thumb = wp_get_attachment_image( $video_thumb, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
        }

        if ( $attachment_ids || $video_thumb ) {
            $loop    = 1;
            $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
            ?>
            <div class="product-thumbnails" id="product-thumbnails">
                <div class="thumbnails <?php echo 'columns-' . $columns; ?>"><?php

                    $image_thumb = get_the_post_thumbnail( $variation_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );

                    if ( $image_thumb ) {

                        printf(
                            '<div>%s</div>',
                            $image_thumb
                        );

                    }

                    if ( $attachment_ids ) {
                        foreach ( $attachment_ids as $attachment_id ) {

                            if ( $video_thumb ) {
                                if ( intval( $video_position ) == $loop + 1 ) {
                                    printf(
                                        '<div class="video-thumb">%s</div>',
                                        $video_thumb
                                    );
                                }
                            }

                            $props = wc_get_product_attachment_props( $attachment_id);

                            if ( ! $props['url'] ) {
                                continue;
                            }

                            echo apply_filters(
                                'woocommerce_single_product_image_thumbnail_html',
                                sprintf(
                                    '<div>%s</div>',
                                    wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $props )
                                ),
                                $attachment_id,
                                $variation_id
                            );

                            $loop++;
                        }
                    }

                    if ( $video_thumb ) {
                        if ( $video_position > count( $attachment_ids ) + 1 ) {
                            printf(
                                '<div class="video-thumb">%s</div>',
                                $video_thumb
                            );
                        }
                    }

                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>