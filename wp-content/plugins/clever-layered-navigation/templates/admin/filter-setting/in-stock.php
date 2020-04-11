<?php
/**
 */
?>

<?php
    $item_type = 'in-stock';
    $add_new = 'multi';
    $content_title = '';
    $vertical_always_visible = '1';

    if (isset($item_data)) {
        $item_number = $item_data['item_number'];
        $sac = (array)reset($item_data['item_config_value']['filter_config_value']);
        $content_title = $sac['title'];
        if (isset($sac['vertical-always-visible'])) {
            $vertical_always_visible = $sac['vertical-always-visible'];
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

    $raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id,'filter-style');
    if ($raw_data !== false) {
        $filter_style = (array)json_decode($raw_data);
        $filter_view_style = $filter_style['filter_view_style'];
    } else {
        $filter_view_style = 'horizontal';
    }
?>

<div id="in-stock-<?php echo($item_number);?>" class="item ui-draggable">
    <div class="item-top">
        <div class="item-title-action">
            <button type="button" class="item-action hide-if-no-js" aria-expanded="false">
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
        </div>
        <div class="item-title ui-draggable-handle">
            <h3><?php esc_html_e('In Stock', 'clever-layered-navigation'); ?><span class="in-item-title"></span></h3>
        </div>
    </div>
    <div class="item-inside">
        <form method="post">
            <div class="item-content">
                <p>
                    <label for="in-stock-<?php echo($item_number);?>-title"><?php esc_html_e('Title', 'clever-layered-navigation'); ?></label>
                    <input class="widefat " id="in-stock-<?php echo($item_number);?>-title" name="in-stock[<?php echo($item_number);?>][title]" value="<?php echo($content_title);?>" type="text">
                </p>
                <?php
                if ($filter_view_style == 'vertical'):
                    ?>
                    <p>
                        <label for="in-stock-<?php echo $item_number?>-vertical-always-visible">
                            <input class="widefat " id="in-stock-<?php echo $item_number ?>-vertical-always-visible"
                                   name="in-stock[<?php echo $item_number ?>][vertical-always-visible]" value="1"
                                   type="checkbox" <?php checked($vertical_always_visible, '1') ?>/>
                            <?php esc_html_e('Disable show/hide filter', 'clever-layered-navigation'); ?>
                        </label>
                    </p>
                    <?php
                endif;
                ?>
            </div>
            <input name="item-id" class="item-id" value="in-stock-<?php echo($item_number);?>" type="hidden">
            <input name="id_base" class="id_base" value="in-stock" type="hidden">
            <input name="item-type" class="item-type" value="<?php echo($item_type);?>" type="hidden">
            <input name="item-width" class="item-width" value="250" type="hidden">
            <input name="item-height" class="item-height" value="200" type="hidden">
            <input name="item_number" class="item_number" value="-1" type="hidden">
            <input name="multi_number" class="multi_number" value="<?php echo($multi_number)?>" type="hidden"><!--no. of multi item-->
            <input name="add_new" class="add_new" value="<?php echo($add_new);?>" type="hidden">
            <div class="item-control-actions">
                <div class="alignleft">
                    <button type="button" class="button-link button-link-delete item-control-remove"><?php esc_html_e('Delete', 'clever-layered-navigation'); ?></button> |
                    <button type="button" class="button-link item-control-close"><?php esc_html_e('Close', 'clever-layered-navigation'); ?></button>
                </div>
                <div class="alignright">
                    <input name="saveitem" id="in-stock-<?php echo($item_number);?>-saveitem" class="button button-primary item-control-save right" value="<?php esc_attr_e('Save', 'clever-layered-navigation'); ?>" type="submit">			<span class="spinner"></span>
                </div>
                <br class="clear">
            </div>
        </form>
    </div>
    <div class="item-description">
        <?php esc_html_e('Show only product in stock after filter ', 'clever-layered-navigation'); ?>
    </div>
</div>
