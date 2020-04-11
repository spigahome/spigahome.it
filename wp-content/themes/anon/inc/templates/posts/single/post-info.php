<?php
/**
 * Block information for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */

$wrap_class='post-info';
if(get_theme_mod('zoo_blog_single_post_info_style', '')=='icon'){
    $wrap_class.=' with-icon';
}else{
    $wrap_class.=' without-icon';
}
?>
<ul class="<?php echo esc_attr($wrap_class);?>">
    <?php
    /**
     * Before single post info content
    */
    do_action('zoo_single_post_before_post_info');
    if (get_theme_mod('zoo_blog_single_post_info_style', '') == 'icon') {
        if (get_theme_mod('zoo_enable_blog_author_post', '1') == 1) {
            ?>
            <li class="author-post">
                <i class="cs-font clever-icon-user-2"></i>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                   title="<?php echo esc_attr(get_the_author()) ?>">
                    <?php echo esc_html(get_the_author()) ?>
                </a>
            </li>
        <?php }
        if (get_theme_mod('zoo_enable_blog_date_post', '1') == 1) {
            ?>
            <li class="post-date">
                <i class="cs-font clever-icon-clock-4"></i>
                <?php echo esc_html(get_the_date()); ?></li>
            <?php }
        if (get_theme_mod('zoo_enable_blog_cat_post', '1') == 1) {
            ?>
            <li class="list-cat">
                <i class="cs-font clever-icon-document"></i>
                <?php echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?></li>
        <?php }
    } else {
        if (get_theme_mod('zoo_enable_blog_author_post', '1') == 1) { ?>
            <li class="author-post">
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"
                   title="<?php echo esc_attr(get_the_author()) ?>">
                    <?php echo esc_html(get_the_author()) ?>
                </a>
            </li>
        <?php }
        if (get_theme_mod('zoo_enable_blog_date_post', '1') == 1) {
            ?>
            <li class="post-date"><?php echo esc_html(get_the_date()); ?></li>
        <?php }
        if (get_theme_mod('zoo_enable_blog_cat_post', '1') == 1) {
            ?>
            <li class="list-cat"><?php echo get_the_term_list(get_the_ID(), 'category', '', ', ', ''); ?></li>
        <?php }
    }
    /**
     * After single post info content
     */
    do_action('zoo_single_post_after_post_info');
    ?>
</ul>

