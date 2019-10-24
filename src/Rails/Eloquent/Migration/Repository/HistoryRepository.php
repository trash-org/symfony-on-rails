<?php

namespace App\Rails\Eloquent\Migration\Repository;

use App\Rails\Eloquent\Migration\Entity\MigrationEntity;
use App\Rails\Eloquent\Migration\Base\BaseCreateTableMigration;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use php7extension\core\common\helpers\ClassHelper;
use php7extension\yii\helpers\ArrayHelper;

class HistoryRepository
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

    private static function insert($version, $connectionName = 'default') {
        $queryBuilder = Manager::table(self::MIGRATION_TABLE_NAME, null, $connectionName);
        $queryBuilder->insert([
            'version' => $version,
            'executed_at' => new \DateTime(),
        ]);
    }

    private static function delete($version, $connectionName = 'default') {
        $queryBuilder = Manager::table(self::MIGRATION_TABLE_NAME, null, $connectionName);
        $queryBuilder->where('version', $version);
        $queryBuilder->delete();
    }

    public static function upMigration($class) {
        /** @var BaseCreateTableMigration $migration */
        $migration = new $class;
        // todo: begin transaction
        Manager::connection()->beginTransaction();
        $migration->up();
        $version = ClassHelper::getClassOfClassName($class);
        Manager::connection()->commit();
        self::insert($version);
        // todo: end transaction
    }

    public static function downMigration($class) {
        /** @var BaseCreateTableMigration $migration */
        $migration = new $class;
        // todo: begin transaction
        Manager::connection()->beginTransaction();
        $migration->down();
        $version = ClassHelper::getClassOfClassName($class);
        self::delete($version);
        Manager::connection()->commit();
        // todo: end transaction
    }

    public static function all($connectionName = 'default') {
        self::forgeMigrationTable($connectionName);
        $queryBuilder = Manager::table(self::MIGRATION_TABLE_NAME, null, $connectionName);
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
        $schema = Manager::schema($connectionName);
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
        $schema = Manager::schema($connectionName);
        $schema->create(self::MIGRATION_TABLE_NAME, $tableSchema);
    }

}