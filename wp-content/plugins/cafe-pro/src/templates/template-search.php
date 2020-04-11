<?php
/**
 * View template for Clever Search widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */
$class_search_form = 'cafe-search-form';
if ($settings['enable_ajax_search'] == 'true') {
    $class_search_form .= ' ajax-search';
    $class_search_form .= ' result-' . $settings['result_layout'] . '-layout';

}
?>
<div class="cafe-wrap-search <?php echo esc_attr($settings['layout']); ?>">
    <?php
    if ($settings['layout'] != 'normal') {
        $toggle_id = 'cafe-toggle-search-' . uniqid();
        ?>
        <input type="checkbox" id="<?php echo esc_attr($toggle_id); ?>" class="cafe-toggle-input"/>
        <label class="cafe-search-toggle-button" for="<?php echo esc_attr($toggle_id); ?>"><i
                    class="cs-font clever-icon-search-4"></i></label>
        <label class="cafe-search-mask cafe-mask-close" for="<?php echo esc_attr($toggle_id); ?>"></label>
        <label class="cafe-search-close" for="<?php echo esc_attr($toggle_id); ?>"><i
                    class="cs-font clever-icon-close"></i></label>
        <?php
    }
    ?>

    <form class="<?php echo esc_attr($class_search_form) ?>" role="search"
          action="<?php echo esc_url(home_url('/')); ?>">
        <div class="cafe-wrap-search-form-content">
            <div class="cafe-wrap-search-fields">
                <input type="search" class="cafe-search-field" autocomplete="on" value="" name="s"
                       placeholder="<?php echo esc_attr($settings['placeholder']) ?>"/>
                <input type="hidden" name="layout" value="<?php echo esc_attr($settings['result_layout'])?>"/>
                <?php
                if ($settings['enable_ajax_search'] == 'true') {
                    ?>
                    <input type="hidden" value="<?php echo esc_attr($settings['max_result']) ?>" name="max_result"/>
                    <?php
                }
                if ($settings['search_product_only'] == 'true') {
                    ?>
                    <input type="hidden" name="post_type" value="product"/>
                    <?php
                } ?>
                <?php
                if ($settings['show_product_categories'] == 'true') :
                    $cats = get_terms([
                        'hide_empty' => true,
                        'taxonomy' => 'product_cat'
                    ]);
                    if ($cats && !is_wp_error($cats)) :
                        ?>
                        <div class="cafe-wrap-product-cat">
                            <select class="cafe-product-cat" name="cafe-product-cat">
                                <option value="all"><?php esc_html_e('All Categories', 'cafe-pro') ?></option>
                                <?php
                                foreach ($cats as $cat) {
                                    ?>
                                    <option value="<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_html($cat->name); ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    <?php
                    endif;
                endif;
                if ($settings['submit_icon'] || $settings['submit_label'] != '') {
                    ?>
                    <button type="submit" class="cafe-search-submit">
                        <?php
                        if ($settings['submit_icon']) {
                            ?>
                            <i class="cs-font clever-icon-search-4"></i>
                            <?php
                        }
                        if ($settings['submit_label'] != '') {
                            echo esc_html($settings['submit_label']);
                        }
                        ?>
                    </button>
                <?php } ?>
            </div>
            <?php
            if ($settings['enable_ajax_search'] == 'true') {
                $css_class = 'cafe-row cafe-grid-lg-' . $settings['columns']['size'] . '-cols cafe-grid-md-' . $settings['columns_tablet']['size'] . '-cols cafe-grid-' . $settings['columns_mobile']['size'] . '-cols';
                if ($settings['search_product_only'] == 'true' && $settings['result_layout']=='grid') {
                    $css_class .= ' products';
                    ?>
                    <div class="cafe-wrap-search-result woocommerce">
                        <ul class="<?php echo esc_attr($css_class) ?>">

                        </ul>
                    </div>
                    <?php
                }else{
                    ?>
                    <div class="cafe-wrap-search-result">
                        <div class="<?php echo esc_attr($css_class) ?>">

                        </div>
                    </div>
                    <?php
                }
            } ?>
        </div>
    </form>
</div>
