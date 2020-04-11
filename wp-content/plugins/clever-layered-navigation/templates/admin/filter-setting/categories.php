<?php
/**
 * Admin control of Categories filter.
 */

?>

<?php
$item_type = 'categories';
$add_new = 'multi';
$content_title = '';
$category_slugs = array();
$display_type = 'list';
$list_show_child = 0;
$show_product_count = 0;
$vertical_always_visible =1;
$hide_empty = 0;
if (isset($item_data)) {
    $item_number = $item_data['item_number'];
    $sac = (array)reset($item_data['item_config_value']['filter_config_value']);
    $content_title = $sac['title'];
    if (isset($sac['category-slugs'])) {
        $category_slugs = $sac['category-slugs'];
    }
    if (isset($sac['display_type'])) {
        $display_type = $sac['display_type'];
    }
    if (isset($sac['list-show-child'])) {
        $list_show_child = $sac['list-show-child'];
    }
    if (isset($sac['show-product-count'])) {
        $show_product_count = $sac['show-product-count'];
    }
    if (isset($sac['vertical-always-visible'])) {
        $vertical_always_visible = $sac['vertical-always-visible'];
    }if (isset($sac['hide-empty'])) {
        $hide_empty = $sac['hide-empty'];
    }
    $add_new = '';
} else {
    $item_number = '0';
}

if (isset($array_multi_number[$item_type])) {
    $multi_number = $array_multi_number[$item_type];
} else {
    $multi_number = 1;
}


$raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id, 'filter-style');
if ($raw_data !== false) {
    $filter_style = (array)json_decode($raw_data);
    $filter_view_style = $filter_style['filter_view_style'];
} else {
    $filter_view_style = 'horizontal';
}

?>

<div id="categories-<?php echo($item_number); ?>" class="item ui-draggable">
    <div class="item-top">
        <div class="item-title-action">
            <button type="button" class="item-action hide-if-no-js" aria-expanded="false">
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
        </div>
        <div class="item-title ui-draggable-handle">
            <h3><?php esc_html_e('Categories','clever-layered-navigation')?><span class="in-item-title"></span></h3>
        </div>
    </div>
    <div class="item-inside">
        <form method="post">
            <div class="item-content">
                <p>
                    <label for="categories-<?php echo($item_number); ?>-title"><?php esc_html_e('Title','clever-layered-navigation')?></label>
                    <input class="widefat " id="categories-<?php echo($item_number); ?>-title"
                           name="categories[<?php echo($item_number); ?>][title]" value="<?php echo($content_title); ?>"
                           type="text">
                </p>
                <p>
                    <label for="categories-<?php echo($item_number); ?>-category-slugs"><?php esc_html_e('Categories','clever-layered-navigation')?></label>
                    <select class="widefat " id="categories-<?php echo($item_number); ?>-category-slugs"
                            name="categories[<?php echo($item_number); ?>][category-slugs][]" size="4" multiple>
                        <?php
                        $html = \Zoo\Admin\Setting\FilterSetting\render_list_categories(0, array(), '', $category_slugs);
                        echo($html);
                        ?>
                    </select>
                </p>
                <!-- <p>
                    <label for="categories-<?php echo $item_number ?>-display-type"><?php esc_html_e('Display type','clever-layered-navigation')?></label>
                    <select class="widefat" id="categories-<?php echo $item_number ?>-display-type"
                            name="categories[<?php echo $item_number ?>][display-type]">
                        <option value="list" <?php selected($display_type, 'list') ?> ><?php esc_html_e('List','clever-layered-navigation')?></option>
                        <option value="multiselect" <?php selected($display_type, 'multiselect') ?> ><?php esc_html_e('Multi select','clever-layered-navigation')?>
                        </option>
                    </select>
                </p> -->
                <p>
                    <label for="categories-<?php echo $item_number ?>-list-show-child">
                        <input class="widefat " id="categories-<?php echo $item_number ?>-list-show-child"
                               name="categories[<?php echo $item_number ?>][list-show-child]" value="1"
                               type="checkbox" <?php checked($list_show_child, 1) ?>/>
                        <?php esc_html_e('Always show child', 'clever-layered-navigation'); ?>
                    </label>
                </p>
                <p>
                    <label for="categories-<?php echo $item_number ?>-show-product-count">
                        <input class="widefat " id="categories-<?php echo $item_number ?>-show-product-count"
                               name="categories[<?php echo $item_number ?>][show-product-count]" value="1"
                               type="checkbox" <?php checked($show_product_count, 1) ?>/>
                        <?php esc_html_e('Show products count', 'clever-layered-navigation'); ?>
                    </label>
                </p>
                <?php
                if ($filter_view_style == 'vertical'):
                    ?>
                    <p>
                        <label for="categories-<?php echo $item_number ?>-vertical-always-visible">
                            <input class="widefat " id="categories-<?php echo $item_number ?>-vertical-always-visible"
                                   name="categories[<?php echo $item_number ?>][vertical-always-visible]" value="1"
                                   type="checkbox" <?php checked($vertical_always_visible, 1) ?>/>
                            <?php esc_html_e('Disable show/hide filter', 'clever-layered-navigation'); ?>
                        </label>
                    </p>
                    <?php
                endif;
                ?>
            </div>
            <input name="item-id" class="item-id" value="categories-<?php echo($item_number); ?>" type="hidden">
            <input name="id_base" class="id_base" value="categories" type="hidden">
            <input name="item-type" class="item-type" value="<?php echo($item_type); ?>" type="hidden">
            <input name="item-width" class="item-width" value="250" type="hidden">
            <input name="item-height" class="item-height" value="200" type="hidden">
            <input name="item_number" class="item_number" value="-1" type="hidden">
            <input name="multi_number" class="multi_number" value="<?php echo($multi_number) ?>" type="hidden">
            <!--no. of multi item-->
            <input name="add_new" class="add_new" value="<?php echo($add_new); ?>" type="hidden">
            <div class="item-control-actions">
                <div class="alignleft">
                    <button type="button" class="button-link button-link-delete item-control-remove"><?php esc_html_e('Delete','clever-layered-navigation')?></button>
                    |
                    <button type="button" class="button-link item-control-close"><?php esc_html_e('Close','clever-layered-navigation')?></button>
                </div>
                <div class="alignright">
                    <input name="saveitem" id="categories-<?php echo($item_number); ?>-saveitem"
                           class="button button-primary item-control-save right" value="<?php esc_attr_e('Save','clever-layered-navigation')?>" type="submit"> <span
                            class="spinner"></span>
                </div>
                <br class="clear">
            </div>
        </form>
    </div>
    <div class="item-description">
        <?php esc_html_e('Filter by product categories.','clever-layered-navigation')?>
    </div>
</div>
