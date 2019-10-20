<?php

return [
    'servers' => [
        'db' => [
            'main' => [
                'driver' => 'pgsql',
                'username' => 'postgres',
                'password' => 'postgres',
                'dbname' => 'dev_yutpl',
                'defaultSchema' => 'common',
            ],
        ],
    ],
    'mode' => [
        'env' => 'dev',
        'debug' => true,
        'benchmark' => true,
    ],
    'domain' => [
        'driver' => [
            'primary' => 'ar',
            'slave' => 'ar',
        ],
    ],
    'encrypt' => [
        'profiles' => [
            'auth' => [
                'key' => [
                    'private' => 'zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz',
                ],
            ],
        ],
    ],
];
