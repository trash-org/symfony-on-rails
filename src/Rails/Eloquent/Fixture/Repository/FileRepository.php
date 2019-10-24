<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use php7extension\core\exceptions\NotFoundException;
use php7extension\core\store\StoreFile;
use php7extension\yii\helpers\ArrayHelper;
use php7extension\yii\helpers\FileHelper;

class FileRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;
    public static $config = [
        'directory' => [],
    ];
    public $extension = 'php';

    public function allTables() : Collection
    {
        $array = [];
        foreach (self::$config['directory'] as $dir) {
            $fixtureArray = $this->scanDir(FileHelper::rootPath() . '/' . $dir);
            $array = ArrayHelper::merge($array, $fixtureArray);
        }
        $collection = $this->forgeEntityCollection($array);
        return $collection;
    }

    public function saveData($name, Collection $collection)
    {
        $data = ArrayHelper::toArray($collection);
        $this->getStoreInstance($name)->save($data);
    }

    public function loadData($name) : Collection
    {
        $data = $this->getStoreInstance($name)->load();
        return new Collection($data);
    }

    private function oneByName(string $name) : FixtureEntity {
        $collection = $this->allTables();
        $collection = $collection->where('name', '=', $name);
        if($collection->count() < 1) {
            return $this->forgeEntity([
                'name' => $name,
                'fileName' => self::$config['directory'][0] . '/' . $name . '.' . $this->extension,
            ]);
        }

        return $this->forgeEntity($collection->first());
    }

    private function getStoreInstance(string $name) : StoreFile {
        $entity = $this->oneByName($name);
        $store = new StoreFile($entity->fileName);
        return $store;
    }

    /*private function getStoreInstance(string $name) : StoreFile {
        $fileName = $this->getFileName($name);
        $store = new StoreFile($fileName, $this->extension);
        return $store;
    }*/

    /*private function getDirectory() : string {
        return FileHelper::rootPath() . '/' . self::$config['directory'][0];
    }*/

    /*private function getFileName(string $name) : string {
        $fileName = $this->getDirectory() . '/' . $name . '.' . $this->extension;
        return $fileName;
    }*/

    private function scanDir($dir) : array
    {
        $files = FileHelper::scanDir($dir);
        $array = [];
        foreach ($files as $file) {
            $name = FileHelper::fileRemoveExt($file);
            $entity = [
                'name' => $name,
                'fileName' => $dir . '/' . $file,
            ];
            $array[] = $entity;
        }
        return $array;
    }

}