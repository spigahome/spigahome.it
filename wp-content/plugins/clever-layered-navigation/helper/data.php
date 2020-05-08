<?php

namespace Zoo\Helper\Data;

function zoo_ln_check_woocommerce_active(){

    if ( function_exists('is_multisite') && is_multisite() ){

        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ){

            return true;
        }
        return false;
    }else{

        if ( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ){

            return true;
        }
        return false;
    }
}

function cw_is_valid_numeric_terms_names(array $terms)
{
    if (!$terms) {
        return false;
    }

    $valid = true;

    foreach ($terms as $term) {
        if (!is_numeric($term->name)) {
            $valid = false;
            break;
        }
    }

    return $valid;
}

//db function
function render_shortcode($short_code){
    $short_code = strval($short_code);
    global $wpdb;
    $table_name = $wpdb->prefix . "zoo_ln_filter_list";

    $i = 0;
    do {
        $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE short_code = '$short_code'" );
        if (isset($mylink)) {
            $i++;
            $short_code .= '_'.$i;
        } else break;
    } while ($i <=100);

    return $short_code;
}

function save_filter_item_with_data($data) {

    global $wpdb;
    $table_name = $wpdb->prefix . "zoo_ln_filter_list";

    if (count($data) && isset($data['item_id'])) {
        $id = $data['item_id'];
        $name = $data['item_name'];
        if (isset($data['item_name_old'])) {
            $name_old = $data['item_name_old'];
        } else $name_old = '';

        if ($name_old != $name) {
            $short_code = sanitize_title($name);
            $short_code = render_shortcode($short_code);
            $db_data = array(
                'item_name' => $name,
                'short_code' => $short_code
            );
        } else {
            $db_data = array(
                'item_name' => $name
            );
        }

        $wpdb->update(
            $table_name,
            $db_data,
            array('item_id' => $id)
        );
        return $id;
    } else {
        if (isset($data['item_name'])) {
            $name = $data['item_name'];
        } else {
            $name = 'Item Name';
        }

        $short_code = sanitize_title($name);
        $short_code = render_shortcode($short_code);

        $wpdb->insert(
            $table_name,
            array(
                'item_name' => $name,
                'short_code' => $short_code
            )
        );
        $lastid = $wpdb->insert_id;

        return ($lastid);
    }
}

function get_filter_item_with_id($item_id = null) {
    $value = false;

    if (isset($item_id)) {
        global $wpdb;
        $table_name = $wpdb->prefix . "zoo_ln_filter_list";
        if ($item_id != 0) {
            $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE item_id = '$item_id'" );
        } else {
            $mylink = $wpdb->get_results("SELECT * FROM $table_name");
        }
        if (isset($mylink)) {
            $value = objectToArray($mylink);
        }
    }

    return $value;
}

function get_filter_item_with_short_code($short_code = null) {
    $value = false;

    if (isset($short_code)) {
        global $wpdb;
        $table_name = $wpdb->prefix . "zoo_ln_filter_list";
        $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE short_code = '$short_code'" );
        if (isset($mylink)) {
            $value = objectToArray($mylink);
        }
    }

    return $value;
}

function delete_filter_item_with_id($item_id = null) {
    $value = false;


    if (isset($item_id)) {
        global $wpdb;
        $table_name = $wpdb->prefix . "zoo_ln_filter_list";
        $wpdb->delete( $table_name, array( 'item_id' => $item_id ) );

        $table_name = $wpdb->prefix . "zoo_ln_filter_config";
        $wpdb->delete( $table_name, array( 'item_id' => $item_id ) );

        $table_name = $wpdb->prefix . "zoo_ln_global_config";
        $wpdb->delete( $table_name, array( 'item_id' => $item_id ) );
    }

    $array_get = $_GET;
    unset($array_get['action']);
    unset($array_get['item-id']);
    $array = array();
    foreach ($array_get as $key => $value) {
        $array[] = $key.'='.$value;
    }
    $str_param = implode('&',$array);
    $admin_url = admin_url( 'admin.php').'?'.$str_param;

    wp_redirect($admin_url);
}

function save_global_config_with_name($item_id = null, $name = null, $value = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . "zoo_ln_global_config";

    if (!is_null($item_id) && !is_null($name) && !is_null($value)) {
        $name = (string)$name;
        $value = (string)$value;
        // save config to db
        $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE item_id = '$item_id' AND config_name = '$name'" );
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
                    'item_id' => $item_id,
                    'config_name' => $name,
                    'config_value' => $value
                )
            );
        }
    }
}

function get_global_config_with_name($item_id = null, $name = "") {
    $value = false;

    if (isset($item_id) && $name != '') {

        global $wpdb;
        $table_name = $wpdb->prefix . "zoo_ln_global_config";

        $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE item_id = '$item_id' AND config_name = '$name'" );

        if (isset($mylink)) {
            $value = $mylink->config_value;
        }
    }

    return $value;
}

function save_filter_config_with_name($item_id = null, $name = null, $data = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . "zoo_ln_filter_config";

    if (!is_null($name) && !is_null($name)) {
        $name = (string)$name;
        // save config to db
        $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE filter_item_name = '$name' AND item_id = $item_id" );
        if (isset($mylink)) {
            $filter_id = $mylink->filter_id;
            $wpdb->update(
                $table_name,
                array(
                    'item_id' => $item_id,
                    'filter_column' => $data['filter_column'],
                    'filter_item_name' => $data['filter_item_name'],
                    'filter_item_type' => $data['filter_item_type'],
                    'filter_config_value' => $data['filter_config_value'],
                ),
                array('filter_id' => $filter_id)
            );
        } else {
            $wpdb->insert(
                $table_name,
                array(
                    'item_id' => $item_id,
                    'filter_column' => $data['filter_column'],
                    'filter_item_name' => $data['filter_item_name'],
                    'filter_item_type' => $data['filter_item_type'],
                    'filter_config_value' => $data['filter_config_value'],
                )
            );
        }
    }
}

function get_filter_config_with_name($item_id, $name = "") {
    $data = array();

    if ($name != '') {
        global $wpdb;
        $table_name = $wpdb->prefix . "zoo_ln_filter_config";
        $mylink = $wpdb->get_row( "SELECT * FROM $table_name WHERE filter_item_name = '$name' AND item_id = $item_id" );
        if (isset($mylink)) {
            $data = (array)$mylink;
        }
    }

    return $data;
}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}

function redirect_to_edit_page($array_get, $item_id){
    $array_get['action'] = 'edit';
    $array_get['item-id'] = $item_id;
    $array = array();
    foreach ($array_get as $key => $value) {
        $array[] = $key.'='.$value;
    }
    $str_param = implode('&',$array);
    $admin_url = admin_url( 'admin.php').'?'.$str_param;

    wp_redirect($admin_url);
}
