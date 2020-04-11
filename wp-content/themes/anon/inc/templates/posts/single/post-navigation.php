<?php
/**
 * Post navigation of single post
 *
 * @package     Zoo Theme
 * @version     3.0.0
 * @author      Zootemplate
 * @link        https://www.zootemplate.com/
 * @copyright   Copyright (c) 2020 ZooTemplate
 *
 **/

if (get_theme_mod('zoo_enable_next_previous_posts', '1') == 1) {
    $zoo_next_post = get_next_post();
    $zoo_prev_post = get_previous_post();
    $wrap_class = 'zoo-single-post-nav';
    if (empty($zoo_prev_post) || empty($zoo_next_post)) {
        $wrap_class .= ' only-1-post';
    }
    ?>
    <section class="<?php echo esc_attr($wrap_class) ?>">
        <?php
        if (!empty($zoo_prev_post)):
            $title = $zoo_prev_post->post_title;
            ?>
            <div class="prev-post zoo-single-post-nav-item">
                <a href="<?php echo esc_url(get_permalink($zoo_prev_post->ID)); ?>"
                   title="<?php echo esc_attr($title); ?>">
                    <?php echo wp_kses_post($title); ?>
                </a>
                <div class="content-single-post-nav-item">
                    <?php
                    echo get_the_post_thumbnail($zoo_prev_post->ID, 'thumbnail');
                    ?>
                    <div class="wrap-text-post-nav">
                        <span><i class="cs-font clever-icon-arrow-left-4"></i><?php echo esc_html__('Previous Post', 'anon'); ?></span>
                        <h4><?php echo wp_kses_post($title); ?></h4>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($zoo_next_post)):
            $title = $zoo_next_post->post_title;
            ?>
            <div class="next-post zoo-single-post-nav-item  pull-right">
                <a href="<?php echo esc_url(get_permalink($zoo_next_post->ID)); ?>"
                   title="<?php echo esc_attr($title); ?>"><?php echo wp_kses_post($title); ?>
                </a>
                <div class="content-single-post-nav-item">
                    <?php
                    echo get_the_post_thumbnail($zoo_next_post->ID, 'thumbnail');
                    ?>
                    <div class="wrap-text-post-nav">
                    <span><?php echo esc_html__('Next Post', 'anon'); ?>
                        <i class="cs-font clever-icon-arrow-right-4"></i></span>
                        <h4><?php echo wp_kses_post($title); ?></h4>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </section>
<?php } ?>