<?php
/**
 * Zoo_Customize_Control_Shadow
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Shadow extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-shadow">
        <#
        var required = '';
        if ( ! _.isUndefined( data.required ) ) {
            required = JSON.stringify( data.required  );
        }
        #>
        <div class="zoo-customize-control zoo-customize-control-{{ data.type }} {{ data.class }} zoo-customize-control-name-{{ data.original_name }}" data-required="{{ required }}" data-field-name="{{ data.name }}">
            <#
                if ( ! _.isObject( data.value ) ) {
                data.value = { };
                }

                var uniqueID = data.name + ( new Date().getTime() );
            #>
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
                        <input type="hidden" class="zoo-input zoo-input--color" data-name="{{ data.name }}-color" value="{{ data.value.color }}">
                        <input type="text" class="zoo-color-panel" data-alpha="true" value="{{ data.value.color }}">
                    </div>

                    <div class="zoo-gr-inputs">
                        <span>
                            <input type="number" class="zoo-input zoo-input-css change-by-js"  data-name="{{ data.name }}-x" value="{{ data.value.x }}">
                            <span class="zoo-small-label"><?php esc_html_e( 'X', 'anon' ); ?></span>
                        </span>
                        <span>
                            <input type="number" class="zoo-input zoo-input-css change-by-js"  data-name="{{ data.name }}-y" value="{{ data.value.y }}">
                            <span class="zoo-small-label"><?php esc_html_e( 'Y', 'anon' ); ?></span>
                        </span>
                        <span>
                            <input type="number" class="zoo-input zoo-input-css change-by-js" data-name="{{ data.name }}-blur" value="{{ data.value.blur }}">
                            <span class="zoo-small-label"><?php esc_html_e( 'Blur', 'anon' ); ?></span>
                        </span>
                        <span>
                            <input type="number" class="zoo-input zoo-input-css change-by-js" data-name="{{ data.name }}-spread" value="{{ data.value.spread }}">
                            <span class="zoo-small-label"><?php esc_html_e( 'Spread', 'anon' ); ?></span>
                        </span>
                        <span>
                            <span class="input">
                                <input type="checkbox" class="zoo-input zoo-input-css change-by-js" <# if ( data.value.inset == 1 ){ #> checked="checked" <# } #> data-name="{{ data.name }}-inset" value="{{ data.value.inset }}">
                            </span>
                            <span class="zoo-small-label"><?php esc_html_e( 'inset', 'anon' ); ?></span>
                        </span>
                    </div>
                </div>
            </div>
            </script>
            <?php
    }
}
