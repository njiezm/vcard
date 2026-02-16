<?php

return [
    'default_theme' => 'default',
    'themes' => [
        'default' => [
            'name' => 'Default',
            'primary_color' => '#667eea',
            'secondary_color' => '#764ba2',
        ],
        'dark' => [
            'name' => 'Dark',
            'primary_color' => '#1a202c',
            'secondary_color' => '#2d3748',
        ],
        'ocean' => [
            'name' => 'Ocean',
            'primary_color' => '#006994',
            'secondary_color' => '#00b4d8',
        ],
        'sunset' => [
            'name' => 'Sunset',
            'primary_color' => '#ff6b6b',
            'secondary_color' => '#feca57',
        ],
    ],
    'max_file_size' => 2048, // KB
    'allowed_photo_types' => ['jpeg', 'jpg', 'png', 'gif'],
    'qr_code_size' => 300,
    'cache_duration' => 3600, // seconds
];