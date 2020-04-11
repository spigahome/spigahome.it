<?php
/**
 * Zoo_Customize_Control_Default
 *
 * @package  Zoo_Theme\Core\Customize\Classes\Controls
 *
 */
class Zoo_Customize_Control_Default extends WP_Customize_Control
{
    public $type = 'zoo';
    public $choices = [];
    public $option_type = 'theme_mod';
    public $setting_type = 'group';
    public $fields = [];
    public $default = null;
    public $defaultValue = null;
    public $device = '';
    public $devices = null;
    public $checkbox_label = '';
    public $reset_controls = [];
    public $limit;
    public $min = 0;
    public $max = 700;
    public $step = 1;
    public $unit = false;
    public $fields_disabled = [];
    public $limit_msg = '';
    public $live_title_field;
    public $addable = null;
    public $title_only = null;
    public $_settings;
    public $_selective_refresh;
    public $device_settings = false;
    public $no_setup = false;
    public $required;
    public $field_class = '';

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @access public
     */
    public function to_json()
    {
        parent::to_json();

        $value = $this->value();
        if ($this->setting_type == 'group') {
            if (! is_array($value)) {
                $value = array();
            }
            foreach ($this->fields as $k => $f) {
                if (isset($value[ $f['name'] ])) {
                    $this->fields[ $k ]['value'] = $value[ $f['name'] ];
                }
            }

            if (! is_array($this->default)) {
                $this->default = array();
            }
        } elseif ($this->setting_type == 'repeater') {
            if (! is_array($value)) {
                $value = array();
            }
            if (! is_array($this->default)) {
                $this->default = array();
            }
        }

        $this->json['device_settings'] = $this->device_settings;
        if (! $this->device_settings && $this->setting_type != 'js_raw') {
            if (is_array($value) && isset($value['desktop'])) {
                $value = $value['desktop'];
            }
        }

        if ('slider' == $this->setting_type) {
            if (! $value || empty($value)) {
                $value = $this->defaultValue;
            }
        }
        $this->json['value']        = $value;

        $this->json['default']      = $this->defaultValue;
        $this->json['fields']       = $this->fields;
        $this->json['setting_type'] = $this->setting_type;
        $this->json['required']     = $this->required;
        $this->json['devices']      = $this->devices;
        $this->json['reset_controls']      = $this->reset_controls;

        if ($this->no_setup) {
            return;
        }

        $this->json['min'] = $this->min;
        $this->json['max'] = $this->max;
        $this->json['step'] = $this->step;
        $this->json['unit'] = $this->unit;
        if ('css_rule' == $this->setting_type) {
            $this->json['fields_disabled'] = $this->fields_disabled;
        }

        if ($this->setting_type == 'repeater') {
            $this->json['l10n'] = array(
                'untitled' => esc_html__('Untitled', 'anon')
            );
            $this->json['live_title_field'] = $this->live_title_field;
            $this->json['limit'] = $this->limit;
            $this->json['limit_msg'] = $this->limit_msg;
            $this->json['title_only'] = $this->title_only;
            if ($this->addable === false) {
                $this->json['addable'] = false;
                if (empty($this->json['value'])) {
                    $this->json['value'] = $this->defaultValue;
                }
            } else {
                $this->json['addable'] = true;
            }

            if ($this->title_only && $this->live_title_field) {
                $new_array = array();
                foreach (( array ) $this->defaultValue as $f) {
                    if (isset($f['_key'])) {
                        if (isset($f[ $this->live_title_field ])) {
                            $new_array[$f['_key']] = $f;
                        }
                    }
                }
                if (! empty($new_array)) {
                    $new_values = array();
                    foreach (( array ) $this->json['value'] as $index => $f) {
                        if (isset($f['_key']) && $new_array[ $f['_key'] ]) {
                            $f[$this->live_title_field] = $new_array[ $f['_key'] ][$this->live_title_field];
                            $new_values[$f['_key']] = $f;
                        }
                    }

                    $new_values = array_merge($new_array, $new_values);
                    if (! empty($new_values)) {
                        $this->json['value'] = array_values($new_values);
                    }
                }
            }
        }

        if ($this->setting_type == 'select' || $this->setting_type == 'radio') {
            $this->json['choices'] = $this->choices;
        }
        if ($this->setting_type == 'checkbox') {
            $this->json['checkbox_label'] = $this->checkbox_label;
        }
    }

    /**
     * Renders the control wrapper and calls $this->render_content() for the internals.
     */
    protected function render()
    {
        $id    = 'customize-control-' . str_replace(['[', ']'], ['-', ''], $this->id);
        $class = 'customize-control customize-control-' . $this->type . '-' . $this->setting_type;

        if ($this->field_class) {
            $class = sanitize_text_field($this->field_class) . ' ' . $class;
        }

        ?><li id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?><?php echo  esc_attr(($this->device) ? ' zoo-device-show zoo-device-' .$this->device : ''); ?>"><?php
            $this->render_content();
        ?></li><?php
    }

    /**
     * Render the control's content.
     *
     * Allows the content to be overriden without having to rewrite the wrapper in $this->render().
     *
     * @access protected
     */
    protected function render_content()
    {
        if ($this->setting_type == 'js_raw') {
            return '';
        }

        if ($this->setting_type == 'heading') :
            ?><div class="zoo-control--heading">
                <label><?php
                    if (!empty($this->label)) :
                        ?><span class="customize-control-title"><?php echo esc_html($this->label); ?></span><?php
                    endif;
                ?></label>
            </div><?php
            if (!empty($this->description)) :
                ?><span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span><?php
            endif;
            return '';
        endif;
        ?><div class="zoo-settings-wrapper"><?php
            if ($this->label) :
                ?><div data-control="<?php echo esc_attr($this->id); ?>" class="zoo-control-field-header zoo-customize-control-heading">
                    <label>
                        <?php if (!empty($this->label)) : ?>
                            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                        <?php endif; ?>
                    </label>
                </div><?php
            endif;
            if ($this->setting_type == 'custom_html') {
                echo '<div class="custom_html">'.$this->description.'</div>';
            } else {
                if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
                <?php endif;
            }
            if ($this->setting_type != 'custom_html') {
                ?><div class="zoo-settings-fields<?php echo esc_attr(($this->setting_type == 'repeater') ? ' zoo-repeater-items' : ''); ?>"></div><?php
                if ($this->setting_type == 'repeater') {
                    ?><div class="zoo-repeater-actions">
                        <a href="#" class="zoo-repeater-reorder" data-text="<?php esc_attr_e('Reorder', 'anon'); ?>" data-done="<?php esc_attr_e('Done', 'anon'); ?>"><?php _e('Reorder', 'anon'); ?></a>
                        <?php if ($this->addable !== false) { ?>
                            <button type="button" class="button zoo-repeater-add-new"><?php esc_html_e('Add an item', 'anon'); ?></button><?php
                        }
                    ?></div><?php
                }
            }
        ?></div><?php
    }

    /**
     * Print template
     */
    static function control_template()
    {
        ?>
        <script type="text/html" id="tmpl-zoo-customize-control-default">
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
                <input type="{{ data.type }}" class="zoo-input zoo-only" data-name="{{ data.name }}" value="{{ data.value }}">
            </div>
        </div>
        </script>
        <?php
    }
}
