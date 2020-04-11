<?php
/**
 * Zoo_Settings_Page
 *
 * @package  Zoo_Theme\Core\Admin\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class Zoo_Settings_Page
{
    /**
     * Slug
     */
    const SLUG = 'zoo-theme-settings';

    /**
     * Option group
     *
     * @var  string
     */
    const GROUP = 'zoo_theme_settings_group';

    /**
     * Hook suffix
     */
    public $hook_suffix;

    /**
     * Settings
     *
     * @var  array
     */
    protected $settings;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->theme = zoo_get_parent_theme_object();
        $this->settings = (array)get_option(ZOO_SETTINGS_KEY) ? : [];
    }

    /**
     * Singleton
     */
    static function getInstance()
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
            add_action('admin_menu', [$self, '_add'], 11);
            add_action('admin_init', [$self, '_register'], 10, 0);
            add_action('admin_notices', [$self, '_notify'], 10, 0);
        }
    }

    /**
     * Add to admin menu
     */
    function _add($context = '')
    {
        if (!function_exists('zoo_base69_encode')) {
            return;
        }

        $this->hook_suffix = zoo_add_submenu_page(
            Zoo_Welcome_Page::SLUG,
            esc_html__('Theme Settings', 'anon'),
            esc_html__('Theme Settings', 'anon'),
            'manage_options',
            self::SLUG,
            array($this, '_render')
        );
    }

    /**
     * Register setting
     */
    function _register()
    {
        register_setting(self::GROUP, ZOO_SETTINGS_KEY, array($this, '_sanitize'));
    }

    /**
     * Render
     */
    function _render()
    {
        $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
        $logo_src = !empty($logo) ? $logo[0] : admin_url('images/wordpress-logo.svg');

        ?>
        <div class="zoo-theme-settings">
            <div class="nav-bar">
                <div class="theme-logo">
                    <img src="<?php echo esc_url($logo_src) ?>" alt="<?php bloginfo('name'); ?>" width="90" height="auto">
                    <p><strong><?php echo esc_html($this->theme->name) . ' &nbsp;<span>v' . esc_html($this->theme->version) ?></span></strong></p>
                </div>
                <ul>
                    <li><a class="nav-item active-item" href="#global-settings"><?php esc_html_e('General', 'anon') ?></a></li>
                    <li><a class="nav-item" href="#advanced-settings"><?php esc_html_e('Advanced', 'anon') ?></a></li>
                    <li><a class="nav-item" href="#import-export"><?php esc_html_e('Import/Export', 'anon') ?></a></li>
                </ul>
            </div>
            <div class="content-tabs">
                <form class="form-table" action="options.php" method="post">
                    <?php settings_fields(self::GROUP) ?>
                    <table id="global-settings" class="tab-table active-tab">
                        <caption><?php esc_html_e('General Options', 'anon') ?></caption>
                        <tr>
                            <?php $enable_mega_menu = intval($this->getValue('enable_builtin_mega_menu')) ?>
                            <th rowspan="scope"><?php esc_html_e('Enable Built-in Mega Menu Editor', 'anon') ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?php echo esc_attr($this->getName('enable_builtin_mega_menu')) ?>" value="1"<?php if ($enable_mega_menu) echo ' checked'; ?>>
                                    <span class="description"><?php esc_html_e('Whether to enable built-in mega menu or not.', 'anon'); ?></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Header scripts', 'anon') ?></th>
                            <td><p><textarea name="<?php echo esc_attr($this->getName('header_scripts')) ?>" rows="6" cols="80"><?php echo wp_unslash($this->getValue('header_scripts')) ?></textarea></p><p class="description"><?php esc_html_e('Here come custom scripts inserted inside HEAD tag.', 'anon') ?></p></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Footer scripts', 'anon') ?></th>
                            <td><p><textarea name="<?php echo esc_attr($this->getName('footer_scripts')) ?>" rows="6" cols="80"><?php echo wp_unslash($this->getValue('footer_scripts')) ?></textarea></p><p class="description"><?php esc_html_e('Here comes your Google Analytics code or any other JS code you want to be loaded in the footer of your website.', 'anon') ?></p></td>
                        </tr>
                        <?php do_settings_fields(self::GROUP, 'global') ?>
                    </table>
                    <table id="advanced-settings" class="tab-table">
                        <?php
                            $enable_dev_mode = intval($this->getValue('enable_dev_mode'));
                        ?>
                        <caption><?php esc_html_e('Advanced Options', 'anon') ?></caption>
                        <tr>
                            <th rowspan="scope"><?php esc_html_e('Enable Development Mode', 'anon') ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?php echo esc_attr($this->getName('enable_dev_mode')) ?>" value="1"<?php if ($enable_dev_mode) echo ' checked'; ?>>
                                    <span class="description"><?php esc_html_e('This option is for theme developers only. Use it with caution!', 'anon'); ?></span></label>
                            </td>
                        </tr>
                        <tr>
                            <th rowspan="scope"><?php esc_html_e('Responsive Breakpoint for Mobile (px)', 'anon') ?></th>
                            <td>
                                <input type="number" name="<?php echo esc_attr($this->getName('mobile_breakpoint_width')) ?>" value="<?php echo esc_attr($this->getValue('mobile_breakpoint_width')) ?>">
                                <p class="description"><?php esc_html_e('Set a custom mobile breakpoint at which your site layout will be rendered for mobile devices. E.g. 768px. The default value is inherited from Bootstrap 4 - 992px.', 'anon'); ?></p>
                            </td>
                        </tr>
                        <?php do_settings_fields(self::GROUP, 'advanced') ?>
                    </table>
                    <table id="import-export" class="tab-table">
                        <caption><?php esc_html_e('Import/Export Theme Options', 'anon') ?></caption>
                        <tr>
                            <th scope="row"><?php esc_html_e('Import', 'anon') ?></th>
                            <td>
                                <p class="description"><?php esc_html_e('Paste encoded settings saved from the "Export" box and click on the "Save Changes" to import.', 'anon') ?></p>
                                <textarea name="<?php echo esc_attr($this->getName('import_settings')) ?>" rows="8" cols="80"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e('Export', 'anon') ?></th>
                            <td>
                                <p class="description"><?php esc_html_e('Copy the below JSON encoded settings and save it somewhere to import later.', 'anon') ?></p>
                                <textarea rows="8" cols="80"><?php echo zoo_base69_encode(serialize($this->settings)) ?></textarea>
                            </td>
                        </tr>
                        <?php do_settings_fields(self::GROUP, 'import/export') ?>
                    </table>
                    <?php do_settings_sections(self::GROUP); submit_button() ?>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Sanitize
     */
    function _sanitize($settings)
    {
        if (!empty($settings['import_settings']) && function_exists('zoo_base69_decode')) {
            $_settings = unserialize(zoo_base69_decode($settings['import_settings']));
            if (is_array($_settings)) {
                $this->importSettings($_settings);
            }
        }

        unset($settings['import_settings']);

        $settings['mobile_breakpoint_width'] = isset($settings['mobile_breakpoint_width']) ? intval($settings['mobile_breakpoint_width']) : '';
         $settings['enable_dev_mode'] = isset($settings['enable_dev_mode']) ? intval($settings['enable_dev_mode']) : 0;
         $settings['enable_builtin_mega_menu'] = isset($settings['enable_builtin_mega_menu']) ? intval($settings['enable_builtin_mega_menu']) : 0;

        return $settings;
    }

    /**
     * Do notification
     */
    function _notify()
    {
        if ($GLOBALS['page_hook'] !== $this->hook_suffix) {
            return;
        }

        if (isset($_REQUEST['settings-updated']) && 'true' === $_REQUEST['settings-updated']) {
            echo '<div class="updated notice is-dismissible"><p><strong>' . esc_html__('Settings have been saved successfully!', 'anon') . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'anon') . '</span></div>';
        }

        if (isset($_REQUEST['error']) && 'true' === $_REQUEST['error']) {
            echo '<div class="updated error is-dismissible"><p><strong>' . esc_html__('Failed to save settings. Please try again!', 'anon') . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'anon') . '</span></div>';
        }
    }

    /**
     * Import settings
     */
    function importSettings(array $settings)
    {
        $updated = update_option(ZOO_SETTINGS_KEY, $settings, true);

        if (!$updated) {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                wp_send_json_error();
            } else {
                wp_redirect( add_query_arg( 'error', 'true',  wp_get_referer() ) );
                exit;
            }
        } else {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                wp_send_json_success();
            } else {
                wp_redirect( add_query_arg( 'settings-updated', 'true',  wp_get_referer() ) );
                exit;
            }
        }
    }

    /**
     * Get name
     *
     * @param  string  $field  Key name.
     *
     * @return  string
     */
    private function getName($key)
    {
        return ZOO_SETTINGS_KEY . '[' . $key . ']';
    }

    /**
     * Get value
     *
     * @param  string  $key  Key name.
     *
     * @return  mixed
     */
    private function getValue($key)
    {
        return isset($this->settings[$key]) ? $this->settings[$key] : '';
    }
}
Zoo_Settings_Page::getInstance();
