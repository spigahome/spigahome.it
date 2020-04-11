<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * class for managing gallery images per variation.
 *
 * @class    Zoo_Clever_Swatch_Admin_Variation_Gallery
 *
 * @version  2.0.0
 * @package  clever-swatches/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Config' ) ){
    class Zoo_Clever_Swatch_Config {

        public $general_config = array();

        public function __construct() {
        }

        /**
         * define all necessary constants of the plugin.
         */
        public function define_constants() {

            $plugin_path = dirname(plugin_dir_path( __FILE__ ))."/";
            $file_url = plugin_dir_url( __FILE__ );
            $plugin_url = substr($file_url, 0, strpos($file_url, 'includes'));

            $this->define( 'ZOO_CW_DIRPATH', $plugin_path );
            $this->define( 'ZOO_CW_TEMPLATES_PATH', $plugin_path."templates/" );
            $this->define( 'ZOO_CW_URL', $plugin_url );
            $this->define( 'ZOO_CW_JSPATH', $plugin_url."assets/js/" );
            $this->define( 'ZOO_CW_CSSPATH', $plugin_url."assets/css/" );
            $this->define( 'ZOO_CW_VENDORPATH', $plugin_url."assets/vendor/" );
            $this->define( 'ZOO_CW_GALLERYPATH', $plugin_url."assets/images/" );
            $this->define( 'ZOO_CW_ABSPATH', untrailingslashit( $plugin_path));
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }

        /**
         * Load global config.
         */
        public function zoo_cw_load_global_config() {
            $this->general_config = get_option('zoo-cw-settings',false);

            $this->general_config = array();

            if (!$this->general_config || !count($this->general_config)) {
                //load default config value
                $this->general_config = $this->zoo_cw_get_default_value_global_config();
                foreach ($this->general_config as $name => $value) {
                    //load from db
                    $setting = $this->zoo_cw_get_global_config_with_name($name);
                    if ($setting === false) {
                        $this->zoo_cw_save_global_config_with_name($name, $value);
                    } else {
                        $this->general_config[$name] = $setting;
                    }
                }
                //save to wp_option
                update_option('zoo-cw-settings', $this->general_config);
            }
        }

        public function zoo_cw_save_global_config_with_name($name = null, $value = null) {
            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_global_config";

            if (!is_null($name) && !is_null($name)) {
                $name = (string)$name;
                $value = (string)$value;
                // save config to db
                $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE config_name = '$name'" );
                if (isset($mylink)) {
                    $config_id = $mylink->config_id;

                    $wpdb->update(
                        $table_name,
                        array('config_value' => $value),
                        array('config_id' => $config_id)
                    );
                } else {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'config_name' => $name,
                            'config_value' => $value
                        )
                    );
                }

                // save config to object for cache
                $this->general_config[$name] = $value;
            }
        }

        public function zoo_cw_update_global_config($general_config = array()) {
            if (count($general_config)) {
                global $wpdb;
                $table_name = $wpdb->prefix . "zoo_cw_global_config";

                foreach ($general_config as $name => $value) {
                    $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE config_name = '$name'" );
                    if (isset($mylink)) {
                        $config_id = $mylink->config_id;

                        $wpdb->update(
                            $table_name,
                            array('config_value' => $value),
                            array('config_id' => $config_id)
                        );
                    } else {
                        $wpdb->insert(
                            $table_name,
                            array(
                                'config_name' => $name,
                                'config_value' => $value
                            )
                        );
                    }
                }
                //save to wp database
                update_option('zoo-cw-settings', $general_config);
            }
        }

        public function zoo_cw_get_global_config_with_name($name = "") {
            $value = false;

            if ($name != '') {

                global $wpdb;
                $table_name = $wpdb->prefix . "zoo_cw_global_config";

                $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE config_name = '$name'" );

                if (isset($mylink)) {
                    $value = $mylink->config_value;
                }
            }

            return $value;
        }

        private function zoo_cw_get_default_value_global_config() {
            $general_settings = array();

            $general_settings['tooltip'] = 0;
            $general_settings['swatch'] = 1;
            $general_settings['product_gallery'] = 1;
            $general_settings['cw_gallery'] = 0;
            $general_settings['product_swatch_display_shape'] = 'square';
            $general_settings['product_swatch_display_size'] = 1;
            $general_settings['product_swatch_display_size_width'] = 20;
            $general_settings['product_swatch_display_size_height'] = 20;
            $general_settings['product_swatch_display_name'] = 1;
            $general_settings['product_image_custom_class'] = "";
            $general_settings['display_shop_page'] = 0;
            $general_settings['display_shop_page_hook'] = 'after';
            $general_settings['shop_swatch_display_shape'] = 1;
            $general_settings['shop_swatch_display_size'] = 1;
            $general_settings['display_shop_page_add_to_cart'] = 1;
            $general_settings['display_cart_page'] = 0;
            $general_settings['cart_swatch_display_shape'] = 1;
            $general_settings['cart_swatch_display_size'] = 1;

            return $general_settings;
        }
    }
}

$zoo_clever_swatch_config = new Zoo_Clever_Swatch_Config();
$zoo_clever_swatch_config->define_constants();
?>
