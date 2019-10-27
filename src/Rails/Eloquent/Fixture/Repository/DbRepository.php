<?php

namespace App\Rails\Eloquent\Fixture\Repository;

use App\Rails\Domain\Data\Collection;
use App\Rails\Domain\Repository\BaseRepository;
use App\Rails\Eloquent\Db\Enum\DbDriverEnum;
use App\Rails\Eloquent\Db\Helper\TableAliasHelper;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\MySqlBuilder;
use Illuminate\Database\Schema\PostgresBuilder;
use php7extension\yii\helpers\ArrayHelper;

class DbRepository extends BaseRepository
{

    public $entityClass = FixtureEntity::class;

    public function __construct()
    {
        $schema = Manager::schema();

        // Выключаем проверку целостности связей
        $schema->disableForeignKeyConstraints();
    }

    public function deleteTable($name)
    {
        $targetTableName = TableAliasHelper::encode('default', $name);
        $schema = Manager::schema();
        $schema->drop($targetTableName);
    }

    public function saveData($name, Collection $collection)
    {
        $targetTableName = TableAliasHelper::encode('default', $name);
        $queryBuilder = Manager::table($targetTableName);
        $queryBuilder->truncate();
        $data = ArrayHelper::toArray($collection);
        $queryBuilder->insert($data);
        $this->resetAutoIncrement($name);
    }

    public function loadData($name) : Collection
    {
        $targetTableName = TableAliasHelper::encode('default', $name);
        $queryBuilder = Manager::table($targetTableName);
        $data = $queryBuilder->get()->toArray();
        return new Collection($data);
    }

    public function allTables() : Collection
    {
        /* @var Builder|MySqlBuilder|PostgresBuilder $schema */
        $schema = Manager::schema();
        $dbName = $schema->getConnection()->getDatabaseName();
        $array = $schema->getAllTables();
        $collection = new Collection;
        foreach ($array as $item) {
            $key = 'Tables_in_' . $dbName;
            $targetTableName = $item->{$key};
            $sourceTableName = TableAliasHelper::decode('default', $targetTableName);
            $entity = $this->forgeEntity([
                'name' => $sourceTableName,
            ]);
            $collection->add($entity);
        }
        return $collection;
    }

    private function resetAutoIncrement($name) {
        $targetTableName = TableAliasHelper::encode('default', $name);
        $schema = Manager::schema();
        $queryBuilder = Manager::table($targetTableName);
        $driver = $schema->getConnection()->getConfig('driver');
        if($driver == DbDriverEnum::PGSQL) {
            $max = $queryBuilder->max('id');
            if($max) {
                $pkName = 'id';
                $sql = 'SELECT setval(\''.$targetTableName.'_'.$pkName.'_seq\', '.($max+1).')';
                $connection = $queryBuilder->getConnection();
                $connection->statement($sql);
            }
        }
    }

}