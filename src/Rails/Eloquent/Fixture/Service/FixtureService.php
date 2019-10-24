<?php

namespace App\Rails\Eloquent\Fixture\Service;

use App\Rails\Eloquent\Fixture\Repository\DbRepository;
use App\Rails\Eloquent\Fixture\Repository\FileRepository;
use Illuminate\Database\Capsule\Manager;
use php7extension\core\store\StoreFile;
use php7extension\yii\helpers\ArrayHelper;

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

    public function exportTable($name) {
        $queryBuilder = Manager::table($name);
        $data = $queryBuilder->get()->toArray();
        if($data) {
            $store = new StoreFile('./data/'.$name.'.php', 'php');
            $data = ArrayHelper::toArray($data);
            $store->save($data);
        }
    }

}