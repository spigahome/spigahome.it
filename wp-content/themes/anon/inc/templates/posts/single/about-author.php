<?php
/**
 * The default template for Author for post
 *
 * Used for both single and author page.
 *
 * @package     Zoo Theme
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 
 */
if(get_the_author_meta('description')!=''){?>
<section class="post-author">
    <div class="author-img">
        <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) )); ?>" title="<?php echo esc_attr(get_the_author()) ?>">
            <?php echo wp_kses(get_avatar(get_the_author_meta('email'), '150'), array('img' => array('class' => array(), 'width' => array(), 'height' => array(), 'alt' => array(), 'src' => array()))); ?>
        </a>
    </div>
    <div class="author-content">
        <h4 class="author-name"><?php the_author_posts_link(); ?></h4>
        <p><?php the_author_meta('description'); ?></p>
        <ul class="wrap-author-social">
            <?php if (get_the_author_meta('facebook')) : ?>
                <li><a target="_blank" class="author-social"
                       href="http://facebook.com/<?php echo esc_url(the_author_meta('facebook')); ?>"><i
                        class="cs-font clever-icon-facebook"></i></a></li><?php endif; ?>
            <?php if (get_the_author_meta('twitter')) : ?>
                <li><a target="_blank" class="author-social"
                       href="http://twitter.com/<?php echo esc_url(the_author_meta('twitter')); ?>"><i
                        class="cs-font clever-icon-twitter"></i></a></li><?php endif; ?>
            <?php if (get_the_author_meta('pinterest')) : ?>
                <li><a target="_blank" class="author-social"
                       href="http://pinterest.com/<?php echo esc_url(the_author_meta('pinterest')); ?>"><i
                        class="cs-font clever-icon-pinterest"></i></a></li><?php endif; ?>
            <?php if (get_the_author_meta('tumblr')) : ?>
                <li><a target="_blank" class="author-social"
                       href="http://<?php echo esc_url(the_author_meta('tumblr')); ?>.tumblr.com/"><i
                        class="cs-font clever-icon-tumblr"></i></a></li><?php endif; ?>
            <?php if (get_the_author_meta('instagram')) : ?>
                <li><a target="_blank" class="author-social"
                       href="http://instagram.com/<?php echo esc_url(the_author_meta('instagram')); ?>"><i
                        class="cs-font clever-icon-instagram"></i></a></li><?php endif; ?>
        </ul>
    </div>
</section>
<?php }