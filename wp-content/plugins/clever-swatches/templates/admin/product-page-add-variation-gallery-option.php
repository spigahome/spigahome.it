<?php
/**
 * @version  2.0.0
 * @package  clever-swatches/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

</br>
<label><?php _e('Variation Gallery Images','clever-swatches') ?></label>
<div class="zoo-cw-variation-gallery-wrapper">
    <div class="zoo-cw-variation-gallery-container product_images">
        <ul class="product_images ui-sortable">
            <?php echo $this->variation_gallery_output($variation->ID); ?>
        </ul>
        <input class="zoo-cw-variation-gallery" type="hidden" value="<?php echo get_post_meta( $variation->ID, 'zoo-cw-variation-gallery', true ) ?>" name="zoo-cw-variation-gallery[<?php echo $variation->ID; ?>]">
    </div>

    <div data-loop="<?php echo $loop; ?>" class="add-variation-gallery-image">
        <a href="#" data-text="Delete" data-delete="Delete image" data-update="Add to gallery" data-choose="Add Images to Product Variation"><?php _e('Add Variation Gallery Images','clever-swatches');?></a>
    </div>
</div>
