<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Db\Enum\DbDriverEnum;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Capsule\Manager;
use php7extension\yii\helpers\ArrayHelper;

class DbRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;

    public function __construct()
    {
        $schema = Manager::schema();
        //Manager::connection()->statement('SET FOREIGN_KEY_CHECKS=0;');
        $schema->disableForeignKeyConstraints();
    }

    public function saveData($name, Collection $collection)
    {
        $queryBuilder = Manager::table($name);
        $queryBuilder->truncate();
        $data = ArrayHelper::toArray($collection);
        $queryBuilder->insert($data);
        $this->resetAutoIncrement($name);
    }

    private function resetAutoIncrement($name) {
        $schema = Manager::schema();
        $queryBuilder = Manager::table($name);
        $driver = $schema->getConnection()->getConfig('driver');
        if($driver == DbDriverEnum::PGSQL) {
            $max = $queryBuilder->max('id');
            if($max) {
                $pkName = 'id';
                $sql = 'SELECT setval(\''.$name.'_'.$pkName.'_seq\', '.($max+1).')';
                $connection = $queryBuilder->getConnection();
                $connection->statement($sql);
            }
        }
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