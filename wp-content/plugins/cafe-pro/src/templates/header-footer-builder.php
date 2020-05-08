<?php
/**
 * Header|Footer builder template
 */
Elementor\Plugin::$instance->frontend->add_body_class('elementor-template-full-width');

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="'width=device-width, initial-scale=1'">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php

Elementor\Plugin::$instance->modules_manager->get_modules('page-templates')->print_content();

wp_footer(); ?>
</body>
</html>
