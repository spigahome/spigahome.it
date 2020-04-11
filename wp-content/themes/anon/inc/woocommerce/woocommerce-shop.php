<?php
/**
 * WooCommerce shop page function for control features and templates.
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 * @des         All custom function, templates, hooks for shop page of WooCommerce will add here.
 */

/**
 * Insert add to wishlist button
 * Add button wishlist to hook woocommerce_after_add_to_cart_button of single product page
 * Add button wishlist to hook zoo_product_button of shop loop with conditional enable wishlist in shop loop
 */
if (get_theme_mod('zoo_enable_wishlist', '1') == 1 && class_exists('CleverAddons')) {
    add_action('woocommerce_after_add_to_cart_button', 'zoo_button_wishlist', 10, 0);
    if (get_theme_mod('zoo_enable_shop_loop_wishlist', '1') == 1) {
        add_action('zoo_product_button', 'zoo_button_wishlist', 5);
    }
}
function zoo_button_wishlist()
{
    if (get_theme_mod('zoo_enable_wishlist', '1') == 1) {
        global $product;
        $class = 'button zoo-wishlist-button add-to-wishlist';
        $url = '#';
        $icon = get_theme_mod('zoo_icon_add_to_wishlist', ['type' => 'zoo-icon', 'icon' => 'zoo-icon-heart-o']);
        if ($icon) {
            $icon = '<i class="' . $icon['icon'] . '"></i>';

        } else {
            $icon = '';
        }
        if (get_theme_mod('zoo_wishlist_page', '') != '') {
            $url = get_page_link(get_page_by_path(get_theme_mod('zoo_wishlist_page', '')));
        }
        if (get_theme_mod('zoo_enable_wishlist_redirect', '1') == 1 && $url != '#') {
            $class .= ' redirect-link';
        }
        echo apply_filters('zoo_button_wishlist', sprintf(
            '<a href="%s" class="%s" rel="nofollow" data-id="%s" title="%s" data-label-added="%s">%s %s</a>',
            $url,
            $class,
            esc_attr($product->get_id()),
            get_theme_mod('zoo_text_add_to_wishlist', esc_attr__('Add to wishlist', 'anon')),
            esc_attr__('Product Added', 'anon'),
            $icon,
            get_theme_mod('zoo_text_add_to_wishlist', esc_html__('Add to wishlist', 'anon'))
        ));
    } else {
        return false;
    }
}

/**
 * Insert add to Compare button
 * Add button compare to hook woocommerce_after_add_to_cart_button of single product page
 * Add button compare to hook zoo_product_button of shop loop with conditional enable compare in shop loop
 */
if (get_theme_mod('zoo_enable_compare', '1') == 1 && class_exists('CleverAddons')) {
    add_action('woocommerce_after_add_to_cart_button', 'zoo_button_products_compare', 10, 0);
    if (get_theme_mod('zoo_enable_shop_loop_compare', '0') == 1) {
        add_action('zoo_product_button', 'zoo_button_products_compare', 10);
    }
}

function zoo_button_products_compare()
{
    if (get_theme_mod('zoo_enable_compare', '1') == 1) {
        global $product;
        $class = 'button zoo-compare-button add-to-products-compare';
        $url = '#';
        if (get_theme_mod('zoo_compare_page', '') != '') {
            $url = get_page_link(get_page_by_path(get_theme_mod('zoo_compare_page', '')));
        }
        $icon = get_theme_mod('zoo_icon_add_to_compare', ['type' => 'zoo-icon', 'icon' => 'zoo-icon-refresh']);
        if ($icon) {
            $icon = '<i class="' . $icon['icon'] . '"></i>';

        } else {
            $icon = '';
        }

        if (get_theme_mod('zoo_enable_compare_redirect', '0') == 1 && $url != '#') {
            $class .= ' redirect-link';
        }
        echo apply_filters('zoo_button_products_compare', sprintf(
            '<a href="%s" class="%s" rel="nofollow" data-id="%s" title="%s"  data-label-added="%s">%s %s</a>',
            $url,
            $class,
            esc_attr($product->get_id()),
            get_theme_mod('zoo_text_add_to_compare', esc_attr__('Add to compare', 'anon')),
            esc_attr__('Product Added', 'anon'),
            $icon,
            get_theme_mod('zoo_text_add_to_compare', esc_html__('Add to compare', 'anon'))
        ));
    } else {
        return false;
    }
}


/**
 * Add open wrapper for woocommerce breadcrumb
 * @uses  hook to  woocommerce_before_main_content
 */

function zoo_open_wrap_woo_breadcrumb()
{
    $allow_html = array('div' => array('class' => array()));
    echo wp_kses('<div class="wrap-breadcrumb"><div class="container">', $allow_html);
}

/**
 * Add close wrapper for woocommerce breadcrumb
 * @uses  hook to  woocommerce_before_main_content
 */
function zoo_close_wrap_woo_breadcrumb()
{
    $allow_html = array('div' => array('class' => array()));
    echo wp_kses('</div></div>', $allow_html);
}


/**
 * Change the breadcrumb separator
 */

if (!function_exists('zoo_change_breadcrumb_delimiter')) {
    function zoo_change_breadcrumb_delimiter($defaults)
    {
        // Change the breadcrumb delimeter from '/' to '>'
        $defaults['delimiter'] = ' <span class="zoo-separator"></span> ';

        return $defaults;
    }
}

if (get_theme_mod('zoo_disable_breadcrumbs', '0') == '1') {
    function woocommerce_breadcrumb()
    {
        return;
    }
}
add_action('woocommerce_before_main_content', 'zoo_open_wrap_woo_breadcrumb', 4);
add_action('woocommerce_before_main_content', 'zoo_close_wrap_woo_breadcrumb', 6);
add_filter('woocommerce_breadcrumb_defaults', 'zoo_change_breadcrumb_delimiter');
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 5);

/**
 * Zoo shop page title and count.
 * Display category name, shop and count of product.
 * @uses hook zoo_shop_title to hook  woocommerce_archive_description
 * @return string html Shop page heading
 */
if (!function_exists('zoo_shop_title')) {
    function zoo_shop_title()
    {
        get_template_part('inc/templates/woocommerce/loop/shop', 'title');
    }
}
add_action('woocommerce_before_main_content', 'zoo_shop_title', 5);

/**
 * woocommerce_output_content_wrapper
 * Change wrapper page of shop page by html of theme
 * @uses override function woocommerce_output_content_wrapper
 * @return html wrapper of theme.
 */
function woocommerce_output_content_wrapper()
{
    $allow_html = array('main' => array('id' => array(), 'class' => array()), 'div' => array('class' => array(), 'id' => array()));
    if (is_product()) {
        $zoo_class = 'container';
    } else {
        $zoo_class = 'zoo-wrap-product-shop-loop ' . zoo_wrap_shop_loop_class();
    }
    echo wp_kses('<main id="site-main-content" class="main-content"><div  class="' . $zoo_class . '">', $allow_html);
}

/**
 * woocommerce_output_content_wrapper_end
 * Change close wrapper page of shop page by html of theme
 * @uses override function woocommerce_output_content_wrapper_end
 * @return html wrapper of theme.
 */
function woocommerce_output_content_wrapper_end()
{
    $allow_html = array('main' => array('id' => array(), 'class' => array()), 'div' => array('id' => array(), 'class' => array()));
    echo wp_kses('</div></main>', $allow_html);
}

/**
 * Change product display per page
 * @uses filter loop_shop_per_page for apply number product display per page.
 * @return number product display per page.
 */
add_filter('loop_shop_per_page', 'zoo_product_per_page');
function zoo_product_per_page()
{
    return get_theme_mod('zoo_products_number_items', '9');
}

/**
 * Remove title page and change it by page heading
 * @uses hook woocommerce_archive_description for remove.
 * @return template product heading page.
 */
add_filter('woocommerce_show_page_title', '__return_null');
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);

/**
 * Zoo shop page description.
 * Display product category and taxonomy description.
 * With shop page, It will display content of shop page follow content field of that page.
 * @uses hook zoo_shop_description to hook  woocommerce_archive_description
 * @return string html Shop page heading
 */
if (!function_exists('zoo_shop_description')) {
    function zoo_shop_description()
    {
        get_template_part('inc/templates/woocommerce/loop/shop', 'description');
    }
}
add_action('woocommerce_archive_description', 'zoo_shop_description', 10);

/**
 * Woocommerce Shop page Sidebar
 * @uses: Check and return sidebar display in shop page.
 * @return: Sidebar
 * */
if (!function_exists('zoo_product_sidebar')) {
    function zoo_product_sidebar()
    {
        $zoo_product_sidebar = get_theme_mod('zoo_shop_sidebar', 'off-canvas');
        if (isset($_GET['sidebar'])):
            if ($_GET['sidebar'] == 'left' || $_GET['sidebar'] == 'top' || $_GET['sidebar'] == 'right') {
                $zoo_product_sidebar = $_GET['sidebar'];
            } else {
                $zoo_product_sidebar = 'off-canvas';
            }
        endif;

        return $zoo_product_sidebar;
    }
}

/**
 * Remove default location woocommerce_sidebar if sidebar config is off canvas.
 * @uses using hook for remove
 * @return woocommerce_sidebar without woocommerce_get_sidebar.
 */
if (zoo_product_sidebar() != 'off-canvas') {
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}


/**
 * Zoo wrap shop loop class
 * @uses call function zoo_wrap_shop_loop_class()
 * @return class css with config full width or border.
 *
 */
if (!function_exists('zoo_wrap_shop_loop_class')) {
    function zoo_wrap_shop_loop_class()
    {
        $css_class = zoo_product_sidebar() . '-sidebar-layout';
        if (get_theme_mod('zoo_shop_full_width', '0') == 1 || isset($_GET['shop-full-width'])) {
            $css_class .= ' container-fluid';
        } else {
            $css_class .= ' container';
        }
        if (get_theme_mod('zoo_enable_shop_loop_item_border', '0') == 1 || isset($_GET['loop-item-border'])) {
            $css_class .= ' products-border';
        }
        return $css_class;
    }
}
/**
 * Open Top block woocommerce_open_shop_loop.
 * @close at zoo_close_top_products_page.
 * @uses hook this function to woocommerce_before_shop_loop
 * @return html wrap top products page with grid control sidebar-control.
 */
if (!function_exists('zoo_open_top_products_page')) {
    function zoo_open_top_products_page()
    {
        return get_template_part('inc/templates/woocommerce/loop/open-top-shop', 'page');
    }
}
add_action('woocommerce_before_shop_loop', 'zoo_open_top_products_page', 15);
/**
 * Remove woocommerce_result_count woocommerce_catalog_ordering from woocommerce_before_shop_loop
 * Add to template cutom hook
 * */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
add_action('zoo_woocommerce_result_count', 'woocommerce_result_count', 10);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
add_action('zoo_woocommerce_catalog_ordering', 'woocommerce_catalog_ordering', 10);

/**
 * Add wrapper for woocommerce_before_shop_loop and sidebar
 * @close at zoo_after_shop_loop.
 * @uses Use function zoo_before_shop_loop and hook to woocommerce_before_shop_loop
 * @return custom html for open before shop loop of theme.
 */
if (!function_exists('zoo_before_shop_loop')) {
    function zoo_before_shop_loop()
    {
        if (!is_single()):
            $allow_html = array(
                'div' => array('class' => array(), 'id' => array()),
            );
            $widget = 'shop';
            if (zoo_product_sidebar() == 'top') {
                $widget = 'top-shop';
            }
            echo wp_kses('<div class="row">', $allow_html);
            if (zoo_product_sidebar() != 'off-canvas' && zoo_product_sidebar() != 'top') {
                woocommerce_get_sidebar();
            }
            if (zoo_product_sidebar() == 'off-canvas' || zoo_product_sidebar() == 'top' || !is_active_sidebar($widget)) {
                echo wp_kses('<div class="zoo-products-shop-loop col-12">', $allow_html);
            } else {
                echo wp_kses('<div class="zoo-products-shop-loop col-12 col-lg-9">', $allow_html);
            }
        endif;
    }
}
add_action('woocommerce_before_main_content', 'zoo_before_shop_loop', 35);

/**
 * Close wrapper for woocommerce_before_shop_loop
 * @uses Use function zoo_before_shop_loop and hook to woocommerce_before_shop_loop
 * @return custom html for open before shop loop of theme.
 */
if (!function_exists('zoo_after_shop_loop')) {
    function zoo_after_shop_loop()
    {
        $allow_html = array(
            'div' => array('class' => array()),
        );
        echo wp_kses('</div></div>', $allow_html);
    }
}
add_action('woocommerce_after_shop_loop', 'zoo_after_shop_loop', 999);

/**
 * Override woocommerce_get_sidebar
 * @return return template sidebar of theme.
 */
function woocommerce_get_sidebar()
{
    if (!is_single()) {
        wc_get_template('shop-sidebar.php');
    }
}

/**
 * Zoo get product columns shop page
 * @uses call function zoo_shop_columns() with parameter device (mobile/table/desktop)
 * @return this function will return shop columns follow device.
 **/
if (!function_exists('zoo_shop_columns')) {
    function zoo_shop_columns($device = 'desktop')
    {
        if ($device == 'tablet') {
            $cols = get_theme_mod('zoo_shop_cols_' . $device, 2);
        } elseif ($device == 'mobile') {
            $cols = get_theme_mod('zoo_shop_cols_' . $device, 2);
        } else {
            if (isset($_GET['cols'])) {
                $cols = $_GET['cols'];
            } else {
                $cols = get_theme_mod('zoo_shop_cols_' . $device, 3);
            }
        }

        return $cols;
    }
}
/**
 * Change html of woocommerce_product_loop_start
 * @return html open loop start of list product, apply for shop page, cart page, shortcode...
 */
if (!function_exists('zoo_product_loop_start')) {
    function zoo_product_loop_start($wc_loop_start)
    {

        $product_loop_class = 'products zoo-products';
        $product_loop_class .= ' hover-effect-' . zoo_product_hover_effect();
        if (isset($_COOKIE['zoo_product_layout'])) {
            $product_loop_class .= ' list-layout';
        } else {
            $product_loop_class .= ' grid-layout';
        }
        if (zoo_highlight_featured() == '1') {
            $product_loop_class .= ' highlight-featured';
        }
        if (get_theme_mod('zoo_enable_shop_loop_cart', '0') != 1 || zoo_enable_catalog_mod()) {
            $product_loop_class .= ' disable-cart';
        }
        $cats = woocommerce_maybe_show_product_subcategories();

        if (!is_page() || is_cart() || is_shop()) {
            $data_config = '{"cols":"' . zoo_shop_columns() . '","tablet":"' . zoo_shop_columns("tablet") . '","mobile":"' . zoo_shop_columns("mobile") . '","highlight_featured":"' . zoo_highlight_featured() . '"}';
            $product_loop_class .= ' grid-lg-' . zoo_shop_columns() . '-cols grid-md-' . zoo_shop_columns("tablet") . '-cols grid-' . zoo_shop_columns("mobile") . '-cols';
        } else {
            $data_config = '{"cols":"' . wc_get_loop_prop('columns') . '"}';
        }
        if ($data_config) {
            if (zoo_highlight_featured() == '1') {
                wp_enqueue_script('isotope');
            }

            return ent2ncr('<ul class="' . $product_loop_class . '" data-zoo-config=\'' . $data_config . '\'>' . $cats);
        } else {
            return ent2ncr('<ul class="' . $product_loop_class . '">' . $cats);
        }

    }
}
add_filter('woocommerce_product_loop_start', 'zoo_product_loop_start');
/**
 * Remove open link wrap product item.
 * @uses override WooCommerce function woocommerce_template_loop_product_link_open()
 * @return custom template of theme.
 */
function woocommerce_template_loop_product_link_open()
{
    return false;
}

/**
 * Add open div for wrap product item content and product image.
 * @close at zoo_close_wrap_img.
 * @uses add to action woocommerce_before_shop_loop_item
 * @return custom template of theme.
 */
function zoo_woocommerce_template_loop_product_open()
{
    $allow_html = array(
        'div' => array('class' => array()),
    );
    echo wp_kses('<div class="wrap-product-loop-content"><div class="wrap-product-img">', $allow_html);
}
add_action('woocommerce_before_shop_loop_item', 'zoo_woocommerce_template_loop_product_open', 0);


/**
 * Close div wrap-product image and open div wrap-product-loop-detail
 * @close at woocommerce_template_loop_product_link_close.
 * @uses using hook woocommerce_shop_loop_item_title
 * @return custom template wrap product loop detail.
 */
function zoo_close_wrap_img()
{
    $allow_html = array(
        'div' => array('class' => array()),
    );
    echo wp_kses('</div><div class="wrap-product-loop-detail">', $allow_html);
}

add_action('woocommerce_shop_loop_item_title', 'zoo_close_wrap_img', 0);

/**
 * Remove woocommerce_template_loop_product_link_close by return false
 * @return bool false.
 */
function woocommerce_template_loop_product_link_close()
{
    return false;
}

/**
 * Close open div.wrap-product-loop-content and div.wrap-product-loop-detail of zoo_woocommerce_template_loop_product_open
 * @uses Auto apply by using hook woocommerce_after_shop_loop_item.
 * @return close html for custom html of theme.
 */
function zoo_woocommerce_template_loop_product_close()
{
    $allow_html = array(
        'div' => array('class' => array()),
    );
    echo wp_kses('</div></div>', $allow_html);
}

add_action('woocommerce_after_shop_loop_item', 'zoo_woocommerce_template_loop_product_close', 999);

/**
 * Change woocommerce_template_loop_product_title by custom title of theme with product url.
 * @uses override function woocommerce_template_loop_product_title.
 * @return custom html title product with url.
 *
 * */
function woocommerce_template_loop_product_title()
{
    $allow_html = array(
        'h3' => array('class' => array()),
        'a' => array('class' => array(), 'href' => array(), 'title' => array()),
    );
    echo wp_kses('<h3 class="product-loop-title"><a href="' . get_the_permalink() . '" title="' . the_title_attribute('echo=0') . '">' . get_the_title() . '</a></h3>', $allow_html);
}

/**
 * Add lazy img for product
 * Change default image template of WooCommerce shop loop by custom function of theme with feature lazy load image for improve site load.
 * @uses overrider function, just add and it will work.
 * @return template loop product thumbnail with lazyload.
 */
if (!function_exists('woocommerce_template_loop_product_thumbnail')) {
    function woocommerce_template_loop_product_thumbnail()
    {
        global $product;
        wp_enqueue_script('lazyload');
        $zoo_img = $product->get_image_id();
        if ($zoo_img) :
            ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"
               class="wrap-img">
                <?php
                echo wp_get_attachment_image($zoo_img, 'woocommerce_thumbnail');
                do_action('zoo_loop_alternative_images');
                ?>
            </a>
        <?php
        endif;
    }
}
/**
 * Alternative
 * display alternative image of shop loop product.
 * @uses with function will work with woocommerce_template_loop_product_thumbnail().
 * @return 1st image of product gallery.
 */
if (!function_exists('zoo_alternative_images')) {
    function zoo_alternative_images()
    {
        $gallery = get_post_meta(get_the_ID(), '_product_image_gallery', true);
        if (!empty($gallery)) {
            $gallery = explode(',', $gallery);
            $first_image_id = $gallery[0];
            echo wp_get_attachment_image($first_image_id, 'woocommerce_thumbnail', '', array('class' => 'sec-img hover-image'));
        } else {
            return false;
        }
    }
}
if (zoo_show_alternate_img()) {
    add_action('zoo_loop_alternative_images', 'zoo_alternative_images', 10);
}

/**
 * Quick view feature
 * All template and script of product quick view is add here
 */

/**
 * Quick view button
 * function add quickview button to shop loop product
 * @uses  hook function zoo_template_quick_view_btn to hook want use.
 * @return template button quick view.
 */
if (!function_exists('zoo_template_quick_view_btn')) {
    function zoo_template_quick_view_btn()
    {
        $quick_view_btn = '<a href="#" class="button btn-quick-view" title="' . esc_attr__('Quick view', 'anon') . '" data-productID="' . get_the_ID() . '"><i class="zoo-icon-eye"></i> ' . esc_html__('Quick view', 'anon') . '</a>';
        echo ent2ncr($quick_view_btn);
    }
}
if (get_theme_mod('zoo_enable_quick_view', '1') == 1) {
    /**
     * Add Quick view button to shop loop
     * Add to hook zoo_product_button if hover effect  is not default style
     * Add to woocommerce_before_shop_loop_item_title if hover effect is default style
     */
    if (zoo_product_hover_effect() != 'default') {
        add_action('zoo_product_button', 'zoo_template_quick_view_btn', 15);
    } else {
        add_action('woocommerce_before_shop_loop_item_title', 'zoo_template_quick_view_btn', 15);
    }
    /**
     * Quick view script
     * function loading script of quick view
     * @uses  hook function zoo_quick_view_scripts add to hook woocommerce_before_main_content.
     * @return script need for product quick view work.
     */
    if (!function_exists('zoo_quick_view_scripts')) {
        function zoo_quick_view_scripts()
        {
            if (get_theme_mod('zoo_enable_wishlist', '1') == 1 && class_exists('CleverAddons')) {
                wp_enqueue_script('zoo-wishlist');
            }
            if (get_theme_mod('zoo_enable_compare', '1') == 1 && class_exists('CleverAddons')) {
                wp_enqueue_script('zoo-products-compare');
            }
            wp_enqueue_script('wc-add-to-cart-variation');
            wp_enqueue_script('slick');
        }
    }
    add_action('woocommerce_before_main_content', 'zoo_quick_view_scripts', 5);
    /**
     * Quick view change wrap images class
     * function change images wrap class of template quickview
     * @uses  use add_filter for change class.
     * @return custom class of images template.
     */
    if (!function_exists('zoo_quick_view_change_images_class')) {
        function zoo_quick_view_change_images_class()
        {
            return array(
                'images',
                'zoo-product-gallery',
                (has_post_thumbnail() ? 'with-images' : 'without-images')
            );
        }
    }
}
/**
 * Shop stock template label
 * Display label if product is out of stock or low of stock.
 * @uses hook to location want use
 * @return template out of stock and notice low stock label
 */
if (!function_exists('zoo_shop_stock_label')) {
    function zoo_shop_stock_label()
    {
        global $product;
        if (!$product->is_in_stock()) {
            ?>
            <span class="out-stock-label zoo-stock-label"><?php esc_html_e('Out of Stock', 'anon'); ?></span>
            <?php
        } else {
            if (get_option('woocommerce_notify_low_stock_amount') > $product->get_stock_quantity() && $product->get_stock_quantity()) {
                ?>
                <span class="low-stock-label zoo-stock-label"><?php esc_html_e('Low Stock', 'anon'); ?></span>
                <?php
            }
        }
    }
}
if (get_theme_mod('zoo_enable_shop_stock_label', '1') == 1) {
    add_action('woocommerce_before_shop_loop_item_title', 'zoo_shop_stock_label', 10);
}
/**
 * Shop New template label
 * Display label if product is new.
 * @uses hook to location want use
 * @return template new label
 */

if (!function_exists('zoo_shop_new_label')) {
    function zoo_shop_new_label()
    {
        global $product;

        if (get_post_meta($product->get_id(), 'zoo_single_product_new', true) == 1) {
            ?>
            <span class="zoo-new-label"><?php esc_html_e('New', 'anon'); ?></span>
            <?php
        }
    }
}
if (get_theme_mod('zoo_enable_shop_new_label', '1') == 1) {
    add_action('woocommerce_before_shop_loop_item_title', 'zoo_shop_new_label', 10);
}
/**
 * Shop loop rating
 * Display or hide shop loop rating.
 * @uses change return value of woocommerce_template_loop_rating if shop loop rating disable
 * @return false
 */
if (get_theme_mod('zoo_enable_shop_loop_rating', '0') != 1) {
    function woocommerce_template_loop_rating()
    {
        return false;
    }
}
/**
 * Product description
 * Add product description to list product in shop loop
 * @uses hook to woocommerce_after_shop_loop_item_title
 * @return string product description
 * */
function zoo_product_description()
{
    if (is_shop() || is_archive()) {
        global $product;
        $description = $product->get_short_description();
        if ($description != '') {
            echo wp_kses_post('<div class="product-description">' . $description . '</div>');
        }
    }
}

add_action('woocommerce_after_shop_loop_item_title', 'zoo_product_description', 15);

/**
 * Add Cart icon
 * Add custom cart icon by using add_filter
 */
if (get_theme_mod('zoo_shop_cart_icon', 'zoo-icon-cart') != '') {
    function zoo_get_icon_cart($url, $product, $args)
    {
        $icon_cart = '<i class="zoo-icon-cart"></i>';
        $temp_icon_cart = get_theme_mod('zoo_shop_cart_icon');
        if ($temp_icon_cart['icon'] != '') {
            $icon_cart = '<i class="' . $temp_icon_cart['icon'] . '"></i> ';
        }
        return sprintf('<a href="%s" data-quantity="%s" class="%s" title="%s" %s>%s%s</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
            esc_attr(isset($args['class']) ? $args['class'] : 'button'),
            esc_html($product->add_to_cart_text()),
            isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
            $icon_cart,
            esc_html($product->add_to_cart_text())
        );
    }

    add_filter('woocommerce_loop_add_to_cart_link', 'zoo_get_icon_cart', 1, 3);
}

/**
 * Disable Shop loop cart
 * Remove cart button in shop page if feature enable shop loop cart is disable or catalog mod enable.
 * @uses change return value of function woocommerce_template_loop_add_to_cart if disable shop loop cart enable
 * @return false
 */

if (get_theme_mod('zoo_enable_shop_loop_cart', '0') != 1 || zoo_enable_catalog_mod()) {
    function woocommerce_template_loop_add_to_cart()
    {
        return false;
    }
}

/**
 * Sale flash template type number.
 * @uses: Override default sale flash by number type if user config sale flash display is number.
 * @template: Default template /loop/sale-flash.php
 * */
if (!function_exists('zoo_sale_flash_number')) {
    function zoo_sale_flash_number()
    {
        global $product;
        if (!$product->is_type('grouped')) {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            if ($product->has_child()) {
                $variation_prices = $product->get_variation_prices();
                $percent = 0;
                foreach ($variation_prices['price'] as $id => $value) {
                    $new_percent = round((($variation_prices['regular_price'][$id] - $value) / $variation_prices['regular_price'][$id]) * 100);
                    if ($new_percent > $percent) {
                        $percent = $new_percent;
                    }
                }
                $regular_price = $product->get_variation_regular_price('max', true);
            } else {
                $percent = round((($regular_price - $sale_price) / $regular_price) * 100);
            }
            if ($regular_price != '') {
                return '<span class="onsale numeric">-' . $percent . '%</span>';
            }
        }
    }
}
if (get_theme_mod('zoo_sale_type', 'text') == 'numeric') {
    add_filter('woocommerce_sale_flash', 'zoo_sale_flash_number');
}


/**
 * High light Featured product
 * @uses call function zoo_highlight_featured
 * @return int 0 or 1
 */
if (!function_exists('zoo_highlight_featured')) {
    function zoo_highlight_featured()
    {
        $zoo_highlight_featured = get_theme_mod('zoo_enable_highlight_featured_product', '0');
        if (isset($_GET['zoo_highlight_featured'])) {
            $zoo_highlight_featured = $_GET['zoo_highlight_featured'];
        }

        return $zoo_highlight_featured;
    }
}

/**
 * Loop Sale countdown template
 * It add to template by using hook woocommerce_before_shop_loop_item_title
 * Change location of this template by change hook woocommerce_before_shop_loop_item_title
 * @return: Sale Countdown template apply for shop loop
 */
if (!function_exists('zoo_loop_sale_countdown')) {
    function zoo_loop_sale_countdown()
    {
        return get_template_part('inc/templates/woocommerce/global/sale-count', 'down');
    }

}
add_action('woocommerce_before_shop_loop_item_title', 'zoo_loop_sale_countdown', 10);

/**
 * Hover effect control template
 * Change location of button add to cart by using hook woocommerce_template_loop_add_to_cart follow layout style.
 * @description: All hover effect will control at here. 6 Default style effect
 * @return: html for hover effect
 */
if (zoo_product_hover_effect() != 'style-6') {
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
}
if (zoo_product_hover_effect() != 'style-5' && zoo_product_hover_effect() != 'style-6') {
    add_action('zoo_product_button', 'woocommerce_template_loop_add_to_cart', 0);
}
if (zoo_product_hover_effect() == 'style-5') {
    add_action('zoo_after_product_button_hover_effect', 'woocommerce_template_loop_add_to_cart', 5);
}
function zoo_product_button_hover_effect()
{
    echo '<div class="wrap-product-loop-buttons">';
    /**
     * Do action zoo_product_button for add another button to zoo_product_button action
     * */
    do_action('zoo_product_button');
    echo '</div>';
    /**
     * Do action zoo_after_product_button_hover_effect for add another button to zoo_product_button action
     * */
    do_action('zoo_after_product_button_hover_effect');
}

if (zoo_product_hover_effect() == 'style-3') {
    add_action('woocommerce_after_shop_loop_item', 'zoo_product_button_hover_effect', 15);
} else {
    add_action('woocommerce_before_shop_loop_item_title', 'zoo_product_button_hover_effect', 15);
}
/**
 * Zoo No Product template
 * @description: Add wrap for no product template
 * @return html no product template
 */
function zoo_open_wrap_no_product()
{
    $allow_html = array('div' => array('class' => array()));
    echo wp_kses('<div class="no-product-found">', $allow_html);
}

add_action('woocommerce_no_products_found', 'zoo_open_wrap_no_product', 0);
function zoo_close_wrap_no_product()
{
    $allow_html = array(
        'div' => array('class' => array()),
        'a' => array('class' => array(), 'href' => array(), 'title' => array())
    );
    echo wp_kses('<a href="' . get_permalink(wc_get_page_id('shop')) . '" title="' . esc_attr__('Return to Shop', 'anon') . '" class="button return-shop">' . esc_html__('Return to Shop', 'anon') . '</a>', $allow_html);
    echo wp_kses('</div>', $allow_html);
}

add_action('woocommerce_no_products_found', 'zoo_close_wrap_no_product', 99);

/**
 * Woocommerce Pagination
 * Add custom arrow icon for pagination
 */
if (!function_exists('zoo_shop_pagination')) {
    function zoo_shop_pagination($args = array())
    {
        $args['prev_text'] = '<i class="zoo-icon-arrow-left"></i>';
        $args['next_text'] = '<i class="zoo-icon-arrow-right"></i>';

        return $args;
    }

    add_filter('woocommerce_pagination_args', 'zoo_shop_pagination', 10);
}
