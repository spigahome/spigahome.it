<?php
/**
 * Zoo_Customize_Control_Font
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Font extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-css-ruler">
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
                <input type="hidden" class="zoo-font-type" data-name="{{ data.name }}-type" >
                <div class="zoo-font-families-wrapper">
                    <select class="zoo-font-families" data-value="{{ JSON.stringify( data.value ) }}" data-name="{{ data.name }}-font"></select>
                </div>
                <div class="zoo-font-variants-wrapper">
                    <label><?php esc_html_e( 'Variants', 'anon' ) ?></label>
                    <select class="zoo-font-variants" data-name="{{ data.name }}-variant"></select>
                </div>
                <div class="zoo-font-subsets-wrapper">
                    <label><?php esc_html_e( 'Languages', 'anon' ) ?></label>
                    <div data-name="{{ data.name }}-subsets" class="list-subsets">
                    </div>
                </div>
            </div>
        </div>
        </script>
        <?php
    }
}
