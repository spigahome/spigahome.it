<?php
/**
 * View template for Clever Account widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if (!class_exists('WooCommerce', false)) {
    return false;
}
$show_label = $settings['show_label'];
$label_text = $settings['label'];
$user_logged_in = is_user_logged_in();
if ($user_logged_in) {
    $label_text = __('My Account', 'cafe-pro');
} else {
    $label_text = $label_text == '' ? __('Login', 'cafe-pro') : $label_text;
}
$myacc_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

?>
<div class="cafe-wrap-account">
    <?php
    if (!is_account_page()) {
        if ($settings['link_only'] != 'yes' && !$user_logged_in) {
            $toggle_id = 'cafe-toggle-account-' . uniqid();
            ?>
            <input type="checkbox" id="<?php echo esc_attr($toggle_id); ?>" class="cafe-toggle-input"/>
            <label class="cafe-account-toggle-button cafe-account-btn" for="<?php echo esc_attr($toggle_id); ?>"><i
                        class="<?php echo esc_attr($settings['icon']) ?>"></i> <?php
                if ($show_label == 'yes') {
                    echo esc_html($label_text);
                }
                ?></label>
            <label class="cafe-account-mask cafe-mask-close" for="<?php echo esc_attr($toggle_id); ?>"><i
                        class="cs-font clever-icon-close"></i> </label>
            <?php
        } else {
            ?>
            <a class="cafe-account-btn cafe-account-url" href="<?php echo esc_url($myacc_url); ?>"
               title="<?php echo esc_attr($label_text); ?>">
                <i class="<?php echo esc_attr($settings['icon']) ?>"></i>
                <?php
                if ($show_label == 'yes') {
                    echo esc_html($label_text);
                }
                ?>
            </a>
            <?php
        }
        if ($user_logged_in) {
            ?>
            <nav class="woocommerce-MyAccount-navigation <?php echo esc_attr($settings['ext_block_align']); ?>">
                <ul>
                    <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                        <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"><?php echo esc_html($label); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <?php
        } else {
            if ($settings['link_only'] != 'yes') {
                ?>
                <div class="cafe-account-modal">
                    <div class="heading-account-modal">
                        <span class="lb-login"><?php echo esc_html__('Sign In', 'cafe-pro'); ?></span>
                        <?php
                        if ('yes' === get_option('woocommerce_enable_myaccount_registration')) {
                            ?>
                            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')) . '?action=register'); ?>"
                               class="register"><?php echo esc_html__('Create an Account', 'cafe-pro'); ?></a>
                        <?php }
                        ?>
                    </div>
                    <?php
                    woocommerce_login_form();
                    ?>
                </div>
                <?php
            } else {
                global $wp;
                ?>
                <nav class="woocommerce-MyAccount-navigation <?php echo esc_attr($settings['ext_block_align']); ?>">
                        <ul>
                            <li><a href="<?php echo esc_url(wp_login_url(home_url($wp->request))) ?>" class="account-login-url"
                                   title="<?php esc_attr_e('Log in', 'cafe-pro') ?>"><?php esc_html_e('Log in', 'cafe-pro') ?></a>
                            </li>
                            <li><a href="<?php echo esc_url(wp_registration_url()) ?>" class="account-register-url"
                                   title="<?php esc_attr_e('Register', 'cafe-pro') ?>"><?php esc_html_e('Register', 'cafe-pro') ?></a>
                            </li>
                        </ul>
                </nav>
                <?php
            }
        }
    }else{
        ?>
        <a class="cafe-account-btn cafe-account-url" href="#customer_login"
           title="<?php echo esc_attr($label_text); ?>">
            <i class="<?php echo esc_attr($settings['icon']) ?>"></i>
            <?php
            if ($show_label == 'yes') {
                echo esc_html($label_text);
            }
            ?>
        </a>
    <?php
    }
    ?>
</div>
