<?php

namespace App\Rails\Eloquent\Db\Repository;

use App\Rails\Domain\Interfaces\CrudRepositoryInterface;
use App\Rails\Eloquent\Db\Helper\QueryBuilderHelper;
use App\Rails\Eloquent\Db\Helper\QueryFilter;
use php7extension\core\exceptions\NotFoundException;
use php7extension\core\helpers\ClassHelper;
use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\BaseEntityWithId;
use php7rails\domain\data\Query;

abstract class BaseDbCrudRepository extends BaseDbRepository implements CrudRepositoryInterface
{

    public function relations() {
        return [];
    }

    protected function queryFilterInstance(Query $query = null) {
        $query = Query::forge($query);
        /** @var QueryFilter $queryFilter */
        $queryFilter = new QueryFilter($this, $query);
        return $queryFilter;
    }

    public function count(Query $query = null) : int
    {
        $query = Query::forge($query);
        $queryBuilder = $this->getQueryBuilder();
        QueryBuilderHelper::setWhere($query, $queryBuilder);
        return $queryBuilder->count();
    }

    public function _all(Query $query = null)
    {
        $query = Query::forge($query);
        $queryBuilder = $this->getQueryBuilder();
        QueryBuilderHelper::setWhere($query, $queryBuilder);
        QueryBuilderHelper::setSelect($query, $queryBuilder);
        QueryBuilderHelper::setOrder($query, $queryBuilder);
        QueryBuilderHelper::setPaginate($query, $queryBuilder);
        $collection = $this->allByBuilder($queryBuilder);
        return $collection;
    }

    public function all(Query $query = null)
    {
        $query = Query::forge($query);
        $queryFilter = $this->queryFilterInstance($query);
        $queryWithoutRelations = $queryFilter->getQueryWithoutRelations();
        $collection = $this->_all($queryWithoutRelations);
        $collection = $queryFilter->loadRelations($collection);
        return $collection;

    }

    public function oneById($id, Query $query = null)
    {
        $query = Query::forge($query);
        $query->where('id', $id);
        return $this->one($query);
    }

    public function one(Query $query = null)
    {
        $query->limit(1);
        $collection = $this->all($query);
        if($collection->count() < 1) {
            throw new NotFoundException('Not found entity!');
        }
        return $collection->first();
    }

    /**
     * @param BaseEntityWithId $entity
     */
    public function create($entity)
    {
        $columnList = $this->getColumnsForModify();
        $queryBuilder = $this->getQueryBuilder();
        $lastId = $queryBuilder->insertGetId($entity->toArray($columnList));
        $entity->id = $lastId;
    }

    private function getColumnsForModify() {
        $schema = $this->getSchema();
        $columnList = $schema->getColumnListing($this->tableName());
        if($this->autoIncrement()) {
            ArrayHelper::removeByValue($this->autoIncrement(), $columnList);
        }
        return $columnList;
    }

    public function updateById($id, $data)
    {
        $columnList = $this->getColumnsForModify();
        $data = ArrayHelper::extractByKeys($data, $columnList);
        $entity = $this->oneById($id);
        ClassHelper::configure($entity, $data);
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->find($id);
        $queryBuilder->update($data);
    }

    public function deleteById($id)
    {
        $this->oneById($id);
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($id);
    }

}