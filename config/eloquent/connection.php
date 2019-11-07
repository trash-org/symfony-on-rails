<?php

use App\Rails\Eloquent\Db\Enum\DbDriverEnum;

$map = [
    'article_post' => 'art_post',
    'article_category' => 'art_category',
    'eq_migration' => 'migration',
    //'fos_user' => 'user',
];

return [
    'defaultConnection' => 'mysqlServer',
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
    ],
];