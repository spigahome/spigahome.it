<?php
/**
 */

namespace Zoo\Admin\Setting\FilterSetting;

require_once ZOO_LN_TEMPLATES_PATH.'admin/filter-setting.php';

function prepare_data($item_id) {
    $data = array();

    $filter_setting_order = \Zoo\Helper\Data\get_global_config_with_name($item_id,'filter-setting-order');
    $filter_setting_order = (array)json_decode($filter_setting_order);

    foreach ($filter_setting_order as $key => $order) {
        if ($order != '') {
            $items = explode(',', $order);
            foreach ($items as $item) {
                if ($item != '') {
                    $item_data = array();

                    $parts = explode('-', $item);
                    $item_number = array_pop($parts);
                    $item_type = implode('-', $parts);

                    $item_data['item_number'] = $item_number;
                    $item_data['item_type'] = $item_type;
                    $filter_config = \Zoo\Helper\Data\get_filter_config_with_name($item_id, $item);

                    $sac = json_decode($filter_config['filter_config_value']);
                    $sac = \Zoo\Helper\Data\objectToArray($sac);
                    $filter_config['filter_config_value'] = $sac;
                    $item_data['item_config_value'] = $filter_config;


                    $data[$key][] = $item_data;
                }
            }
        }

    }

    return $data;
}

function prepare_multi_number($item_id = null) {
    $array_multi_number = array();
    if (isset($item_id)) {
        $filter_setting_order = \Zoo\Helper\Data\get_global_config_with_name($item_id,'filter-setting-order');
        $filter_setting_order = (array)json_decode($filter_setting_order);

        foreach ($filter_setting_order as $item) {
            $parts = explode('-', $item);
            $item_number = intval(array_pop($parts));
            $item_type = implode('-', $parts);

            if (!isset($array_multi_number[$item_type]) || ($array_multi_number[$item_type] <= $item_number) ) {
                $array_multi_number[$item_type] = $item_number + 1;
            }
        }
    }

    return $array_multi_number;
}

function render_list_categories($parent_id, $parent_ids, $space, $category_slugs) {
    $taxonomy     = 'product_cat';
    $orderby      = 'name';
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no
    $title        = '';

    $args = array(
        'taxonomy'     => $taxonomy,
        'child_of'     => 0,
        'parent'       => $parent_id,
        'orderby'      => $orderby,
        'show_count'   => $show_count,
        'pad_counts'   => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li'     => $title
    );

    $cates = get_categories( $args );

    $html = '';
    foreach ($cates as $cate) {
        if (in_array($cate->slug, $category_slugs)) {
            $selected = 'selected';
        } else $selected = '';
        $data_parents = esc_html(wp_json_encode($parent_ids));
        $html .= '<option data-parents="'.$data_parents.'" value="'.$cate->slug.'" '.$selected.'>'.$space.$cate->name.'</option>';
        $term_parent_ids = $parent_ids;
        $term_parent_ids[] = $cate->term_id;
        $html .= render_list_categories($cate->term_id, $term_parent_ids, $space.' - ', $category_slugs);
    }
    return $html;
}
