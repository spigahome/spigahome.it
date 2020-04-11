<?php
/**
 *Filter by onsale
 */
$wrap_class='zoo-ln-filter-onsale zoo-filter-block';
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
    <?php
    if (isset($selected_filter_option['on-sale']) && $selected_filter_option['on-sale'] == '1') $checked = 'checked';
    else $checked = '';
    ?>
    <ul class="zoo-list-filter-item">
        <li class="zoo-filter-item <?php if (isset($selected_filter_option['on-sale']) && $selected_filter_option['on-sale'] == '1') echo 'selected';?>"><label><input type="checkbox" value="1" name="on-sale" <?php echo($checked); ?>/><?php echo esc_html__('Show On Sale only','clever-layered-navigation')?></label>
        </li>
    </ul>
</div>
