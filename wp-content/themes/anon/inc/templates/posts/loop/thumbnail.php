<?php
/**
 * Media block for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

$img_size = get_theme_mod('zoo_blog_img_size','full');
if(get_theme_mod('zoo_blog_layout','list')!='list'){
	$img_size = get_theme_mod('zoo_blog_grid_img_size','medium');
}
if (has_post_thumbnail()) {
    ?>
    <div class="wrap-media">
        <?php
        get_template_part('inc/templates/posts/global/sticky');
        ?>
        <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute() ?>">
            <?php
            the_post_thumbnail($img_size);
            ?>
        </a>
    </div>
<?php }