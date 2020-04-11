<?php namespace Cafe;

use Elementor\Plugin as Elementor;

/**
 * CafeTemplatekitSourceBase
 */
abstract class CafeTemplatekitSourceBase
{
    /**
     * @abstract
     */
    abstract public function get_slug();

    /**
     * @abstract
     */
    abstract public function get_version();

    /**
     * @abstract
     */
    abstract public function get_items();

    /**
     * @abstract
     */
    abstract public function get_categories();

    /**
     * @abstract
     */
    abstract public function get_keywords();

    /**
     * @abstract
     */
    abstract public function get_item($template_id);

    /**
     * Returns templates transient key for current source
     *
     * @return string
     */
    public function templates_key()
    {
        return 'templatekit_templates_' . $this->get_slug() . '_' . $this->get_version();
    }

    /**
     * Returns categories  transient key for current source
     *
     * @return string
     */
    public function categories_key()
    {
        return 'templatekit_categories_' . $this->get_slug() . '_' . $this->get_version();
    }

    /**
     * Returns keywords transient key for current source
     *
     * @return string
     */
    public function keywords_key()
    {
        return 'templatekit_keywords_' . $this->get_slug() . '_' . $this->get_version();
    }

    /**
     * Set templates cache.
     *
     * @param array $value
     */
    public function set_templates_cache($value)
    {
        set_transient($this->templates_key(), $value, DAY_IN_SECONDS);
    }

    /**
     * Set templates cache.
     *
     * @param array $value
     */
    public function get_templates_cache()
    {
        if ($this->is_debug_active()) {
            return false;
        }

        return get_transient($this->templates_key());
    }

    /**
     * Delete templates cache
     */
    public function delete_templates_cache()
    {
        delete_transient($this->templates_key());
    }

    /**
     * Set categories cache.
     *
     * @param array $value
     */
    public function set_categories_cache($value)
    {
        set_transient($this->categories_key(), $value, DAY_IN_SECONDS);
    }

    /**
     * Set categories cache.
     *
     * @param array $value
     */
    public function get_categories_cache()
    {
        if ($this->is_debug_active()) {
            return false;
        }

        return get_transient($this->categories_key());
    }

    /**
     * Delete categories cache
     */
    public function delete_categories_cache()
    {
        delete_transient($this->categories_key());
    }

    /**
     * Set categories cache.
     *
     * @param array $value
     */
    public function set_keywords_cache($value)
    {
        set_transient($this->keywords_key(), $value, DAY_IN_SECONDS);
    }

    /**
     * Set categories cache.
     *
     * @param array $value
     */
    public function get_keywords_cache()
    {
        if ($this->is_debug_active()) {
            return false;
        }

        return get_transient($this->keywords_key());
    }

    /**
     * Delete categories cache
     */
    public function delete_keywords_cache()
    {
        delete_transient($this->keywords_key());
    }

    /**
     * Check if debug is active
     *
     * @return boolean
     */
    public function is_debug_active()
    {
        return false;
    }

    /**
     * Returns template ID prefix for templates
     *
     * @return string
     */
    public function id_prefix()
    {
        return 'templatekit_';
    }

    /**
     * @author Elementor.com
     */
    protected function replace_elements_ids($content)
    {
        return Elementor::$instance->db->iterate_data($content, function ($element) {
            $element['id'] = dechex(rand());
            return $element;
        });
    }

    /**
     * @author Elementor.com
     */
    protected function process_export_import_content($content, $method)
    {
        return Elementor::$instance->db->iterate_data(
            $content,
            function ($element_data) use ($method) {
                $element = Elementor::$instance->elements_manager->create_element_instance($element_data);

                // If the widget/element isn't exist, like a plugin that creates a widget but deactivated
                if (! $element) {
                    return null;
                }

                return $this->process_element_export_import_content($element, $method);
            }
        );
    }

    /**
     * @author Elementor.com
     */
    protected function process_element_export_import_content($element, $method)
    {
        $element_data = $element->get_data();

        if (method_exists($element, $method)) {
            // TODO: Use the internal element data without parameters.
            $element_data = $element->{$method}($element_data);
        }

        foreach ($element->get_controls() as $control) {
            $control_class = Elementor::$instance->controls_manager->get_control($control['type']);

            // If the control isn't exist, like a plugin that creates the control but deactivated.
            if (! $control_class) {
                return $element_data;
            }

            if (method_exists($control_class, $method)) {
                $element_data['settings'][$control['name']] = $control_class->{$method}($element->get_settings($control['name']), $control);
            }
        }

        return $element_data;
    }
}
