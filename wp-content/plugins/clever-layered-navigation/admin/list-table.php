<?php
/**
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Zoo_Ln_Filter_List_Table extends WP_List_Table {
    var $list_data = array();
    var $found_data = array();

    function __construct(){
        global $status, $page;
        parent::__construct( array(
            'singular'  => __( 'Filter', 'clever-layered-navigation' ),     //singular name of the listed records
            'plural'    => __( 'Filters', 'clever-layered-navigation' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        add_action( 'admin_head', array( &$this, 'admin_header' ) );
    }
    function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'my_list_test' != $page )
            return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 5%; }';
        echo '.wp-list-table .column-name { width: 40%; }';
        echo '.wp-list-table .column-short_code { width: 35%; }';
        echo '</style>';
    }
    function no_items() {
        _e( 'No item found!','clever-layered-navigation' );
    }
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'name':
            case 'short_code':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
    function get_sortable_columns() {
        $sortable_columns = array(
            'name'  => array('name',false),
            'short_code' => array('short_code',false)
        );
        return $sortable_columns;
    }
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'name' => esc_html__( 'Name', 'clever-layered-navigation' ),
            'short_code'    => esc_html__( 'ShortCode', 'clever-layered-navigation' )
        );
        return $columns;
    }
    function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }
    function column_name($item){
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&item-id=%s">'.esc_html__( 'Edit', 'clever-layered-navigation' ).'</a>',$_REQUEST['page'],'edit',$item['id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&item-id=%s">'.esc_html__( 'Delete', 'clever-layered-navigation' ).'</a>',$_REQUEST['page'],'delete',$item['id']),
        );
        $shortcode_name=sprintf('<a href="?page=%s&action=%s&item-id=%s">%s</a>',$_REQUEST['page'],'edit',$item['id'],$item['name']);
        return sprintf('%1$s %2$s', $shortcode_name, $this->row_actions($actions) );
    }
    function get_bulk_actions() {
        $actions = array(
            'delete'    => esc_html__( 'Delete', 'clever-layered-navigation' )
        );
        return $actions;
    }
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="filter_ids[]" value="%s" />', $item['id']
        );
    }
    function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        usort( $this->list_data, array( &$this, 'usort_reorder' ) );

        $per_page = 5;
        $current_page = $this->get_pagenum();
        $total_items = count( $this->list_data );
        // only ncessary because we have sample data
        $this->found_data = array_slice( $this->list_data,( ( $current_page-1 )* $per_page ), $per_page );
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page                     //WE have to determine how many items to show on a page
        ) );
        $this->items = $this->found_data;
    }
} //class

