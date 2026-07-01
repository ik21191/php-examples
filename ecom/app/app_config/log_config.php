<?php
use Monolog\Level;

return [
    'channel_name' => 'app_global',
    'handlers' => [
        'file' => [
            'path' => __DIR__ . '/../../../logs/app.log',
            'level' => Level::Debug // Log everything from DEBUG up
        ]
    ],
    'format' => "[%datetime%] %channel%.%level_name%: %message% \n",
];
