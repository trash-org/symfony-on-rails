<?php

namespace App\Rails\Domain\Db;

use Illuminate\Database\Query\Builder;
use php7extension\core\exceptions\NotFoundException;

abstract class BaseDbRepository extends BaseRepository
{

    public $connectionName = 'default';
    public $tableName;

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
        $queryBuilder = Connection::getQueryBuilder($this->connectionName());
        $queryBuilder->from('post');
        return $queryBuilder;
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
