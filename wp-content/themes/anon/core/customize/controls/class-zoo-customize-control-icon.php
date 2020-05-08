<?php
/**
 * Zoo_Customize_Control_Icon
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Icon extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
       <script type="text/html" id="tmpl-zoo-customize-control-icon">
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
            <div class="zoo-icon-picker">
                <div class="zoo-icon-preview">
                    <input type="hidden" class="zoo-input zoo-input-icon-type" data-name="{{ data.name }}-type" value="{{ data.value.type }}">
                    <div class="zoo-icon-preview-icon zoo-pick-icon">
                        <# if ( data.value.icon ) {  #>
                            <i class="{{ data.value.icon }}"></i>
                        <# }  #>
                    </div>
                </div>
                <input type="text" readonly class="zoo-input zoo-pick-icon zoo-input-icon-name" placeholder="<?php esc_attr_e( 'Pick an icon', 'anon' ); ?>" data-name="{{ data.name }}" value="{{ data.value.icon }}">
                <span class="zoo-icon-remove" title="<?php esc_attr_e( 'Remove', 'anon' ); ?>">
                    <span class="dashicons dashicons-no-alt"></span>
                    <span class="screen-reader-text">
                    <?php esc_html_e( 'Remove', 'anon' ) ?></span>
                </span>
            </div>
        </div>
        </div>
        </script>
        <div id="zoo-sidebar-icons">
            <div class="zoo-sidebar-header">
                <a class="customize-controls-icon-close" href="#">
                    <span class="screen-reader-text"><?php esc_html_e( 'Cancel', 'anon' );  ?></span>
                </a>
                <div class="zoo-icon-type-inner">
                    <select id="zoo-sidebar-icon-type">
                        <option value="all"><?php esc_html_e( 'All Icon Types', 'anon' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="zoo-sidebar-search">
               <input type="text" id="zoo-icon-search" placeholder="<?php esc_attr_e( 'Type icon name', 'anon' ) ?>">
            </div>
            <div id="zoo-icon-browser"></div>
        </div>
        <?php
    }
}
