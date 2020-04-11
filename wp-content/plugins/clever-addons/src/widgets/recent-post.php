<?php
/**
 * Zoo Posts Widget
 *
 * @package     Clever Addon
 * @version     1.0.0
 * @author      clever-addons
 * @link        https://www.clever-addons.com/
 * @copyright   Copyright (c) 2018 clever-addons
 
 */

add_action( 'widgets_init', 'zoo_posts_load_widget' );

function zoo_posts_load_widget() {
    register_widget( 'zoo_posts_widget' );
}

class zoo_posts_widget extends WP_Widget {

    /**
     * Widget setup.
     */
    function __construct() {
        /* Widget settings. */
        $widget_ops = array( 'classname' => 'zoo_posts_widget', 'description' => esc_html__('A widget that displays posts', 'clever-addons') );

        /* Widget control settings. */
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'zoo_posts_widget' );

        /* Create the widget. */
        parent::__construct( 'zoo_posts_widget', esc_html__('Zoo: Posts for Sidebar', 'clever-addons'), $widget_ops, $control_ops );
    }

    /**
     * How to display the widget on the screen.
     */
    function widget( $args, $instance ) {
        extract( $args );

        /* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title'] );
        $categories = $instance['categories'];
        $number = $instance['number'];
        $orderby = $instance['orderby'];
        $order = $instance['order'];


        $query = array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'cat' => $categories,'orderby'=>$orderby,'order'=>$order);

        $loop = new WP_Query($query);
        if ($loop->have_posts()) :

            /* Before widget (defined by themes). */
            echo ent2ncr($before_widget);
            /* Display the widget title if one was input (before and after defined by themes). */
            if ( $title )
                echo ent2ncr($before_title) . esc_html($title) . ent2ncr($after_title);
            ?>
            <ul class="zoo-posts-widget">

            <?php  while ($loop->have_posts()) : $loop->the_post(); ?>

            <li class="post-widget-item <?php echo esc_attr(has_post_thumbnail()?'':'no-thumb');?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-widget-image">
                            <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                                <?php if(has_post_thumbnail()) : the_post_thumbnail('thumbnail');endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="post-widget-item-text">
                        <span class="date-post"><i class="zoo-icon-clock"></i> <?php echo esc_html(get_the_date()); ?></span>
                        <h4 class="title-post"><a href="<?php echo esc_url(get_permalink()); ?>" rel="<?php echo esc_attr('bookmark'); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
                    </div>
            </li>

        <?php endwhile;  wp_reset_postdata(); endif; ?>

        </ul>

        <?php

        /* After widget (defined by themes). */
        echo ent2ncr($after_widget);
    }

    /**
     * Update the widget settings.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['categories'] = $new_instance['categories'];
        $instance['number'] = strip_tags( $new_instance['number'] );
        $instance['orderby']=$new_instance['orderby'];
        $instance['order']=$new_instance['order'];
        return $instance;
    }


    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => esc_html__('Posts Widget', 'clever-addons'), 'number' => 5, 'categories' => '', 'orderby' => 'date', 'order' => 'DESC');
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'clever-addons'); ?></label>
            <input  type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>"  />
        </p>

        <!-- Category -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php echo esc_html(__('Filter by Category:', 'clever-addons')); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('categories')); ?>" name="<?php echo esc_attr($this->get_field_name('categories')); ?>" class="widefat categories">
                <option value='all' <?php if ('all' == $instance['categories']){ ?>selected="selected"<?php }?>><?php echo esc_html(__('All categories', 'clever-addons')); ?></option>
                <?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
                <?php foreach($categories as $category) { ?>
                    <option value='<?php echo esc_attr($category->term_id); ?>' <?php if ($category->term_id == $instance['categories']){ ?>selected="selected"<?php }?>><?php echo esc_html($category->cat_name); ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php echo esc_html(__('Order by:', 'clever-addons')); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>" class="widefat orderby">
                <option value='date' <?php if ('date' == $instance['orderby']){ ?>selected="selected"<?php }?>><?php echo esc_html(__('Date', 'clever-addons')); ?></option>
                <option value='author' <?php if ('author' == $instance['orderby']){ ?>selected="selected"<?php }?>><?php echo esc_html(__('Author', 'clever-addons')); ?></option>
                <option value='title' <?php if ('title' == $instance['orderby']){ ?>selected="selected"<?php }?>><?php echo esc_html__('Title', 'clever-addons'); ?></option>
                <option value='name' <?php if ('name' == $instance['orderby']){ ?>selected="selected"<?php }?>><?php echo esc_html__('Name', 'clever-addons'); ?></option>
                <option value='comment_count' <?php if ('comment_count' == $instance['orderby']){ ?>selected="selected"<?php }?>><?php echo esc_html__('Comment Count', 'clever-addons'); ?></option>
                <option value='rand' <?php if ('rand' == $instance['orderby']){ ?>selected="selected"<?php }?>><?php echo esc_html__('Random', 'clever-addons'); ?></option>
            </select>
        </p>        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php echo esc_html(__('Order:', 'clever-addons')); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>" class="widefat orderby">
                <option value='DESC' <?php if ('DESC' == $instance['order']){ ?>selected="selected"<?php }?>><?php echo esc_html(__('DESC', 'clever-addons')); ?></option>
                <option value='ASC' <?php if ('ASC' == $instance['order']) { ?>selected="selected"<?php } ?>><?php echo esc_html(__('ASC', 'clever-addons')); ?></option>
             </select>
        </p>
        <!-- Number of posts -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e('Number of posts to show:', 'clever-addons'); ?></label>
            <input  type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" value="<?php echo esc_attr($instance['number']); ?>" size="3" />
        </p>


    <?php
    }
}

?>