<?php

namespace App\Rails\Domain\Db;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection as IlluminateConnection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\MySqlBuilder;

class Connection
{

    private static $connections = [];

    /** @var Manager */
    private static $capsule;
    private static $capsules = [];

    public static function addList(array $connections) : void {
        foreach ($connections as $connectionName => $config) {
            self::addConnection($config, $connectionName);
        }
    }

    public static function getQueryBuilder(string $connectionName = 'default') : Builder {
        $connection = self::getConnection($connectionName);
        $connectionQuery = $connection->query();
        //$connectionQuery->from($table);
        return $connectionQuery;
    }

    public static function getSchema(string $connectionName = 'default') /*: \Illuminate\Database\Schema\MySqlBuilder*/ {
        return self::getCapsule()->schema($connectionName);
    }

    public static function getCapsule() : Manager {
        if(empty(self::$capsule)) {
            self::$capsule = new Manager;
            self::$capsule->setAsGlobal();
        }
        return self::$capsule;
    }

    public static function getConnection($connectionName = 'default') : IlluminateConnection {
        return self::$capsule->getConnection($connectionName);
        //return self::$connections$connectionName[];
    }

    private static function addConnection(array $config, string $connectionName = 'default') : void {
        $capsule = self::getCapsule();
        $capsule->addConnection($config);
        $capsule->bootEloquent();
        //self::$connections[$connectionName] = self::$capsule->getConnection($connectionName);
    }

}