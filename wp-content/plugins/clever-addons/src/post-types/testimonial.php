<?php
/**
 * Register post type
 */
add_action('init', function()
{
    if (!current_theme_supports('testimonial-post-type')) {
        return;
    }

    $labels = [
        'name'               => esc_html__('Testimonials', 'zoo-framework'),
        'singular_name'      => esc_html__('Testimonial', 'zoo-framework'),
        'add_new'            => esc_html__('Add New', 'zoo-framework'),
        'add_new_item'       => esc_html__('Add New Testimonial', 'zoo-framework'),
        'edit_item'          => esc_html__('Edit Testimonial', 'zoo-framework'),
        'new_item'           => esc_html__('New Testimonial', 'zoo-framework'),
        'view_item'          => esc_html__('View Testimonial', 'zoo-framework'),
        'search_items'       => esc_html__('Search Testimonials', 'zoo-framework'),
        'not_found'          => esc_html__('No testimonials have been added yet', 'zoo-framework'),
        'not_found_in_trash' => esc_html__('Nothing found in Trash', 'zoo-framework'),
        'parent_item_colon'  => ''
    ];

    register_post_type('testimonial' , [
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_in_nav_menus' => false,
        'menu_icon'         => 'dashicons-format-quote',
        'rewrite'           => false,
        'supports'          => ['title', 'editor'],
        'has_archive'       => true,
    ]);

    register_taxonomy('testimonial_category', 'testimonial', [
        'label' 		    => esc_html__('Testimonial Categories', 'zoo-framework'),
        'singular_label'    => esc_html__('Testimonial Category', 'zoo-framework'),
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_nav_menus' => false,
        'args'              => ['orderby' => 'term_order'],
        'rewrite'           => ['slug' => 'testimonial_category', 'with_front' => 0, 'hierarchical' => 1],
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
    if (!current_theme_supports('testimonial-post-type')) {
        return;
    }

    $messages['testimonial'] = [
        0  => '', // Unused. Messages start at index 1.
        1  => esc_html__('Testimonial updated.', 'zoo-framework'),
        2  => esc_html__('Custom field updated.', 'zoo-framework'),
        3  => esc_html__('Custom field deleted.', 'zoo-framework'),
        4  => esc_html__('Testimonial updated.', 'zoo-framework'),
        5  => isset($_GET['revision']) ? esc_html__('Testimonial restored to revision from', 'zoo-framework') . ' ' . wp_post_revision_title(absint($_GET['revision'])) : false,
        6  => esc_html__('Testimonial published.', 'zoo-framework'),
        7  => esc_html__('Testimonial saved.', 'zoo-framework'),
        8  => esc_html__('Testimonial submitted.', 'zoo-framework'),
        9  => esc_html__('Testimonial scheduled.', 'zoo-framework'),
        10 => esc_html__('Testimonial draft updated.', 'zoo-framework')
    ];

    return $messages;
});
