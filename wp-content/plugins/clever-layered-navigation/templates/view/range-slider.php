<?php
/**
 * View of Product filter by range
 */
wp_enqueue_script('slider-pips');
if(wp_is_mobile()){
    wp_enqueue_script('touch-punch');
}
$attribute_taxonomy_name = wc_attribute_taxonomy_name($content_data['attribute-ids']);
$terms = get_terms($attribute_taxonomy_name, array('menu_order' => 'ASC'));
// All range values.
if (!$terms || $terms instanceof WP_Error) {
    return;
}
$range_values = wp_list_pluck($terms, 'name');
asort($range_values);
// Unit.
$range_unit = !empty($content_data['unit']) ? $content_data['unit'] : '';

// Min range value.
$range_min = !empty($content_data['min']) ? $content_data['min'] : min($range_values);

// Max range value.
$range_max = !empty($content_data['max']) ? $content_data['max'] : max($range_values);

// Range step.
$range_step = !empty($content_data['step']) ? $content_data['step'] : 1;

$range_values=implode(', ', $range_values);

$filter_id = mt_rand();
if (isset($selected_filter_option['range'][$attribute_taxonomy_name]['min'])) $selected_min = $selected_filter_option['range'][$attribute_taxonomy_name]['min'];
else $selected_min = '';
if (isset($selected_filter_option['range'][$attribute_taxonomy_name]['max'])) $selected_max = $selected_filter_option['range'][$attribute_taxonomy_name]['max'];
else $selected_max = '';
?>
<div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="zoo_ln_range_slider zoo-filter-block range-slider-<?php echo esc_attr($attribute_taxonomy_name);?>">
    <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?></h4>
    <div class="zoo-flat-slider zoo-ln-range-slider-control" data-config='{"min":"<?php echo esc_attr($range_min)?>", "max":"<?php echo esc_attr($range_max)?>", "val":"<?php echo esc_attr($range_values)?>"}'></div>
    <input class="attr-from" name="range-min-<?php echo esc_attr($attribute_taxonomy_name);?>" value="<?php echo esc_attr($selected_min); ?>" type="hidden">
    <input class="attr-to" name="range-max-<?php echo esc_attr($attribute_taxonomy_name);?>" value="<?php echo  esc_attr($selected_max); ?>" type="hidden">
</div>
