<?php
/**
 * Template used to render site header
 *
 * @internal
 * @var int $site_header_id
 * @see CafePro\DocumentsManager::__construct()
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <?php if (!current_theme_supports('title-tag')) : ?>
            <title><?php echo wp_get_document_title(); ?></title>
        <?php endif; ?>
        <?php wp_head(); ?>
    </head>
<body <?php body_class(); ?>>
<?php
do_action('cafe_before_header');
    echo Elementor\Plugin::$instance->frontend->get_builder_content($site_header_id, false);
do_action('cafe_after_header'); 
