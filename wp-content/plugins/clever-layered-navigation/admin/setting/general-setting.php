<?php
/**
 */

namespace Zoo\Admin\Setting\GeneralSetting;

save_setting();
$data = prepare_data();

require_once ZOO_LN_TEMPLATES_PATH.'admin/general-setting.php';


function save_setting() {
    if ( isset( $_POST['zoo_ln_nonce_setting'] ) && wp_verify_nonce( $_POST['zoo_ln_nonce_setting'], 'general_setting' ) ) {
        $item_list_data = array(
            'item_name' => $_POST['item_name'],
            'item_name_old' => $_POST['item_name_old']
        );

        if (isset($_GET['action']) && $_GET['action'] == 'add-new') {
            $item_id = \Zoo\Helper\Data\save_filter_item_with_data($item_list_data);
        }else if (isset($_GET['action']) && $_GET['action'] == 'edit') {
            $item_list_data['item_id'] = $_GET['item-id'];
            $item_id = \Zoo\Helper\Data\save_filter_item_with_data($item_list_data);
        }

        // process form data
        $post_data = $_POST;
        unset($post_data['zoo_ln_nonce_setting']);
        unset($post_data['_wp_http_referer']);
        $data = json_encode($post_data);
        \Zoo\Helper\Data\save_global_config_with_name($item_id,'general-setting', $data);

        \Zoo\Helper\Data\redirect_to_edit_page($_GET, $item_id);
    }
}

function prepare_data() {
    $item_id = 0;
    if (isset($_GET['item-id'])) {
        $item_id = $_GET['item-id'];
    }

    $raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id,'general-setting');
    $data = (array)json_decode($raw_data);

    if ($item_id != 0) {
        $ssss = \Zoo\Helper\Data\get_filter_item_with_id($item_id);
        $data['item_id'] = $ssss['item_id'];
        $data['item_name'] = $ssss['item_name'];
    }

    return ($data);
}
