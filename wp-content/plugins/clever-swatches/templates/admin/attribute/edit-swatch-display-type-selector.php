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


<tr class="form-field form-required">
    <th scope="row" valign="top">
        <label for="attribute_type"><?php echo esc_html__('CleverSwatches Type','clever-swatches')?></label>
    </th>
    <td>
        <select name="zoo_cw_swatch_display_type" id="" class="zoo_cw_swatch_display_type">
            <option value="select" <?php selected($selected_type, 'select'); ?>><?php echo esc_html__('Select','clever-swatches')?></option>
            <option value="image" <?php selected($selected_type, 'image'); ?>><?php echo esc_html__('Image','clever-swatches')?></option>
            <option value="color" <?php selected($selected_type, 'color'); ?>><?php echo esc_html__('Color','clever-swatches')?></option>
            <option value="text" <?php selected($selected_type, 'text'); ?>><?php echo esc_html__('Text','clever-swatches')?></option>
        </select>
        <p class="description"><?php echo esc_html__('Display type of swatch attribute','clever-swatches')?></p>
    </td>
</tr>