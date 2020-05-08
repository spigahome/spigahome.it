<?php
/**
 */

namespace Zoo\Admin\Setting\FilterStyle;

save_setting();
$data = prepare_data();

require_once ZOO_LN_TEMPLATES_PATH.'admin/filter-style.php';


function save_setting() {
    if ( isset( $_POST['zoo_ln_nonce_setting'] ) && wp_verify_nonce( $_POST['zoo_ln_nonce_setting'], 'filter_style' ) ) {

        if (isset($_GET['action']) && $_GET['action'] == 'add-new') {
            $item_id = \Zoo\Helper\Data\save_filter_item_with_data(array());
        } else {
            $item_id = $_GET['item-id'];
        }

        // process form data
        $post_data = $_POST;
        unset($post_data['zoo_ln_nonce_setting']);
        unset($post_data['_wp_http_referer']);
        $data = json_encode($post_data);
        \Zoo\Helper\Data\save_global_config_with_name($item_id,'filter-style', $data);

        if (isset($_GET['action']) && $_GET['action'] == 'add-new') {
            \Zoo\Helper\Data\redirect_to_edit_page($_GET, $item_id);
        }
    }
}

function prepare_data() {
    $item_id = 0;
    if (isset($_GET['item-id'])) {
        $item_id = $_GET['item-id'];
    }

    $raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id,'filter-style');
    $data = (array)json_decode($raw_data);

    if ($item_id != 0) {
        $ssss = \Zoo\Helper\Data\get_filter_item_with_id($item_id);
        $data['item_id'] = $ssss['item_id'];
    }

    return ($data);
}
