<?php
/**
 * Zoo_Customize_Control_Font_Style
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Font_Style extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-font-style">
        <#
        var required = '';
        if ( ! _.isUndefined( data.required ) ) {
            required = JSON.stringify( data.required  );
        }
        #>
        <div class="zoo-customize-control zoo-customize-control-{{ data.type }} {{ data.class }} zoo-customize-control-name-{{ data.original_name }}" data-required="{{ required }}" data-field-name="{{ data.name }}">
        <# if ( data.label || data.description ) { #>
        <div class="zoo-customize-control-header">
            <# if ( data.label ) { #>
                <div class="zoo-customize-control-heading">
                    <label class="customize-control-title">{{{ data.label }}}</label>
                </div>
            <# } #>
            <# if ( data.description ) { #>
                <p class="description">{{{ data.description }}}</p>
            <# } #>
        </div>
        <# } #>
        <#
        if ( ! _.isObject( data.value ) ) {
            data.value = { };
        }
        #>
        <div class="zoo-customize-control-settings-inner zoo-font-style">
            <label title="<?php esc_attr_e( 'Bold', 'anon' ); ?>" class="button <# if ( data.value.b == 1 ){ #> zoo-checked <# } #>"><input type="checkbox" <# if ( data.value.b == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}-b" value="1"><span class="dashicons dashicons-editor-bold"></span></label>
            <label title="<?php esc_attr_e( 'Italic', 'anon' ); ?>" class="button <# if ( data.value.i == 1 ){ #> zoo-checked <# } #>"><input type="checkbox" <# if ( data.value.i == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}-i" value="1"><span class="dashicons dashicons-editor-italic"></span></label>
            <label title="<?php esc_attr_e( 'Underline', 'anon' ); ?>" class="button <# if ( data.value.u == 1 ){ #> zoo-checked <# } #>"><input type="checkbox" <# if ( data.value.u == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}-u" value="1"><span class="dashicons dashicons-editor-underline"></span></label>
            <label title="<?php esc_attr_e( 'Strikethrough', 'anon' ); ?>" class="button <# if ( data.value.s == 1 ){ #> zoo-checked <# } #>"><input type="checkbox" <# if ( data.value.s == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}-s" value="1"><span class="dashicons dashicons-editor-strikethrough"></span></label>
            <label title="<?php esc_attr_e( 'Uppercase', 'anon' ); ?>" class="button <# if ( data.value.t == 1 ){ #> zoo-checked <# } #>"><input type="checkbox" <# if ( data.value.t == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}-t" value="1"><span class="dashicons dashicons-editor-textcolor"></span></label>
        </div>
        </div>
        </script>
        <?php
    }
}
