<?php
return [
    'code_length' => 6,
    'code_expiry' => 300, // 5 minutes en secondes
    'max_attempts' => 3,
    'email' => [
        'enabled' => true,
        'recipients' => [
            'contact@njiezm.fr',
            'njiezamon10@gmail.com'
        ],
        'smtp' => [
            'host' => env('SMTP_HOST', 'smtp.gmail.com'),
            'port' => env('SMTP_PORT', 587),
            'username' => env('SMTP_USERNAME'),
            'password' => env('SMTP_PASSWORD'),
            'encryption' => env('SMTP_ENCRYPTION', 'tls'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'noreply@digitcard.com'),
                'name' => 'Digitcard Security'
            ]
        ]
    ],
    'whatsapp' => [
        'enabled' => true,
        'phone' => '+596696703922',
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'webhook_url' => env('WHATSAPP_WEBHOOK_URL'),
        'business_id' => env('WHATSAPP_BUSINESS_ID')
    ],
    'backup_codes' => [
        'enabled' => true,
        'count' => 10
    ]
];