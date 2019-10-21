<?php

namespace App\Rails\Eloquent\Helper;


use App\Rails\Eloquent\Entity\MigrationEntity;
use php7extension\yii\helpers\ArrayHelper;

class MigrationService
{

    public static function upMigration(MigrationEntity $migrationEntity) {
        MigrateHistoryHelper::upMigration($migrationEntity->className);
    }

    public static function downMigration(MigrationEntity $migrationEntity) {
        MigrateHistoryHelper::downMigration($migrationEntity->className);
    }

    public static function allForUp() {
        /*
         * читать коллекцию из БД
         * читать коллекцию классов
         * оставить только те классы, которых нет в БД
         * сортировать по возрастанию (version)
         * выпонить up
         */

        $sourceCollection = MigrateSourceHelper::getAll();
        $historyCollection = MigrateHistoryHelper::all();
        $filteredCollection = MigrateHistoryHelper::filterVersion($sourceCollection, $historyCollection);
        ArrayHelper::multisort($filteredCollection, 'version');
        return $filteredCollection;
    }

    public static function allForDown() {
        /**
         * @var MigrationEntity[] $historyCollection
         * @var MigrationEntity[] $sourceCollection
         * @var MigrationEntity[] $sourceCollectionIndexed
         */

        /*
         * читать коллекцию из БД
         * найди совпадения классов
         * сортировать по убыванию (executed_at)
         * выпонить down
         */

        $historyCollection = MigrateHistoryHelper::all();
        $sourceCollection = MigrateSourceHelper::getAll();
        $sourceCollectionIndexed = ArrayHelper::index($sourceCollection, 'version');
        foreach ($historyCollection as $migrationEntity) {
            $migrationEntity->className = $sourceCollectionIndexed[$migrationEntity->version]->className;
        }
        ArrayHelper::multisort($historyCollection, 'version', SORT_DESC);
        return $historyCollection;
    }

}