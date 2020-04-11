<?php
/**
 * Block Related for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 */
if (get_theme_mod('zoo_enable_blog_related', '1') == 1) {
    $zoo_categories = get_the_category(get_the_ID());
    $zoo_category_ids = array();
    foreach ($zoo_categories as $zoo_category) {
        $zoo_category_ids[] = $zoo_category->term_id;
    }
    $args = array(
        'post_type' => 'post',
        'post__not_in' => array(get_the_ID()),
        'showposts' => get_theme_mod('zoo_blog_related_numbers', '3'),
        'ignore_sticky_posts' => -1,
        'category__in' => $zoo_category_ids
    );
    $zoo_related_query = new wp_query($args);
    if ($zoo_related_query->have_posts()) {
        $class = 'post-loop-item grid-layout-item ';
        switch (get_theme_mod('zoo_blog_related_cols', '3')) {
            case '2':
                $class .= "col-12 col-md-6";
                break;
            case '3':
                $class .= "col-12 col-md-4";
                break;
            case '4':
                $class .= "col-12 col-md-3";
                break;
            case '5':
                $class .= "col-12 col-md-1-5";
                break;
            case '6':
                $class .= "col-12 col-md-2";
                break;
            default:
                $class .= "col-12";
                break;
        }
        $img_size = get_theme_mod('zoo_blog_grid_img_size', 'medium');
        ?>
        <section class="post-related wrap-loop-content grid-layout">
            <h3 class="title-block"><?php esc_html_e('Related Post', 'anon'); ?></h3>
            <div class="row">
                <?php while ($zoo_related_query->have_posts()) {
                    $zoo_related_query->the_post(); ?>
                    <article <?php echo post_class($class) ?>>
                        <div class="zoo-post-inner">
                            <?php
                            if (has_post_thumbnail()) {
                                ?>
                                <div class="wrap-media">
                                    <?php
                                    get_template_part('inc/templates/posts/global/sticky');
                                    ?>
                                    <a href="<?php echo esc_url(get_permalink()); ?>"
                                       title="<?php the_title_attribute() ?>">
                                        <?php
                                        the_post_thumbnail($img_size);
                                        ?>
                                    </a>
                                </div>
                            <?php } else {
                                get_template_part('inc/templates/posts/global/sticky');
                            }
                            ?>
                            <div class="wrap-post-item-content">
                                <?php
                                the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="' . esc_attr__('bookmark', 'anon') . '">', esc_url(get_permalink())), '</a></h3>');
                                get_template_part('inc/templates/posts/loop/post', 'info');
                                ?>
                            </div>
                        </div>
                    </article>
                    <?php
                } ?>
            </div>
        </section>

    <?php }
    wp_reset_postdata();
}
?>