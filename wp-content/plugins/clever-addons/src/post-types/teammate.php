<?php
/**
 * Team member post type
 */
add_action('init', function()
{
    if (!current_theme_supports('team-member-post-type')) {
        return;
    }

    $labels = [
        'name'               => esc_html__('Team Member', 'zoo-framework'),
        'singular_name'      => esc_html__('Team Member', 'zoo-framework'),
        'add_new'            => esc_html__('Add New', 'zoo-framework'),
        'add_new_item'       => esc_html__('Add New Testimonial', 'zoo-framework'),
        'edit_item'          => esc_html__('Edit Team Member', 'zoo-framework'),
        'new_item'           => esc_html__('New Team Member', 'zoo-framework'),
        'view_item'          => esc_html__('View Team Member', 'zoo-framework'),
        'search_items'       => esc_html__('Search Team Members', 'zoo-framework'),
        'not_found'          => esc_html__('No team members have been added yet', 'zoo-framework'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'zoo-framework'),
        'parent_item_colon'  => ''
    ];

    register_post_type('team' , [
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => false,
        'menu_icon'         => 'dashicons-groups',
        'rewrite'           => ['slug' => 'team-member', 'with_front' => 0, 'hierarchical' => 0, 'ep_mask'=>'true'],
        'supports'          => ['title', 'editor', 'team_category','thumbnail'],
        'has_archive'       => true,
    ]);

    register_taxonomy('team_category', 'team', [
        "label" 		    => esc_html__('Team Categories', 'zoo-framework'),
        "singular_label"    => esc_html__('Team Category', 'zoo-framework'),
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_nav_menus' => false,
        'args'              => ['orderby' => 'term_order'],
        'rewrite'           => ['slug' => 'team_category', 'with_front' => 0, 'hierarchical' => true, 'ep_mask' => 'true'],
        'query_var'         => true
    ]);
}, 10, 0 );

/**
 * Do notification
 *
 * @see  https://developer.wordpress.org/reference/hooks/post_updated_messages/
 */
add_filter('post_updated_messages', function($messages)
{
    if (!current_theme_supports('team-member-post-type')) {
        return;
    }

    $messages['team'] = [
        0  => '', // Unused. Messages start at index 1.
        1  => esc_html__('Team member updated.', 'zoo-framework'),
        2  => esc_html__('Custom field updated.', 'zoo-framework'),
        3  => esc_html__('Custom field deleted.', 'zoo-framework'),
        4  => esc_html__('Team member updated.', 'zoo-framework'),
        5  => isset($_GET['revision']) ? esc_html__('Team member restored to revision from', 'zoo-framework') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
        6  => esc_html__('Team member published.', 'zoo-framework'),
        7  => esc_html__('Team member saved.', 'zoo-framework'),
        8  => esc_html__('Team member submitted.', 'zoo-framework'),
        9  => esc_html__('Team member scheduled.', 'zoo-framework'),
        10 => esc_html__('Team member draft updated.', 'zoo-framework')
    ];

    return $messages;
});
