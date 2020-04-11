<?php
/**
 * Zoo_Customize_Control_Repeater
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_Repeater extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-repeater">
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
            </div>
            </div>
        </script>
        <script type="text/html" id="tmpl-customize-control-repeater-item">
            <div class="zoo-repeater-item">
                <div class="zoo-repeater-item-heading">
                    <label class="zoo-repeater-visible" title="<?php esc_attr_e( 'Toggle item visible', 'anon' ); ?>"><input type="checkbox" class="r-visible-input"><span class="r-visible-icon"></span><span class="screen-reader-text"><?php _e( 'Show', 'anon' ) ?></label>
                    <span class="zoo-repeater-live-title"></span>
                    <div class="zoo-nav-reorder">
                        <span class="zoo-down" tabindex="-1"><span class="screen-reader-text"><?php esc_html_e( 'Move Down', 'anon' ) ?></span></span>
                        <span class="zoo-up" tabindex="0"><span class="screen-reader-text"><?php esc_html_e( 'Move Up', 'anon' ) ?></span></span>
                    </div>
                    <a href="#" class="zoo-repeater-item-toggle"><span class="screen-reader-text"><?php esc_html_e( 'Close', 'anon' ) ?></span></a>
                </div>
                <div class="zoo-repeater-item-settings">
                    <div class="zoo-repeater-item-inside">
                        <div class="zoo-repeater-item-inner"></div>
                        <# if ( data.addable ){  #>
                            <a href="#" class="zoo-remove"><?php esc_html_e( 'Remove', 'anon' ); ?></a>
                            <# } #>
                    </div>
                </div>
            </div>
        </script>
        <script type="text/html" id="tmpl-customize-control-repeater-inner">
            <div class="zoo-repeater-inner">
                <div class="zoo-settings-fields zoo-repeater-items"></div>
                <div class="zoo-repeater-actions">
                    <a href="#" class="zoo-repeater-reorder" data-text="<?php esc_attr_e( 'Reorder', 'anon' ); ?>" data-done="<?php _e( 'Done', 'anon' ); ?>"><?php _e( 'Reorder', 'anon' ); ?></a>
                    <# if ( data.addable ){  #>
                        <button type="button" class="button zoo-repeater-add-new"><?php esc_html_e( 'Add an item', 'anon' ); ?></button>
                        <# } #>
                </div>
            </div>
        </script>
        <?php
    }
}
