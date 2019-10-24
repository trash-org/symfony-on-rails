<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Capsule\Manager;
use php7extension\yii\helpers\FileHelper;

class FileRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;

    public function allTables()
    {
        return $this->scanDir(FileHelper::rootPath() . '/data/');
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