<?php
/**
 * View template for Contact form 7 widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

if ($settings['ct7_form_id'] != 0):
    $cf7_id = '[contact-form-7 id="' . $settings['ct7_form_id'] . '"]';
    $class_css = 'cafe-contact-form-7 ' . $settings['css_class'];
    ?>
    <div class="<?php echo esc_attr($class_css) ?>">
        <?php
        echo do_shortcode($cf7_id);
        ?>
    </div>
<?php
endif;
?>