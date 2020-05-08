<?php
/**
 * The template for displaying search results pages.
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */
get_header();

/**
 * Zoo Before Main Content
 *
 * @hooked zoo_breadcrumb - 10
 * @hooked zoo_blog_cover - 20
 */
do_action('zoo_before_main_content');
?>
    <main id="site-main-content" class="<?php echo esc_attr(zoo_main_content_css()) ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr(zoo_loop_content_css()) ?>">
                    <?php
                    /**
                     * Zoo Before Loop Blog Content
                     *
                     */
                    do_action('zoo_before_loop_content');
                    ?>
                    <div class="row">
                        <h1 class="page-title the-title"><?php printf(esc_html__('Search Results for: %s', 'anon'), '&ldquo;' . get_search_query() . '&rdquo;'); ?></h1>
                        <?php if (have_posts()) :
                            while (have_posts()) : the_post();
                                get_template_part('inc/templates/posts/loop/post', 'item');
                            endwhile;
                        else :
                            get_template_part('content', 'none');
                        endif; ?>
                    </div>
                    <?php
                    /**
                     * Zoo After Loop Blog Content
                     *@hooked zoo_post_pagination - 10
                     */
                    do_action('zoo_after_loop_content');
                    ?>
                </div>
                <?php get_sidebar();?>
            </div>
        </div>
    </main>
<?php
/**
 * Zoo After Main Content
 *
 */
do_action('zoo_after_main_content');
get_footer();