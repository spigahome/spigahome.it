<?php
/**
 * @version  2.0.0
 * @package  clever-swatches/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$woo_settings = new WC_Admin_Settings();

$current_tab = "general";

if (isset($_GET['tab'])) {
    $current_tab = $_GET['tab'];
}
$authenticate_notice = array();

if (isset($_POST['save'])) {
    if ($current_tab == "general") {
        $general_settings_array = array();
        $enable_tooltip = isset($_POST['zoo_cw_enable_tooltip']) ? 1 : 0;
        $enable_swatch = isset($_POST['zoo_cw_enable_swatch']) ? 1 : 0;
        $enable_product_gallery = isset($_POST['zoo_cw_enable_product_gallery']) ? 1 : 0;
        $enable_cw_gallery = !empty($_POST['zoo_enable_cw_gallery']) ? 1 : 0;
        $cw_gallery_layout = !empty($_POST['zoo_cw_gallery_layout']) ? $_POST['zoo_cw_gallery_layout'] : 'horizontal';
        //product page
        $product_swatch_display_shape = isset($_POST['zoo_cw_product_swatch_display_shape']) ? $_POST['zoo_cw_product_swatch_display_shape'] : 1;
        $product_swatch_display_size = isset($_POST['zoo_cw_product_swatch_display_size']) ? $_POST['zoo_cw_product_swatch_display_size'] : 1;
        if ($product_swatch_display_size == "custom") {
            $product_swatch_display_size_width = isset($_POST['zoo_cw_product_swatch_display_size_width']) ? $_POST['zoo_cw_product_swatch_display_size_width'] : 20;
            $product_swatch_display_size_height = isset($_POST['zoo_cw_product_swatch_display_size_height']) ? $_POST['zoo_cw_product_swatch_display_size_height'] : $product_swatch_display_size_width;
        }
        $product_swatch_display_name = isset($_POST['zoo_cw_product_swatch_display_name']) ? intval($_POST['zoo_cw_product_swatch_display_name']) : 1;
        $product_image_custom_class = isset($_POST['zoo_cw_product_image_custom_class']) ? $_POST['zoo_cw_product_image_custom_class'] : "";
        //shop page
        $display_shop_page = isset($_POST['zoo_cw_display_shop_page']) ? intval($_POST['zoo_cw_display_shop_page']) : 0;
        $display_shop_page_hook = isset($_POST['zoo_cw_display_shop_page_hook']) ? $_POST['zoo_cw_display_shop_page_hook'] : 'after';
        $shop_swatch_display_shape = isset($_POST['zoo_cw_shop_swatch_display_shape']) ? $_POST['zoo_cw_shop_swatch_display_shape'] : 1;
        $shop_swatch_display_size = isset($_POST['zoo_cw_shop_swatch_display_size']) ? $_POST['zoo_cw_shop_swatch_display_size'] : 1;
        if ($shop_swatch_display_size == "custom") {
            $shop_swatch_display_size_width = isset($_POST['zoo_cw_shop_swatch_display_size_width']) ? $_POST['zoo_cw_shop_swatch_display_size_width'] : 20;
            $shop_swatch_display_size_height = isset($_POST['zoo_cw_shop_swatch_display_size_height']) ? $_POST['zoo_cw_shop_swatch_display_size_height'] : $shop_swatch_display_size_width;
        }
        $display_shop_page_add_to_cart = isset($_POST['zoo_cw_display_shop_page_add_to_cart']) ? intval($_POST['zoo_cw_display_shop_page_add_to_cart']) : 1;
        //cart page
        $display_cart_page = isset($_POST['zoo_cw_display_cart_page']) ? intval($_POST['zoo_cw_display_cart_page']) : 0;
        $cart_swatch_display_shape = isset($_POST['zoo_cw_cart_swatch_display_shape']) ? $_POST['zoo_cw_cart_swatch_display_shape'] : 1;
        $cart_swatch_display_size = isset($_POST['zoo_cw_cart_swatch_display_size']) ? $_POST['zoo_cw_cart_swatch_display_size'] : 1;
        if ($cart_swatch_display_size == "custom") {
            $cart_swatch_display_size_width = isset($_POST['zoo_cw_cart_swatch_display_size_width']) ? $_POST['zoo_cw_cart_swatch_display_size_width'] : 20;
            $cart_swatch_display_size_height = isset($_POST['zoo_cw_cart_swatch_display_size_height']) ? $_POST['zoo_cw_cart_swatch_display_size_height'] : $cart_swatch_display_size_width;
        }

        $general_settings_array['tooltip'] = $enable_tooltip;
        $general_settings_array['swatch'] = $enable_swatch;
        $general_settings_array['product_gallery'] = $enable_product_gallery;
        $general_settings_array['cw_gallery'] = $enable_cw_gallery;
        $general_settings_array['cw_gallery_layout'] = $cw_gallery_layout;
        $general_settings_array['product_swatch_display_shape'] = $product_swatch_display_shape;
        $general_settings_array['product_swatch_display_size'] = $product_swatch_display_size;
        if ($product_swatch_display_size == "custom") {
            $general_settings_array['product_swatch_display_size_width'] = $product_swatch_display_size_width;
            $general_settings_array['product_swatch_display_size_height'] = $product_swatch_display_size_height;
        }
        $general_settings_array['product_swatch_display_name'] = $product_swatch_display_name;
        $general_settings_array['product_image_custom_class'] = $product_image_custom_class;
        $general_settings_array['display_shop_page'] = $display_shop_page;
        $general_settings_array['display_shop_page_hook'] = $display_shop_page_hook;
        $general_settings_array['shop_swatch_display_shape'] = $shop_swatch_display_shape;
        $general_settings_array['shop_swatch_display_size'] = $shop_swatch_display_size;
        if ($shop_swatch_display_size == "custom") {
            $general_settings_array['shop_swatch_display_size_width'] = $shop_swatch_display_size_width;
            $general_settings_array['shop_swatch_display_size_height'] = $shop_swatch_display_size_height;
        }
        $general_settings_array['display_cart_page'] = $display_cart_page;
        $general_settings_array['cart_swatch_display_shape'] = $cart_swatch_display_shape;
        $general_settings_array['cart_swatch_display_size'] = $cart_swatch_display_size;
        if ($cart_swatch_display_size == "custom") {
            $general_settings_array['cart_swatch_display_size_width'] = $cart_swatch_display_size_width;
            $general_settings_array['cart_swatch_display_size_height'] = $cart_swatch_display_size_height;
        }
        $general_settings_array['display_shop_page_add_to_cart'] = $display_shop_page_add_to_cart;

        if (is_array($general_settings_array)) {
            //save config to db and wp db
            $zoo_clever_swatch_config = new Zoo_Clever_Swatch_Config();
            $zoo_clever_swatch_config->zoo_cw_update_global_config($general_settings_array);
        }

    } ?>
    <div class="notice notice-success is-dismissible">
        <p><strong><?php _e('Settings Saved Successfully', 'clever-swatches'); ?></strong></p>
    </div>
<?php }

?>

<div class="wrap woocommerce">
    <form novalidate="novalidate" action="" method="post">
        <h2 class="nav-tab-wrapper woo-nav-tab-wrapper zoo-cw-heading-page">
            <img src="<?php echo ZOO_CW_GALLERYPATH . 'cleverswatch_40.png' ?>"/><?php esc_html_e('CleverSwatches', 'clever-swatches') ?>
            <?php
            echo zoo_cw_plugin_ver();
            ?>
        </h2>
    </form>
</div>

<div class="zoo-cw-settings-wrapper">
    <?php if ($current_tab == "general"): ?>
        <?php $general_settings = get_option('zoo-cw-settings', true); //print_r($general_settings); die("test");?>
        <?php if (!is_array($general_settings)): $general_settings = array(); endif; ?>
        <form name="zoo-cw-settings-form" action="" method="post">
            <div class="zoo-cw-settings-container">
                <div class="zoo-cw-settings-header">
                    <h3 class="zoo-cw-heading"><?php _e('Global Settings', 'clever-swatches') ?></h3>
                    <div class="zoo-cw-div-wrapper">
                        <table class="zoo-cw-settings-table">
                            <?php
                            $tooltip = isset($general_settings['tooltip']) ? intval($general_settings['tooltip']) : 0;
                            $swatch = isset($general_settings['swatch']) ? intval($general_settings['swatch']) : 1;
                            $enable_product_gallery = isset($general_settings['product_gallery']) ? intval($general_settings['product_gallery']) : 1;
                            ?>
                            <tr>
                                <td><?php _e('Enable Tooltip', 'clever-swatches'); ?></td>
                                <td><input type="checkbox"
                                           name="zoo_cw_enable_tooltip" <?php checked($tooltip, 1); ?>>
                                    <p class="description"><?php _e('Enable the use of tooltip', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Enable CleverSwatch', 'clever-swatches'); ?></td>
                                <td><input type="checkbox"
                                           name="zoo_cw_enable_swatch" <?php checked($swatch, 1); ?>>
                                    <p class="description"><?php _e('Enable the use of CleverSwatch', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Enable Variation Gallery Images', 'clever-swatches'); ?></td>
                                <td><input type="checkbox"
                                           name="zoo_cw_enable_product_gallery" <?php checked($enable_product_gallery, 1); ?>>
                                    <p class="description"><?php _e('Uncheck if you don’t like to use variation wise gallery.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                        </table>
                        <p class="description"><?php _e('Please go to the attribute settings for setting up global term thumbnails.', 'clever-swatches'); ?>
                            <a href="<?php echo admin_url('edit.php?post_type=product&page=product_attributes'); ?>"><?php _e('Click Here', 'clever-swatches'); ?></a>
                        </p>
                    </div>
                    <h3 class="zoo-cw-heading"><?php _e('Single Product Page', 'clever-swatches') ?></h3>
                    <div class="zoo-cw-div-wrapper">
                        <table class="zoo-cw-settings-table">
                            <?php
                            $product_swatch_display_shape = isset($general_settings['product_swatch_display_shape']) ? $general_settings['product_swatch_display_shape'] : 'square';
                            $product_swatch_display_size = isset($general_settings['product_swatch_display_size']) ? $general_settings['product_swatch_display_size'] : 1;
                            $product_swatch_display_size_width = isset($general_settings['product_swatch_display_size_width']) ? intval($general_settings['product_swatch_display_size_width']) : 20;
                            $product_swatch_display_size_height = isset($general_settings['product_swatch_display_size_height']) ? intval($general_settings['product_swatch_display_size_height']) : $product_swatch_display_size_width;
                            $product_swatch_display_name = isset($general_settings['product_swatch_display_name']) ? intval($general_settings['product_swatch_display_name']) : 1;
                            $product_image_custom_class = isset($general_settings['product_image_custom_class']) ? $general_settings['product_image_custom_class'] : "";
                            ?>
                            <tr>
                                <td><?php _e('Swatch display shape', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_product_swatch_display_shape" class="zoo_cw_atds zoo_cw_product_swatch_display_shape">
                                        <option value="square" <?php selected($product_swatch_display_shape, 'square'); ?>><?php _e('SQUARE', 'clever-swatches') ?></option>
                                        <option value="circle" <?php selected($product_swatch_display_shape, 'circle'); ?>><?php _e('CIRCLE', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description"><?php _e('Shape of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch display size', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_product_swatch_display_size" class="zoo_cw_atds zoo_cw_product_swatch_display_size">
                                        <option value="1" <?php selected($product_swatch_display_size, 1); ?>><?php _e('20px * 20px', 'clever-swatches') ?></option>
                                        <option value="2" <?php selected($product_swatch_display_size, 2); ?>><?php _e('40px * 40px', 'clever-swatches') ?></option>
                                        <option value="3" <?php selected($product_swatch_display_size, 3); ?>><?php _e('60px * 60px', 'clever-swatches') ?></option>
                                        <option value="custom" <?php selected($product_swatch_display_size, 'custom'); ?>><?php _e('Custom', 'clever-swatches') ?></option>
                                    </select>
                                    <input type="text" placeholder="size in px." id="zoo_cw_product_swatch_display_size_width"
                                           name="zoo_cw_product_swatch_display_size_width"
                                           value="<?php echo $product_swatch_display_size_width; ?>" <?php if ($product_swatch_display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <input type="text" placeholder="size in px." id="zoo_cw_product_swatch_display_size_height"
                                           name="zoo_cw_product_swatch_display_size_height"
                                           value="<?php echo $product_swatch_display_size_height; ?>" <?php if ($product_swatch_display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <p class="description"><?php _e('Size of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch show name?', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_product_swatch_display_name" class="zoo_cw_atds">
                                        <option value="1" <?php selected($product_swatch_display_name, 1); ?>><?php _e('Yes', 'clever-swatches') ?></option>
                                        <option value="0" <?php selected($product_swatch_display_name, 0); ?>><?php _e('No', 'clever-swatches') ?></option>
                                    </select>

                                    <p class="description"><?php _e('Show name of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('CSS class Wrapper jQuery Selector', 'clever-swatches'); ?></td>
                                <td>
                                    <input type="text" name="zoo_cw_product_image_custom_class"
                                           value="<?php echo $product_image_custom_class; ?>" placeholder=".images">
                                    <p class="description"><?php _e('Enter custom wrapper jQuery selector of product gallery if the default setting is not working. Default selector: .images', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                $enable_cw_gallery = !empty($general_settings['cw_gallery']) ? 1 : 0;
                                ?>
                                <td><?php _e('Enable Swatches Gallery', 'clever-swatches'); ?></td>
                                <td>
                                    <input type="checkbox" name="zoo_enable_cw_gallery" <?php checked($enable_cw_gallery, 1); ?>>
                                    <p class="description"><?php _e('Check if you want to use swatches gallery. Clever Swatches gallery will replace default product gallery of theme', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                $cw_gallery_layout = !empty($general_settings['cw_gallery_layout']) ? $general_settings['cw_gallery_layout']:'horizontal';
                                ?>
                                <td><?php _e('Swatches Gallery Layout', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_gallery_layout">
                                        <option value="horizontal" <?php selected($cw_gallery_layout, 'horizontal'); ?>><?php esc_html_e('Horizontal','clever-swatches');?></option>
                                        <option value="vertical" <?php selected($cw_gallery_layout, 'vertical'); ?>><?php esc_html_e('Vertical','clever-swatches');?></option>
                                    </select>
                                    <p class="description"><?php _e('Layout of Swatches gallery. It work when Enable Swatches Gallery checked.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <h3 class="zoo-cw-heading"><?php _e('Shop Page', 'clever-swatches') ?></h3>
                    <div class="zoo-cw-div-wrapper">
                        <table class="zoo-cw-settings-table">
                            <?php
                            $display_shop_page = isset($general_settings['display_shop_page']) ? intval($general_settings['display_shop_page']) : 0;
                            $display_shop_page_hook = isset($general_settings['display_shop_page_hook']) ? $general_settings['display_shop_page_hook'] : 'after';
                            $shop_swatch_display_shape = isset($general_settings['shop_swatch_display_shape']) ? $general_settings['shop_swatch_display_shape'] : 'square';
                            $shop_swatch_display_size = isset($general_settings['shop_swatch_display_size']) ? $general_settings['shop_swatch_display_size'] : 1;
                            $shop_swatch_display_size_width = isset($general_settings['shop_swatch_display_size_width']) ? intval($general_settings['shop_swatch_display_size_width']) : 20;
                            $shop_swatch_display_size_height = isset($general_settings['shop_swatch_display_size_height']) ? intval($general_settings['shop_swatch_display_size_height']) : $shop_swatch_display_size_width;
                            $display_shop_page_add_to_cart = isset($general_settings['display_shop_page_add_to_cart']) ? intval($general_settings['display_shop_page_add_to_cart']) : 1;
                            ?>
                            <tr>
                                <td><?php _e('Show Swatch on shop/archive pages', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_shop_page"
                                            class="zoo_cw_atds zoo_cw_display_shop_page">
                                        <option value="0" <?php selected($display_shop_page, 0); ?>><?php _e('NO', 'clever-swatches') ?></option>
                                        <option value="1" <?php selected($display_shop_page, 1); ?>><?php _e('YES', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description"><?php _e('Show swatch on shop page or not.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr <?php if ($display_shop_page == 0): echo 'style="display: none;"'; endif; ?>>
                                <td><?php _e('Swatch on shop page hook to:', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_shop_page_hook"
                                            class="zoo_cw_atds zoo_cw_display_shop_page_hook">
                                        <option value="before" <?php selected($display_shop_page_hook, 'before'); ?>><?php _e('woocommerce_before_shop_loop_item_title', 'clever-swatches') ?></option>
                                        <option value="after" <?php selected($display_shop_page_hook, 'after'); ?>><?php _e('woocommerce_after_shop_loop_item_title', 'clever-swatches') ?></option>
                                        <option value="shortcode" <?php selected($display_shop_page_hook, 'shortcode'); ?>><?php _e('Use Shortcode', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description zoo_cw_display_shop_page_hook_description" <?php if ($display_shop_page_hook != 'shortcode'): echo 'style="display: none;"'; endif; ?>>
                                        <?php _e('Use this short code: [zoo_cw_shop_swatch]', 'clever-swatches') ?>
                                    </p>
                                </td>
                            </tr>
                            <tr <?php if ($display_shop_page == 0): echo 'style="display: none;"'; endif; ?>>
                                <td><?php _e('Swatch display shape', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_shop_swatch_display_shape" class="zoo_cw_atds zoo_cw_shop_swatch_display_shape">
                                        <option value="square" <?php selected($shop_swatch_display_shape, 'square'); ?>><?php _e('SQUARE', 'clever-swatches') ?></option>
                                        <option value="circle" <?php selected($shop_swatch_display_shape, 'circle'); ?>><?php _e('CIRCLE', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description"><?php _e('Shape of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr <?php if ($display_shop_page == 0): echo 'style="display: none;"'; endif; ?>>
                                <td><?php _e('Swatch display size', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_shop_swatch_display_size" class="zoo_cw_atds zoo_cw_shop_swatch_display_size">
                                        <option value="1" <?php selected($shop_swatch_display_size, 1); ?>><?php _e('20px * 20px', 'clever-swatches') ?></option>
                                        <option value="2" <?php selected($shop_swatch_display_size, 2); ?>><?php _e('40px * 40px', 'clever-swatches') ?></option>
                                        <option value="3" <?php selected($shop_swatch_display_size, 3); ?>><?php _e('60px * 60px', 'clever-swatches') ?></option>
                                        <option value="custom" <?php selected($shop_swatch_display_size, 'custom'); ?>><?php _e('Custom', 'clever-swatches') ?></option>
                                    </select>
                                    <input type="text" placeholder="size in px." id="zoo_cw_shop_swatch_display_size_width"
                                           name="zoo_cw_shop_swatch_display_size_width"
                                           value="<?php echo $shop_swatch_display_size_width; ?>" <?php if ($shop_swatch_display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <input type="text" placeholder="size in px." id="zoo_cw_shop_swatch_display_size_height"
                                           name="zoo_cw_shop_swatch_display_size_height"
                                           value="<?php echo $shop_swatch_display_size_height; ?>" <?php if ($shop_swatch_display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <p class="description"><?php _e('Size of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr <?php if ($display_shop_page == 0): echo 'style="display: none;"'; endif; ?>>
                                <td><?php _e('Show Add To Cart button on shop page?', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_shop_page_add_to_cart"
                                            class="zoo_cw_atds zoo_cw_display_shop_page_add_to_cart">
                                        <option value="0" <?php selected($display_shop_page_add_to_cart, 0); ?>><?php _e('NO', 'clever-swatches') ?></option>
                                        <option value="1" <?php selected($display_shop_page_add_to_cart, 1); ?>><?php _e('YES', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description"><?php _e('Show add to cart button on shop page or not.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <h3 class="zoo-cw-heading"><?php _e('Cart Page', 'clever-swatches') ?></h3>
                    <div class="zoo-cw-div-wrapper">
                        <table class="zoo-cw-settings-table">
                            <?php
                            $display_cart_page = isset($general_settings['display_cart_page']) ? intval($general_settings['display_cart_page']) : 0;
                            $cart_swatch_display_shape = isset($general_settings['cart_swatch_display_shape']) ? $general_settings['cart_swatch_display_shape'] : 'square';
                            $cart_swatch_display_size = isset($general_settings['cart_swatch_display_size']) ? $general_settings['cart_swatch_display_size'] : 1;
                            $cart_swatch_display_size_width = isset($general_settings['cart_swatch_display_size_width']) ? intval($general_settings['cart_swatch_display_size_width']) : 20;
                            $cart_swatch_display_size_height = isset($general_settings['cart_swatch_display_size_height']) ? intval($general_settings['cart_swatch_display_size_height']) : $cart_swatch_display_size_width;
                            ?>
                            <tr>
                                <td><?php _e('Show Swatch on cart page?', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_display_cart_page"
                                            class="zoo_cw_atds zoo_cw_display_cart_page">
                                        <option value="0" <?php selected($display_cart_page, 0); ?>><?php _e('NO', 'clever-swatches') ?></option>
                                        <option value="1" <?php selected($display_cart_page, 1); ?>><?php _e('YES', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description"><?php _e('Show swatch on cart page or not.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch display shape', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_cart_swatch_display_shape" class="zoo_cw_atds zoo_cw_cart_swatch_display_shape">
                                        <option value="square" <?php selected($cart_swatch_display_shape, 'square'); ?>><?php _e('SQUARE', 'clever-swatches') ?></option>
                                        <option value="circle" <?php selected($cart_swatch_display_shape, 'circle'); ?>><?php _e('CIRCLE', 'clever-swatches') ?></option>
                                    </select>
                                    <p class="description"><?php _e('Shape of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><?php _e('Swatch display size', 'clever-swatches'); ?></td>
                                <td>
                                    <select name="zoo_cw_cart_swatch_display_size" class="zoo_cw_atds zoo_cw_cart_swatch_display_size">
                                        <option value="1" <?php selected($cart_swatch_display_size, 1); ?>><?php _e('20px * 20px', 'clever-swatches') ?></option>
                                        <option value="2" <?php selected($cart_swatch_display_size, 2); ?>><?php _e('40px * 40px', 'clever-swatches') ?></option>
                                        <option value="3" <?php selected($cart_swatch_display_size, 3); ?>><?php _e('60px * 60px', 'clever-swatches') ?></option>
                                        <option value="custom" <?php selected($cart_swatch_display_size, 'custom'); ?>><?php _e('Custom', 'clever-swatches') ?></option>
                                    </select>
                                    <input type="text" placeholder="size in px." id="zoo_cw_cart_swatch_display_size_width"
                                           name="zoo_cw_cart_swatch_display_size_width"
                                           value="<?php echo $cart_swatch_display_size_width; ?>" <?php if ($cart_swatch_display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <input type="text" placeholder="size in px." id="zoo_cw_cart_swatch_display_size_height"
                                           name="zoo_cw_cart_swatch_display_size_height"
                                           value="<?php echo $cart_swatch_display_size_height; ?>" <?php if ($cart_swatch_display_size != "custom"): echo 'style="display: none;"'; endif; ?>>
                                    <p class="description"><?php _e('Size of attribute Swatch.', 'clever-swatches') ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <p class="submit">
                <input class="zoo-cw-button zoo-cw-submit" type="submit" value="Save changes" name="save">
            </p>
        </form>
    <?php endif; ?>
</div>
