<?php
/**
 * The template for displaying archive pages.
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
                <?php
                if(get_the_author_meta('description')!='') {
                    get_template_part('author', 'bio');
                }else{
                    get_sidebar();
                }
                ?>
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