<?php
return [
    'gate'       => env('OTP_GATE', 'sendpulse'),
    'gate_class' => [
        'sendpulse' => \App\Services\Auth\Otp\SendPulseOtpAuth\SendPulseOtpAuth::class,
        'smstraffic' => \App\Services\Auth\Otp\SmsTrafficOtpAuth\SmsTrafficOtpAuth::class,
    ],
    'gate_data'  => [
        'sendpulse'  => [
            'base_api_url' => 'https://api.sendpulse.com',
            'auth_path'    => '/oauth/access_token',
            'auth_data'    => [
                'grant_type'    => 'client_credentials',
                'client_id'     => env('SENDPULSE_ID', ''),
                'client_secret' => env('SENDPULSE_SECRET', ''),
            ]
        ],
        'smstraffic' => [
            'base_api_url' => 'http://www.smstraffic.ru',
            'main_path'    => '/multi.php',
            'auth_data'    => [
                'login'        => env('SMSTRAFFIC_LOGIN', ''),
                'password'     => env('SMSTRAFFIC_PASSWORD', ''),
                'want_sms_ids' => '1',
                'max_parts'    => '1',
                'rus'          => '0',
                'originator'   => 'nau.io',
                //'udh'          => ''
            ]
        ]
    ],
];
