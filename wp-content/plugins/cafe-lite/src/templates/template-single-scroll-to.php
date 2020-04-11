<?php
/**
 * View template for Clever Icon widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ($settings['target_id'] != '') {
    if ($settings['icon'] != 'font-icon') {
        $icon = '<i class="icon-' . $settings['icon'] . '"></i>';
    } else {
        $icon = '<i class="' . $settings['font_icon'] . '"></i>';
    }
    ?>
    <div class="cafe-wrap-single-scroll-button">
        <?php
        printf('<a class="cafe-single-scroll-button" href="%s"><span class="bg-box"><i class="edge-left"></i></span><span class="cafe-scroll-icon %s">%s</span></a>', $settings['target_id'], $settings['button_animation'], $icon);
        ?>
    </div>
    <?php
}