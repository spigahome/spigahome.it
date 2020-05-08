<?php
/**
 * Zoo_Customize_Control_Color
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Color extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-color">
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
                <div class="zoo-input-color" data-default="{{ data.default }}">
                    <input type="hidden" class="zoo-input zoo-input--color" data-name="{{ data.name }}" value="{{ data.value }}">
                    <input type="text" class="zoo-color-panel" data-alpha="true" value="{{ data.value }}">
                </div>
            </div>
        </div>
        </script>
        <?php
    }
}
