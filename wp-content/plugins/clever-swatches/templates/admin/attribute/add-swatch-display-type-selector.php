<?php
/**
 * @version  2.0.0
 * @package  clever-swatches/templates/admin/attribute
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>


<div class="form-field">
    <label for="zoo_cw_swatch_display_type"><?php echo esc_html__('CleverSwatches Type','clever-swatches')?></label>
    <select name="zoo_cw_swatch_display_type" id="" class="zoo_cw_swatch_display_type">
        <option value="select"><?php echo esc_html__('Select','clever-swatches')?></option>
        <option value="image"><?php echo esc_html__('Image','clever-swatches')?></option>
        <option value="color"><?php echo esc_html__('Color','clever-swatches')?></option>
        <option value="text"><?php echo esc_html__('Text','clever-swatches')?></option>
    </select>
    <p class="description"><?php echo esc_html__('Display type of swatch attribute','clever-swatches')?></p>
</div>