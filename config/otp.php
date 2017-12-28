<?php
return [
    'sendpulse' => [
        'base_api_url' => 'https://api.sendpulse.com',
        'auth_path'    => '/oauth/access_token',
        'auth_data'    => [
            'grant_type'    => 'client_credentials',
            'client_id'     => env('SENDPULSE_ID', ''),
            'client_secret' => env('SENDPULSE_SECRET', ''),
        ]
    ]
];