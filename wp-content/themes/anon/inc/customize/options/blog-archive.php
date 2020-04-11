<?php
/**
 * Customize for Shop loop product
 */
return [
    [
        'name' => 'zoo_blog',
        'type' => 'panel',
        'label' => esc_html__('Blog', 'anon'),
    ],[
        'name' => 'zoo_blog_archive',
        'type' => 'section',
        'label' => esc_html__('Blog Archive', 'anon'),
        'panel' => 'zoo_blog',
    ],
    [
        'name' => 'zoo_blog_general_settings',
        'type' => 'heading',
        'label' => esc_html__('General Settings', 'anon'),
        'section' => 'zoo_blog_archive',
    ],
    [
        'name' => 'zoo_blog_layout',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Layout', 'anon'),
        'default' => 'list',
        'choices' => [
            'list' => esc_html__('List', 'anon'),
            'grid' => esc_html__('Grid', 'anon'),
        ]
    ],
    [
        'name' => 'zoo_blog_grid_img_size',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Image size', 'anon'),
        'description' => esc_html__('Select image size fit with layout you want use for improve performance.', 'anon'),
        'default' => 'medium',
        'required' => ['zoo_blog_layout', '!=', 'list'],
	    'choices'=>zoo_get_image_sizes()

    ],
	[
        'name' => 'zoo_blog_img_size',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Image size', 'anon'),
        'description' => esc_html__('Select image size fit with layout you want use for improve performance.', 'anon'),
        'default' => 'full',
        'required' => ['zoo_blog_layout', '==', 'list'],
	    'choices'=>zoo_get_image_sizes()

    ],
	[
        'name' => 'zoo_blog_cols',
        'type' => 'number',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Columns', 'anon'),
        'default' => 3,
        'required' => ['zoo_blog_layout', '!=', 'list'],
        'input_attrs' => array(
            'min' => 1,
            'max' => 6,
            'class' => 'zoo-range-slider'
        ),
    ],
    [
        'name' => 'zoo_enable_blog_cover',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Blog Cover', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 0
    ],
    [
        'name' => 'zoo_blog_cover',
        'type' => 'styling',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Blog cover style', 'anon'),
        'description' => esc_html__('Styling for categories page', 'anon'),
        'required' => ['zoo_enable_blog_cover', '==', '1'],
        'selector' => [
            'normal' => '.wrap-blog-cover',
        ],
        'css_format' => 'styling',
        'default' => [],
        'fields' => [
            'normal_fields' => [
                'margin' => false,
                'link_color' => false,
                'border_style' => false,
                'border_heading' => false,
                'border_radius' => false,
                'box_shadow' => false,
                'link_hover_color'   => false,
            ],
            'hover_fields' => false
        ]
    ],
    [
        'name' => 'zoo_blog_sidebar_settings',
        'type' => 'heading',
        'label' => esc_html__('Sidebar Settings', 'anon'),
        'section' => 'zoo_blog_archive'
    ],
    [
        'name' => 'zoo_blog_sidebar_config',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Sidebar layout', 'anon'),
        'default' => 'right',
        'choices' => [
            '' => esc_html__('None', 'anon'),
            'left' => esc_html__('Left', 'anon'),
            'right' => esc_html__('Right', 'anon'),
        ]
    ],[
        'name' => 'zoo_blog_sidebar',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Sidebar', 'anon'),
        'required' => ['zoo_blog_sidebar_config', '!=', 'none'],
        'choices' => zoo_get_registered_sidebars()
    ],
    [
        'name' => 'zoo_blog_item_settings',
        'type' => 'heading',
        'label' => esc_html__('Blog Item', 'anon'),
        'section' => 'zoo_blog_archive',
    ],
    [
        'name' => 'zoo_blog_loop_post_info_style',
        'type' => 'select',
        'section' => 'zoo_blog_archive',
        'title' => esc_html__('Post info style', 'anon'),
        'default' => 'icon',
        'choices' => [
            'icon' => esc_html__('icon', 'anon'),
            'text' => esc_html__('Text', 'anon'),
        ]
    ],
    [
        'name' => 'zoo_enable_loop_author_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Author Post', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_loop_date_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Date Post', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ], [
        'name' => 'zoo_enable_loop_cat_post',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Post Categories', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ],
    [
        'name' => 'zoo_enable_loop_excerpt',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Blog Excerpt', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 0
    ],
    [
        'name' => 'zoo_loop_excerpt_length',
        'type' => 'number',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Blog Excerpt length', 'anon'),
        'default' => 30,
        'required' => ['zoo_enable_loop_excerpt', '==', 1],
        'input_attrs' => array(
            'min' => 1,
            'max' => 256,
            'class' => 'zoo-range-slider'
        ),
    ],
    [
        'name' => 'zoo_enable_loop_readmore',
        'type' => 'checkbox',
        'section' => 'zoo_blog_archive',
        'label' => esc_html__('Enable Read more', 'anon'),
        'checkbox_label' => esc_html__('Will be enabled if checked.', 'anon'),
        'default' => 1
    ]
];