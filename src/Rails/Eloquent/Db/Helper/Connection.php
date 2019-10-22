<?php

namespace App\Rails\Eloquent\Db\Helper;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Connection as IlluminateConnection;
use Illuminate\Database\Query\Builder;

class Connection
{

    /** @var Manager */
    private static $capsule;

    public static function defineConnections(array $connections) : void {
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

    public static function getSchema(string $connectionName = 'default') : \Illuminate\Database\Schema\Builder {
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
    }

    private static function addConnection(array $config, string $connectionName = 'default') : void {
        $capsule = self::getCapsule();
        $capsule->addConnection($config);
        $capsule->bootEloquent();
    }

}