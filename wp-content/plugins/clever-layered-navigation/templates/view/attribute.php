<?php
/**
 * @template:Filter attributes template
 * @package  clever-layered-navigation/templates/view
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since: 1.0.0
 */
$attribute_taxonomy_name = wc_attribute_taxonomy_name($content_data['attribute-ids']);
$terms = get_terms([
    'taxonomy' => $attribute_taxonomy_name
]);

if (is_wp_error($terms)) {
    return;
}

$terms = cln_maybe_remove_unsatisfied_terms($terms, $attribute_taxonomy_name);

if (count($terms) > 0 ) {
    if (isset($selected_filter_option['attribute'][$attribute_taxonomy_name])){
        $checked_slug = $selected_filter_option['attribute'][$attribute_taxonomy_name];
    } else $checked_slug = array();
    ?>
    <div id="cln-filter-item-<?php echo mt_rand();?>" class="zoo-filter-block zoo-filter-by-<?php echo esc_attr($attribute_taxonomy_name) ?>">
        <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?>
            <?php
            if (!isset($content_data['vertical-always-visible'])) {
                ?><span class="zoo-ln-toggle-block-view"><i class="cs-font clever-icon-caret-up"></i></span><?php
            }
            ?></h4>
        <ul class="zoo-list-attribute zoo-list-filter-item">
            <?php
            foreach ($terms as $term) {
                $item_class='';
                if (in_array($term->slug, $checked_slug)) {
                    $checked = ' checked';
                    $item_class =' selected';
                } else  $checked = '';
                $html = '';
                $html .= '<li class="zoo-filter-item'.$item_class.'">';
                $html .= '<label>';
                $html .= '<input type="checkbox" value="' . $term->slug . '" name="attribute[' . $attribute_taxonomy_name . '][]" ' . $checked . '/>';
                $html .= $term->name;
                $html .= '</label>';
                if (isset($content_data['show-product-count']) && $content_data['show-product-count'] == 1) {
                    $html .= '<span class="count">' . $term->count . '</span>';
                }
                $html .= '</li>';
                echo ($html);
            }
            ?>
        </ul>
    </div>
    <?php
}
?>
