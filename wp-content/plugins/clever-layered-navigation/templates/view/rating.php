<?php
/**
 * Filter by rating
 */
$wrap_class = 'zoo-filter-block zoo-filter-by-rating';
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
    if (isset($selected_filter_option['rating-from'])) {
        $selected_val = intval($selected_filter_option['rating-from']);
    } else {
        $selected_val = 0;
        if (!isset($content_data['rating-base'])) {
            $selected_val = intval($content_data['rating-base']);
        }
    }
    ?>
    <ul class="zoo-list-filter-item zoo-list-rating">
        <?php for ($star = 1; $star <= 5; $star++) {
            $class_item = 'zoo-filter-item zoo-ln-rating-item';
            if ($star == $selected_val) {
                $class_item .= ' selected';
            }
            ?>
            <li class="<?php echo esc_attr($class_item); ?>">
                <span class="zoo-ln-star zoo-ln-<?php echo esc_attr($star); ?>-star"
                      data-zoo-ln-star="<?php echo esc_attr($star) ?>"></span>
            </li>
        <?php } ?>
    </ul>
    <input name="rating-from" value="<?php echo($selected_val); ?>" type="hidden">
</div>