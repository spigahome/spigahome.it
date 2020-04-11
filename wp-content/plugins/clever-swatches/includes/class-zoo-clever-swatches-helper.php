<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main Plugin class for managing admin interfaces.
 *
 * @class    Zoo_Clever_Swatch_Helper
 *
 * @version  1.0.0
 * @package  clever-swatches/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Helper' ) ){

    class Zoo_Clever_Swatch_Helper {

        public function get_display_type_by_attribute_taxonomy_name( $taxonomy_name) {
            //get attribute id by taxonomy name
            $attribute_id = wc_attribute_taxonomy_id_by_name($taxonomy_name);

            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE attribute_id = $attribute_id" );

            $display_type = '';
            if (isset($mylink)) {
                $display_type = $mylink->swatch_type;
            }

            return $display_type;
        }

        public function get_default_value_of_attribute_option($term) {
            $default_array = array();

            $id = $term->term_id;
            $default_array['display_type'] = $this->get_display_type_by_attribute_taxonomy_name( $term->taxonomy );
            $default_array['default_color'] = get_woocommerce_term_meta( $id, 'slctd_clr', true );
            $default_image = get_woocommerce_term_meta( $id, 'slctd_img', true );
            if(empty($default_image))
                $default_image = wc_placeholder_img_src();

            $default_array['default_image'] = $default_image;

            return $default_array;
        }

        public function zoo_cw_get_all_options_data_by_attribute_name($product) {
            $attributes = $product->get_variation_attributes();
            $available_variations = $product->get_available_variations();
            $all_attributes = $product->get_attributes();

            foreach ($available_variations as $item){
                $variation = $item['attributes'];
                foreach ($variation as $key => $value) {
                    if ($value == "") {
                        $attribute_name = str_replace("attribute_", "", $key);
                        $option_array = array();
                        $current_attribute = isset($all_attributes[$attribute_name])? $all_attributes[$attribute_name]:false;
                        if($current_attribute) {
                            if ($current_attribute->is_taxonomy()) {
                                $args = array(
                                    'orderby' => 'name',
                                    'hide_empty' => 0,
                                );
                                $all_terms = get_terms($current_attribute->get_taxonomy(), apply_filters('woocommerce_product_attribute_terms', $args));

                                foreach ($all_terms as $term) {
                                    $option_array[] = $term->slug;
                                }
                            } else {
                                $option_array = $current_attribute['data']['options'];
                            }
                        }
                        //check if isset in attribute
                        $is_in = false;
                        foreach ($attributes as $attribute_key => $attribute_value) {
                            if ($attribute_name == sanitize_title($attribute_key)) {
                                $is_in = true;
                                $attributes[$attribute_key] = $option_array;
                            }
                        }
                        if (!$is_in) {
                            $attributes[$attribute_name] = $option_array;
                        }

                    }
                }
            }
            return $attributes;
        }

        public function zoo_cw_check_hook_exits($hook_name) {
            global $wp_filter;
            return isset($wp_filter[$hook_name]);
        }
    }
}

$zoo_cw_helper =  new Zoo_Clever_Swatch_Helper();

