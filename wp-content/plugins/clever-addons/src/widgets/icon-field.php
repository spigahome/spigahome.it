<?php
/**
 * Zoo Icon Field Widget
 *
 * @package     Clever Addons
 * @version     1.0.0
 * @author      Zootemplate
 * @link        https://www.clever-addons.com/
 * @copyright   Copyright (c) 2018 Zootemplate
 
 */

if (!class_exists('ZooIconField')) {
    class ZooIconField extends WP_Widget
    {
        function __construct()
        {
            $widget_ops = array('classname' => 'ZooIconField', 'description' => esc_html__('Displays Text with Font icons.', 'clever-addons'));

            parent::__construct(false, esc_html__('Zoo: Icon Field', 'clever-addons'), $widget_ops);
        }

        function widget($args, $instance)
        {
            extract($args);
            $title = apply_filters('widget_title', $instance['title']);
            echo ent2ncr($before_widget);
            echo ent2ncr('<div class="zoo-icon-field clearfix"><div class="wrap-icon-item"><i class="'.$instance['icon'].'"></i></div>');
            echo ent2ncr('<div class="wrap-text-content">');
            if ($title) {
                echo ent2ncr($before_title . $title . $after_title);
            }
            echo ent2ncr($instance['text'].'</div></div>');
            echo ent2ncr($after_widget);
        }

        function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $instance = $new_instance;
            /* Strip tags (if needed) and update the widget settings. */
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['icon'] = strip_tags($new_instance['icon']);
            $instance['text'] = $new_instance['text'];
            return $instance;
        }

        function form($instance)
        {
            ?>
            <p>
                <label
                    for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'clever-addons'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" type="text"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                       value="<?php echo isset($instance['title']) ? esc_attr($instance['title']) : ''; ?>"/>
            </p>
            <p>
                <label
                        for="<?php echo esc_attr($this->get_field_id('icon')); ?>"><?php echo esc_html__('Class Font Icon:','clever-addons'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('icon')); ?>" type="text"
                       name="<?php echo esc_attr($this->get_field_name('icon')); ?>"
                       value="<?php echo isset($instance['icon']) ? esc_attr($instance['icon']) : ''; ?>"/>
                <?php echo esc_html__('Push class font of font icon','clever-addons')?>
            </p>
            <p>
                <label
                        for="<?php echo esc_attr($this->get_field_id('text')); ?>"><?php echo esc_html__('Text content:','clever-addons'); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('text')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo stripslashes(isset($instance['text']) ? $instance['text'] : ''); ?></textarea>
            </p>
            <?php
        }
    }
}

add_action('widgets_init', 'ZooIconFieldWidget');

function ZooIconFieldWidget()
{
    register_widget('ZooIconField');
}