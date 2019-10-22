<?php

namespace App\Rails\Eloquent\Migration\Helper;

use App\Rails\Eloquent\Helper\Connection;
use App\Rails\Eloquent\Migration\Entity\MigrationEntity;
use App\Rails\Eloquent\Migration\Base\BaseCreateTableMigrate;
use Illuminate\Database\Schema\Blueprint;
use php7extension\core\common\helpers\ClassHelper;
use php7extension\yii\helpers\ArrayHelper;

class MigrateHistoryHelper
{

    const MIGRATION_TABLE_NAME = 'eq_migration';

    public static function filterVersion(array $sourceCollection, array $historyCollection) {
        /**
         * @var MigrationEntity[] $historyCollection
         * @var MigrationEntity[] $sourceCollection
         */

        $sourceVersionArray = ArrayHelper::getColumn($sourceCollection, 'version');
        $historyVersionArray = ArrayHelper::getColumn($historyCollection, 'version');

        $diff = array_diff($sourceVersionArray, $historyVersionArray);

        foreach ($sourceCollection as $key => $migrationEntity) {
            if( ! in_array($migrationEntity->version, $diff)) {
                unset($sourceCollection[$key]);
            }
        }
        return $sourceCollection;
    }

    /*public static function getAll($connectionName = 'default') {

        $collection = self::all($connectionName);
        dd($collection);
        $versionList = ArrayHelper::getColumn($collection, 'version');
        return $versionList;
    }*/

    private static function insert($version, $connectionName = 'default') {
        $queryBuilder = Connection::getQueryBuilder($connectionName);
        $queryBuilder->from(self::MIGRATION_TABLE_NAME);
        $queryBuilder->insert([
            'version' => $version,
            'executed_at' => new \DateTime(),
        ]);
    }

    private static function delete($version, $connectionName = 'default') {
        $queryBuilder = Connection::getQueryBuilder($connectionName);
        $queryBuilder->from(self::MIGRATION_TABLE_NAME);
        $queryBuilder->where('version', $version);
        $queryBuilder->delete();
    }

    public static function upMigration($class) {
        /** @var BaseCreateTableMigrate $migration */
        $migration = new $class;
        // todo: begin transaction
        Connection::getConnection()->beginTransaction();
        $migration->up();
        $version = ClassHelper::getClassOfClassName($class);
        Connection::getConnection()->commit();
        self::insert($version);
        // todo: end transaction
    }

    public static function downMigration($class) {
        /** @var BaseCreateTableMigrate $migration */
        $migration = new $class;
        // todo: begin transaction
        Connection::getConnection()->beginTransaction();
        $migration->down();
        $version = ClassHelper::getClassOfClassName($class);
        self::delete($version);
        Connection::getConnection()->commit();
        // todo: end transaction
    }

    public static function all($connectionName = 'default') {
        self::forgeMigrationTable($connectionName);
        $queryBuilder = Connection::getQueryBuilder($connectionName);
        $queryBuilder->from(self::MIGRATION_TABLE_NAME);
        $array = $queryBuilder->get()->toArray();
        $collection = [];
        foreach ($array as $item) {
            $entity = new MigrationEntity;
            $entity->version = $item->version;
            //$entity->className = $className;
            $collection[] = $entity;
        }
        return $collection;
    }

    private static function forgeMigrationTable($connectionName = 'default') {
        $schema = Connection::getSchema($connectionName);
        $hasTable = $schema->hasTable(self::MIGRATION_TABLE_NAME);
        if($hasTable) {
            return;
        }
        self::createMigrationTable($connectionName);
    }

    private static function createMigrationTable($connectionName = 'default') {
        $tableSchema = function (Blueprint $table) {
            $table->string('version')->primary();
            $table->timestamp('executed_at');
        };
        $schema = Connection::getSchema($connectionName);
        $schema->create(self::MIGRATION_TABLE_NAME, $tableSchema);
    }

}