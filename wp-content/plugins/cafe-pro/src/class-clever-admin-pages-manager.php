<?php namespace CafePro;

/**
 * AdminPagesManager
 */
final class AdminPagesManager
{
    /**
     * Nope constructor
     */
    function __construct()
    {
        add_action('admin_menu', [$this, '_add'], 1000);
    }

    /**
     * Add to admin menu
     */
    function _add($context = '')
    {
		add_submenu_page(
			'clever-addons-for-elementor',
			esc_html__('Site Header', 'zootemplate'),
			esc_html__('Site Header', 'zootemplate'),
			'edit_posts',
			admin_url('edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=site_header')
		);

		add_submenu_page(
			'clever-addons-for-elementor',
			esc_html__('Site Footer', 'zootemplate'),
			esc_html__('Site Footer', 'zootemplate'),
			'edit_posts',
			admin_url('edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=site_footer')
		);
    }
}

return new AdminPagesManager();
