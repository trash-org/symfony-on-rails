<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use php7extension\core\store\StoreFile;
use php7extension\yii\helpers\ArrayHelper;
use php7extension\yii\helpers\FileHelper;

class FileRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;
    public $directory = 'data';
    public $extension = 'php';

    public function allTables() : Collection
    {
        return $this->scanDir($this->getDirectory());
    }

    public function saveData($name, Collection $collection)
    {
        $data = ArrayHelper::toArray($collection);
        $store = $this->getStoreInstance($name);
        $store->save($data);
    }

    public function loadData($name) : Collection
    {
        $store = $this->getStoreInstance($name);
        $data = $store->load();
        return new Collection($data);
    }

    private function getStoreInstance(string $name) : StoreFile {
        $fileName = $this->getFileName($name);
        $store = new StoreFile($fileName, $this->extension);
        return $store;
    }

    private function getDirectory() : string {
        return FileHelper::rootPath() . '/' . $this->directory;
    }

    private function getFileName(string $name) : string {
        $fileName = $this->getDirectory() . '/' . $name . '.' . $this->extension;
        return $fileName;
    }

    private function scanDir($dir) : Collection
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