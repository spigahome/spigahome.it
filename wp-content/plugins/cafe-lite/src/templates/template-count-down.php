<?php
/**
 * View template for Count Down widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$css_class = 'cafe-countdown ' . $settings['css_class'];
?>
<div class="<?php echo esc_attr($css_class) ?>">
    <div class="cafe-countdown-block" data-countdown="countdown" data-date="<?php echo esc_attr(date("m-d-Y-G-i-s", strtotime($settings['date']))) ?>">
    </div>
</div>

