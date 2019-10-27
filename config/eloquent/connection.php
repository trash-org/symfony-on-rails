<?php

use App\Rails\Eloquent\Db\Enum\DbDriverEnum;

return [
    'defaultConnection' => 'mysqlServer',
    //'defaultConnection' => 'sqliteServer',
    //'defaultConnection' => 'pgsqlServer',
    'connections' => [
        'mysqlServer' => [
            "driver" => 'mysql',
            "host" => 'localhost',
            "database" => 'symfony4',
            "username" => 'root',
        ],
        'sqliteServer' => [
            "driver" => 'sqlite',
            "database" => __DIR__ . '/../../var/sqlite/default.sqlite',
        ],
        'pgsqlServer' => [
            "driver" => DbDriverEnum::PGSQL,
            "host" => 'localhost',
            "database" => 'symfony4',
            "username" => 'postgres',
            "password" => 'postgres',
        ],
    ],
];