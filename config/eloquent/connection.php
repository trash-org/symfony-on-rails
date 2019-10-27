<?php

use App\Rails\Eloquent\Db\Enum\DbDriverEnum;

return [
    'defaultConnection' => 'mysqlServer',
    //'defaultConnection' => 'sqliteServer',
    //'defaultConnection' => 'pgsqlServer',
    'connections' => [
        'mysqlServer' => [
            'driver' => DbDriverEnum::MYSQL,
            'host' => 'localhost',
            'database' => 'symfony4',
            'username' => 'root',
            'map' => [
                'article_post' => 'art_post',
                'article_category' => 'art_category',
                'eq_migration' => 'migration',
            ],
        ],
        'sqliteServer' => [
            'driver' => DbDriverEnum::SQLITE,
            'database' => __DIR__ . '/../../var/sqlite/default.sqlite',
        ],
        'pgsqlServer' => [
            'driver' => DbDriverEnum::PGSQL,
            'host' => 'localhost',
            'database' => 'symfony4',
            'username' => 'postgres',
            'password' => 'postgres',
        ],
    ],
];