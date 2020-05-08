<?php
/**
 * Customize for Shop loop product
 */
return [
    [
        'type' => 'section',
        'name' => 'zoo_contact',
        'title' => esc_html__('Contact', 'anon'),
    ],
    [
        'name' => 'zoo_contact_general_settings',
        'type' => 'heading',
        'label' => esc_html__('Contact Settings', 'anon'),
        'section' => 'zoo_contact',
    ],
    [
        'name' => 'zoo_contact_type',
        'type' => 'select',
        'section' => 'zoo_contact',
        'title' => esc_html__('Contact Type', 'anon'),
        'default' => 'none',
        'choices' => [
            'none' => esc_html__('None', 'anon'),
            'phone' => esc_html__('Phone', 'anon'),
            'email' => esc_html__('Email', 'anon'),
            'messenger' => esc_html__('Messenger', 'anon'),
            'whatsapp' => esc_html__('Whatsapp', 'anon'),
            'skype' => esc_html__('Skype', 'anon'),
        ]
    ],
    [
        'type' => 'text',
        'name' => 'zoo_contact_id',
        'label' => esc_html__('Contact ID', 'anon'),
        'section' => 'zoo_contact',
        'description' => esc_html__('Your contact id. That is your phone, email or social id follow type contact you selected', 'anon'),
        'required' => ['zoo_contact_type', '!=', 'none'],
    ],[
        'type'    => 'select',
        'name' => 'zoo_contact_pos',
        'label' => esc_html__('Contact Position', 'anon'),
        'section' => 'zoo_contact',
        'description' => esc_html__('', 'anon'),
        'default' => 'left',
        'choices' => [
            'left' => esc_html__('Left', 'anon'),
            'right' => esc_html__('Right', 'anon'),
        ],
        'required' => ['zoo_contact_type', '!=', 'none'],
    ],
    [
        'name' => 'zoo_contact_size',
        'type' => 'slider',
        'section' => 'zoo_contact',
        'min' => 0,
        'step' => 1,
        'max' => 300,
        'required' => ['zoo_contact_type', '!=', 'none'],
        'selector'  => '.zoo-contact-button>.zoo-contact-link',
        'css_format' => 'width: {{value}};height: {{value}}',
    ],[
        'name' => 'zoo_contact_font_size',
        'type' => 'slider',
        'section' => 'zoo_contact',
        'min' => 0,
        'step' => 1,
        'max' => 100,
        'required' => ['zoo_contact_type', '!=', 'none'],
        'selector'  => '.zoo-contact-button>.zoo-contact-link',
        'css_format' => 'font-size: {{value}}',
    ],
    [
        'name' => 'zoo_contact_color',
        'type' => 'color',
        'section' => 'zoo_contact',
        'title' => esc_html__('Color', 'anon'),
        'selector' => '.zoo-contact-button>.zoo-contact-link',
        'css_format' => 'color: {{value}};',
        'required' => ['zoo_contact_type', '!=', 'none'],
    ],[
        'name' => 'zoo_contact_bg_color',
        'type' => 'color',
        'section' => 'zoo_contact',
        'title' => esc_html__('Background Color', 'anon'),
        'selector' => '.zoo-contact-button>.zoo-contact-link',
        'css_format' => 'background-color: {{value}};',
        'required' => ['zoo_contact_type', '!=', 'none'],
    ],
];
