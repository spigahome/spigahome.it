<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin class for managing admin interfaces.
 *
 * @class    Zoo_Clever_Swatch_Admin_Manager
 *
 * @version  2.0.0
 * @package  clever-swatches/includes
 * @category Class
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

require_once ZOO_CW_DIRPATH.'includes/class-zoo-clever-swatches-admin-variation-gallery.php';

if (!class_exists('Zoo_Clever_Swatch_Admin_Manager')) {
    class Zoo_Clever_Swatch_Admin_Manager
    {
        private static $_instance;

        public static function getInstance()
        {
            if (!self::$_instance instanceof self) {
                self::$_instance = new self;
            }

            return self::$_instance;
        }

        public function __construct()
        {
            add_action('admin_enqueue_scripts', array($this,'zoo_cw_enqueue_product_page_scripts'));

            $zoo_cw_variation_gallery_manager = Zoo_Clever_Swatch_Admin_Variation_Gallery::getInstance();

            //adding tab in admin menu.
            add_action('admin_menu', array($this, 'zoo_cw_add_settings_page'));

            //adding tab for variation swatch images.
            add_action('woocommerce_product_data_tabs', array( $this ,'zoo_cw_variation_product_tab' )) ;

            //custom tab fields.
            add_action('woocommerce_product_data_panels', array( $this ,'zoo_cw_customTabFields' )) ;

            //saving custom tab data..
            add_action('woocommerce_process_product_meta', array( $this ,'zoo_cw_saveCustomTabFields' ));

            $attribute_taxonomies = $this->wc_get_attribute_taxonomies();

            if (! empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $attribute) {
                    add_action('pa_' . $attribute->attribute_name . '_add_form_fields', array( $this, 'zoo_cw_add_image_color_selector' ));
                    add_filter('manage_edit-'.'pa_' . $attribute->attribute_name.'_columns', array( $this,'zoo_cw_add_preview_column'));
                    add_filter('manage_pa_' . $attribute->attribute_name.'_custom_column', array( $this,'zoo_cw_preview_column_content'), 10, 3);
                    add_action('pa_' . $attribute->attribute_name .'_edit_form_fields', array( $this, 'zoo_cw_edit_attr_fields' ), 10);
                }
            }

            //add attribute
            add_filter('woocommerce_after_add_attribute_fields', array( $this,'zoo_cw_add_attribute_image_color_selector'), 10, 3);
            add_action('woocommerce_attribute_added', array( $this, 'zoo_wc_process_add_attribute_swatch_type' ), 10, 3);

            //edit attribute
            add_filter('woocommerce_after_edit_attribute_fields', array( $this,'zoo_cw_edit_attribute_image_color_selector'), 10, 3);
            add_action('woocommerce_attribute_updated', array( $this, 'zoo_wc_process_edit_attribute_swatch_type' ), 10, 3);

            //delete attribute
            add_action('woocommerce_attribute_deleted', array( $this, 'zoo_wc_process_delete_attribute_swatch_type' ), 10, 3);

            add_action('created_term', array( $this, 'zoo_cw_save_attr_extra_fields' ), 10, 3);
            add_action('edit_term', array( $this, 'zoo_cw_save_attr_extra_fields' ), 10, 3);

            //custom tab fields.
            add_action("wp_ajax_zoo_cw_update_term_data", array( $this ,'zoo_cw_customTabFields' ));
        }

        public function zoo_wc_process_edit_attribute_swatch_type($attribute_id, $attribute = null, $old_attribute_name = null)
        {
            global $wpdb;

            $attribute_id = (int)$attribute_id;
            $display_type = (string)$_POST['zoo_cw_swatch_display_type'];

            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $mylink = $wpdb->get_row("SELECT * FROM $table_name WHERE attribute_id = '$attribute_id'");

            if (isset($mylink)) {
                $id = $mylink->id;
                $wpdb->update(
                    $table_name,
                    array(
                        'swatch_type' => $display_type
                    ),
                    array( 'id' => $id )
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'attribute_id' => $attribute_id,
                        'swatch_type' => $display_type
                    )
                );
            }
        }

        public function zoo_wc_process_add_attribute_swatch_type($attribute_id, $attribute = null)
        {
            global $wpdb;

            $attribute_id = (int)$attribute_id;
            $display_type = (string)(isset($_POST['zoo_cw_swatch_display_type'])?$_POST['zoo_cw_swatch_display_type']:'select');

            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $wpdb->insert(
                $table_name,
                array(
                    'attribute_id' => $attribute_id,
                    'swatch_type' => $display_type
                )
            );
        }

        public function zoo_wc_process_delete_attribute_swatch_type($attribute_id, $attribute_name = null, $taxonomy = null)
        {
            global $wpdb;

            $attribute_id = (int)$attribute_id;

            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $wpdb->delete(
                $table_name,
                array( 'attribute_id' => $attribute_id )
            );
        }

        public function zoo_cw_add_attribute_image_color_selector()
        {
            include ZOO_CW_TEMPLATES_PATH.'admin/attribute/add-swatch-display-type-selector.php';
        }

        public function zoo_cw_edit_attribute_image_color_selector()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";
            $attribute_id = absint($_GET['edit']);

            $mylink = $wpdb->get_row("SELECT * FROM $table_name WHERE attribute_id = $attribute_id");

            $selected_type = '';

            if (isset($mylink)) {
                $selected_type = $mylink->swatch_type;
            }

            include ZOO_CW_TEMPLATES_PATH.'admin/attribute/edit-swatch-display-type-selector.php';
        }

        public function wc_get_attribute_taxonomies()
        {
            if (false === ($attribute_taxonomies = get_transient('wc_attribute_taxonomies'))) {
                global $wpdb;

                $attribute_taxonomies = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;");

                set_transient('wc_attribute_taxonomies', $attribute_taxonomies);
            }

            return (array) array_filter(apply_filters('woocommerce_attribute_taxonomies', $attribute_taxonomies));
        }

        private function get_display_type_by_attribute_taxonomy_name($taxonomy_name)
        {
            //get attribute id by taxonomy name
            $attribute_id = wc_attribute_taxonomy_id_by_name($taxonomy_name);

            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $mylink = $wpdb->get_row("SELECT * FROM $table_name WHERE attribute_id = $attribute_id");

            $display_type = '';
            if (isset($mylink)) {
                $display_type = $mylink->swatch_type;
            }

            return $display_type;
        }

        /**
         * adding attribute fields for swatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_edit_attr_fields($term)
        {
            $id = $term->term_id;
            $display_type = $this->get_display_type_by_attribute_taxonomy_name($term->taxonomy);
            $color_code = get_woocommerce_term_meta($id, 'slctd_clr', true);
            $image = get_woocommerce_term_meta($id, 'slctd_img', true);
            if (empty($image)) {
                $image = wc_placeholder_img_src();
            }

            require_once ZOO_CW_TEMPLATES_PATH.'admin/attribute/option/edit-default-swatch-option.php';
        }

        /**
         * saving added attribute fields for swatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_save_attr_extra_fields($term_id, $tt_id = '', $taxonomy = '')
        {
            //get attribute id by taxonomy name
            $attribute_id = wc_attribute_taxonomy_id_by_name($taxonomy);

            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $mylink = $wpdb->get_row("SELECT * FROM $table_name WHERE attribute_id = $attribute_id");

            $display_type = '';
            if (isset($mylink)) {
                $display_type = $mylink->swatch_type;

                if ($display_type == 'color') {
                    update_woocommerce_term_meta($term_id, 'slctd_clr', $_POST['zoo_cw_slctdclr']);
                } elseif ($display_type == 'image') {
                    update_woocommerce_term_meta($term_id, 'slctd_img', $_POST['zoo-cw-selected-attr-img']);
                }
            }
        }

        /**
         * displaying table content on attribute edit for swatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_preview_column_content($columns, $column, $id)
        {
            if ('thumb' == $column) {
                $term = get_term_by('term_taxonomy_id', $id, 'category');
                $display_type = $this->get_display_type_by_attribute_taxonomy_name($term->taxonomy);

                if ($display_type == 'color') {
                    $color_code = get_woocommerce_term_meta($id, 'slctd_clr', true);

                    $columns .= '<div style="height:48px; width:48px; background-color:'.$color_code.';" ></div>';
                } elseif ($display_type == 'image') {
                    $img_url = get_woocommerce_term_meta($id, 'slctd_img', true);
                    if ($img_url) {
                        $image = $img_url;
                    } else {
                        $image = wc_placeholder_img_src();
                    }
                    $image = str_replace(' ', '%20', $image);

                    $columns .= '<img src="' . esc_url($image) . '" alt="' . __('Thumbnail', 'clever-swatches') . '" class="zoo-cw-attr-thumb" height="48" width="48" />';
                }
            }
            return $columns;
        }

        /**
         * adding table column on attribute listing for swatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_add_preview_column($columns)
        {
            $new_columns = array();

            if (isset($columns['cb'])) {
                $new_columns['cb'] = $columns['cb'];
                unset($columns['cb']);
            }

            $new_columns['thumb'] = __('Image', 'clever-swatches');

            return array_merge($new_columns, $columns);
        }

        /**
         * adding custom fields on attribute term editing for swatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_add_image_color_selector($taxonomy)
        {
            //get attribute id by taxonomy name
            $attribute_id = wc_attribute_taxonomy_id_by_name($taxonomy);


            global $wpdb;
            $table_name = $wpdb->prefix . "zoo_cw_product_attribute_swatch_type";

            $mylink = $wpdb->get_row("SELECT * FROM $table_name WHERE attribute_id = $attribute_id");

            $display_type = '';

            if (isset($mylink)) {
                $display_type = $mylink->swatch_type;
            }

            require ZOO_CW_TEMPLATES_PATH.'admin/attribute/option/add-default-swatch-option.php';
        }

        /**
         * settings page of variation manager.
         *
         * @since 1.0.0
         */
        public function zoo_cw_add_settings_page()
        {
            add_menu_page('CleverSwatches', 'CleverSwatches', 'manage_woocommerce', 'zoo-cw-settings', array($this, 'zoo_cw_settings_callback'), ZOO_CW_GALLERYPATH.'cleverswatch.png', 30);
        }

        /**
         * callback function of settings page of CleverSwatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_settings_callback()
        {
            require_once ZOO_CW_TEMPLATES_PATH.'admin/zoo-clever-swatches-settings-page.php';
        }

        /**
         * loading conditional admin scripts.
         *
         * @since 1.0.0
         */
        public function zoo_cw_enqueue_product_page_scripts()
        {
            global $wp_query, $post;

            $screen       = get_current_screen();
            $screen_id    = $screen ? $screen->id : '';
            $wc_screen_id = sanitize_title(__('WooCommerce', 'woocommerce'));
            $suffix       = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
            $screen_page = $screen ? $screen->base : '';

            if ($screen_page == 'toplevel_page_zoo-cw-settings') {
                wp_enqueue_style('zoo-clever-swatches-style', ZOO_CW_CSSPATH. 'admin/clever-swatches-style.css');
            }

            //condition for the product edit page only.
            if (in_array($screen_id, array( 'product', 'edit-product' ))) {
                wp_enqueue_style('wp-color-picker');
                wp_register_script('zoo-cw-product-edit', ZOO_CW_JSPATH . 'admin/product-edit-page-custom-js.js', array( 'jquery','wp-color-picker' ), ZOO_CW_VERSION);
                wp_localize_script('zoo-cw-product-edit', 'zoo_cw_params', array( 'ajax_url' => admin_url('admin-ajax.php') ));
                wp_enqueue_script('zoo-cw-product-edit');

                wp_enqueue_style('zoo-cw-product-edit-style', ZOO_CW_CSSPATH. 'admin/zoo-cw-product-edit-style.css');
            }

            if (in_array($screen_page, array( 'edit-tags' )) || in_array($screen_page, array( 'term'))) {
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_media();

                wp_register_script('zoo-cw-tags-edit', ZOO_CW_JSPATH . 'admin/edit-tags.js', array( 'jquery','wp-color-picker' ), ZOO_CW_VERSION);
                wp_enqueue_script('zoo-cw-tags-edit');
            }
            wp_register_script('zoo-cw-global-config', ZOO_CW_JSPATH . 'admin/global-config.js', array( 'jquery'), ZOO_CW_VERSION);
            wp_enqueue_script('zoo-cw-global-config');
            if ($screen_id == "toplevel_page_zoo-cw-settings") {
                wp_enqueue_style('wp-color-picker');
            }
        }

        /**
         * adding tab for variable products.
         *
         * @since 1.0.0
         */
        public function zoo_cw_variation_product_tab($tabs)
        {
            $general_settings = get_option('zoo-cw-settings', true);

            if (is_array($general_settings) && $general_settings['swatch'] != 0) {
                $tabs['swatches'] = array(
                    'label'  => __('CleverSwatches', 'clever-swatches'),
                    'target' => 'zoo-cw-variation-swatch-data',
                    'class'  => array( 'show_if_variable','zoo-cw-term-swatches' ),
                );
            }

            return $tabs;
        }

        /**
         * tab fields for swatches for variable products.
         *
         * @since 1.0.0
         */
        public function zoo_cw_customTabFields()
        {
            if (is_ajax()) {
                if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
                    $post_id = $_POST['post_id'];
                    $post = get_post($post_id);
                }
            } else {
                global $post;
            }

            $_product 		= 	wc_get_product($post->ID);

            if ($_product->is_type('variable')) {
                require_once ZOO_CW_TEMPLATES_PATH.'admin/product-page-custom-tab-fields.php';
            } else {
                // not an variable product.....
            }
            if (is_ajax()) {
                exit;
            }
        }

        /**
         * saving custom fields of variable products for swatches.
         *
         * @since 1.0.0
         */
        public function zoo_cw_saveCustomTabFields($post_id)
        {
            $zoo_cw_helper =  new Zoo_Clever_Swatch_Helper();
            $_product = wc_get_product($post_id);
            $zoo_cw_swatch_array = array();
            if ($_product->is_type('variable')) {
                $attributes = $zoo_cw_helper->zoo_cw_get_all_options_data_by_attribute_name($_product);
                if (is_array($attributes)) {
                    if ('draft' === $_product->status) {
                        $t_args = ['menu_order' => 'ASC', 'hide_empty' => false];
                    } else {
                        $t_args = ['menu_order' => 'ASC'];
                    }
                    foreach ($attributes as $attribute_name => $options) {
                        $tmp_attr_data_array = array();
                        $attribute_name = sanitize_title($attribute_name);
                        $attrName =  $attribute_name;
                        $tmp_attr_data_array['label'] = isset($_POST["zoo_cw_label_$attrName"])&& !empty($_POST["zoo_cw_label_$attrName"]) ?  $_POST["zoo_cw_label_$attrName"] : wc_attribute_label($attribute_name) ;

                        $default_display_type = $zoo_cw_helper->get_display_type_by_attribute_taxonomy_name($attribute_name);
                        $display_type = isset($_POST["zoo_cw_display_type_$attrName"]) ? $_POST["zoo_cw_display_type_$attrName"] : $default_display_type ;
                        $tmp_attr_data_array['display_type'] = $display_type;
                        $tmp_attr_data_array['product_swatch_display_size'] = isset($_POST["zoo_cw_product_swatch_display_size_$attrName"]) ? $_POST["zoo_cw_product_swatch_display_size_$attrName"] : 'default' ;
                        $tmp_attr_data_array['product_swatch_display_shape'] = isset($_POST["zoo_cw_product_swatch_display_shape_$attrName"]) ? $_POST["zoo_cw_product_swatch_display_shape_$attrName"] : 'default' ;
                        if ($display_type == 'text') {
                            $tmp_attr_data_array['product_swatch_display_name_yn'] = 0 ;
                        } else {
                            $tmp_attr_data_array['product_swatch_display_name_yn'] = isset($_POST["zoo_cw_product_swatch_display_name_$attrName"]) ?  $_POST["zoo_cw_product_swatch_display_name_$attrName"]  : 'default' ;
                        }

                        if (is_array($options)) {
                            $tmp_option_array = array();
                            if (taxonomy_exists($attribute_name)) {
                                $terms = get_terms($attribute_name, $t_args);
                                foreach ($terms as $term) {
                                    if (in_array($term->slug, $options)) {
                                        $default_value = $zoo_cw_helper->get_default_value_of_attribute_option($term);
                                        $tmp_term_name =  $term->slug;
                                        if ($display_type == 'image') {
                                            $tmp_option_array[$tmp_term_name]['image'] = isset($_POST["zoo-cw-input-scimg-$tmp_term_name"]) ? sanitize_text_field($_POST["zoo-cw-input-scimg-$tmp_term_name"]) : $default_value['default_image'] ;
                                        } elseif ($display_type == 'color') {
                                            $tmp_option_array[$tmp_term_name]['color'] = isset($_POST["zoo_cw_slctclr_$tmp_term_name"]) ? sanitize_text_field($_POST["zoo_cw_slctclr_$tmp_term_name"]) : $default_value['default_color'] ;
                                        } elseif ($display_type == 'text') {
                                            $tmp_option_array[$tmp_term_name]['text'] = $term->name;
                                        } elseif ($display_type == 'default') {
                                            $tmp_option_array[$tmp_term_name]['select'] = $term->name;
                                        }
                                    }
                                }
                            } else {
                                foreach ($options as $option) {
                                    $tmp_term_name =  sanitize_title($option);
                                    $tmp_option_array[$tmp_term_name]['name']=$option;
                                    $tmp_option_array[$tmp_term_name]['value']=$option;
                                    if ($display_type == 'image') {
                                        $tmp_option_array[$tmp_term_name]['image'] = isset($_POST["zoo-cw-input-scimg-$tmp_term_name"]) ? sanitize_text_field($_POST["zoo-cw-input-scimg-$tmp_term_name"]) : '' ;
                                    } elseif ($display_type == 'color') {
                                        $tmp_option_array[$tmp_term_name]['color'] = isset($_POST["zoo_cw_slctclr_$tmp_term_name"]) ? sanitize_text_field($_POST["zoo_cw_slctclr_$tmp_term_name"]) : '' ;
                                    } elseif ($display_type == 'text') {
                                        $tmp_option_array[$tmp_term_name]['text'] = $option ;
                                    } elseif ($display_type == 'default') {
                                        $tmp_option_array[$tmp_term_name]['default'] = $option ;
                                    }
                                }
                            }
                            $tmp_attr_data_array['options_data'] = $tmp_option_array;
                        }
                        $zoo_cw_swatch_array[$attrName] = $tmp_attr_data_array;
                    }
                } else {
                    //not available attributes..
                }
                update_post_meta($post_id, 'zoo_cw_product_swatch_data', $zoo_cw_swatch_array);
            } else {
                // not an variable product.....
            }
        }
    }
}

$zoo_cw_admin_manager_object = new Zoo_Clever_Swatch_Admin_Manager();
