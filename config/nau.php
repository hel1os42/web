<?php
return [
    'multiplier'             => env('MULTIPLIER', 4),
    'reservation_multiplier' => env('NAU_RESERVATION_MULTIPLIER', 10),
    'key_secret_investor'    => env('INVESTOR_SECRET_KEY', 'deadbeef'),
    'image_types'            => [
        'offer'         => [
            'mobile'   => [
                'width'  => 360,
                'height' => 360,
            ],
            'desktop'  => [
                'width'  => 32,
                'height' => 32,
            ],
            'original' => [
                'width'  => 1024,
                'height' => 1024,
            ],
        ],
        'user'          => [
            'mobile'   => [
                'width'  => 120,
                'height' => 120,
            ],
            'desktop'  => [
                'width'  => 120,
                'height' => 120,
            ],
            'original' => [
                'width'  => 1024,
                'height' => 1024,
            ],
        ],
        'place_cover'   => [
            'mobile'   => [
                'width'  => 720,
                'height' => 240,
            ],
            'desktop'  => [
                'width'  => 1170,
                'height' => 390,
            ],
            'original' => [
                'width'  => 1500,
                'height' => 500,
            ],
        ],
        'place_picture' => [
            'mobile'   => [
                'width'  => 360,
                'height' => 360,
            ],
            'desktop'  => [
                'width'  => 120,
                'height' => 120,
            ],
            'original' => [
                'width'  => 1024,
                'height' => 1024,
            ],
        ],
    ]
];
