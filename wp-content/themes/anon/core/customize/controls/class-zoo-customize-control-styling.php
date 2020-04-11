<?php
/**
 * Zoo_Customize_Control_Styling
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Styling extends Zoo_Customize_Control_Modal
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-styling">
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
        <div class="zoo-actions">
            <a href="#" title="<?php esc_attr_e( 'Reset to default', 'anon' ); ?>" class="action--reset" data-control="{{ data.name }}"><span class="dashicons dashicons-image-rotate"></span></a>
            <a href="#" title="<?php esc_attr_e( 'Toggle edit panel', 'anon' ); ?>" class="action--edit" data-control="{{ data.name }}"><span class="dashicons dashicons-edit"></span></a>
        </div>
        <div class="zoo-customize-control-settings-inner">
            <input type="hidden" class="zoo-hidden-modal-input zoo-only" data-name="{{ data.name }}" value="{{ JSON.stringify( data.value ) }}" data-default="{{ JSON.stringify( data.default ) }}">
        </div>
        </div>
        </script>
        <script type="text/html" id="tmpl-zoo-modal-settings">
            <div class="zoo-modal-settings">
                <div class="zoo-modal-settings--inner">
                    <div class="zoo-modal-settings--fields"></div>
                </div>
            </div>
        </script>
        <?php
    }
}
