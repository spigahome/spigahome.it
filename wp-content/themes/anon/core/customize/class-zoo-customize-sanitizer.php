<?php
/**
 * Zoo_Customize_Sanitizer
 *
 * @package  Zoo_Theme\Core\Customize\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Customize_Sanitizer
{
    private $control;
    private $setting;
    private $fonts = [];
    private $icons = [];
    private $skip_devices = false;

    /**
     * Constructor
     */
    public function __construct($control = null, $setting = null)
    {
        if (is_array($control)) {
            $control = (object)$control;
        }

        if (is_array($setting)) {
            $setting = (object)$setting;
        }

        $this->control = $control;
        $this->setting = $setting;
    }

    /**
     * Sanitize css_rule input
     *
     * @param  array  $value
     *
     * @return array
     */
    private function sanitize_css_rule(array $value)
    {
        $value = wp_parse_args($value, [
            'unit'   => 'px',
            'top'    => null,
            'right'  => null,
            'bottom' => null,
            'left'   => null,
            'link'   => 1
        ]);

        $new_value = [];
        $new_value['unit'] = sanitize_text_field($value['unit']);
        $new_value['top'] = sanitize_text_field($value['top']);
        $new_value['right'] = sanitize_text_field($value['right']);
        $new_value['bottom'] = sanitize_text_field($value['bottom']);
        $new_value['left'] = sanitize_text_field($value['left']);
        $new_value['link'] = $value['link'] ? 1 : null;

        return $new_value;
    }

    /**
     * Sanitize slider input
     *
     * @param  array  $value
     *
     * @return array
     */
    private function sanitize_slider(array $value)
    {
        $value = wp_parse_args($value, [
            'unit'  => 'px',
            'value' => null
        ]);
        $new_value = [];
        $new_value['unit'] = sanitize_text_field($value['unit']);
        $new_value['value'] = sanitize_text_field($value['value']);

        return $new_value;
    }
    /**
     * Sanitize media field
     */
    private function sanitize_media($value)
    {
        $value = wp_parse_args($value, [
            'id'   => '',
            'url'  => '',
            'mime' => ''
        ]);

        $value['id'] = sanitize_text_field($value['id']);
        $value['url'] = sanitize_text_field($value['url']);
        $value['mime'] = sanitize_text_field($value['mime']);

        return $value;
    }

    /**
     * Sanitize icon field
     */
    private function sanitize_icon($value)
    {
        $value = wp_parse_args($value, [
            'type' => '',
            'icon' => '',
        ]);

        $value['type'] = sanitize_text_field($value['type']);
        $value['icon'] = sanitize_text_field($value['icon']);

        $this->icons[$value['type']] = true;

        return $value;
    }

    /**
     * Sanitize group field
     */
    private function sanitize_group($value, $skip_device = false)
    {
        if (!is_array($value)) {
            $value = array();
        }

        foreach ($this->control->fields as $field) {
            if (!isset($value[$field['name']])) {
                $value[$field['name']] = '';
            }
            $_v = $value[$field['name']];
            $_v = $this->sanitize($_v, $field);
            $value[$field['name']] = $_v;
        }

        return $value;
    }

    /**
     * Sanitize repater field
     */
    private function sanitize_repeater($value)
    {
        if (!is_array($value)) {
            $value = array();
        }

        foreach ($value as $k => $iv) {
            foreach ($this->control->fields as $field) {
                if (!isset($iv[$field['name']])) {
                    $iv[$field['name']] = '';
                }

                $_v = $iv[$field['name']];
                $_v = $this->sanitize($_v, $field);
                $iv[$field['name']] = $_v;
            }

            $value[$k] = $iv;
        }

        return $value;
    }

    /**
     * Do sanitization
     *
     * @api
     */
    public function sanitize($value, $field = array())
    {
        $type = null;
        $device_settings = false;
        if (is_array($field) && !empty($field)) {
            if (isset($field['type'])) {
                $type = $field['type'];
            } elseif (isset($field['setting_type'])) {
                $type = $field['setting_type'];
            }

            if (isset($field['device_settings']) && $field['device_settings']) {
                $device_settings = true;
            }
        } else {
            if (isset($this->control->setting_type)) {
                $type = $this->control->setting_type;
            } else {
                $type = $this->control->type;
            }
            if (isset($this->control->device_settings)) {
                $device_settings = $this->control->device_settings;
            } else {
                $device_settings = false;
            }
        }

        if ($type != 'js_raw') {
            if (!$device_settings) {
                // Fallback value when device_settings from tru to false
                if (is_array($value) && isset($value['desktop'])) {
                    $value = $value['desktop'];
                }
            }
        }

        switch ($type) {
            case 'color':
                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = zoo_sanitize_color_input($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = zoo_sanitize_color_input($value);
                }
                break;
            case 'group':
                $has_device = false;
                if ($device_settings) {
                    $this->skip_devices = true;
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = $this->sanitize_group($value[$device]);
                            }
                        }
                    }
                    $this->skip_devices = false;
                }
                if (!$has_device) {
                    $value = $this->sanitize_group($value);
                }
                break;
            case 'repeater':

                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = $this->sanitize_repeater($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = $this->sanitize_repeater($value);
                }

                break;
            case 'media':
            case 'image':
            case 'attachment':
            case 'video':
            case 'autio':

                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = $this->sanitize_media($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = $this->sanitize_media($value);
                }

                break;
            case 'select':
            case 'radio':
            case 'image_select':
            case 'radio_group':

                $default = null;
                $choices = array();
                if (!empty($field)) {
                    if (isset($field['default'])) {
                        $default = $field['default'];
                    }

                    if (isset($field['choices'])) {
                        $choices = $field['choices'];
                    }
                } else {
                    $default = $this->setting->default;
                    $choices = $this->control->choices;
                }
                if (!is_array($choices)) {
                    $choices = array();
                }
                if (!$value) {
                    return '';
                }

                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                if (!isset($choices[(string )$value[$device]])) {
                                    if (is_array($default) && isset($default[$device])) {
                                        $value[$device] = $default;
                                    } else {
                                        $value[$device] = $default;
                                    }
                                }
                            }
                        }
                    }
                }
                if (!$has_device) {
                    if (is_array($value) || !isset($choices[$value])) {
                        $value = $default;
                    }
                }

                break;
            case 'checkbox':
                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = zoo_sanitize_checkbox_value($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = zoo_sanitize_checkbox_value($value);
                }

                break;
            case 'css_rule':

                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = $this->sanitize_css_rule($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = $this->sanitize_css_rule($value);
                }

                break;
            case 'slider':

                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = $this->sanitize_slider($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = $this->sanitize_slider($value);
                }

                break;
            case 'icon':

                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = $this->sanitize_icon($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = $this->sanitize_icon($value);
                }

                break;
            case 'js_raw':
            case 'textarea':
                break;
            default:
                $has_device = false;
                if ($device_settings && !$this->skip_devices) {
                    if (is_array($value)) {
                        $has_device = false;
                        foreach (Zoo_Customizer::SUPPORT_DEVICES as $device) {
                            if (isset($value[$device])) {
                                $has_device = true;
                                $value[$device] = zoo_sanitize_text_input_recursive($value[$device]);
                            }
                        }
                    }
                }
                if (!$has_device) {
                    $value = zoo_sanitize_text_input_recursive($value);
                }

        }

        return $value;
    }
}
