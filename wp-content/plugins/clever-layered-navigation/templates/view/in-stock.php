<?php
/**
 * Filter by stock
 */
$wrap_class='zoo-filter-block zoo-filter-by-stock';

if (!isset($content_data['vertical-always-visible'])) {
    $wrap_class .= ' allow-hide';
} else {
    $wrap_class .= ' always-visible';
}

$filter_id = mt_rand();
?>
<div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="<?php echo esc_attr($wrap_class) ?>">
    <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?>
        <?php
        if (!isset($content_data['vertical-always-visible'])) {
            ?><span class="zoo-ln-toggle-block-view"><i class="cs-font clever-icon-caret-up"></i></span><?php
        }
        ?>
    </h4>
    <ul class="zoo-list-filter-item zoo-list-in-stock">
        <li class="zoo-filter-item zoo-ln-stock-item <?php if (isset($selected_filter_option['in-stock']) && $selected_filter_option['in-stock'] == '1') echo 'selected';?>">
            <?php
            if (isset($selected_filter_option['in-stock']) && $selected_filter_option['in-stock'] == '1'){
                $checked = 'checked';
            } else {
                $checked = '';
            }
            ?>
            <label><input type="checkbox" value="1" name="in-stock" <?php echo($checked);?>/><?php esc_html_e('Show in stock products only','clever-layered-navigation')?></label>
        </li>
    </ul>
</div>
