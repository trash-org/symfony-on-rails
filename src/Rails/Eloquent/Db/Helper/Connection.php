<?php

namespace App\Rails\Eloquent\Db\Helper;

use Illuminate\Database\Capsule\Manager;

class Connection
{

    /** @var Manager */
    private static $capsule;

    public static function defineConnections(array $connections) : void {
        $capsule = self::getCapsule();
        foreach ($connections as $connectionName => $config) {
            $capsule->addConnection($config);
        }
        $capsule->bootEloquent();
    }

    private static function getCapsule() : Manager {
        if(empty(self::$capsule)) {
            self::$capsule = new Manager;
            self::$capsule->setAsGlobal();
        }
        return self::$capsule;
    }

}