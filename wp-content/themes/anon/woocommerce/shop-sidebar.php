<?php
/**
 * Template Shop sidebar.
 * This template is custom template of theme, override default sidebar of WooCommerce.
 * @package Zoo-Theme-core
 * @version 3.0.0
 */

$allow_html = array(
    'div' => array('class' => array(), 'id' => array()),
    'aside' => array('class' => array(), 'id' => array()),
);
$widget = 'shop';
if (zoo_product_sidebar() == 'top') {
    $widget = 'top-shop';
}
$zoo_sidebar_class = 'zoo-' . zoo_product_sidebar() . '-sidebar sidebar product-sidebar';
if (zoo_product_sidebar() != 'off-canvas' && zoo_product_sidebar() != 'top') {
    $zoo_sidebar_class .= ' col-12 col-lg-3';
}
if(is_active_sidebar($widget)):
echo wp_kses('<aside id="zoo-' . zoo_product_sidebar() . '-sidebar" class="' . $zoo_sidebar_class . '">',$allow_html);
    ?>
    <div class="top-sidebar ">
        <span><?php esc_html_e('Filter', 'anon') ?></span>
        <a href="#" class="close-btn close-sidebar">
            <i class="zoo-icon-close"></i>
        </a>
    </div>
    <div class="wrap-product-sidebar">
<?php
dynamic_sidebar($widget);
echo wp_kses('</div></aside>',$allow_html);
endif;