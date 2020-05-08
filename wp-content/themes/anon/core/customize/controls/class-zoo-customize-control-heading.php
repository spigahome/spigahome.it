<?php
/**
 * Zoo_Customize_Control_Heading
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Heading extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-heading">
        <#
        var required = '';
        if ( ! _.isUndefined( data.required ) ) {
            required = JSON.stringify( data.required  );
        }
        #>
        <div class="zoo-customize-control zoo-customize-control-{{ data.type }} {{ data.class }} zoo-customize-control-name-{{ data.original_name }}" data-required="{{ required }}" data-field-name="{{ data.name }}">
            <h3 class="zoo-customize-control--heading">{{ data.label }}</h3>
        </div>
        </script>
        <?php
    }
}
