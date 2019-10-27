<?php

namespace App\Rails\Eloquent\Db\Helper;

use App\Rails\Eloquent\Db\Enum\DbDriverEnum;
use Illuminate\Database\Capsule\Manager;
use php7extension\yii\helpers\FileHelper;

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

    /**
     * Страховка наличия файла БД SQLite
     *
     * Если файл БД не создан, то создает пустой файл
     *
     * @param string $connectionName
     */
    public static function forgeDb(string $connectionName = 'default') {
        $schema = Manager::schema($connectionName);
        $driver = $schema->getConnection()->getConfig('driver');
        if($driver == DbDriverEnum::SQLITE) {
            $database = $schema->getConnection()->getConfig('database');
            FileHelper::touch($database);
        }
    }

}