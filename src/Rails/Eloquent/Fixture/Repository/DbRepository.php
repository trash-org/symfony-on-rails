<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Capsule\Manager;
use php7extension\yii\helpers\ArrayHelper;

class DbRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;

    public function saveData($name, Collection $collection)
    {
        $queryBuilder = Manager::table($name);
        $queryBuilder->truncate();
        $data = ArrayHelper::toArray($collection);
        $queryBuilder->insert($data);
    }

    public function loadData($name) : Collection
    {
        $queryBuilder = Manager::table($name);
        $data = $queryBuilder->get()->toArray();
        return new Collection($data);
    }

    public function allTables() : Collection
    {
        $schema = Manager::schema();
        $dbName = $schema->getConnection()->getDatabaseName();
        $array = $schema->getAllTables();
        $collection = new Collection;
        foreach ($array as $item) {
            $key = 'Tables_in_' . $dbName;
            $entity = $this->forgeEntity([
                'name' => $item->{$key}
            ]);
            $collection->add($entity);
        }
        return $collection;
    }

}