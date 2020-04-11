<?php
/**
 * The template for displaying image attachments
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
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-item post-detail'); ?>>
                            <div class="header-post">
                                <?php
                                the_title('<h1 class="title-detail">', '</h1>');
                                ?>
                            </div>
                            <div class="post-media single-image">
                            <?php
                            /**
                             * Filter the default image attachment size.
                             *
                             * @param string $image_size Image size. Default 'large'.
                             */
                            $image_size = apply_filters( 'zoo_theme_attachment_size', 'large' );

                            echo wp_get_attachment_image( get_the_ID(), $image_size );
                            ?>
                            </div>
                            <?php if ( has_excerpt() ) : ?>
                                <div class="post-content">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif;
                            /**
                             * Before single content post
                             * zoo_single_post_pagination 10
                             * zoo_single_post_bottom_content 20
                             */
                            do_action('zoo_after_single_content');
                            ?>
                            <nav id="image-navigation" class="navigation image-navigation">
                                <div class="nav-links">
                                    <div class="nav-previous"><?php previous_image_link( false, esc_html__( 'Previous Image', 'anon' ) ); ?></div><div class="nav-next"><?php next_image_link( false, esc_html__( 'Next Image', 'anon' ) ); ?></div>
                                </div>
                            </nav>
                        </article>
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template('', true);
                        endif;
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
