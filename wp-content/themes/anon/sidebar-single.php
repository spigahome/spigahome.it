<?php
/**
 * The sidebar containing the main widget area for single post.
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */
if(is_active_sidebar(get_theme_mod('zoo_blog_single_sidebar','sidebar'))&& zoo_single_post_sidebar()!='') {
?>
<aside id="sidebar"
       class="sidebar widget-area col-12 col-md-3" role="complementary">
    <?php dynamic_sidebar(get_theme_mod('zoo_blog_single_sidebar','sidebar')); ?>
</aside>
<?php }