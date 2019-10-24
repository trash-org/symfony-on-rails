<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Capsule\Manager;

class DbRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;

    public function allTables()
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