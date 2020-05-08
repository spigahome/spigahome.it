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

if ($product->is_type('variable')):
    $product_gallery_popup =  epico_opt('product_gallery_popup');
    $processed_images = array();
    //EpicoMedia codes
    $image_num = count($attachment_ids) + ( has_post_thumbnail($variation_id) ? 1 : 0 );
    if(isset($is_quick_view) )
    {
        $product_detail_style = 'classic'; // style of product detail for quickview
    }
    else
    {
        if(epico_get_meta('product_detail_style_inherit') == '1')
        {
            $product_detail_style = epico_get_meta('product_detail_style'); // style of product detail in product page
        }
        else
        {
            $product_detail_style = epico_opt("product-detail-style"); // style of product detail in theme settings
        }

    }
    ?>

    <div class="images">
        <div id="product-fullview-thumbs" class="<?php if(count($attachment_ids) == 0) { echo "no-gallery";} ?>">
            <div class="zoom-container">
                <?php
                if($image_num > 1)
                {
                    ?>

                    <div class="swiper-container clearfix">
                        <div class="swiper-wrapper">

                            <?php
                            $zoom = esc_attr(epico_get_meta('shop_enable_zoom'));

                            $slide_num = 0;

                            if ( has_post_thumbnail($variation_id) )
                            {
                                $image_title = get_the_title( get_post_thumbnail_id($variation_id) );

                                if(function_exists('wc_get_image_size')) {

                                    $image_dimension = wc_get_image_size('shop_single');

                                    $image_link  = wp_get_attachment_url( get_post_thumbnail_id($variation_id) );
                                    $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);

                                    if(!$img_url) {
                                        $img_url = $image_link;
                                    }

                                    $image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

                                } else {

                                    $image = get_the_post_thumbnail( $variation_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );

                                }


                                //echo slide
                                if ($zoom == 1 && $product_detail_style != 'pd_top') {

                                    $big_image = wp_get_attachment_image_src( get_post_thumbnail_id($variation_id ), 'full' );
                                    if($product_gallery_popup  )
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom enable-popup"  data-zoom-image="%s" data-src="%s" data-slide="%s" >%s</div>', esc_url($big_image[0]), esc_url($img_url), esc_attr($slide_num), $image ), $variation_id );
                                    else
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom" data-zoom-image="%s" data-slide="%s">%s</div>', esc_url($big_image[0]), esc_attr($slide_num), $image ), $variation_id );

                                }
                                else
                                {
                                    if($product_gallery_popup )
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide enable-popup"   data-src="%s" data-slide="%s" >%s</div>',  esc_url($img_url), esc_attr($slide_num), $image ), $variation_id );
                                    else
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide" data-slide="%s" >%s</div>',  esc_attr($slide_num), $image ), $variation_id );

                                }

                                preg_match( '@src="([^"]+)"@' , $image, $match );
                                $src = array_pop($match);
                                $processed_images[] = $src;

                                $slide_num++;

                            }
                            foreach ( $attachment_ids as $attachment_id ) {

                                $image_title = get_the_title( $attachment_id );

                                if(function_exists('wc_get_image_size')) {

                                    $image_dimension = wc_get_image_size('shop_single');

                                    $image_link  = wp_get_attachment_url( $attachment_id );
                                    $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);

                                    if(!$img_url) {
                                        $img_url = $image_link;
                                    }

                                    $image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

                                } else {

                                    $image = get_the_post_thumbnail( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );

                                }

                                preg_match( '@src="([^"]+)"@' , $image, $match );
                                $src = array_pop($match);
                                if(in_array($src, $processed_images))
                                {
                                    continue;
                                }
                                $processed_images[] = $src;


                                //echo slide
                                if ($zoom == 1 && $product_detail_style != 'pd_top') {

                                    $big_image = wp_get_attachment_image_src( $attachment_id , 'full' );
                                    if($product_gallery_popup  )
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom enable-popup"  data-zoom-image="%s" data-src="%s" data-slide="%s" >%s</div>', esc_url($big_image[0]), esc_url($img_url), esc_attr($slide_num), $image ), $variation_id );
                                    else
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide easyzoom" data-zoom-image="%s" data-slide="%s">%s</div>', esc_url($big_image[0]), esc_attr($slide_num), $image ), $variation_id );

                                }
                                else
                                {
                                    if($product_gallery_popup )
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide enable-popup"   data-src="%s" data-slide="%s" >%s</div>',  esc_url($img_url), esc_attr($slide_num), $image ), $variation_id );
                                    else
                                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="swiper-slide" data-slide="%s" >%s</div>',  esc_attr($slide_num), $image ), $variation_id );

                                }

                                $slide_num++;
                            }

                            ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <?php
                }
                else
                {

                    $zoom = esc_attr(epico_get_meta('shop_enable_zoom'));

                    if ( has_post_thumbnail($variation_id) )
                    {

                        $image_title = get_the_title( get_post_thumbnail_id($variation_id) );

                        if($product_detail_style == 'pd_top')
                        {

                            $image = wp_get_attachment_image( get_post_thumbnail_id($variation_id ), 'full' );

                        }
                        else
                        {
                            if(function_exists('wc_get_image_size')) {

                                $image_dimension = wc_get_image_size('shop_single');

                                $image_link  = wp_get_attachment_url( get_post_thumbnail_id($variation_id) );
                                $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);

                                if(!$img_url) {
                                    $img_url = $image_link;
                                }

                                $image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

                            } else {

                                $image = get_the_post_thumbnail( $variation_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );

                            }

                        }


                        //echo slide
                        if ($zoom == 1 && $product_detail_style != 'pd_top') {

                            $big_image = wp_get_attachment_image_src( get_post_thumbnail_id($variation_id ), 'full' );

                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom" data-zoom-image="%s">%s</div>', esc_url($big_image[0]), $image ), $variation_id );

                        }
                        else
                        {

                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $variation_id );

                        }

                    }

                    foreach ( $attachment_ids as $attachment_id )
                    {

                        $image_title = get_the_title( $attachment_id );

                        if($product_detail_style == 'pd_top')
                        {

                            $image = wp_get_attachment_image( $attachment_id, 'full' );

                        }
                        else
                        {
                            if(function_exists('wc_get_image_size')) {

                                $image_dimension = wc_get_image_size('shop_single');

                                $image_link  = wp_get_attachment_url( $attachment_id );
                                $img_url = aq_resize($image_link, $image_dimension['width'], $image_dimension['height'], $image_dimension['crop'], true, true);

                                if(!$img_url) {
                                    $img_url = $image_link;
                                }

                                $image = '<img src="'.esc_url($img_url).'" alt="'.esc_attr($image_title).'" />';

                            } else {

                                $image = get_the_post_thumbnail( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );

                            }

                        }


                        //echo slide
                        if ($zoom == 1 && $product_detail_style != 'pd_top') {

                            $big_image = wp_get_attachment_image_src( $attachment_id , 'full' );

                            if($product_gallery_popup )
                                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom enable-popup"  data-src="%s" data-zoom-image="%s">%s</div>', esc_url($big_image[0]), esc_url($big_image[0]), $image ), $variation_id );
                            else
                                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="easyzoom" data-zoom-image="%s">%s</div>', esc_url($big_image[0]), $image ), $variation_id );

                        }
                        else
                        {

                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $variation_id );

                        }

                    }

                }
                ?>
            </div>
        </div>
        <?php if ( count($attachment_ids) > 0 ) {

            $processed_images = array();

            if ( $attachment_ids || count($variable_images) > 0 ) {
                ?>
                <div class="thumbnails zoom-gallery">
                    <div id="product-thumbs">
                        <div class="swiper-container clearfix">
                            <div class="swiper-wrapper">
                                <?php

                                $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
                                $image_dimension = wc_get_image_size('shop_thumbnail');

                                if ( has_post_thumbnail($variation_id) )
                                {
                                    $thumb_image       = wp_get_attachment_image_src(get_post_thumbnail_id( $variation_id) ,'full');
                                    $image       = aq_resize($thumb_image[0], $image_dimension['width'], $image_dimension['height'], true, true);
                                    //var_dump($image);
                                    if(!$image)
                                        $image = $thumb_image[0];



                                    $image = '<img src="' . $image . '" alt="">';

                                    echo '<div class="swiper-slide">';
                                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $image, $variation_id, $variation_id );
                                    echo '</div>';

                                    preg_match( '@src="([^"]+)"@' , $image, $match );
                                    $src = array_pop($match);
                                    $processed_images[] = $src;
                                }
                                foreach ( $attachment_ids as $attachment_id ) {

                                    $image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );

                                    preg_match( '@src="([^"]+)"@' , $image, $match );
                                    $src = array_pop($match);
                                    if(in_array($src, $processed_images))
                                    {
                                        continue;
                                    }
                                    $processed_images[] = $src;



                                    echo '<div class="swiper-slide">';
                                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $image , $attachment_id, $variation_id);
                                    echo '</div>';
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } ?>
    </div>
    <?php
else:
    wc_get_template_part('single-product/product', 'image');
endif;
?>