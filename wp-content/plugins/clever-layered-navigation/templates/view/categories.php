<?php
/**
 * Filter by Categories
 */
$wrap_class='zoo_ln_categories_filter zoo-filter-block zoo-filter-by-categories';
if(!isset($content_data['vertical-always-visible'])){
    $wrap_class.=' allow-hide';
}else{
    $wrap_class.=' always-visible';
}
$filter_id = mt_rand();
?>
<div id="cln-filter-item-<?php echo esc_attr($filter_id) ?>" class="<?php echo esc_attr($wrap_class)?>">
    <?php if($content_data['title']!='') { ?>
        <h4 class="zoo-title-filter-block"><?php echo esc_html($content_data['title']); ?>
            <?php
            if (!isset($content_data['vertical-always-visible'])) {
                ?><span class="zoo-ln-toggle-block-view"><i class="cs-font clever-icon-caret-up"></i></span><?php
            }
            ?>
        </h4>
        <?php
    }
    if (isset($selected_filter_option['categories'])) {
        $selected_ids = $selected_filter_option['categories'];
    } else {
        $selected_ids = array();
    }
    echo '<ul class="zoo-list-filter-item zoo-list-categories">'.\Zoo\Frontend\Hook\render_list_categories($selected_ids, $content_data).'</ul>';
    ?>
</div>
