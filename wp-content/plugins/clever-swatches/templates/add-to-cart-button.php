<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * template file for translate js with add-to-cart button.
 *
 * @version  2.0.0
 * @package  clever-swatches/templates
 * @category Templates
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    2.0.0
 */
?>
<script type="text/template" id="tmpl-add-to-cart-button">
    <span class="zoo_cw_add_to_cart_button_label">
        <?php _e('Add to cart', 'clever-swatches'); ?>
    </span>
</script>
<script type="text/template" id="tmpl-add-to-cart-button-out-stock">
    <span class="zoo_cw_add_to_cart_button_label">
        <?php _e('Out of stock', 'clever-swatches'); ?>
    </span>
</script>
<script type="text/template" id="tmpl-add-to-cart-button-select-option">
    <span class="zoo_cw_add_to_cart_button_label">
        <?php _e('Select options', 'clever-swatches'); ?>
    </span>
</script>
