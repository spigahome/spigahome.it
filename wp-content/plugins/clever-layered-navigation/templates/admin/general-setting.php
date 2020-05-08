<?php
/**
 */

$item_id = isset($data['item_id']) ? $data['item_id'] : '';
$item_name = isset($data['item_name']) ? $data['item_name'] : 'Item Name';
$item_name_old = isset($data['item_name']) ? $data['item_name'] : '';
$instant_filtering = isset($data['instant_filtering']) ? intval($data['instant_filtering']) : 0;
$show_search_field = isset($data['show_search_field']) ? intval($data['show_search_field']) : 0;

?>

<form action="" method="post">
    <table class="form-table zoo-ln-table">
        <tbody>
            <tr>
                <th class="titledesc"><?php esc_html_e('Filter Title','clever-layered-navigation')?></th>
                <td class="forminp forminp-text">
                    <fieldset>
                        <input name="item_name" id="item_name" class="" value="<?php echo($item_name);?>" type="text">
                        <input name="item_name_old" id="item_name_old" class="" value="<?php echo($item_name_old);?>" type="hidden">
                        <p class="description">
                            <?php esc_html_e('Name of filter, and it use for generate shortcode slug.','clever-layered-navigation')?>
                        </p>
                    </fieldset>
                </td>
            </tr>

            <tr>
                <th class="titledesc"><?php esc_html_e('Instant Filtering','clever-layered-navigation')?></th>
                <td class="forminp forminp-checkbox">
                    <fieldset>
                        <input name="instant_filtering" id="instant_filtering" class="" value="1" type="checkbox" <?php checked($instant_filtering, 1);?>>
                        <p class="description">
                            <?php esc_html_e('If check, filter will starting when you select to filter item. Work with out filter button','clever-layered-navigation')?>
                        </p>
                    </fieldset>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="submit" class="zoo-ln-button" value="<?php esc_attr_e( 'Save Changes', 'clever-layered-navigation' )?>">
    <?php if ($item_id != ''):?>
        <input name="item_id" id="item_id" class="" value="<?php echo($item_id);?>" type="hidden">
    <?php endif;?>
    <?php wp_nonce_field( 'general_setting', 'zoo_ln_nonce_setting' ); ?>
</form>
