<?php
/**
 * View template for RevSlider widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */


if ($settings['id'] != '0'):
    $rev_slider = '[rev_slider alias="'.$settings['id'].'"]';
    $class_css = 'cafe-rev-slider ' . $settings['css_class'];
    ?>
    <div class="<?php echo esc_attr($class_css) ?>">
        <?php
        echo do_shortcode($rev_slider);
        ?>
    </div>
<?php
endif;