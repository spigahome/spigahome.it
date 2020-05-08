<?php
/**
 * View template for Clever Site Logo widget
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

$site_logo = $sticky_logo = '';
$wrap_class = 'cafe-wrap-site-logo';
if ($settings['site_logo']['url'] != '') {
    $site_logo = $settings['site_logo']['url'];
} else {
    $site_logo = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
}
if ($settings['sticky_logo']['url'] != '') {
    $sticky_logo = $settings['sticky_logo']['url'];
}
if ($site_logo == '') {
    return;
}
if ($sticky_logo != '') {
    $wrap_class .= ' has-sticky-logo';
}
?>
<div class="<?php echo esc_attr($wrap_class); ?>">
<a class="cafe-site-logo" href="<?php echo esc_url(home_url('/')); ?>"
   rel="<?php esc_attr_e('home', 'cafe-pro'); ?>"
   title="<?php bloginfo('name'); ?>">
    <img src="<?php echo esc_url($site_logo); ?>" alt="<?php bloginfo('name'); ?>"/>
</a>
<?php if ($sticky_logo != '') {
    ?>
    <span class="cafe-wrap-sticky-logo">
            <a class="cafe-inner-sticky-logo" href="<?php echo esc_url(home_url('/')); ?>"
               title="<?php bloginfo('name'); ?>">
            <img src="<?php echo esc_url($sticky_logo); ?>" class="cafe-sticky-logo" alt="<?php bloginfo('name'); ?>"/>
            </a>
    </span>
    <?php
} ?>
</div>