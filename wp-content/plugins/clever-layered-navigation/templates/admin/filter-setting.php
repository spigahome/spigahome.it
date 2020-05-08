<?php
/**
 */

if (isset($_GET['item-id'])) {
    $item_id = $_GET['item-id'];
    $array_multi_number = \Zoo\Admin\Setting\FilterSetting\prepare_multi_number($item_id);

    $raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id, 'filter-style');
    if ($raw_data !== false) {
        $filter_style = (array)json_decode($raw_data);
        if (isset($filter_style['filter_view_style']) && $filter_style['filter_view_style'] == 'horizontal') {
            $horizontal_number_col = $filter_style['horizontal_number_col'];
            if (isset($filter_style['horizontal_add_primary']) && $filter_style['horizontal_add_primary'] == '1') {
                $horizontal_number_col ++;
            }
        } else {
            $horizontal_number_col = 1;
        }
    } else {
        $horizontal_number_col = 1;
    }
} else {
    $item_id = 0;
    $array_multi_number = \Zoo\Admin\Setting\FilterSetting\prepare_multi_number();

    $horizontal_number_col = 1;
}

$wc_attributes = wc_get_attribute_taxonomies();
?>
    <div class="item-liquid-left">
        <div id="items-left">
            <div id="available-items" class="items-holder-wrap ui-droppable">
                <div class="sidebar-name">
                    <h2><?php esc_html_e('Available Filter', 'clever-layered-navigation')?></h2>
                </div>
                <div class="item-holder">
                    <div class="sidebar-description">
                        <p class="description">
                            <?php esc_html_e('To activate a filter drag it to a Filter Layer or click on it. To deactivate a item and delete its settings, drag it back.', 'clever-layered-navigation')?>
                        </p>
                    </div>
                    <div id="item-list">
                        <?php
                        global $wpdb;
                        $table = $wpdb->prefix . 'zoo_ln_filter_config';
                        $array_multi_number['attribute'] = $wpdb->get_var("SELECT filter_id from $table WHERE filter_item_type='attribute' ORDER BY filter_id DESC LIMIT 1");
                        $array_multi_number['range-slider'] = $wpdb->get_var("SELECT filter_id from $table WHERE filter_item_type='range-slider' ORDER BY filter_id DESC LIMIT 1");
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/active-filter.php';
                        if (!empty($wc_attributes)) {
                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/attribute.php';
                        }
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/categories.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/tags.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/on-sale.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/price.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/in-stock.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/rating.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/range-slider.php';
                        require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/review.php';
                        if (class_exists('Zoo_Clever_Swatch_Admin_Manager')) {
                            $array_multi_number['cw-attribute'] = $wpdb->get_var("SELECT filter_id from $table WHERE filter_item_type='cw-attribute' ORDER BY filter_id DESC LIMIT 1");
                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/cw-attribute.php';
                        }
                        ?>

                    </div>
                    <br class="clear">
                </div>
                <br class="clear">
            </div>
        </div>
    </div>
    <div class="item-liquid-right">
        <div id="items-right" class="wp-clearfix">
            <?php
                $data = \Zoo\Admin\Setting\FilterSetting\prepare_data($item_id);

                for ($i = 1; $i <= $horizontal_number_col; $i++) :
                    $column = 'sidebars-column-'.strval(($i - 1) % 2 + 1);

                    if (isset($filter_style) && $filter_style['filter_view_style'] == 'horizontal' &&isset($filter_style['horizontal_add_primary']) && $filter_style['horizontal_add_primary'] == '1') {
                        if ($i == 1) {
                            $col_id = 'primary';
                            $col_title = "Primary";
                        } else {
                            $col_id = 'col-'.($i - 1);
                            $col_title = "Column ".($i - 1);
                        }
                    } else {
                        $col_id = 'col-'.$i;
                        $col_title = "Column ".$i;
                    }

                    ?>
                    <div class="<?php echo($column); ?>">
                        <div class="items-holder-wrap">
                            <div id="<?php echo($col_id); ?>" class="items-sortables ui-droppable ui-sortable">
                                <div class="sidebar-name">
                                    <div class="sidebar-name-arrow"><br></div>
                                    <h2><?php echo($col_title);?><span class="spinner"></span></h2>
                                </div>
                                <div class="sidebar-description">
                                    <p class="description">
                                        <?php esc_html_e('Add filter item here to to active it.', 'clever-layered-navigation')?>
                                        </p>
                                </div>
                                <?php
                                if (isset($data[$col_id]) && count($data[$col_id])) {
                                    $sidebar_data = $data[$col_id];

                                    foreach ($sidebar_data as $item_data) {
                                        if ($item_data['item_type'] == 'price') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/price.php';
                                        } elseif ($item_data['item_type'] === 'range-slider') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/range-slider.php';
                                        } elseif ($item_data['item_type'] == 'attribute') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/attribute.php';
                                        } elseif (class_exists('Zoo_Clever_Swatch_Admin_Manager') && ($item_data['item_type'] === 'cw-attribute')) {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/cw-attribute.php';
                                        } elseif ($item_data['item_type'] == 'categories') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/categories.php';
                                        } elseif ($item_data['item_type'] == 'tags') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/tags.php';
                                        } elseif ($item_data['item_type'] == 'on-sale') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/on-sale.php';
                                        } elseif ($item_data['item_type'] == 'in-stock') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/in-stock.php';
                                        } elseif ($item_data['item_type'] == 'review') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/review.php';
                                        } elseif ($item_data['item_type'] == 'rating') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/rating.php';
                                        } elseif ($item_data['item_type'] == 'active-filter') {
                                            require ZOO_LN_TEMPLATES_PATH.'admin/filter-setting/active-filter.php';
                                        }
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        if ($i % 2 == 0) {
                            echo('<br class="clear">');
                        }
                    ?>
            <?php endfor;?>
        </div>
    </div>
    <form method="post">
        <input id="_wpnonce_items" name="_wpnonce_items" value="b04e940eeb" type="hidden">
        <?php if (isset($item_id)): ?>
            <input id="item_list_id" name="item_list_id" value="<?php echo($item_id);?>" type="hidden">
        <?php endif; ?>
        <?php wp_nonce_field('filter_setting', 'zoo_ln_nonce_setting'); ?>
    </form>
    <br class="clear">
