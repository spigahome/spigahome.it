<?php
/**
 * Zoo_Customize_Control_Media
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
class Zoo_Customize_Control_Media extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-media">
        <#
        var required = '';
        if ( ! _.isUndefined( data.required ) ) {
            required = JSON.stringify( data.required  );
        }
        #>
        <div class="zoo-customize-control zoo-customize-control-{{ data.type }} {{ data.class }} zoo-customize-control-name-{{ data.original_name }}" data-required="{{ required }}" data-field-name="{{ data.name }}">
            <#
            if ( ! _.isObject(data.value) ) {
                data.value = {};
            }
            var url = data.value.url;
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
            <div class="zoo-customize-control-settings-inner zoo-media-type-{{ data.type }}">
                <div class="zoo-media">
                    <input type="hidden" class="attachment-id" value="{{ data.value.id }}" data-name="{{ data.name }}">
                    <input type="hidden" class="attachment-url"  value="{{ data.value.url }}" data-name="{{ data.name }}-url">
                    <input type="hidden" class="attachment-mime"  value="{{ data.value.mime }}" data-name="{{ data.name }}-mime">
                    <div class="zoo-image-preview <# if ( url ) { #> zoo-has-file <# } #>" data-no-file-text="<?php esc_attr_e( "No file selected", 'anon' ); ?>">
                        <#

                        if ( url ) {
                            if ( url.indexOf('http://') > -1 || url.indexOf('https://') ){

                            } else {
                                url = ZooCustomizeBuilder.home_url + url;
                            }

                            if ( ! data.value.mime || data.value.mime.indexOf('image/') > -1 ) {
                                #>
                                <img src="{{ url }}">
                            <# } else if ( data.value.mime.indexOf('video/' ) > -1 ) { #>
                                <video width="100%" height="" controls><source src="{{ url }}" type="{{ data.value.mime }}">Your browser does not support the video tag.</video>
                            <# } else {
                            var basename = url.replace(/^.*[\\\/]/, '');
                            #>
                                <a href="{{ url }}" class="attachment-file" target="_blank">{{ basename }}</a>
                            <# }
                        }
                        #>
                    </div>
                    <button type="button" class="button zoo-add <# if ( url ) { #> zoo-hide <# } #>"><?php esc_html_e( 'Add', 'anon' ); ?></button>
                    <button type="button" class="button zoo-change <# if ( ! url ) { #> zoo-hide <# } #>"><?php esc_html_e( 'Change', 'anon' ); ?></button>
                    <button type="button" class="button zoo-remove <# if ( ! url ) { #> zoo-hide <# } #>"><?php esc_html_e( 'Remove', 'anon' ); ?></button>
                </div>
            </div>
        </div>
        </script>
        <?php
    }
}
