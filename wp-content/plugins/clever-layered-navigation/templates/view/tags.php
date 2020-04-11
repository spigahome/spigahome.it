<?php
/**
 * @template:Filter attributes template
 * @package  clever-layered-navigation/templates/view
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since: 1.0.0
 */
$list_slug='';
if(isset($content_data['tag-slugs']))
$list_slug=$content_data['tag-slugs'];

$filter_tags = get_terms([
    'taxonomy' => 'product_tag',
    'slug'    => $list_slug,
]);

if (!isset($selected_filter_option['tags'])) $selected_filter_option['tags'] = [];
if ($filter_tags && !is_wp_error($filter_tags)) :

$filter_tags = cln_maybe_remove_unsatisfied_terms($filter_tags, 'product_tag');

?>
<div id="cln-filter-item-<?php echo mt_rand();?>" class="zoo-filter-block zoo-filter-by-tags">
    <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?>
        <?php
        if (!isset($content_data['vertical-always-visible'])) {
            ?><span class="zoo-ln-toggle-block-view"><i class="cs-font clever-icon-caret-up"></i></span><?php
        }
        ?></h4>
    <ul class="zoo-list-attribute zoo-list-filter-item">
        <?php
        foreach ($filter_tags as $term) {
            $item_class='';
            if (in_array($term->slug, $selected_filter_option['tags'])) {
                $checked = ' checked';
                $item_class =' selected';
            } else  $checked = '';
            $html = '';
            $html .= '<li class="zoo-filter-item'.$item_class.'">';
            $html .= '<label>';
            $html .= '<input class="filter-tag-' . $term->slug . '" type="checkbox" value="' . $term->slug . '" name="tags[]" ' . $checked . '/>';
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
<?php endif; ?>
