<?php
/**
 * List layout for post
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @core        3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate

 */


$class = zoo_post_loop_item_css();
?>
<article <?php echo post_class($class) ?>>
    <div class="zoo-post-inner">
        <?php
        /**
         * Zoo Before Post Item
         *
         */
        do_action('zoo_before_post_item');

        /**
         * Zoo Before Post Item title
         * zoo_post_item_thumbnail 10 Case layout is grid
         * zoo_post_item_cat 20
         *
         */
        do_action('zoo_before_post_item_title');
        the_title(sprintf('<h3 class="entry-title title-post"><a href="%s" rel="' . esc_attr__('bookmark', 'anon') . '">', esc_url(get_permalink())), '</a></h3>');
        /**
         * Zoo After Post Item title
         * zoo_post_item_info 10
         * zoo_post_item_thumbnail 20 Case layout is list
         *
         */
        do_action('zoo_after_post_item_title');
        ?>
        <div class="entry-content<?php if (get_theme_mod('zoo_enable_loop_excerpt', 0) == 1) { echo esc_attr(' excerpt');}?>">
            <?php
            if (get_theme_mod('zoo_enable_loop_excerpt', 0) == 1 || is_search()) {
                the_excerpt();
            }else{
                the_content();
            }
            ?>
        </div>
        <?php
        /**
         * Zoo After Post Item
         * zoo_post_item_readmore 10
         *
         */
        do_action('zoo_after_post_item');
        ?>
    </div>
</article>