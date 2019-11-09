<?php

$map = [
    'article_post' => 'art_post',
    'article_category' => 'art_category',
    'eq_migration' => 'migration',
    //'fos_user' => 'user',
];

return [
    'connection' => [
        'map' => $map,
        /*'defaultConnection' => 'mysqlServer',
        //'defaultConnection' => 'sqliteServer',
        //'defaultConnection' => 'pgsqlServer',
        'connections' => [
            'mysqlServer' => [
                'driver' => DbDriverEnum::MYSQL,
                'host' => 'localhost',
                'database' => 'symfony-on-rails',
                'username' => 'root',
                'map' => $map,
            ],
            'sqliteServer' => [
                'driver' => DbDriverEnum::SQLITE,
                'database' => __DIR__ . '/../../var/sqlite/default.sqlite',
                'map' => $map,
            ],
            'pgsqlServer' => [
                'driver' => DbDriverEnum::PGSQL,
                'host' => 'localhost',
                'database' => 'symfony-on-rails',
                'username' => 'postgres',
                'password' => 'postgres',
                'map' => $map,
            ],
        ],*/
    ],
    'fixture' => [
        'directory' => [
            'default' => '/src/Fixture',
            '/src/Bundle/Article/Domain/Fixture',
            '/src/Bundle/User/Fixture',
        ],
    ],
    'migrate' => [
        'directory' => [
            '/src/Bundle/Article/Domain/Migration',
            '/src/Bundle/User/Migrations',
            // '/../../src/Migrations',
        ],
    ],
];
