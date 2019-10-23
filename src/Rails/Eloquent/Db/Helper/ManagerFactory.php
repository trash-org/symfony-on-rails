<?php

namespace App\Rails\Eloquent\Db\Helper;

use Illuminate\Database\Capsule\Manager;

class ManagerFactory
{

    public static function createManager(array $connections) : Manager {
        $capsule = new Manager;
        $capsule->setAsGlobal();
        foreach ($connections as $connectionName => $config) {
            $capsule->addConnection($config);
        }
        $capsule->bootEloquent();
        return $capsule;
    }

}