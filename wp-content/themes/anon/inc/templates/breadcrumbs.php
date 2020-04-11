<?php
/**
 * Breadcrumbs template
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */

if (class_exists('WooCommerce', false)) {
    if (is_woocommerce()) {
        return;
    }
}
if (get_theme_mod('zoo_disable_breadcrumbs', '0') == '1') {
    return;
}
if ((is_single() || is_page()) && get_post_meta(get_the_ID(), 'zoo_disable_breadcrumbs', true) == 1) {
    return;
}
?>
<div class="wrap-breadcrumb">
    <div class="container">
        <?php zoo_breadcrumbs(esc_html__('Home', 'anon'), '', 'zoo-separator'); ?>
    </div>
</div>