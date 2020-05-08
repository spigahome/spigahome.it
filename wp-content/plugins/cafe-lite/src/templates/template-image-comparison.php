<?php
/**
 * View template for Image Comparison widget.
 *
 * @package CAFE\Templates
 * @copyright 2018 CleverSoft. All rights reserved.
 */

?>

<div class="cafe-wrap-image-comparison" data-orientation="<?php echo esc_attr($settings['orientation']);?>">
	<?php if ( $settings['before_image']['url'] != '' ) { ?>
            <img class="before-img" src="<?php echo esc_url( $settings['before_image']['url'] ); ?>"
                 alt="<?php echo esc_attr( $settings['before_title'] ) ?>"/>
	<?php } ?>
	<?php if ( $settings['after_image']['url'] != '' ) { ?>
            <img class="after-img" src="<?php echo esc_url( $settings['after_image']['url'] ); ?>"
                 alt="<?php echo esc_attr( $settings['after_title'] ) ?>"/>
	<?php } ?>
</div>
