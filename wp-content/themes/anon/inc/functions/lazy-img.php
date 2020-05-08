<?php
/**
 * Theme functions for Lazy image
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 * @des         This function will change default image by image have small size. Load default image by using defer js.
 */

/**
 * zoo_lazy_image_attributes
 * Add lazy attributes for image
 * Help for all lazy image effect for all image call by wp_get_attachment_image
 */
function zoo_lazy_image_attributes($attr, $attachment, $size)
{
    $id=(array)$attachment;
    if(!is_array($size)) {
        $lazy_src = wp_get_attachment_image_src($id['ID'], 'zoo-lazy-' . $size);
    }else{
        $lazy_src=false;
    }
    $lazy_src=$lazy_src?$lazy_src[0]:get_template_directory_uri() . '/assets/images/placeholder.png';
    $attr['data-src'] = $attr['src'];
    $attr['src'] = $lazy_src;

    if(isset($attr['srcset'])) {
        $attr['data-srcset'] = $attr['srcset'];
        $attr['srcset'] =$lazy_src. ' 100w';
    }
    $attr['class'] .= ' lazy-img';
    return $attr;
}
if(get_theme_mod('zoo_enable_lazy_image',1)==1) {
    if(!is_admin() && ! wp_doing_ajax()) {
        add_filter('wp_get_attachment_image_attributes', 'zoo_lazy_image_attributes', 1, 3);
    }
    /**
     * Get size information for all currently-registered image sizes.
     *
     * @global $_wp_additional_image_sizes
     * @uses   get_intermediate_image_sizes()
     * @return array $sizes Data for all currently-registered image sizes.
     */
    function zoo_add_lazy_image_sizes() {
        global $_wp_additional_image_sizes;
        add_image_size('zoo-lazy-full', 30, 30, false);
        foreach ( get_intermediate_image_sizes() as $_size ) {
            if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
                $width= get_option( "{$_size}_size_w" );
                $height= get_option( "{$_size}_size_h" );
                $crop=  (bool) get_option( "{$_size}_crop" );
                if($width==0||$height==0){
                    $width=$height=1;
                }
                add_image_size('zoo-lazy-'.$_size, 30, intval( intval(($height*30)/$width)), $crop);

            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

                $width= $_wp_additional_image_sizes[ $_size ]['width'];
                $height= $_wp_additional_image_sizes[ $_size ]['height'];
                $crop=$_wp_additional_image_sizes[ $_size ]['crop'];
                if($width==0||$height==0){
                    $width=$height=1;
                }
                add_image_size('zoo-lazy-'.$_size, 30, intval(($height*30)/$width), $crop);
            }
        }

        return false;
    }

    add_action('init','zoo_add_lazy_image_sizes',11);
}