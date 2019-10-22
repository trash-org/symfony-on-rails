<?php

namespace App\Rails\Eloquent\Migration\Service;

use App\Rails\Eloquent\Migration\Entity\MigrationEntity;
use App\Rails\Eloquent\Migration\Repository\HistoryRepository;
use App\Rails\Eloquent\Migration\Repository\SourceRepository;
use php7extension\yii\helpers\ArrayHelper;

class MigrationService
{

    private $sourceRepository;
    private $historyRepository;

    public function __construct()
    {
        $this->sourceRepository = new SourceRepository;
        $this->historyRepository = new HistoryRepository;
    }

    public function upMigration(MigrationEntity $migrationEntity) {
        $this->historyRepository->upMigration($migrationEntity->className);
    }

    public function downMigration(MigrationEntity $migrationEntity) {
        $this->historyRepository->downMigration($migrationEntity->className);
    }

    public function allForUp() {
        /*
         * читать коллекцию из БД
         * читать коллекцию классов
         * оставить только те классы, которых нет в БД
         * сортировать по возрастанию (version)
         * выпонить up
         */

        $sourceCollection = $this->sourceRepository->getAll();
        $historyCollection = $this->historyRepository->all();
        $filteredCollection = $this->historyRepository->filterVersion($sourceCollection, $historyCollection);
        ArrayHelper::multisort($filteredCollection, 'version');
        return $filteredCollection;
    }

    public function allForDown() {
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

        $historyCollection = $this->historyRepository->all();
        $sourceCollection = $this->sourceRepository->getAll();
        $sourceCollectionIndexed = ArrayHelper::index($sourceCollection, 'version');
        foreach ($historyCollection as $migrationEntity) {
            $migrationEntity->className = $sourceCollectionIndexed[$migrationEntity->version]->className;
        }
        ArrayHelper::multisort($historyCollection, 'version', SORT_DESC);
        return $historyCollection;
    }

}