<?php

use App\Rails\Eloquent\Db\Enum\DbDriverEnum;

return [
    //'defaultConnection' => 'mysqlServer',
    //'defaultConnection' => 'sqliteServer',
    //'defaultConnection' => 'pgsqlServer',
    'connections' => [
        'mysqlServer' => [
            "driver" => 'mysql',
            "host" => 'localhost',
            "database" => 'symfony4',
            "username" => 'root',
            "password" => '',
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
        ],
        'sqliteServer' => [
            "driver" => 'sqlite',
            "database" => __DIR__ . '/../../var/sqlite/default.sqlite',
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
        ],
        'pgsqlServer' => [
            "driver" => DbDriverEnum::PGSQL,
            "host" => 'localhost',
            "database" => 'symfony4',
            "username" => 'postgres',
            "password" => 'postgres',
            "charset" => "utf8",
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
        ],
    ],
];