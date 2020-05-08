<?php
/**
 * View template for Clever Site Nav Menu
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$container_class = 'cafe-site-menu ' . $settings['layout'];
$container_class .= ' icon-submenu-' . $settings['sub_menu_arrow'];
if ($settings['hover_style'] != '') {
    $container_class .= ' hover-style-' . $settings['hover_style'];
} else {
    $container_class .= ' hover-style-normal';
}
if ($settings['hamburger_style'] != '')
    $container_class .= ' cafe-hamburger-' . $settings['hamburger_style'] . '-effect';
if ($settings['canvas_position'] != '')
    $container_class .= ' ' . $settings['canvas_position'];
if ($settings['mobile_break_point'] != '')
    $container_class .= ' ' . $settings['mobile_break_point'];

if ($settings['site_menu'] != '') {
    $menu_args = array(
        'menu' => $settings['site_menu'],
        'container' => false,
        'container_id' => false,
        'container_class' => false,
        'menu_id' => false,
        'menu_class' => 'cafe-menu',
        'fallback_cb' => false,
    );

    if (function_exists('zoo_get_walker_nav_menu')) {
        $menu_args['walker'] = zoo_get_walker_nav_menu();
    }
    $toggle_id = 'cafe-hamburger-toggle-' . uniqid();
    ?>
    <nav class="<?php echo esc_attr($container_class) ?>">
        <?php
        if ($settings['layout'] == 'slide-down') {
            if($settings['show_menu']){
                $show = 'style="display:block"';
            }
            else{
                $show = 'style="display:none"';
            }
        ?>
            <div class="cafe-wrap-menu">
                <div class="toggle">
                    <span class="icon <?php echo esc_attr($settings['toggle_icon']); ?>"></span>
                    <label class="text"><?php echo esc_attr($settings['toggle_text']); ?></label>
                    <?php if ($settings['toggle_arrow']) { ?>
                        <span class="arrow cs-font clever-icon-down"></span>
                        <?php
                    } ?>
                </div>
                <div class="wrap-menu-inner" <?php echo ent2ncr($show); ?>>
                    <?php
                    wp_nav_menu($menu_args);
                    ?>
                </div>
            </div>
            <?php
        } else { ?>
            <input id="<?php echo esc_attr($toggle_id); ?>" class="cafe-hamburger-input-control" type="checkbox"/>
            <label class="cafe-hamburger-button" for="<?php echo esc_attr($toggle_id); ?>">
                <span class="cafe-hamburger-icon"></span>
            </label>
            <div class="cafe-wrap-menu">
                <label class="cafe-hamburger-close-button" for="<?php echo esc_attr($toggle_id); ?>">
                    <i class="cs-font clever-icon-close"></i>
                </label>
                <?php
                wp_nav_menu($menu_args);
                ?>
            </div>
            <label class="cafe-hamburger-mask" for="<?php echo esc_attr($toggle_id); ?>"></label>
            <?php
        } ?>
    </nav>
    <?php
}