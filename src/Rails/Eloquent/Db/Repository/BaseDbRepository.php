<?php

namespace App\Rails\Eloquent\Db\Repository;

use App\Rails\Domain\Repository\BaseRepository;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Query\Builder;
use php7extension\core\exceptions\NotFoundException;

abstract class BaseDbRepository extends BaseRepository
{

    public $connectionName = 'default';
    public $tableName;
    public $autoIncrement = 'id';

    public function autoIncrement()
    {
        return $this->autoIncrement;
    }

    public function connectionName()
    {
        return $this->connectionName;
    }

    public function tableName()
    {
        return $this->tableName;
    }

    protected function getQueryBuilder() : Builder
    {
        $queryBuilder = Manager::table($this->tableName(), null, $this->connectionName());
        return $queryBuilder;
    }

    protected function getSchema() : \Illuminate\Database\Schema\Builder
    {
        $schema = Manager::schema($this->connectionName());
        return $schema;
    }

    protected function allByBuilder(Builder $queryBuilder) {
        $postCollection = $queryBuilder->get();
        $array = $postCollection->toArray();
        return $this->forgeEntityCollection($array);
    }

    protected function oneByBuilder(Builder $queryBuilder) {
        $item = $queryBuilder->first();
        if(empty($item)) {
            throw new NotFoundException('Not found entity!');
        }
        return $this->forgeEntity($item);
    }

}
