<?php
/**
 * Zoo_Customize_Control_CSS_Rule
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
final class Zoo_Customize_Control_CSS_Rule extends Zoo_Customize_Control_Default
{
    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-css_rule">
        <#
        var required = '';
        if (!_.isUndefined(data.required)) {
            required = JSON.stringify(data.required);
        }
        #>
        <div class="zoo-customize-control zoo-customize-control-{{ data.type }} {{ data.class }} zoo-customize-control-name-{{ data.original_name }}" data-required="{{ required }}" data-field-name="{{ data.name }}">
            <#
            if (!_.isObject(data.value)) {
                data.value = { link: 0 };
            }
            var fields_disabled;
            if (!_.isObject(data.fields_disabled)) {
                fields_disabled = {};
            } else {
                fields_disabled = _.clone(data.fields_disabled);
            }
            var defaultpl = <?php echo json_encode(__('Auto', 'anon')); ?>;
            _.each(['top', 'right', 'bottom', 'left'], function(key){
                if (!_.isUndefined(fields_disabled[key])) {
                    if (!fields_disabled[key]) {
                        fields_disabled[key] = defaultpl;
                    }
                } else {
                    fields_disabled[key] = false;
                }
            });
            var uniqueID = data.name + (new Date().getTime());
            #>
            <# if (data.label || data.description) { #>
            <div class="zoo-customize-control-header">
                <# if (data.label) { #>
                    <div class="zoo-customize-control-heading">
                        <label class="customize-control-title">{{{ data.label }}}</label>
                    </div>
                <# } #>
                <# if (data.description) { #>
                    <p class="description">{{{ data.description }}}</p>
                <# } #>
            </div>
            <# } #>
            <div class="zoo-customize-control-settings-inner">
                <div class="zoo-css-ruler zoo-gr-inputs">
                    <span>
                        <input type="number" class="zoo-input zoo-input-css change-by-js" <# if (fields_disabled['top']) { #> disabled="disabled" placeholder="{{ fields_disabled['top'] }}" <# } #> data-name="{{ data.name }}-top" value="{{ data.value.top }}">
                        <span class="zoo-small-label"><?php esc_html_e('Top', 'anon'); ?></span>
                    </span>
                    <span>
                        <input type="number" class="zoo-input zoo-input-css change-by-js" <# if (fields_disabled['right']) { #> disabled="disabled" placeholder="{{ fields_disabled['right'] }}" <# } #> data-name="{{ data.name }}-right" value="{{ data.value.right }}">
                        <span class="zoo-small-label"><?php esc_html_e('Right', 'anon'); ?></span>
                    </span>
                    <span>
                        <input type="number" class="zoo-input zoo-input-css change-by-js" <# if (fields_disabled['bottom']) { #> disabled="disabled" placeholder="{{ fields_disabled['bottom'] }}" <# } #> data-name="{{ data.name }}-bottom" value="{{ data.value.bottom }}">
                        <span class="zoo-small-label"><?php esc_html_e('Bottom', 'anon'); ?></span>
                    </span>
                    <span>
                        <input type="number" class="zoo-input zoo-input-css change-by-js" <# if (fields_disabled['left']) { #> disabled="disabled" placeholder="{{ fields_disabled['left'] }}" <# } #> data-name="{{ data.name }}-left" value="{{ data.value.left }}">
                        <span class="zoo-small-label"><?php esc_html_e('Left', 'anon'); ?></span>
                    </span>
                    <label title="<?php esc_attr_e('Toggle values together', 'anon'); ?>" class="zoo-css-ruler-link <# if (data.value.link == 1){ #> zoo-label-active <# } #>">
                        <input type="checkbox" class="zoo-input zoo-label-parent change-by-js" <# if (data.value.link == 1){ #> checked="checked" <# } #> data-name="{{ data.name }}-link" value="1">
                    </label>
                </div>
            </div>
        </div>
        </script>
        <?php
    }
}
