<?php
/**
 * Filter product by attributes
 */
$item_type = 'range-slider';
$content_title = $attribute_ids = $min_value = $max_value = $unit_label = '';
$step_value = 1;
$add_new = 'multi';
$vertical_always_visible = '1';

if (isset($item_data)) {
    $item_number = $item_data['item_number'];
    $sac = (array)reset($item_data['item_config_value']['filter_config_value']);
    $content_title = $sac['title'];
    $unit_label = $sac['unit'];
    $min_value = $sac['min'];
    $max_value = $sac['max'];
    $step_value = $sac['step'];
    $attribute_ids = $sac['attribute-ids'];
    $vertical_always_visible = isset($sac['vertical-always-visible'])?$sac['vertical-always-visible']:'';
    $add_new = '';
} else {
    $item_number = '0';
}

if (isset($array_multi_number[$item_type])) {
    $multi_number = $array_multi_number[$item_type];
} else {
    $multi_number = 1;
}

$list_attribute = wc_get_attribute_taxonomies();

$raw_data = \Zoo\Helper\Data\get_global_config_with_name($item_id,'filter-style');
if ($raw_data !== false) {
    $filter_style = (array)json_decode($raw_data);
    $filter_view_style = $filter_style['filter_view_style'];
} else {
    $filter_view_style = 'horizontal';
}

?>

<div id="range-slider-<?php echo $item_number?>" class="item ui-draggable">
    <div class="item-top">
        <div class="item-title-action">
            <button type="button" class="item-action hide-if-no-js" aria-expanded="false">
                <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
        </div>
        <div class="item-title ui-draggable-handle">
            <h3><?php esc_html_e('Range Slider', 'clever-layered-navigation') ?><span class="in-item-title"></span></h3>
        </div>
    </div>
    <div class="item-inside">
        <form method="post">
            <div class="item-content">
                <?php
                    $valid_numeric_taxonomies = [];
                    foreach ($list_attribute as $taxonomy) {
                        $valid_numeric_term = true;
                        $wc_tax_name = wc_attribute_taxonomy_name($taxonomy->attribute_name);
                        $wc_attr_terms = get_terms([
                            'taxonomy' => $wc_tax_name,
                            'hide_empty' => true
                        ]);
                        if (Zoo\Helper\Data\cw_is_valid_numeric_terms_names($wc_attr_terms)) {
                            $valid_numeric_taxonomies[] = $taxonomy;
                        } else {
                            continue;
                        }
                    }
                ?>
                <?php if ($valid_numeric_taxonomies) : ?>
                    <p>
                        <label for="range-slider-<?php echo $item_number?>-title"><?php esc_html_e('Title','clever-layered-navigation'); ?></label>
                        <input class="widefat " id="range-slider-<?php echo $item_number?>-title" name="range-slider[<?php echo $item_number?>][title]" value="<?php echo($content_title);?>" type="text">
                    </p>
                    <p>
                        <label for="range-slider-<?php echo $item_number?>-attribute-ids"><?php esc_html_e('Select a Range', 'clever-layered-navigation') ?></label>
                        <select class="widefat " id="range-slider-<?php echo $item_number?>-attribute-ids" name="range-slider[<?php echo $item_number?>][attribute-ids]">
                            <option value=""><?php esc_html_e('--Select--', 'clever-layered-navigation') ?></option>
                            <?php
                                foreach ($valid_numeric_taxonomies as $attribute) {
                                    echo ('<option value="'.$attribute->attribute_name.'" '.selected($attribute_ids,$attribute->attribute_name).'>'.$attribute->attribute_label.'</option>');
                                }
                            ?>
                        </select>
                    </p>
                    <p>
                        <label for="range-slider-<?php echo $item_number?>-unit-value"><?php esc_html_e('Unit','clever-layered-navigation'); ?></label>
                        <input class="widefat " id="range-slider-<?php echo $item_number?>-unit-value" name="range-slider[<?php echo $item_number?>][unit]" value="<?php echo $unit_label;?>" type="text">
                    </p>
                    <p>
                        <label for="range-slider-<?php echo $item_number?>-min-value"><?php esc_html_e('Min Value','clever-layered-navigation'); ?></label>
                        <input class="widefat " id="range-slider-<?php echo $item_number?>-min-value" name="range-slider[<?php echo $item_number?>][min]" value="<?php echo $min_value;?>" type="number">
                    </p>
                    <p>
                        <label for="range-slider-<?php echo $item_number?>-max-value"><?php esc_html_e('Max Value','clever-layered-navigation'); ?></label>
                        <input class="widefat " id="range-slider-<?php echo $item_number?>-max-value" name="range-slider[<?php echo $item_number?>][max]" value="<?php echo $max_value;?>" type="number">
                    </p>
                    <p>
                        <label for="range-slider-<?php echo $item_number?>-step-value"><?php esc_html_e('Step Value','clever-layered-navigation'); ?></label>
                        <input class="widefat " id="range-slider-<?php echo $item_number?>-step-value" name="range-slider[<?php echo $item_number?>][step]" value="<?php echo $step_value;?>" type="text">
                    </p>
                    <?php if ($filter_view_style == 'vertical'): ?>
                        <p>
                            <label for="range-slider-<?php echo $item_number?>-vertical-always-visible">
                                <input class="widefat " id="range-slider-<?php echo $item_number?>-vertical-always-visible" name="range-slider[<?php echo $item_number?>][vertical-always-visible]" value="1" type="checkbox" <?php checked($vertical_always_visible,'1')?>>
                                <?php esc_html_e('Disable show/hide filter','clever-layered-navigation'); ?>
                            </label>
                        </p>
                    <?php endif; ?>
                <?php else : ?>
                    <p class="notice warning"><strong><?php esc_html_e('Range Slider only works with numeric attributes. No valid numeric attributes found.', 'clever-layered-navigation') ?></strong></p>
                <?php endif;  ?>
            </div>
            <input name="item-id" class="item-id" value="range-slider-<?php echo $item_number?>" type="hidden">
            <input name="item-type" class="item-type" value="<?php echo esc_attr($item_type);?>" type="hidden">
            <input name="id_base" class="id_base" value="range-slider" type="hidden">
            <input name="item-width" class="item-width" value="250" type="hidden">
            <input name="item-height" class="item-height" value="200" type="hidden">
            <input name="item_number" class="item_number" value="-1" type="hidden">
            <input name="multi_number" class="multi_number" value="<?php echo $multi_number;?>" type="hidden">
            <input name="add_new" class="add_new" value="<?php echo($add_new);?>" type="hidden">
            <div class="item-control-actions">
                <div class="alignleft">
                    <button type="button" class="button-link button-link-delete item-control-remove"><?php esc_html_e('Delete','clever-layered-navigation'); ?></button> |
                    <button type="button" class="button-link item-control-close"><?php esc_html_e('Close','clever-layered-navigation'); ?></button>
                </div>
                <div class="alignright">
                    <input name="saveitem" id="range-slider-<?php echo $item_number?>-saveitem" class="button button-primary item-control-save right" value="<?php esc_html_e('Save','clever-layered-navigation'); ?>" type="submit">			<span class="spinner"></span>
                </div>
                <br class="clear">
            </div>
        </form>
    </div>
    <div class="item-description">
        <?php esc_html_e('Range Slider is the best product filter for numeric attributes such as size, weight, height...', 'clever-layered-navigation') ?>
    </div>
</div>
