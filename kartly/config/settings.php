<?php

return [
    'general_status' => [
        'active' => 1,
        'in_active' => 2,
    ],
    'user_status' => [
        'active' => 1,
        'in_active' => 2,
    ],
    'roles' => [
        'supper_admin' => 1
    ],
    'currency_position' => [
        2 => "[Amount][Currency]",
        1 => "[Currency][Amount]",
    ],
    'media_file_type' => [
        'pdf' => 'PDF',
        'zip' => 'ZIP',
        'video' => 'Video',
        'image' => 'Image',
        'webp' => 'Webp'
    ],
    // Visibility Status List
    'visibility_status' => [
        'public',
        'password',
        'private'
    ],
    // Page Status Number
    'page_status' => [
        'publish' => '1',
        'draft' => '2',
        'trash' => '3',
    ],
    // Blog Status number
    'blog_status' => [
        'publish' => '1',
        'pending' => '2',
        'draft' => null,
    ],
    // Blog Comment Status list
    'blog_comment_status_name' => [
        'approve',
        'pending',
        'spam'
    ],
    // Blog Comment Status number
    'blog_comment_status' => [
        'approve' => '1',
        'pending' => '2',
        'spam' => '3',
        'trash' => '4'
    ],
    // Blog Comment Default Avatar name
    'blog_comment_default_avatar' => [
        'mystery',
        'blank',
        'gravatar',
        'identicon',
        'wavatar',
        'monsterid',
        'retro'
    ],

    'email_template' => [
        'blog_comment_email_template' => 1,
        'reset_user_password' => 2,
    ],
    'menu_position' => [
        'Main Header Menu',
    ],
];
