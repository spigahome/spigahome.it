<?php
/**
 * The template for displaying all single posts.
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
                <div class="<?php echo esc_attr(zoo_single_content_css()) ?>">
                    <?php
                    /**
                     * Zoo Before Single Blog Content
                     *
                     */
                    do_action('zoo_before_single_main_content');
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                            get_template_part('content', 'single');
                        endwhile;
                    endif;
                    /**
                     * Zoo After Loop Single Content
                     *
                     */
                    do_action('zoo_after_single_main_content');
                    ?>
                </div>
                <?php
                get_sidebar('single');
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
