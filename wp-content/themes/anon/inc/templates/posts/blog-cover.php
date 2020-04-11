<?php
/**
 * Blog Cover template
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

$title = esc_html__('Blog', 'anon');
if (is_archive()) {
    $title = get_the_archive_title();
} elseif (is_category()) {
    $title = get_the_category();
}
if(get_theme_mod('zoo_enable_blog_cover',0)==1){
?>
<div class="wrap-blog-cover">
    <?php if (is_author())
        get_template_part('author', 'bio');
    else { ?>
        <h2 class="blog-page-title"><?php echo ent2ncr($title); ?></h2>
    <?php } ?>
</div>
<?php }?>