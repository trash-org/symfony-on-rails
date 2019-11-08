<?php

use PhpLab\Eloquent\Db\Enum\DbDriverEnum;

/*$url = preg_replace('#^((?:pdo_)?sqlite3?):///#', '$1://localhost/', 'mysql://db_user:db_password@127.0.0.1:3306/db_name');
$url = parse_url($url);
if ($url === false) {
    throw new \Exception('Malformed parameter "url".');
}
$url = array_map('rawurldecode', $url);*/

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