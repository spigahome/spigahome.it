<?php
/**
 * The template for displaying the header
 *
 * @package  Zoo_Theme\Templates
 * @author   zootemplate
 * @link     https://www.zootemplate.com/
 *
 */
?>
    <!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="//gmpg.org/xfn/11">
        <?php wp_head(); ?>
    </head>
<body <?php body_class(); ?>>
<header id="site-header" class="site-header base-site-header">
    <div class="container">
        <div class="wrap-header">
            <div class="row">
                <div id="site-identity" class="col-8 col-md-3 site-identity">
                    <?php get_template_part('inc/templates/logo'); ?>
                </div>
                <div class="col-4 col-md-9 wrap-site-navigation">
                    <nav id="primary-menu" class="primary-menu">
                        <span class="button-close-nav close-nav">
                            <i class="zoo-icon-close"></i>
                        </span>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary-menu',
                            'container' => false,
                            'container_id' => false,
                            'container_class' => false,
                            'menu_id' => false,
                            'menu_class' => 'menu nav-menu',
                            //'walker' => zoo_get_walker_nav_menu(),
                            'fallback_cb' => false,
                        ));
                        ?>
                    </nav>
                    <div class="menu-overlay close-nav"></div>
                    <span class="nav-button">
                        <i class="zoo-css-icon-menu"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</header>
