<?php
/**
 * Register service post type
 */
add_action( 'init', function()
{
    if (!current_theme_supports('service-post-type')) {
        return;
    }

    $labels = [
        'name'               => esc_html__('Services', 'zoo-framework'),
        'singular_name'      => esc_html__('Service Item', 'zoo-framework'),
        'add_new'            => esc_html__('Add New', 'zoo-framework'),
        'add_new_item'       => esc_html__('Add New Service Item', 'zoo-framework'),
        'edit_item'          => esc_html__('Edit Service Item', 'zoo-framework'),
        'new_item'           => esc_html__('New Service Item', 'zoo-framework'),
        'view_item'          => esc_html__('View Service Item', 'zoo-framework'),
        'search_items'       => esc_html__('Search Service', 'zoo-framework'),
        'not_found'          => esc_html__('No Service items have been added yet', 'zoo-framework'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'zoo-framework'),
        'parent_item_colon'  => ''
    ];

    $args = [
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => true,
        'menu_icon'         => 'dashicons-admin-tools',
        'hierarchical'      => false,
        'has_archive'       => true,
        'rewrite'           => ['slug' => 'service'],
        'supports'          => ['title', 'editor', 'thumbnail', 'revisions'],
    ];

    register_post_type('service', $args);
}, 10, 0);

/**
 * Do notification
 *
 * @see  https://developer.wordpress.org/reference/hooks/post_updated_messages/
 */
add_filter('post_updated_messages', function($messages)
{
    if (!current_theme_supports('service-post-type')) {
        return;
    }

    $messages['service'] = [
        0  => '', // Unused. Messages start at index 1.
        1  => esc_html__('Service updated.', 'zoo-framework'),
        2  => esc_html__('Custom field updated.', 'zoo-framework'),
        3  => esc_html__('Custom field deleted.', 'zoo-framework'),
        4  => esc_html__('Service updated.', 'zoo-framework'),
        5  => isset($_GET['revision']) ? esc_html__('Service restored to revision from', 'zoo-framework') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
        6  => esc_html__('Service published.', 'zoo-framework'),
        7  => esc_html__('Service saved.', 'zoo-framework'),
        8  => esc_html__('Service submitted.', 'zoo-framework'),
        9  => esc_html__('Service scheduled.', 'zoo-framework'),
        10 => esc_html__('Service draft updated.', 'zoo-framework')
    ];

    return $messages;
});
