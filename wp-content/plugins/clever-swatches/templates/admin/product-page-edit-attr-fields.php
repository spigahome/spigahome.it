<?php
/**
 * @version  2.0.0
 * @package  clever-swatches/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */
?>


<tr class="form-field">
    <th scope="row" valign="top"><label><?php _e( 'Display type', 'clever-swatches' ); ?></label></th>
    <td>
        <select id="zoo-cw-display-type" name="zoo-cw-display-type" class="postform">
            <option value="0" <?php selected($dt,0);?>><?php _e( 'None', 'clever-swatches' ); ?></option>
            <option value="1" <?php selected($dt,1);?>><?php _e( 'Image', 'clever-swatches' ); ?></option>
            <option value="2" <?php selected($dt,2);?>><?php _e( 'Color', 'clever-swatches' ); ?></option>
        </select>
    </td>
</tr>
<tr class="form-field zoo-cw-attr-colorpickerdiv zoo-cw-dt-option" <?php if($dt != 2): echo 'style="display:none";'; endif;?>>
    <th scope="row" valign="top"><label><?php _e( 'Select Color', 'clever-swatches' ); ?></label></th>
    <td>
        <input type="text" name="zoo_cw_slctdclr" class="zoo-cw-colorpicker" value="<?php echo $color_code; ?>"/>
    </td>
</tr>
<tr class="form-field zoo-cw-attr-image-uploader zoo-cw-dt-option" <?php if($dt != 1): echo 'style="display:none";'; endif;?>>
    <th scope="row" valign="top"><label><?php _e( 'Select/Upload Image', 'clever-swatches' ); ?></label></th>
    <td>
        <input type="hidden" class="zoo-cw-selected-attr-img" name="zoo-cw-selected-attr-img" value="<?php echo $image;?>">
        <img class="zoo-cw-slctd-img" src="<?php echo $image;?>" alt="<?php _e('Select Image','clever-swatches')?>" height="50px" width="50px">
        <button class="zoo-cw-image-picker" type="button"><?php _e('Browse','clever-swatches');?></button>
    </td>
</tr>
