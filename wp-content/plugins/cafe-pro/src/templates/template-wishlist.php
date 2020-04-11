<?php
/**
 * View template for Clever Wishlist widget
 * This widget working only with clever theme and need active plugin Clever Addons, WooCommerce for work
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if (!class_exists('WooCommerce') || (get_theme_mod( 'zoo_enable_wishlist', '1' ) != 1 || !class_exists( 'CleverAddons' ))) {
    return;
}

?>
<div class="cafe-wlcp">
    <a class="cafe-wlcp-url<?php if ($settings['link_only'] != 'yes') echo ' browse-wishlist'; ?>"
       href="<?php if (get_theme_mod('zoo_wishlist_page', '') != '') echo esc_url(get_page_link(get_page_by_path(get_theme_mod('zoo_wishlist_page', '')))) ?>">
        <?php if ($settings['icon'] != '') { ?>
            <span class="cafe-wlcp-icon">
                    <i class="<?php echo esc_attr($settings['icon']); ?>"></i>
                <?php if ($settings['show_count'] == 'yes'): ?>
                    <span class="cafe-wlcp-counter wishlist-counter <?php echo esc_attr($settings['count_position']);?>">0</span>
                <?php endif; ?>
            </span>
            <?php
        }
        if ($settings['show_label'] == 'yes'): ?>
            <span class="cafe-wlcp-label"><?php echo esc_html($settings['label']) ?></span>
        <?php endif; ?>
    </a>
</div>
