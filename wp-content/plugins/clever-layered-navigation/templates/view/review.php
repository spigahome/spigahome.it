<?php
/**
 * Filter by review
 */
$wrap_class='zoo-filter-block zoo-filter-by-review';
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
    <ul class="zoo-list-filter-item zoo-list-review">
            <li class="zoo-filter-item zoo-ln-review-item">
                <?php
                if (isset($selected_filter_option['review-from'])) $value = intval($selected_filter_option['review-from']);
                else $value = 0;
                ?>
                <span><?php esc_html_e('From','clever-layered-navigation')?></span>
                <input name="review-from" value="<?php echo($value);?>" type="text">
            </li>
    </ul>
</div>