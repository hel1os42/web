<?php

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings'      => [
        'default' => [
            'HTML.Doctype'          => 'HTML 4.01 Transitional',
            'HTML.Allowed'          => 'p,b,i,u,a[href|title],ul,ol,li',
            'CSS.AllowedProperties' => 'line-height',
        ],
    ],
];
