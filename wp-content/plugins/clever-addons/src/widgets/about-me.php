<?php
/**
 * Plugin Name: About Me widget
 */

add_action( 'widgets_init', 'zoo_aboutme' );

function zoo_aboutme() {
    register_widget( 'zoo_about_me_widget' );
}

// add admin scripts

if ( function_exists( 'js_enqueue' ) ) {
    function js_enqueue() {
        wp_enqueue_media();
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_script( 'media-upload' );
        wp_enqueue_script( 'thickbox' );
    }

    add_action( 'admin_enqueue_scripts', 'js_enqueue' );
}

class zoo_about_me_widget extends WP_Widget {
    public $socials = array(
        'ion-social-facebook'   => array(
            'title' => 'Facebook',
            'name'  => 'facebook_username',
            'link'  => 'http://www.facebook.com/*',
            'icon'  => 'fa-facebook',
        ),
        'ion-social-twitter'    => array(
            'title' => 'Twitter',
            'name'  => 'Twitter_username',
            'link'  => 'http://twitter.com/*',
            'icon'  => 'fa-twitter',
        ),
        'ion-social-instagram'  => array(
            'title' => 'Instagram',
            'name'  => 'instagram_username',
            'link'  => 'http://instagram.com/*',
            'icon'  => 'fa-instagram',
        ),
        'ion-social-pinterest'  => array(
            'title' => 'Pinterest',
            'name'  => 'pinterest_username',
            'link'  => 'http://pinterest.com/*',
            'icon'  => 'fa-pinterest',
        ),
        'ion-social-skype'      => array(
            'title' => 'Skype',
            'name'  => 'skype_username',
            'link'  => 'skype:*',
            'icon'  => 'fa-skype',
        ),
        'ion-social-vimeo'      => array(
            'title' => 'Vimeo',
            'name'  => 'vimeo_username',
            'link'  => 'http://vimeo.com/*',
            'icon'  => 'fa-vimeo-square',
        ),
        'ion-social-youtube'    => array(
            'title' => 'Youtube',
            'name'  => 'youtube_username',
            'link'  => 'http://www.youtube.com/user/*',
            'icon'  => 'fa-youtube',
        ),
        'ion-social-dribbble'   => array(
            'title' => 'Dribbble',
            'name'  => 'dribbble_username',
            'link'  => 'http://dribbble.com/*',
            'icon'  => 'fa-dribbble',
        ),
        'ion-social-linkedin'   => array(
            'title' => 'Linkedin',
            'name'  => 'linkedin_username',
            'link'  => '*',
            'icon'  => 'fa-linkedin',
        ),
        'ion-social-rss'        => array(
            'title' => 'Rss',
            'name'  => 'rss_username',
            'link'  => 'http://*/feed',
            'icon'  => 'fa-rss',
        )
    );

    /**
     * Widget setup.
     */
    public function __construct() {
        /* Widget settings. */
        $widget_ops = array( 'classname'   => 'about-me-widget',
            'description' => esc_html__( 'About me widget. Help make information for author is easy.', 'clever-addons' )
        );

        /* Widget control settings. */
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'about_me_widget' );

        /* Create the widget. */
        parent::__construct( 'about_me_widget', esc_html__( 'Zoo: About me', 'clever-addons' ), $widget_ops, $control_ops );
    }

    /**
     * How to display the widget on the screen.
     */
    function widget( $args, $instance ) {
        extract( $args );

        /* Our variables from the widget settings. */
        $title   = apply_filters( 'widget_title', $instance['title'] );
        $image   = $instance['image'];
        $name    = $instance['name'];
        $sign    = $instance['sign'];
        $caption = $instance['caption'];
        $class   = $instance['class'];
        echo ent2ncr( $args['before_widget'] );
        if ( $title ) {
            echo ent2ncr( $args['before_title'] ) . esc_html( $title ) . ent2ncr( $args['after_title'] );
        }
        ?>
        <div class="zoo-about-me <?php echo esc_attr( $class ); ?>">
            <?php if ( $image != '' ) {
                ?>
                <img src="<?php echo esc_url( $image ) ?>" alt="<?php echo esc_html( $name ) ?>" class="zoo-avatar"/>
            <?php }
            if ( $name != '' ) { ?>
                <h6 class="zoo-name"><?php echo esc_attr( $name ); ?></h6>
            <?php }
            if ( $caption != '' ) {
                ?>
                <div class="caption">
                    <?php echo esc_html( $caption ); ?>
                </div>
                <?php
            }
            if ( $sign != '' ) {
                ?>
                <div class="wrap-sign">
                    <img src="<?php echo esc_url( $sign ) ?>" alt="<?php echo esc_html( $name ) ?>"/>
                </div>
            <?php }
            $socail_html = '';
            foreach ( $this->socials as $key => $social ) {
                if ( ! empty( $instance[ $social['name'] ] ) ) {
                    $socail_html .= '<a href="' . str_replace( '*', esc_attr( $instance[ $social['name'] ] ), $social['link'] ) . '" target="_blank" title="' . esc_attr( $social['title'] ) . '" class="' . esc_attr( $key . ' social-icon' ) . '">';
                    $socail_html .= '<i class="fa ' . esc_attr( $social['icon'] ) . '"></i>';
                    $socail_html .= '</a>';
                }
            }
            if ( $socail_html != '' ) {
                ?>
                <div class="wrap-social-icon clearfix">
                    <?php echo ent2ncr( $socail_html ) ?>
                </div>
            <?php } ?>
        </div>
        <?php

        /* After widget (defined by themes). */
        echo ent2ncr( $args['after_widget'] );
    }

    /**
     * Update the widget settings.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance = $new_instance;
        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title']   = strip_tags( $new_instance['title'] );
        $instance['image']   = strip_tags( $new_instance['image'] );
        $instance['caption'] = strip_tags( $new_instance['caption'] );
        $instance['name']    = strip_tags( $new_instance['name'] );
        $instance['sign']    = strip_tags( $new_instance['sign'] );

        return $instance;
    }

    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array(
            'title'   => esc_html__( '', 'clever-addons' ),
            'image'   => '',
            'caption' => '',
            'name'    => '',
            'sign'    => '',
            'class'   => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'clever-addons' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   value="<?php echo esc_attr( $instance['title'] ); ?>"/>
        </p>

        <p id="<?php echo esc_attr( $this->get_field_id( 'image' ) . '-wrapp' ); ?>">
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Image:', 'clever-addons' ); ?></label>
            <img id="<?php echo esc_attr( $this->get_field_id( 'image' ) . '-img' ); ?>"
                 src="<?php echo esc_url( $instance['image'] ) ?>"
                 style="max-width:100%"
                 class="custom_media_image <?php echo( $instance['image'] == '' ? esc_attr( 'hidden' ) : '' ); ?>"/>
            <input type="text" class="widefat custom_media_url hidden"
                   name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"
                   value="<?php echo esc_attr( $instance['image'] ); ?>"/>
            <br>
            <input type="button" class="button button-primary custom_media_button"
                   id="<?php echo esc_attr( $this->get_field_id( 'image' ) . '-button' ); ?>"
                   value="<?php esc_attr_e( 'Select Image', 'clever-addons' ) ?>"/>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"><?php esc_html_e( 'Name:', 'clever-addons' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'name' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'name' ) ); ?>"
                   value="<?php echo esc_attr( $instance['name'] ); ?>"/>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>"><?php esc_html_e( 'Caption:', 'clever-addons' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>"
                      name="<?php echo esc_attr( $this->get_field_name( 'caption' ) ); ?>">
                <?php echo esc_html( $instance['caption'] ); ?></textarea>
        </p>
        <p id="<?php echo esc_attr( $this->get_field_id( 'sign' ) . '-wrapp' ); ?>">
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'sign' ) ); ?>"><?php esc_html_e( 'Sign Image:', 'clever-addons' ); ?></label>
            <img id="<?php echo esc_attr( $this->get_field_id( 'sign' ) . '-img' ); ?>"
                 src="<?php echo esc_url( $instance['sign'] ) ?>"
                 style="max-width:100%"
                 class="custom_media_image <?php echo( $instance['sign'] == '' ? esc_attr( 'hidden' ) : '' ); ?>"/>
            <input type="text" class="widefat custom_media_url hidden"
                   name="<?php echo esc_attr( $this->get_field_name( 'sign' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'sign' ) ); ?>"
                   value="<?php echo esc_attr( $instance['sign'] ); ?>"/>
            <br>
            <input type="button" class="button button-primary custom_media_button"
                   id="<?php echo esc_attr( $this->get_field_id( 'sign' ) . '-button' ); ?>"
                   value="<?php esc_attr_e( 'Select Sign', 'clever-addons' ) ?>"/>
        </p>
        <?php foreach ( $this->socials as $key => $social ) {
            ?>
            <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( $social['name'] ) ); ?>"><?php echo esc_html( $social['title'] ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $social['name'] ) ); ?>" type="text"
                   name="<?php echo esc_attr( $this->get_field_name( $social['name'] ) ); ?>"
                   value="<?php echo isset( $instance[ $social['name'] ] ) ? esc_attr( $instance[ $social['name'] ] ) : ''; ?>"/>
            </p><?php
        } ?>
        <p>
            <label
                    for="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"><?php esc_html_e( 'Class:', 'clever-addons' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'class' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'class' ) ); ?>"
                   value="<?php echo esc_attr( $instance['class'] ); ?>"/>
        </p>
        <?php
    }
}

?>