<?php
/**
 * Public Hooks
 *
 * Hooks used on public screens only.
 *
 * @package  Zoo_Theme\Core\Admin
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */

 /**
  * Add drop-down icon for menus
  *
  * @see  https://developer.wordpress.org/reference/hooks/nav_menu_item_title/
  */
 add_filter('nav_menu_item_title', function ($title, $item, $args, $depth) {
     if (in_array('menu-item-has-children', $item->classes)) {
         $menu_locations = get_nav_menu_locations();
         if(isset($args->menu->term_id)) {
             if (!empty($menu_locations['primary-menu']) && $menu_locations['primary-menu'] == $args->menu->term_id && !zoo_customize_get_setting('header_primary_menu_hide_arrow')) {
                 $title .= '<span class="zoo-icon-down"></span>';
             } elseif (!empty($menu_locations['top-menu']) && $menu_locations['top-menu'] == $args->menu->term_id && !zoo_customize_get_setting('header_top_menu_hide_arrow')) {
                 $title .= '<span class="zoo-icon-down"></span>';
             } elseif (!empty($menu_locations['mobile-menu']) && $menu_locations['mobile-menu'] == $args->menu->term_id && !zoo_customize_get_setting('header_mobile_menu__hide-arrow')) {
                 $title .= '<span class="zoo-icon-down"></span>';
             }
         }
     }

     return $title;
 }, 25, 4);

/**
 * @see  https://developer.wordpress.org/reference/hooks/wp_head/
 */
add_action('wp_enqueue_scripts', function () {
    $settings = get_option(ZOO_SETTINGS_KEY);

    if (!empty($settings['header_scripts'])) {
        wp_add_inline_script('jquery-core', wp_unslash($settings['header_scripts']));
    }
}, PHP_INT_MAX, 0);

/**
 * @see  https://developer.wordpress.org/reference/hooks/wp_head/
 */
add_action('wp_enqueue_scripts', function () {
    $settings = get_option(ZOO_SETTINGS_KEY);
    if (!empty($settings['footer_scripts'])) {
        wp_add_inline_script('zoo-scripts', wp_unslash($settings['footer_scripts']));
    }
}, PHP_INT_MAX, 0);

/**
 * Enqueue frontend styles and scripts
 */
add_action('wp_enqueue_scripts', function () {
    $zoo_auto_css = Zoo_Customize_Live_CSS::get_instance();
    $theme_options = get_option(ZOO_SETTINGS_KEY, []);

    unset($theme_options['header_scripts'], $theme_options['footer_scripts']);

    $theme_options['isRtl'] = is_rtl();
    $theme_options['ajaxUrl'] = admin_url('admin-ajax.php');

    wp_enqueue_style('clever-font', ZOO_THEME_URI . 'assets/vendor/cleverfont/style.min.css', [], ZOO_THEME_VERSION);

    if (class_exists('WooCommerce', false)) {
        if(get_theme_mod('zoo_enable_wishlist','1')){
            wp_enqueue_script('zoo-wishlist', ZOO_THEME_URI . 'core/assets/js/wishlist' . ZOO_JS_SUFFIX, ['jquery-core'], ZOO_THEME_VERSION, true);
            $add_to_wishlist_icon = get_theme_mod('zoo_icon_add_to_wishlist', ['type' => 'zoo-icon', 'icon' => 'zoo-icon-heart-o']);
            if ($add_to_wishlist_icon) {
                $add_to_wishlist_icon = '<i class="' . $add_to_wishlist_icon['icon'] . '"></i> ';
            } else {
                $add_to_wishlist_icon = '';
            }
            $browse_to_wishlist_icon = get_theme_mod('zoo_icon_browse_to_wishlist', ['type' => 'zoo-icon', 'icon' => 'zoo-icon-heart']);
            if ($browse_to_wishlist_icon) {
                $browse_to_wishlist_icon = '<i class="' . $browse_to_wishlist_icon['icon'] . '"></i> ';
            } else {
                $browse_to_wishlist_icon = '';
            }

            wp_localize_script('zoo-wishlist', 'zooWishlistCDATA', [
                'addToWishlist' => get_theme_mod('zoo_text_add_to_wishlist', esc_html__('Add to Wishlist', 'anon')),
                'addToWishlistIcon' => $add_to_wishlist_icon,
                'browseWishlist' => get_theme_mod('zoo_text_browse_to_wishlist', esc_html__('Browse Wishlist', 'anon')),
                'browseWishlistIcon' => $browse_to_wishlist_icon,
                'addToWishlistErr' => esc_html__('Failed to add the item to Wishlist.', 'anon'),
                'wishlistIsEmpty' => esc_html__('Wishlist is empty.', 'anon')
            ]);
        }
        if(get_theme_mod('zoo_enable_compare','1')) {
            wp_enqueue_script( 'zoo-products-compare', ZOO_THEME_URI . 'core/assets/js/products-compare' . ZOO_JS_SUFFIX, [ 'jquery-core' ], ZOO_THEME_VERSION, true );
            $add_to_compare_icon = get_theme_mod( 'zoo_icon_add_to_compare', [
                'type' => 'zoo-icon',
                'icon' => 'zoo-icon-refresh'
            ] );
            if ( $add_to_compare_icon ) {
                $add_to_compare_icon = '<i class="' . $add_to_compare_icon['icon'] . '"></i> ';
            } else {
                $add_to_compare_icon = '';
            }
            $browse_to_compare_icon = get_theme_mod( 'zoo_icon_browse_to_compare', [
                'type' => 'zoo-icon',
                'icon' => 'zoo-icon-refresh'
            ] );
            if ( $browse_to_compare_icon ) {
                $browse_to_compare_icon = '<i class="' . $browse_to_compare_icon['icon'] . '"></i> ';
            } else {
                $browse_to_compare_icon = '';
            }

            wp_localize_script( 'zoo-products-compare', 'zooProductsCompareCDATA', [
                'addToCompare'      => get_theme_mod( 'zoo_text_add_to_compare', esc_html__( 'Add to Compare', 'anon' ) ),
                'addToCompareIcon'  => $add_to_compare_icon,
                'browseCompare'     => get_theme_mod( 'zoo_text_browse_to_compare', esc_html__( 'Browse Compare', 'anon' ) ),
                'browseCompareIcon' => $browse_to_compare_icon,
                'addToCompareErr'   => esc_html__( 'Failed to add the item to compare list.', 'anon' ),
                'compareIsEmpty'    => esc_html__( 'No products to compare.', 'anon' )
            ] );
        }
    }

    wp_localize_script('jquery-core', 'zooThemeSettings', $theme_options);

    wp_add_inline_style('zoo-styles', $zoo_auto_css->auto_css());

    if ($google_fonts_url = $zoo_auto_css->get_font_url()) {
        wp_enqueue_style('zoo-google-fonts', $google_fonts_url, [], ZOO_THEME_VERSION);
    }
}, 10, 0);

/**
 * @see  https://developer.wordpress.org/reference/hooks/default_title/
 * @see  https://developer.wordpress.org/reference/hooks/default_content/
 * @see  https://developer.wordpress.org/reference/functions/current_filter/
 */
if (function_exists('pll_get_post')) { // make sure that Polylang activated.
    function zoo_localize_pll_post_content($content, $post)
    {
        $filter = current_filter();
        $from_post = isset($_GET['from_post']) ? (int)$_GET['from_post'] : false;

        if ($content == '') {
            $from_post = get_post($from_post);
            if ($from_post) {
                switch ($filter) {
                    case 'default_content':
                        $content = $from_post->post_content;
                        break;
                    case 'default_title':
                        $content = $from_post->post_title;
                        break;
                    default:
                        $content = apply_filters('zoo_localize_pll_post_content', $content, $from_post);
                        break;
                }
            }
        }

        return $content;
    }

    add_filter('default_title', 'zoo_localize_pll_post_content', 100, 2);
    add_filter('default_content', 'zoo_localize_pll_post_content', 100, 2);
}
