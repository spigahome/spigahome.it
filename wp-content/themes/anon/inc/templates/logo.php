<?php
/** Logo
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2019 Zootemplate
 */

if (get_theme_mod('custom_logo')) { ?>
    <div id="logo" class="site-logo">
        <a href="<?php echo esc_url(home_url('/')); ?>"
           rel="<?php esc_attr_e('home', 'anon'); ?>"
           title="<?php bloginfo('name'); ?>">
            <img src="<?php echo wp_get_attachment_image_url(get_theme_mod('custom_logo'),'full')?>" alt="<?php bloginfo('name'); ?>"/>
        </a>
    </div>
    <?php
}
if (display_header_text()) {
    ?>
    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                              rel="<?php esc_attr_e('home', 'anon'); ?>"
                              title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
    <?php
    $zoo_site_description = get_bloginfo('description', 'display');
    if ($zoo_site_description || is_customize_preview()) { ?>
        <p class="site-description"><?php echo esc_html($zoo_site_description); ?></p>
    <?php }
}
