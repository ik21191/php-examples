<?php
use Monolog\Level;

return [
    'channel_name' => 'app_global',
    'handlers' => [
        'file' => [
            'path' => __DIR__ . '/../logs/app.log',
            'level' => Level::Debug, // Log everything from DEBUG up
            'bubble' => true,
        ],
        'error_file' => [
            'path' => __DIR__ . '/../logs/error.log',
            'level' => Level::Error, // Route higher priority errors separately
            'bubble' => false,
        ]
    ],
    'format' => "[%datetime%] %channel%.%level_name%: %message% \n",
];
