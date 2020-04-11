<?php
/**
 */

$filter_max_height = !empty($data['filter_max_height']) ? intval($data['filter_max_height']) : '';
$filter_view_style = isset($data['filter_view_style']) ? strval($data['filter_view_style']) : 'vertical';
$horizontal_number_col = isset($data['horizontal_number_col']) ? intval($data['horizontal_number_col']) : 1;
$horizontal_add_primary = isset($data['horizontal_add_primary']) ? intval($data['horizontal_add_primary']) : 0;
$horizontal_alway_visible = isset($data['horizontal_alway_visible']) ? intval($data['horizontal_alway_visible']) : 0;
$class_hidden='';
if($filter_view_style!='horizontal'){
    $class_hidden='hidden';
}
?>

<form action="" method="post">
    <table class="form-table zoo-ln-table">
        <tbody>
            <tr>
                <th class="">
                    <?php _e('Max height', 'clever-layered-navigation') ?>
                </th>
                <td>
                    <fieldset>
                        <label>
                            <input type="number" name="filter_max_height" value="<?php echo $filter_max_height ?>" placeholder="<?php _e('max height (px)', 'clever-layered-navigation') ?>">
                            <!-- <span class="description"><?php _e('Max height of the filter.', 'clever-layered-navigation') ?></span> -->
                        </label>
                    </fieldset>
                </td>
            </tr>
        <tr>
            <th class=""><?php esc_html_e( 'Filter view style', 'clever-layered-navigation' )?></th>
            <td class="">
                <fieldset>
                    <select name="filter_view_style" id="filter_view_style">
                        <option value="horizontal" <?php selected($filter_view_style, 'horizontal'); ?>><?php esc_html_e( 'Horizontal', 'clever-layered-navigation' )?></option>
                        <option value="vertical" <?php selected($filter_view_style, 'vertical'); ?>><?php esc_html_e( 'Vertical', 'clever-layered-navigation' )?></option>
                    </select>
                    <p class="description">
                        <?php esc_html_e( 'Orientation of Clever Layered Navigation filter widget container', 'clever-layered-navigation' )?>
                    </p>
                </fieldset>
            </td>
        </tr>
        <tr class="horizontal-options <?php echo esc_attr($class_hidden)?>">
            <th class=""><?php esc_html_e( 'Number of column', 'clever-layered-navigation' )?></th>
            <td class="">
                <fieldset>
                    <select name="horizontal_number_col" id="horizontal_number_col">
                        <option value="1" <?php selected($horizontal_number_col, 1); ?>>1 <?php esc_html_e( 'column', 'clever-layered-navigation' )?></option>
                        <option value="2" <?php selected($horizontal_number_col, 2); ?>>2 <?php esc_html_e( 'columns', 'clever-layered-navigation' )?></option>
                        <option value="3" <?php selected($horizontal_number_col, 3); ?>>3 <?php esc_html_e( 'columns', 'clever-layered-navigation' )?></option>
                        <option value="4" <?php selected($horizontal_number_col, 4); ?>>4 <?php esc_html_e( 'columns', 'clever-layered-navigation' )?></option>
                        <option value="5" <?php selected($horizontal_number_col, 5); ?>>5 <?php esc_html_e( 'columns', 'clever-layered-navigation' )?></option>
                        <option value="6" <?php selected($horizontal_number_col, 6); ?>>6 <?php esc_html_e( 'columns', 'clever-layered-navigation' )?></option>
                    </select>
                    <label for="horizontal_number_col">
                        <?php esc_html_e( 'Number column display of layout horizontal', 'clever-layered-navigation' )?>
                    </label>
                </fieldset>
            </td>
        </tr>
        <tr class="horizontal-options  <?php echo esc_attr($class_hidden)?>">
            <th class="titledesc"><?php esc_html_e( 'Add primary filter', 'clever-layered-navigation' )?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <input name="horizontal_add_primary" id="horizontal_add_primary" class="" value="1" type="checkbox" <?php checked($horizontal_add_primary, 1);?>>
                    <label for="horizontal_add_primary">
                        <?php esc_html_e( 'Work with layout horizontal. If check, a new filter layer Primary will show. All filter on that layer will default visible. Display out side column of layout horizontal.', 'clever-layered-navigation' )?>
                    </label>
                </fieldset>
            </td>
        </tr>
        <tr class="horizontal-options  <?php echo esc_attr($class_hidden)?>">
            <th class="titledesc"><?php esc_html_e( 'Always visible', 'clever-layered-navigation' )?></th>
            <td class="forminp forminp-checkbox">
                <fieldset>
                    <input name="horizontal_alway_visible" id="horizontal_alway_visible" class="" value="1" type="checkbox" <?php checked($horizontal_alway_visible, 1);?>>
                    <label for="horizontal_alway_visible">
                        <?php esc_html_e( 'If check, filter with horizontal layout will always show.', 'clever-layered-navigation' )?>
                    </label>
                </fieldset>
            </td>
        </tr>
        </tbody>
    </table>
    <input type="submit" class="zoo-ln-button" value="<?php esc_attr_e( 'Save Changes', 'clever-layered-navigation' )?>">
    <?php wp_nonce_field( 'filter_style', 'zoo_ln_nonce_setting' ); ?>
</form>
