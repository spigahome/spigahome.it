<?php
/**
 * Customize for Blog Single
 */
return [
    [
        'name' => 'zoo_blog_single',
        'type' => 'section',
        'label' => esc_html__('Blog Single', 'anon'),
        'panel' => 'zoo_blog',
    ],
    [
        'name' => 'zoo_blog_single_sidebar_settings',
        'type' => 'heading',
        'label' => esc_html__('Sidebar Settings', 'anon'),
        'section' => 'zoo_blog_single',
    ],
    [
        'name' => 'zoo_blog_single_sidebar_config',
        'type' => 'select',
        'section' => 'zoo_blog_single',
        'title' => esc_html__('Sidebar layout', 'anon'),
        'default' => '',
        'choices' => [
            '' => esc_html__('None', 'anon'),
            'left' => esc_html__('Left', 'anon'),
            'right' => esc_html__('Right', 'anon'),
        ]
    ],
    [
        'name' => 'zoo_blog_single_sidebar',
        'type' => 'select',
        'section' => 'zoo_blog_single',
        'title' => esc_html__('Sidebar', 'anon'),
        'required' => ['zoo_blog_single_sidebar_config', '!=', 'none'],
        'choices' => zoo_get_registered_sidebars()
    ],
    [
        'name' => 'zoo_blog_single_features_settings',
        'type' => 'heading',
        'label' => esc_html__('Features Settings', 'anon'),
        'section' => 'zoo_blog_single',
    ],
    [
        'name' => 'zoo_blog_single_post_info_style',
        'type' => 'select',
        'section' => 'zoo_blog_single',
        'title' => esc_html__('Post info style', 'anon'),
        'default' => 'icon',
        'choices' => [
            'icon' => esc_html__('icon', 'anon'),
            'text' => esc_html__('Text', 'anon'),
        ]
    ],
    [
        'name' => 'zoo_enable_blog_author_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Author Post', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_blog_date_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Date Post', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_blog_cat_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Post Categories', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_blog_tags',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Tags', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_blog_share',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Share', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_next_previous_posts',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Next & Previous Posts', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ],
    [
        'name' => 'zoo_blog_single_related_post',
        'type' => 'heading',
        'label' => esc_html__('Related Post Settings', 'anon'),
        'section' => 'zoo_blog_single',
        'theme_supports' => 'woocommerce'
    ],
    [
        'name' => 'zoo_enable_blog_related',
        'type' => 'checkbox',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Enable Related Posts', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ],
    [
        'name' => 'zoo_blog_related_numbers',
        'type' => 'number',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Related post numbers', 'anon'),
        'default' => 3,
        'required' => ['zoo_enable_blog_related', '==', 1],
        'input_attrs' => array(
            'min' => 1,
            'max' => 20,
            'class' => 'zoo-range-slider'
        ),
    ], [
        'name' => 'zoo_blog_related_cols',
        'type' => 'number',
        'section' => 'zoo_blog_single',
        'label' => esc_html__('Related post columns', 'anon'),
        'default' => 3,
        'required' => ['zoo_enable_blog_related', '==', 1],
        'input_attrs' => array(
            'min' => 1,
            'max' => 6,
            'class' => 'zoo-range-slider'
        ),
    ],
];