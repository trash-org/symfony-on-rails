<?php

namespace App\Rails\Eloquent\Fixture\Service;

use App\Rails\Domain\Data\Collection;
use App\Rails\Eloquent\Fixture\Repository\DbRepository;
use App\Rails\Eloquent\Fixture\Repository\FileRepository;
use App\Rails\Eloquent\Migration\Repository\HistoryRepository;

class FixtureService
{

    private $dbRepository;
    private $fileRepository;
    private $excludeNames = [
        HistoryRepository::MIGRATION_TABLE_NAME,
    ];

    public function __construct(DbRepository $dbRepository, FileRepository $fileRepository)
    {
        $this->dbRepository = $dbRepository;
        $this->fileRepository = $fileRepository;
    }

    public function allFixtures() {
        $collection = $this->fileRepository->allTables();
        return $this->filterByExclude($collection);
    }

    public function allTables() {
        $collection = $this->dbRepository->allTables();
        return $this->filterByExclude($collection);
    }

    public function importTable($name) {
        $data = $this->fileRepository->loadData($name);
        $this->dbRepository->saveData($name, $data);
    }

    public function exportTable($name) {
        $collection = $this->dbRepository->loadData($name);
        if($collection->count()) {
            $this->fileRepository->saveData($name, $collection);
        }
    }

    private function filterByExclude(Collection $collection) {
        $excludeNames = $this->excludeNames;
        return $collection->whereNotIn('name', $excludeNames);
    }

}