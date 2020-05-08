<?php
/**
 * WooCommerce functions
 * Functions for check and functions for 3rd plugin.
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate
 
 * @des         All custom functions of WooCommerce will add at this file.
 */

/**
 * zoo_product_hover_effect
 * Hover effect of product item
 * @uses using function zoo_product_hover_effect()
 * @return Hover effect style.
 */
if (!function_exists('zoo_product_hover_effect')) {
    function zoo_product_hover_effect()
    {
        $zoo_hover_effect = get_theme_mod('zoo_product_hover_effect', 'default');
        if (isset($_GET['product_style'])):
            $zoo_hover_effect = $_GET['product_style'];
        endif;
        return $zoo_hover_effect;
    }
}

/**
 * zoo_show_alternate_img
 * Check allow show alternative image, disable in mobile;
 * @uses using function zoo_show_alternate_img()
 * @return allow show or not.
 */
if (!function_exists('zoo_show_alternate_img')) {
    function zoo_show_alternate_img()
    {
        if (get_theme_mod('zoo_enable_alternative_images', '1') != '0' && !wp_is_mobile()) {
            return true;
        }else{
            return false;
        }
    }
}
/**
 * zoo_enable_catalog_mod
 * Enable catalog mod
 * @uses using function zoo_enable_catalog_mod()
 * @return Remove button cart by using hook, and catalog status enable or not.
 */
if (!function_exists('zoo_enable_catalog_mod')) {
    function zoo_enable_catalog_mod()
    {
        $zoo_catalog_status = get_theme_mod('zoo_enable_catalog_mod', '') == '1' ? true : false;
        if (isset($_GET['catalog_mod'])):
            $zoo_catalog_status = true;
        endif;
        return $zoo_catalog_status;
    }
}
/*-------Single Woocommerce functions-------*/
/**
 * zoo_enable_product_share
 * Check allow show/hide template share of single product.
 * @uses use function zoo_enable_product_share() for check.
 * @return bool true/false.
 */
if (!function_exists('zoo_enable_product_share')) {
    function zoo_enable_product_share()
    {
        $zoo_status = false;
        if (get_theme_mod('zoo_enable_product_share', '1') == 1) {
            $zoo_status = true;
        }
        return $zoo_status;
    }
}
/**
 * zoo_single_product_layout Single Product layout
 * @return: Layout of single product page.
 *
 */
if (!function_exists('zoo_single_product_layout')) {
    function zoo_single_product_layout($productId = '')
    {
        if ($productId != '') {
            $zoo_layout_single = get_post_meta($productId, 'zoo_single_product_layout', true);
        } else {
            $zoo_layout_single = get_post_meta(get_the_ID(), 'zoo_single_product_layout', true);
        }
        if ($zoo_layout_single == 'inherit' || $zoo_layout_single == '') {
            $zoo_layout_single = get_theme_mod('zoo_single_product_layout', 'vertical-thumb');
        }
        return $zoo_layout_single;
    }
}
/**
 * zoo_add_tab_to_summary Single Product layout
 * @return: Return add product tabs to product summary.
 *
 */
if (!function_exists('zoo_add_tab_to_summary')) {
    function zoo_add_tab_to_summary($productId = '')
    {
        $zoo_layout_single = zoo_single_product_layout($productId);
        if ($zoo_layout_single == 'custom') {
            if ($productId != '') {
                $zoo_tabs_option = get_post_meta($productId, 'zoo_add_tab_to_summary', true);
            } else {
                $zoo_tabs_option = get_post_meta(get_the_ID(), 'zoo_add_tab_to_summary', true);
            }
            if ($zoo_tabs_option == 'inherit' || $zoo_tabs_option == '') {
                $zoo_tabs_option = get_theme_mod('zoo_add_tab_to_summary', true);
            }
            if($zoo_tabs_option=='yes'||$zoo_tabs_option=='1'){
                $zoo_tabs_option=true;
            }else{
                $zoo_tabs_option=false;
            }
        } else {
            switch ($zoo_layout_single) {
                case 'carousel':
                    $zoo_tabs_option = true;
                    break;
                case 'sticky-1':
                    $zoo_tabs_option = true;
                    break;
                case 'sticky-2':
                    $zoo_tabs_option = true;
                    break;
                default:
                    $zoo_tabs_option = false;
            }
        }
        return $zoo_tabs_option;
    }
}

/**
 * zoo_single_product_content_layout Single Product layout
 * @return: Layout content of single product page.
 *
 */
if (!function_exists('zoo_single_product_content_layout')) {
    function zoo_single_product_content_layout($productId = '')
    {
        $zoo_layout_single = zoo_single_product_layout($productId);
        if ($zoo_layout_single == 'custom') {
            if ($productId != '') {
                $zoo_layout_content_single = get_post_meta($productId, 'zoo_single_product_content_layout', true);
            } else {
                $zoo_layout_content_single = get_post_meta(get_the_ID(), 'zoo_single_product_content_layout', true);
            }
            if ($zoo_layout_content_single == 'inherit' || $zoo_layout_content_single == '') {
                $zoo_layout_content_single = get_theme_mod('zoo_single_product_content_layout', 'left_content');
            }
        } else {
            switch ($zoo_layout_single) {
                case 'vertical-thumb':
                    $zoo_layout_content_single = 'right_content';
                    break;
                case 'horizontal-thumb':
                    $zoo_layout_content_single = 'right_content';
                    break;
                case 'carousel':
                    $zoo_layout_content_single = 'full_content';
                    break;
                default:
                    $zoo_layout_content_single = 'sticky_content';
            }
        }
        return $zoo_layout_content_single;
    }
}
/**
 * zoo_product_gallery_layout Product gallery layout
 * @return: layout of product gallery
 *
 */
if (!function_exists('zoo_product_gallery_layout')) {
    function zoo_product_gallery_layout($productId = '')
    {
        $zoo_layout_gallery = zoo_single_product_layout($productId);
        if ($zoo_layout_gallery == 'custom') {
            if ($productId != '') {
                $zoo_layout_gallery = get_post_meta($productId, 'zoo_product_gallery_layout', true);
            } else {
                $zoo_layout_gallery = get_post_meta(get_the_ID(), 'zoo_product_gallery_layout', true);
            }
            if ($zoo_layout_gallery == 'inherit' || $zoo_layout_gallery == '') {
                $zoo_layout_gallery = get_theme_mod('zoo_product_gallery_layout', 'grid');
            }
        } else {
            switch ($zoo_layout_gallery) {
                case 'vertical-thumb':
                    $zoo_layout_gallery = 'vertical-left';
                    break;
                case 'carousel':
                    $zoo_layout_gallery = 'slider';
                    break;
                case 'grid-thumb':
                    $zoo_layout_gallery = 'grid';
                    break;
                case 'sticky-2':
                    $zoo_layout_gallery = 'vertical-left';
                    break;
                case 'sticky-3':
                    $zoo_layout_gallery = 'sticky';
                    break;
                default:
                    $zoo_layout_gallery = 'horizontal';
            }
        }
        return $zoo_layout_gallery;
    }
}

/**
 * zoo_product_gallery_layout Product gallery layout
 * @return: layout of product gallery
 *
 */
if(!function_exists('zoo_product_gutter')){
    function zoo_product_gutter(){
        $gutter=get_theme_mod('zoo_shop_loop_item_gutter', 30) / 2;
        if (isset($_GET['gutter'])):
            $gutter = $_GET['gutter']/2;
        endif;
        return $gutter;
    }
}
/**3rd plugin support*/
//Vendor hook WCMp
if (class_exists('WCMp')) {
    add_filter('wcmp_sold_by_text_after_products_shop_page', '__return_false');
    function zoo_WCMp_vendor_name()
    {
        global $post;
        if ('Enable' === get_wcmp_vendor_settings('sold_by_catalog', 'general')) {
            $vendor = get_wcmp_product_vendors($post->ID);
            if ($vendor) {
                $sold_by_text = apply_filters('wcmp_sold_by_text', esc_html__('Sold By', 'anon'), $post->ID);
                echo ent2ncr('<a class="zoo-by-vendor-name-link" href="' . $vendor->permalink . '">' . $sold_by_text . ' ' . $vendor->user_data->display_name . '</a>');
            }
        }
    }

    // Add link Register form vendor
    if (!function_exists('clever_register_vendor_url')) {
        function clever_register_vendor_url()
        {
            echo ent2ncr('<p class="vendor-register"><a href="' . esc_url(get_permalink(wcmp_vendor_registration_page_id())) . '"> Create a Vendor account. </a></p>');
        }
    }
    add_action('woocommerce_register_vendor_form', 'clever_register_vendor_url', 10);

    // Get vendor user
    if (!function_exists('get_vendor_user')) {
        function get_vendor_user()
        {
            global $WCMp;
            $ob_vendors = get_wcmp_vendors();
            $vendors = array();
            foreach ($ob_vendors as $key => $value) {
                $id = $value->user_data->data->ID;
                $name = $value->user_data->data->user_login;
                $vendors[]['id'] = $id;
                $vendors[]['name'] = $name;
            }
            return $vendors;
        }
    }
    if (!function_exists('zoo_get_vendor_id')) {
        function zoo_get_vendor_id()
        {
            global $WCMp, $vendor, $wp;
            $vendor_store = get_wcmp_vendor_by_store_url(home_url($wp->request));
            $check_vendor_store = $vendor_store ? $vendor_store->taxonomy : '';
            $vendor_id = false;
            if ($check_vendor_store == 'dc_vendor_shop') {
                $vendor_id = $vendor->id;
            }
            return $vendor_id;
        }
    }
    if (!function_exists('zoo_get_vendor_info')) {
        function zoo_get_vendor_info()
        {
            global $WCMp, $vendor, $wp;
            $vendor_archive_info = array();
            $vendor_archive_info['vendor_id'] = $vendor_archive_info['display_name'] = $vendor_archive_info['profile'] = '';
            $vendor_archive_info['banner'] = $vendor_archive_info['description'] = $vendor_store = $check_vendor_store = '';

            $vendor_store = get_wcmp_vendor_by_store_url(home_url($wp->request));
            $check_vendor_store = $vendor_store ? $vendor_store->taxonomy : '';

            if ($check_vendor_store == 'dc_vendor_shop') {
                $image = $vendor->get_image();
                $allow_htmlddress = '';
                if ($vendor->city) {
                    $address = $vendor->city . ', ';
                }
                if ($vendor->state) {
                    $address .= $vendor->state . ', ';
                }
                if ($vendor->country) {
                    $address .= $vendor->country;
                }
                $vendor_archive_info['vendor_id'] = $vendor->id;
                $vendor_archive_info['display_name'] = $vendor->user_data->data->display_name;
                $vendor_archive_info['profile'] = $image;
                $vendor_archive_info['banner'] = $vendor->get_image('banner');
                $vendor_archive_info['description'] = stripslashes($vendor->description);
                $vendor_archive_info['mobile'] = $vendor->phone;
                $vendor_archive_info['location'] = $address;
                $vendor_archive_info['email'] = $vendor->user_data->user_email;

                $vendor_archive_info['address_1'] = get_user_meta($vendor->id, '_vendor_address_1', true);
                $vendor_archive_info['address_2'] = get_user_meta($vendor->id, '_vendor_address_2', true);
                $vendor_archive_info['social']['fb'] = get_user_meta($vendor->id, '_vendor_fb_profile', true);
                $vendor_archive_info['social']['tw'] = get_user_meta($vendor->id, '_vendor_twitter_profile', true);
                $vendor_archive_info['social']['ld'] = get_user_meta($vendor->id, '_vendor_linkdin_profile', true);
                $vendor_archive_info['social']['gp'] = get_user_meta($vendor->id, '_vendor_google_plus_profile', true);
                $vendor_archive_info['social']['yt'] = get_user_meta($vendor->id, '_vendor_youtube', true);
                $vendor_archive_info['social']['it'] = get_user_meta($vendor->id, '_vendor_instagram', true);
            }
            return $vendor_archive_info;
        }
    }
}

if (!function_exists('check_vendor')) {
    function check_vendor()
    {
        global $WCMp, $vendor, $wp;
        $check_vendor = false;
        if (class_exists('WCMp')) {
            $vendor_store = get_wcmp_vendor_by_store_url(home_url($wp->request));
            $check_vendor_store = $vendor_store ? $vendor_store->taxonomy : '';
            if ($check_vendor_store == 'dc_vendor_shop') {
                $check_vendor = true;
            }
        }
        return $check_vendor;
    }

}
//GDPR Hook
remove_action('register_form', array('GDPR', 'consent_checkboxes'));