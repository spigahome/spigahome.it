<?php
/**
 * Shop page Swatch
 * @description: Display swatch on shop page.
 **/
if (!defined('ABSPATH')) {
    exit;
}

$data_variations = array();
$child = $product->get_children();
foreach ($child as $variation_id) {
    $variation = wc_get_product($variation_id);
    $data_variations[$variation_id] = $variation->get_attributes();
}
$first_group = true;

$available_variations = $product->get_available_variations();

$general_settings = get_option('zoo-cw-settings', true);
$tooltip_active=isset($general_settings['tooltip']) ? intval($general_settings['tooltip']) : 1;
$zoo_cw_wrap_shop='zoo-cw-wrap-shop';
if($tooltip_active){
    wp_enqueue_script('tippy');
    $zoo_cw_wrap_shop.=' allow-tooltip';
}
?>
<div class="<?php echo esc_attr($zoo_cw_wrap_shop);?>" data-product_id="<?php the_ID(); ?>"
     data-attributes_count="<?php echo(count($attributes)); ?>"
     data-product_variations="<?php echo htmlspecialchars(wp_json_encode($available_variations)) ?>"
     data-count-attributes="<?php echo esc_attr(count($attributes)); ?>"
     data-allow-add-to-cart="<?php echo($allow_add_to_cart); ?>">
    <?php foreach ($attributes as $attribute_name => $options) :
        $attribute_slug = sanitize_title($attribute_name);
        if (isset($product_swatch_data_array[$attribute_slug])) {
            $zoo_cw_attribute = $product_swatch_data_array[$attribute_slug];
            $zoo_cw_attribute_options = $zoo_cw_attribute['options_data'];
	        $zoo_cw_attr_class = "zoo-cw-group-attribute attribute_" . $attribute_slug;
	        $zoo_cw_attr_class.= ' zoo-cw-type-'.$zoo_cw_attribute['display_type'];
            ?>
            <div class="<?php echo(esc_attr($zoo_cw_attr_class)); ?>"
                 data-group-attribute="<?php echo(esc_attr("attribute_" . $attribute_slug)); ?>"
                 data-attribute-display-type="<?php echo(esc_attr($zoo_cw_attribute['display_type'])); ?>">
                <?php
                if ($zoo_cw_attribute['display_type'] != "default"):
                    foreach ($zoo_cw_attribute_options as $zoo_cw_attribute_option):
                        $cw_attribute_option_val = isset($zoo_cw_attribute_option['value']) ? $zoo_cw_attribute_option['value'] : '';
                        $cw_attribute_option_name = isset($zoo_cw_attribute_option['name']) ? $zoo_cw_attribute_option['name'] : '';
                        if ($cw_attribute_option_val != '') {
                        ?>
                        <div class="zoo-cw-attribute-option"
                             data-attribute-option="<?php echo esc_attr($cw_attribute_option_val) ?>">
                                <div class="zoo-cw-attr-item <?php echo esc_attr(strtolower($zoo_cw_attribute['class_options'])); ?>"
                                    <?php if (isset($zoo_cw_attribute['custom_style'])) echo($zoo_cw_attribute['custom_style']); ?>
                                     title="<?php echo esc_attr($cw_attribute_option_name); ?>">
                                    <?php
                                    if ($zoo_cw_attribute['display_type'] == 'color'): ?>
                                        <span style="background-color: <?php echo esc_attr($zoo_cw_attribute_option['color']); ?>;"
                                              class="zoo-cw-label-color">
                                            </span>
                                    <?php elseif ($zoo_cw_attribute['display_type'] == 'text'): ?>
                                        <span class="zoo-cw-label-text">
                                                <?php echo esc_html($cw_attribute_option_name); ?>
                                            </span>
                                    <?php elseif ($zoo_cw_attribute['display_type'] == 'image'): ?>
                                        <img src="<?php echo esc_url($zoo_cw_attribute_option['image']); ?>"
                                             class="zoo-cw-label"
                                             title="<?php echo esc_attr($cw_attribute_option_name); ?>"
                                             alt="<?php echo esc_attr($cw_attribute_option_name); ?>"/>
                                    <?php endif; ?>
                                </div>
                                <input type="hidden" name="<?php echo esc_attr('attribute_' . $attribute_slug); ?>"
                                       data-attribute_name="<?php echo esc_attr('attribute_' . $attribute_slug); ?>"
                                       value="<?php echo esc_attr($cw_attribute_option_val); ?>">
                        </div>
                    <?php } endforeach; ?>
                <?php else: ?>
                    <select class="zoo-cw-attribute-select" name="">
                        <option value=""><?php echo esc_html__('Choose an option', 'clever-swatches') ?></option>
                        <?php foreach ($zoo_cw_attribute_options as $zoo_cw_attribute_option): ?>
                            <?php $cw_attribute_option_val = isset($zoo_cw_attribute_option['value']) ? $zoo_cw_attribute_option['value'] : ''; ?>
                            <?php $cw_attribute_option_name = isset($zoo_cw_attribute_option['name']) ? $zoo_cw_attribute_option['name'] : ''; ?>
                            <option value="<?php echo esc_attr($cw_attribute_option_val); ?>"
                                    class=""><?php echo esc_attr($cw_attribute_option_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <?php $first_group = false;
        }
    endforeach; ?>
</div>