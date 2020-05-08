<?php
/**
 */

/**
 * Adds Zoo_Ln_Widget widget.
 */
class Zoo_Ln_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'zoo_ln_widget', // Base ID
            esc_html__( '+ Clever Layered Navigation Filter', 'clever-layered-navigation' ), // Name
            array( 'description' => esc_html__( 'Display filter follow filter preset.', 'clever-layered-navigation' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        do_shortcode('['.$instance['short_code'].']');

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $filter_items = \Zoo\Helper\Data\get_filter_item_with_id(0);
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'short_code' ) ); ?>"><?php esc_attr_e( 'Filter Preset:', 'text_domain' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'short_code' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'short_code' ) ); ?>">
            <?php
                foreach ($filter_items as $item) {
                    if (isset($instance['short_code']) && $instance['short_code'] == $item['short_code']) $selected = 'selected';
                    else $selected = '';
                    echo('<option value="' . $item['short_code'] . '" '.$selected.'>' . $item['item_name'] . '</option>');
                }
            ?>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['short_code'] = $new_instance['short_code'];
        return $instance;
    }

} // class Zoo_Ln_Widget