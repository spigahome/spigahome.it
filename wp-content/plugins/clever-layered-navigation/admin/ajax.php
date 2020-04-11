<?php
/**
 */

namespace Zoo\Admin\Ajax;

function zoo_ln_save_filter()
{

    $post_data = $_POST;

    if ($post_data['add_new'] == 'multi') {
        $post_data['item-id'] = $post_data['id_base'] . '-' . $post_data['multi_number'];
    }

    $item_list_id = $post_data['item_list_id'];
    $filter_item_name = (string)$post_data['item-id'];
    $filter_item_type = (string)$post_data['item-type'];
    $filter_config_value = json_encode($post_data[$filter_item_type]);
    $filter_column = (string)$post_data['sidebar'];

    $data = array(
        'filter_column' => $filter_column,
        'filter_item_name' => $filter_item_name,
        'filter_item_type' => $filter_item_type,
        'filter_config_value' => $filter_config_value
    );


    \Zoo\Helper\Data\save_filter_config_with_name($item_list_id, $filter_item_name, $data);

    wp_send_json($post_data);

    wp_die();
}

function zoo_ln_save_filter_order()
{

    if (isset($_POST['zoo_ln_nonce_setting']) && wp_verify_nonce($_POST['zoo_ln_nonce_setting'], 'filter_setting')) {
        // process form data
        $post_data = $_POST;
        $data = json_encode($post_data['sidebars']);
        $item_id = $_POST['item_list_id'];

        \Zoo\Helper\Data\save_global_config_with_name($item_id, 'filter-setting-order', $data);
    }
    $data = array(
        'result' => 'done'
    );

    wp_send_json($data);

    wp_die();
}
