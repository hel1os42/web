<?php
return [
    'gate'       => env('OTP_GATE', 'stub'),
    'gate_class' => [
        'sendpulse'  => \App\Services\Auth\Otp\SendPulseOtpAuth\SendPulseOtpAuth::class,
        'smstraffic' => \App\Services\Auth\Otp\SmsTrafficOtpAuth\SmsTrafficOtpAuth::class,
        'smsfly'     => \App\Services\Auth\Otp\SmsFlyOtpAuth\SmsFlyOtpAuth::class,
        'twilio'     => \App\Services\Auth\Otp\TwilioOtpAuth\TwilioOtpAuth::class,
        'stub'       => \App\Services\Auth\Otp\Stub\StubOtpAuth::class,
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
        ],
        'smsfly'     => [
            'base_api_url' => 'http://sms-fly.com',
            'main_path'    => '/api/api.noai.php',
            'auth_data'    => [
                'login'    => env('SMSFLY_LOGIN', ''),
                'password' => env('SMSFLY_PASSWORD', ''),
            ]
        ],
        'twilio'     => [
            'auth_data'     => [
                'client_id'     => env('TWILIO_ID', ''),
                'client_secret' => env('TWILIO_SECRET', ''),
            ],
            'sender_number' => env('TWILIO_NUMBER', '')
        ]
    ],
    'special_number' => env('SPECIAL_OTP_PHONE', ''),
];
