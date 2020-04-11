<?php
/**
 * Meta data for custom post type
 *
 * @author      Zootemplate
 * @link        http://www.zootemplate.com
 * @copyright   Copyright (c) 2017 Zootemplate

 */
add_filter('rwmb_meta_boxes', function($meta_boxes)
{
    $prefix = 'cvca_';

    $meta_boxes[] = [
        'id'      => 'post_meta_box',
        'title'   => esc_html__('Post Meta', 'zoo-framework'),
        'pages'   => ['testimonial'],
        'context' => 'normal',
        'fields'  => [
            [
                'name'             => esc_html__('Author avatar', 'zoo-framework'),
                'desc'             => esc_html__('Author avatar display in frontend', 'zoo-framework'),
                'id'               => $prefix.'author_img',
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ],
            [
                'name' => esc_html__('Author name', 'zoo-framework'),
                'desc' => esc_html__('Author name display in frontend', 'zoo-framework'),
                'id'   => $prefix.'author',
                'type' => 'text',
            ],
            [
                'name' => esc_html__('Author description', 'zoo-framework'),
                'desc' => esc_html__('Author description display in frontend', 'zoo-framework'),
                'id'   => $prefix.'author_des',
                'type' => 'text',
            ],
        ]
    ];

    $meta_boxes[] = [
        'id'      => 'zoo_tm_meta_box',
        'title'   => esc_html__('Team member information', 'zoo-framework'),
        'pages'   => ['team'],
        'context' => 'normal',
        'fields'  => [
            [
                'name' => esc_html__('Team member position', 'zoo-framework'),
                'desc' => esc_html__('Team member position display in frontend', 'zoo-framework'),
                'id'   => $prefix.'team_member_pos',
                'type' => 'text',
            ],
            [
                'name' => esc_html__('Experience', 'zoo-framework'),
                'desc' => esc_html__('Team member experience display in frontend', 'zoo-framework'),
                'id'   => $prefix.'team_member_exp',
                'type' => 'text',
            ],
            [
                'name' => esc_html__('Team member description', 'zoo-framework'),
                'desc' => esc_html__('Team member description display in frontend', 'zoo-framework'),
                'id'   => $prefix.'team_member_des',
                'type' => 'textarea',
            ],
            [
                'name' => esc_html__('Phone', 'zoo-framework'),
                'desc' => esc_html__('Phone number of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_phone',
                'type' => 'tel',
            ],
            [
                'name' => esc_html__('Email', 'zoo-framework'),
                'desc' => esc_html__('Email of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_email',
                'type' => 'email',
            ],
            [
                'name' => esc_html__('Facebook profile', 'zoo-framework'),
                'desc' => esc_html__('Full url profile of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_fb',
                'type' => 'url',
            ],
            [
                'name' => esc_html__('Twitter profile', 'zoo-framework'),
                'desc' => esc_html__('Full url profile of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_tw',
                'type' => 'url',
            ],
            [
                'name' => esc_html__('Google plus profile', 'zoo-framework'),
                'desc' => esc_html__('Full url profile of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_gp',
                'type' => 'url',
            ],
            [
                'name' => esc_html__('LinkedIn profile', 'zoo-framework'),
                'desc' => esc_html__('Full url profile of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_li',
                'type' => 'url',
            ],
            [
                'name' => esc_html__('Youtube profile', 'zoo-framework'),
                'desc' => esc_html__('Full url profile of team member', 'zoo-framework'),
                'id'   => $prefix.'team_member_yt',
                'type' => 'url',
            ],
        ]
    ];

    $meta_boxes[] = [
        'id'      => 'zoo_tm_project',
        'title'   => esc_html__('Project complete', 'zoo-framework'),
        'pages'   => ['team'],
        'context' => 'normal',
        'fields'  => [
            [
                'id'      => $prefix.'tm_project',
                'name'    => esc_html__('Project complete', 'zoo-framework'),
                'type'    => 'fieldset_text',
                'clone'   => true,
                'desc'    => esc_html__('Please enter following details:', 'zoo-framework'),
                'options' => [
                    'name' => esc_html__('Name', 'zoo-framework'),
                    'date' => esc_html__('Date complete', 'zoo-framework'),
                    'link' => esc_html__('Project url', 'zoo-framework'),
                ],
            ],
        ]
    ];

    return $meta_boxes;
});
