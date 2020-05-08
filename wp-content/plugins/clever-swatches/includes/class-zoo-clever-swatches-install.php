<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * class for install or uninstall CleverSwatches.
 *
 * @class    Zoo_Clever_Swatch_Install
 *
 * @version  2.0.0
 * @package  clever-swatches/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if( !class_exists( 'Zoo_Clever_Swatch_Install' ) ) {

    class Zoo_Clever_Swatch_Install {


        public function __construct() {
        }

        public function zoo_cw_active_action() {
            //create new table for global config
            $this->zoo_cw_add_table_for_global_config();

            //install admin config
            $zoo_clever_swatch_config = new Zoo_Clever_Swatch_Config();
            $zoo_clever_swatch_config->zoo_cw_load_global_config();

            //create new table for product attribute swatch
            $this->zoo_cw_add_table_for_attribute_swatch();
        }

        public function zoo_cw_add_table_for_global_config() {
            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_global_config";

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                config_id int(10) NOT NULL AUTO_INCREMENT,
                config_name varchar(256) DEFAULT '' NULL,
                config_value longtext DEFAULT '' NULL,
                PRIMARY KEY  (config_id)
                ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }


        public function zoo_cw_add_table_for_attribute_swatch() {
            global $wpdb;

            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                attribute_id bigint(20) DEFAULT 0 NOT NULL,
                swatch_type varchar(20) DEFAULT '' NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        public function zoo_cw_deactive_action() {

            //remove table for product attribute swatch
        }

    }
}

$zoo_clever_swatch_install = new Zoo_Clever_Swatch_Install();