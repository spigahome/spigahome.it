<?php
/**
 * Zoo_Customize_Control_Hidden
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Hidden extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-hidden">
        <#
        var required = '';
        if ( ! _.isUndefined( data.required ) ) {
            required = JSON.stringify( data.required  );
        }
        #>
        <div class="zoo-customize-control zoo-customize-control-{{ data.type }} {{ data.class }} zoo-customize-control-name-{{ data.original_name }}" data-required="{{ required }}" data-field-name="{{ data.name }}">
            <input type="hidden" class="zoo-input zoo-only" data-name="{{ data.name }}" value="{{ data.value }}">
        </div>
        </script>
        <?php
    }
}
