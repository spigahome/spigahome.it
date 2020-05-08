<?php
/**
 * Zoo_Customize_Control_Checkbox
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Checkbox extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-checkbox">
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
        <div class="zoo-customize-control-settings-inner">
            <label>
                <input type="checkbox" class="zoo-input" <# if ( data.value == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}" value="1">
                {{{ data.checkbox_label }}}
            </label>
        </div>
        </div>
        </script>
        <?php
    }
}
