<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Capsule\Manager;
use php7extension\core\store\StoreFile;
use php7extension\yii\helpers\ArrayHelper;
use php7extension\yii\helpers\FileHelper;

class FileRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;

    public function allTables()
    {
        return $this->scanDir(FileHelper::rootPath() . '/data/');
    }

    public function saveData($name, $data)
    {
        $store = new StoreFile('./data/'.$name.'.php', 'php');
        $data = ArrayHelper::toArray($data);
        $store->save($data);
    }

    public function loadData($name)
    {
        $store = new StoreFile('./data/'.$name.'.php', 'php');
        $data = $store->load();
        return $data;
    }

    private function scanDir($dir)
    {
        $files = FileHelper::scanDir($dir);
        $collection = new Collection;
        foreach ($files as $file) {
            $name = FileHelper::fileRemoveExt($file);
            $entity = $this->forgeEntity([
                'name' => $name,
            ]);
            $collection->add($entity);
        }
        return $collection;
    }

}