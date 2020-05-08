<?php
/**
 * Zoo_Customize_Control_Textarea
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Textarea extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-textarea">
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
                <textarea rows="10" class="zoo-input" data-name="{{ data.name }}">{{ DOMPurify.sanitize(data.value) }}</textarea>
            </div>
        </div>
        </script>
        <?php
    }
}
