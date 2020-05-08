<?php
/**
 */

namespace Zoo\Admin\Hook;

//add hook load admin template

//add hook
register_activation_hook( ZOO_LN_DIRPATH.'clever-layered-navigation.php', 'Zoo\Admin\Hook\active_action' );
add_action( 'admin_menu', 'Zoo\Admin\Hook\register_zoo_ln_menu_page' );

//admin ajax hook
add_action( 'wp_ajax_zoo_ln_save_filter','Zoo\Admin\Ajax\zoo_ln_save_filter' );
add_action( 'wp_ajax_nopriv_zoo_ln_save_filter', 'Zoo\Admin\Ajax\zoo_ln_save_filter' );

add_action( 'wp_ajax_zoo_ln_save_filter_order','Zoo\Admin\Ajax\zoo_ln_save_filter_order' );
add_action( 'wp_ajax_nopriv_zoo_ln_save_filter_order', 'Zoo\Admin\Ajax\zoo_ln_save_filter_order' );

//functions

function register_zoo_ln_menu_page(){
    add_menu_page(__( 'Clever Layered Navigation', 'clever-layered-navigation' ), __( 'Clever Layered Navigation', 'clever-layered-navigation' ), 'manage_woocommerce', 'zoo-ln-settings', null,ZOO_LN_GALLERYPATH.'cln.png',54.560 );
    $hook = add_submenu_page( 'clever-layered-navigation.php', __( 'All Preset Filter', 'clever-layered-navigation' ),  __( 'All Preset Filter', 'clever-layered-navigation' ) , 'manage_woocommerce', 'zoo-ln-settings', 'Zoo\Admin\Hook\settings_callback' );
    add_action( "load-$hook", 'Zoo\Admin\Hook\add_options' );
}

function add_options() {

    if ($_GET['page'] == 'zoo-ln-settings' && !isset($_GET['action'])) {
        global $myListTable;

        require ZOO_LN_DIRPATH.'admin/list-table.php';
        $myListTable = new \Zoo_Ln_Filter_List_Table();
    }
}
function zoo_plugin_ver()
{
    $zoo_cw_plugin_data = get_plugin_data(ZOO_LN_DIRPATH . '/clever-layered-navigation.php');
    return $zoo_cw_plugin_data['Version'];
}

function settings_callback(){

    if ($_GET['page'] == 'zoo-ln-settings' && isset($_GET['action']) && $_GET['action'] == 'delete') {
        $item_id = $_GET['item-id'];
        \Zoo\Helper\Data\delete_filter_item_with_id($item_id);
    }else if ($_GET['page'] == 'zoo-ln-settings' && isset($_GET['action'])) {
        require ZOO_LN_DIRPATH.'admin/setting.php';
    } else {
        global $myListTable;

        $list_data = array();
        $filter_items = \Zoo\Helper\Data\get_filter_item_with_id(0);
        foreach ($filter_items as $item) {
            $list_data[] = array(
                'id' => $item['item_id'],
                'name' => $item['item_name'],
                'short_code' => $item['short_code']
            );
        }

        $myListTable->list_data = $list_data;

        echo '<div class="wrap"><div class="zoo-ln-wrap-head-page"><h2><img src="'.ZOO_LN_GALLERYPATH.'cln-40.png'.'"/><span>'.esc_html__('Clever Layered Navigation','clever-layered-navigation').'<i>'.esc_html__('Version','clever-layered-navigation').' '.zoo_plugin_ver().'</i></span></h2>';
        echo '<a href="?page='.$_REQUEST['page'].'&action=add-new" class="page-title-action">'.esc_html__('Add New','clever-layered-navigation').'</a></div>';
        $myListTable->prepare_items();
        ?>
        <form method="post">
            <input type="hidden" name="page" value="">
        <?php
        $myListTable->display();
        echo '</form></div>';
    }

}

function active_action() {
    require_once ZOO_LN_DIRPATH.'admin/install.php';
}
