<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * class for managing gallery images per variation.
 *
 * @class    Zoo_Clever_Swatch_Cart_Page
 *
 * @version  2.0.0
 * @package  clever-swatches/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Cart_Page' ) ) {

    class Zoo_Clever_Swatch_Cart_Page {

        public function __construct()
        {
            $general_settings = get_option('zoo-cw-settings', true);
            if($general_settings['display_cart_page']==1) {
                add_filter('woocommerce_is_attribute_in_product_name', '__return_false');
                add_filter('woocommerce_get_item_data', array($this, 'zoo_wc_prepare_cart_item_swatch_option_data'), 10, 2);
                add_filter('woocommerce_locate_template', array($this, 'load_template'), 10, 3);
            }
        }

        private function get_attribute_term_translation_in_current_language($attribute_taxonomy, $attribute_value, $type, $value){
            if( taxonomy_exists( $attribute_taxonomy ) ){
                $term = get_term_by( 'slug', $attribute_value, $attribute_taxonomy );
                return array('slug' => $term->slug , $type => $value);
            }

            return false;
        }

        private function getSwatchesDataTranslated($data){
            $temp = $data;
            foreach ($data as $key=>&$d) {
                foreach ($d['options_data'] as $k=>&$dt) {
                    $display_type=$d['display_type']=='default'?'select':$d['display_type'];
                    $new_opt_translated = $this->get_attribute_term_translation_in_current_language($key, $k, $display_type, $dt[$display_type]);
                    if($new_opt_translated) {
                        $temp[$key]['options_data'][$new_opt_translated['slug']][$display_type] = $new_opt_translated[$display_type];
                        unset($temp[$key]['options_data'][$k]);
                    }
                }
            }
            return $temp;
        }

        public function zoo_wc_prepare_cart_item_swatch_option_data($item_data, $cart_item) {
            $general_settings = get_option('zoo-cw-settings', true);
            if (count($cart_item['variation'])) {
                $product_swatch_data_array = get_post_meta($cart_item['product_id'], 'zoo_cw_product_swatch_data', true);
                $product_swatch_data_array_translated = $this->getSwatchesDataTranslated($product_swatch_data_array);
                $variations = $cart_item['variation'];
                foreach ($product_swatch_data_array as $attribute_name => $data) {
                    $attribue_key = wc_variation_attribute_name($attribute_name);
                    $j = 0;
                    foreach ($variations as $name => $value) {
                        if ($name == $attribue_key) {
                            $label = $product_swatch_data_array[$attribute_name]['label'];
                            $display_type = $product_swatch_data_array[$attribute_name]['display_type']=='default'?'select':$product_swatch_data_array[$attribute_name]['display_type'];
                            $display_size = $product_swatch_data_array[$attribute_name]['product_swatch_display_size'];
                            $display_shape = $product_swatch_data_array[$attribute_name]['product_swatch_display_shape'];
                            $swatch_value = $product_swatch_data_array[$attribute_name]['options_data'][$value][$display_type] ? $product_swatch_data_array[$attribute_name]['options_data'][$value][$display_type] : $product_swatch_data_array_translated[$attribute_name]['options_data'][$value][$display_type] ;
                            $display_class = '';
                            if ($display_type == "image" || $display_type == "color") {
                                $display_class .= ' zoo-cw-type-' . $display_type;
                            }
                            if ($display_size == 'default') {
                                $display_size = $general_settings['cart_swatch_display_size'];
                            }
                            $custom_style = '';
                            if ($display_size == 'custom') {
                                $custom_style = 'style="min-width: ' . $general_settings['cart_swatch_display_size_width'] . 'px;height: ' . $general_settings['cart_swatch_display_size_height'] . 'px;"';
                            }
                            if ($display_shape == 'default') {
                                $display_shape = $general_settings['cart_swatch_display_shape'];
                            }
                            $display_class .= ' zoo-cw-option-display-size-' . $display_size;
                            $display_class .= ' zoo-cw-option-display-shape-' . $display_shape;
//                            foreach ($item_data as $index => $item) {
//                                if (strtolower($item['key']) == strtolower($label)) {
                            $item_data[$j]['display_type'] = $display_type;
                            $item_data[$j]['display_size'] = $display_size;
                            $item_data[$j]['display_shape'] = $display_shape;
                            $item_data[$j]['swatch_value'] = $swatch_value;
                            $item_data[$j]['display_class'] = $display_class;
                            $item_data[$j]['custom_style'] = $custom_style;
//                                }
//                            }
                        }
                        $j++;
                    }
                }
            }
            return $item_data;
        }

        public function load_template($template, $template_name, $template_path)
        {
            if ($template_name == 'cart/cart-item-data.php') {
                $general_settings = get_option('zoo-cw-settings', true);
                if (is_array($general_settings) && $general_settings['swatch'] == 1) {
                    add_filter('body_class', 'zoo_cw_body_class');
                    if (!function_exists('zoo_cw_body_class')) {
                        function zoo_cw_body_class($classes)
                        {
                            $classes[] = 'zoo-cw-page';
                            return $classes;
                        }
                    }
                }
                if (!is_array($general_settings) || $general_settings['swatch'] == 0) {
                    return $template;
                }

                global $woocommerce;

                $_template = $template;
                if (!$template_path) $template_path = $woocommerce->template_url;
                $plugin_path = ZOO_CW_TEMPLATES_PATH . 'woocommerce/';

                // check the template is available in theme or not.
                $template = locate_template(
                    array(
                        $template_path . $template_name,
                        $template_name
                    )
                );
                // check that the template is there in plugin or not.
                if (file_exists($plugin_path . $template_name))
                    $template = $plugin_path . $template_name;

                // return the default template.
                if (!$template)
                    $template = $_template;
            }

            // replace with our plugin template.
            return $template;
        }
    }

}

$zoo_clever_swatch_cart_page = new Zoo_Clever_Swatch_Cart_Page();