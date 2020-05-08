<?php namespace Cafe;

use DirectoryIterator;

/**
 * SettingsPage
 *
 * @package  Zoo_Theme\Core\Admin\Classes
 * @author   Zootemplate
 * @link     http://www.zootemplate.com
 *
 */
final class SettingsPage
{
    /**
     * Option group
     *
     * @var  string
     */
    const GROUP = 'cafe_plugin_settings_group';

    /**
     * Hook suffix
     */
    public $hook_suffix;

    /**
     * Settings
     *
     * @var  array
     */
    private $settings;

    /**
     * Constructor
     */
    private function __construct()
    {
        $this->settings = (array)get_option(Plugin::OPTION_NAME) ? : [];
    }

    /**
     * Singleton
     */
    public static function init()
    {
        static $self = null;

        if (null === $self) {
            $self = new self;
            add_action('admin_menu', [$self, '_add'], 999);
            add_action('admin_init', [$self, '_register'], 10, 0);
            add_action('admin_notices', [$self, '_notify'], 10, 0);
            add_action('admin_enqueue_scripts', [$self, '_scripts']);
        }
    }

    /**
     * Add to admin menu
     */
    public function _add($context = '')
    {
        $this->hook_suffix = add_menu_page(
            esc_html__('CAFE', 'cafe-lite'),
            esc_html__('CAFE', 'cafe-lite'),
            'manage_options',
            'clever-addons-for-elementor',
            [$this, '_render'],
            'dashicons-index-card',
            59
        );
    }

    /**
     * Register setting
     */
    public function _register()
    {
        register_setting(self::GROUP, Plugin::OPTION_NAME, [$this, '_sanitize']);
    }

    /**
     * Render
     */
    public function _render()
    {
        $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
        $logo_src = !empty($logo) ? $logo[0] : admin_url('images/wordpress-logo.svg');

        ?>
        <div class="cafe-plugin-settings">
            <div class="nav-bar">
                <div class="branding">
                    <h2 class="plugin-name">CAFE</h2>
                    <p class="plugin-version"><?php esc_html_e('Version', 'cafe-lite') ?> <span><?php echo Plugin::VERSION ?></span></p>
                </div>
                <ul>
                    <!-- <li><a class="nav-item active-item" href="#global-settings"><?php esc_html_e('General', 'cafe-lite') ?></a></li> -->
                    <li><a class="nav-item active-item" href="#elements-settings"><?php esc_html_e('Elements', 'cafe-lite') ?></a></li>
                </ul>
            </div>
            <div class="content-tabs">
                <form class="form-table" action="options.php" method="post">
                    <?php settings_fields(self::GROUP) ?>
                    <!-- <table id="global-settings" class="tab-table active-tab">
                        <caption><?php esc_html_e('General Settings', 'cafe-lite') ?></caption>
                        <?php do_settings_fields(self::GROUP, 'global') ?>
                    </table> -->
                    <table id="elements-settings" class="tab-table">
                        <?php
                            $widgets = new DirectoryIterator(CAFE_DIR . 'src/widgets');
                        ?>
                        <caption><?php esc_html_e('Disable Elements', 'cafe-lite') ?></caption>
                        <tr>
                            <td>
                                <p class="description"><?php esc_html_e('Disable unused elements may improve your site performance.', 'cafe-lite') ?></p>
                            </td>
                            <?php
                            foreach ($widgets as $widget) :
                                if ($widget->isFile()) {
                                    $filename = $widget->getFileName();
                                    if (false !== strpos($filename, '.php') && 'class-clever-widget-base.php' !== $filename) :
                                        $disabled = intval($this->getValue($filename));
                                        $element  = ucwords(trim(str_replace(['class', '-', '.php'], ['', ' ', ''], $filename)));
                                        ?>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="<?php echo esc_attr($this->getName($filename)) ?>" value="1"<?php if ($disabled) echo ' checked';  ?>>
                                                <span><?php echo $element ?></span>
                                            </label>
                                        </td>
                                        <?php
                                    endif ;
                                }
                            endforeach;
                            if (defined('CAFE_PRO_DIR')) {
                                $pro_widgets = new DirectoryIterator(CAFE_PRO_DIR . 'src/widgets');
                                foreach ($pro_widgets as $pro_widget) :
                                    if ($pro_widget->isFile()) {
                                        $filename = $pro_widget->getFileName();
                                        if (false !== strpos($filename, '.php') && 'class-clever-widget-base.php' !== $filename) :
                                            $disabled = intval($this->getValue($filename));
                                            $element  = str_replace('.php', '', $filename);
                                            ?>
                                            <td>
                                                <label>
                                                    <input type="checkbox" name="<?php echo esc_attr($this->getName($filename)) ?>" value="1"<?php if ($disabled) echo ' checked';  ?>>
                                                    <span><?php echo $element ?></span>
                                                </label>
                                            </td>
                                            <?php
                                        endif ;
                                    }
                                endforeach;
                            }
                            ?>
                        </tr>
                        <?php do_settings_fields(self::GROUP, 'advanced') ?>
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
    public function _sanitize($settings)
    {
        return $settings;
    }

    /**
     * Do notification
     */
    public function _notify()
    {
        if ($GLOBALS['page_hook'] !== $this->hook_suffix) {
            return;
        }

        if (isset($_REQUEST['settings-updated']) && 'true' === $_REQUEST['settings-updated']) {
            echo '<div class="updated notice is-dismissible"><p><strong>' . esc_html__('Settings have been saved successfully!', 'cafe-lite') . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'cafe-lite') . '</span></div>';
        }

        if (isset($_REQUEST['error']) && 'true' === $_REQUEST['error']) {
            echo '<div class="updated error is-dismissible"><p><strong>' . esc_html__('Failed to save settings. Please try again!', 'cafe-lite') . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'cafe-lite') . '</span></div>';
        }
    }

    /**
     * Scripts
     */
    function _scripts($hook_suffix)
    {
        if ($this->hook_suffix !== $hook_suffix) {
            return;
        }

        wp_enqueue_style('cafe-settings', CAFE_URI . 'assets/css/admin.min.css', [], 'v32837');

        wp_add_inline_script('jquery-core', '
            jQuery(function($) {
                $(".cafe-plugin-settings .nav-item").on("click", function(e)
                {
                    e.preventDefault();

                    var items, tabs, activeItem, activeTab;

                    activeItem = $(this);
                    items      = $(".cafe-plugin-settings .nav-item");
                    tabs       = $(".cafe-plugin-settings .tab-table");
                    activeTab  = $(activeItem.attr("href"));

                    items.removeClass("active-item");
                    activeItem.addClass("active-item");
                    tabs.hide();
                    tabs.removeClass("active-tab");
                    activeTab.addClass("active-tab");
                    activeTab.show();
                });
            })
        ');
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
        return Plugin::OPTION_NAME . '[' . $key . ']';
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
SettingsPage::init();
