<?php
/**
 * Open Top Shop Page template
 * Display count down end sale
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

$widget = 'shop';
if (zoo_product_sidebar() == 'top') {
    $widget = 'top-shop';
}
$css_class = 'wrap-top-shop-loop';
?>
<div id="top-shop-loop" class="<?php echo esc_attr($css_class) ?>">
    <div class="row">
        <div class="left-top-shop-loop col-lg-4 col-12">
            <?php
            if (is_active_sidebar($widget)) {
                ?>
                <a href="#" class="zoo-sidebar-control"
                   title="<?php esc_attr__('Show/Hide Sidebar', 'anon') ?>">
                    <span class="togglelines"></span>
                    <span class="text-before"><?php echo esc_html__('Filter', 'anon') ?></span>
                    <span class="text-after"><?php echo esc_html__('Close', 'anon') ?></span>
                </a>
                <?php
            }
            /**
             * Add ordering select box.
             * @hooked woocommerce_catalog_ordering - 10
             */
            do_action('zoo_woocommerce_catalog_ordering');
            $grid_active = ' active';
            $list_active = ' ';
            if (isset($_COOKIE['zoo_product_layout'])) {
                $grid_active = '';
                $list_active = ' active';
            }
            ?>
            <div class="wrap-toggle-products-layout">
                <span class="label-toggle-products-layout"><?php esc_html_e('View as', 'anon'); ?></span>
                <a class="toggle-products-grid-layout toggle-products-layout-button<?php echo esc_attr($grid_active) ?>"
                   data-layout="grid" href="#" title="<?php esc_attr_e('Grid layout', 'anon') ?>"><i
                            class="cs-font clever-icon-grid"></i></a>
                <a class="toggle-products-list-layout toggle-products-layout-button<?php echo esc_attr($list_active) ?>"
                   data-layout="list" href="#" title="<?php esc_attr_e('List layout', 'anon') ?>"><i
                            class="togglelines"></i></a>
            </div>
        </div>
        <div class="center-top-shop-loop col-lg-4 col-md-6 col-12">
            <?php
            /**
             * Add result count
             * @hooked woocommerce_result_count - 10
             */
            do_action('zoo_woocommerce_result_count');
            ?>
        </div>
        <div class="right-top-shop-loop top-page-pagination col-lg-4 col-md-6 col-12">
            <?php
            global $wp_query;
            $total_page = $wp_query->max_num_pages;
            $current_page = get_query_var('paged', 1) ? get_query_var('paged', 1) : 1;
            esc_html_e('Page', 'anon');
            ?>
            <div class="wrap-drop-down-pagination">
                <span class="current-page">
                    <?php echo esc_html($current_page); ?>
                </span>
                <?php
                woocommerce_pagination();
                ?>
            </div>
            <span class="separator">
            <?php
            echo '/';
            ?></span>
            <span class="total-page">
                <?php
                echo esc_html($total_page);
                ?>
            </span>
            <div class="wrap-next-prev-page">
                <span class="prev-page">
                    <?php previous_posts_link('<i class="zoo-icon-arrow-left"></i>'); ?></span>
                <span class="next-page">
                <?php
                    next_posts_link('<i class="zoo-icon-arrow-right"></i>');
                ?>
                </span>
            </div>
        </div>
    </div>
    <?php
    if (zoo_product_sidebar() == 'top') {
        woocommerce_get_sidebar();
    }
    ?>

</div>