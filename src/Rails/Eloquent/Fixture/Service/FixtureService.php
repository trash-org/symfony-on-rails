<?php

namespace App\Rails\Eloquent\Fixture\Service;

use App\Rails\Eloquent\Fixture\Repository\DbRepository;
use App\Rails\Eloquent\Fixture\Repository\FileRepository;

class FixtureService
{

    private $dbRepository;
    private $fileRepository;

    public function __construct(DbRepository $dbRepository, FileRepository $fileRepository)
    {
        $this->dbRepository = $dbRepository;
        $this->fileRepository = $fileRepository;
    }

    public function allFixtures() {
        return $this->fileRepository->allTables();
    }

    public function allTables() {
        return $this->dbRepository->allTables();
    }

    public function importTable($name) {
        $data = $this->fileRepository->loadData($name);
        $this->dbRepository->saveData($name, $data);
    }

    public function exportTable($name) {
        $collection = $this->dbRepository->loadData($name);
        if($collection->count()) {
            $this->fileRepository->saveData($name, $collection->toArray());
        }
    }

}