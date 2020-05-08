<?php
/**
 * Zoo Social Widget
 *
 * @package     Clever Addon
 * @version     1.0.0
 * @author      clever-addons
 * @link        https://www.clever-addons.com/
 * @copyright   Copyright (c) 2018 clever-addons
 
 */

if (!class_exists('ZooSocialWidget')) {
    class ZooSocialWidget extends WP_Widget
    {
        public $socials = array(
            'icon-social-facebook' => array(
                'title' => 'Facebook',
                'name' => 'facebook_username',
                'link' => '*',
                'icon' => 'zoo-icon-facebook',
            ),
            'icon-social-twitter' => array(
                'title' => 'Twitter',
                'name' => 'Twitter_username',
                'link' => '*',
                'icon' => 'zoo-icon-twitter',
            ),
            'icon-social-instagram' => array(
                'title' => 'Instagram',
                'name' => 'instagram_username',
                'link' => '*',
                'icon' => 'zoo-icon-instagram',
            ),
            'icon-social-pinterest' => array(
                'title' => 'Pinterest',
                'name' => 'pinterest_username',
                'link' => '*',
                'icon' => 'zoo-icon-pinterest',
            ),
            'icon-social-skype' => array(
                'title' => 'Skype',
                'name' => 'skype_username',
                'link' => '*',
                'icon' => 'zoo-icon-skype',
            ),
            'icon-social-vimeo' => array(
                'title' => 'Vimeo',
                'name' => 'vimeo_username',
                'link' => '*',
                'icon' => 'zoo-icon-vimeo-square',
            ),
            'icon-social-youtube' => array(
                'title' => 'Youtube',
                'name' => 'youtube_username',
                'link' => '*',
                'icon' => 'zoo-icon-youtube',
            ),
            'icon-social-dribbble' => array(
                'title' => 'Dribbble',
                'name' => 'dribbble_username',
                'link' => '*',
                'icon' => 'zoo-icon-dribbble',
            ),
            'icon-social-linkedin' => array(
                'title' => 'Linkedin',
                'name' => 'linkedin_username',
                'link' => '*',
                'icon' => 'zoo-icon-linkedin',
            ),
            'icon-social-rss' => array(
                'title' => 'Rss',
                'name' => 'rss_username',
                'link' => '*',
                'icon' => 'zoo-icon-rss',
            )
        );

        function __construct()
        {
            $widget_ops = array('classname' => 'ZooSocialWidget', 'description' => esc_html__('Displays your social profile.', 'clever-addons'));

            parent::__construct(false, esc_html__('Zoo: Social profile', 'clever-addons'), $widget_ops);
        }

        function widget($args, $instance)
        {
            extract($args);
            $title = isset($instance['title'])?apply_filters('widget_title', $instance['title']):'';
            $mode = isset($instance['mode'])?$instance['mode']:'text';
            echo ent2ncr($before_widget);
            if ($title) {
                echo ent2ncr($before_title . $title . $after_title);
            }
            echo '<ul class="zoo-widget-social-icon ' . esc_attr($mode) . ' clearfix">';
            foreach ($this->socials as $key => $social) {
                if (!empty($instance[$social['name']])) {
                    ?>
                    <li><a href="<?php echo esc_url(str_replace('*', esc_attr($instance[$social['name']]), $social['link'])) ?>" target="_blank" title="<?php echo esc_attr($social['title'])?>" class="<?php echo esc_attr($key . ' social-icon')?>"><?php
                    if ($mode == 'icon' || $mode == 'both') {
                        echo '<i class="' . esc_attr($social['icon']) . '"></i>';
                    }
                    if ($mode == 'text' || $mode == 'both') {
                        echo esc_html($social['title']);
                    }
                    ?>
                        </a></li><?php
                }
            }
            echo '</ul>';
            echo ent2ncr($after_widget);
        }

        function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $instance = $new_instance;
            /* Strip tags (if needed) and update the widget settings. */
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['mode'] = $new_instance['mode'];
            return $instance;
        }

        function form($instance)
        {
            if (!isset($instance['mode'])) {
                $instance['mode'] = 'text';
            }
            ?>
            <p>
                <label
                    for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title', 'clever-addons'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" type="text"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                       value="<?php echo isset($instance['title']) ? esc_attr($instance['title']) : ''; ?>"/>
            </p>
            <p>
                <label
                    for="<?php echo esc_attr($this->get_field_id('mode')); ?>"><?php echo esc_html__('Display', 'clever-addons'); ?></label>
                <select id="<?php echo esc_attr($this->get_field_id('mode')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('mode')); ?>">
                    <option
                        value='text'  <?php selected( $instance['mode'], 'text' ); ?>><?php echo esc_html__('Only Text', 'clever-addons'); ?></option>
                    <option
                        value='icon' <?php selected( $instance['mode'], 'icon' ); ?>><?php echo esc_html__('Only Icon', 'clever-addons'); ?></option>
                    <option
                        value='both' <?php selected( $instance['mode'], 'both' ); ?>><?php echo esc_html__('Both Text and Icon', 'clever-addons'); ?></option>
                </select>
            </p> <?php
            foreach ($this->socials as $key => $social) {
                ?>
                <p>
                <label
                    for="<?php echo esc_attr($this->get_field_id($social['name'])); ?>"><?php echo esc_html($social['title']); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id($social['name'])); ?>" type="text"
                       name="<?php echo esc_attr($this->get_field_name($social['name'])); ?>"
                       value="<?php echo isset($instance[$social['name']]) ? esc_attr($instance[$social['name']]) : ''; ?>"/>
                </p><?php
            }
        }
    }
}

add_action('widgets_init', 'zoo_social_load_widgets');

function zoo_social_load_widgets()
{
    register_widget('ZooSocialWidget');
}
