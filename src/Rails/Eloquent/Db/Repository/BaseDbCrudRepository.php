<?php

namespace App\Rails\Eloquent\Db\Repository;

use App\Rails\Domain\Interfaces\CrudServiceInterface;
use App\Rails\Eloquent\Db\Helper\QueryBuilderHelper;
use App\Rails\Eloquent\Db\Helper\QueryFilter;
use php7extension\core\helpers\ClassHelper;
use php7rails\domain\BaseEntityWithId;
use php7rails\domain\data\Query;
use App\Rails\Domain\Helper\Repository\RelationWithHelper;

abstract class BaseDbCrudRepository extends BaseDbRepository implements CrudServiceInterface
{

    public function relations() {
        return [];
    }

    protected function queryFilterInstance(Query $query = null) {
        $query = Query::forge($query);
        /** @var QueryFilter $queryFilter */
        $queryFilter = new QueryFilter($this, $query);
        /*$queryFilter = ClassHelper::createObject([
            'class' => QueryFilter::class,
            'repository' => $this,
            'query' => $query,
        ]);*/
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
        QueryBuilderHelper::setPaginate($query, $queryBuilder);
        $collection = $this->allByBuilder($queryBuilder);
        return $collection;
    }

    public function all(Query $query = null)
    {
        $query = Query::forge($query);

        $queryFilter = $this->queryFilterInstance($query);
        $queryWithoutRelations = $queryFilter->getQueryWithoutRelations();
        //$this->with = RelationWithHelper::cleanWith($this->relations(), $query);

        $collection = $this->_all($queryWithoutRelations);

        //$collection = $this->forgeEntity($models);

        $collection = $queryFilter->loadRelations($collection);
        if(is_array($collection)) dd($collection);

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
        $query = Query::forge($query);
        $queryBuilder = $this->getQueryBuilder();
        QueryBuilderHelper::setWhere($query, $queryBuilder);
        QueryBuilderHelper::setSelect($query, $queryBuilder);
        return $this->oneByBuilder($queryBuilder);
    }

    /**
     * @param BaseEntityWithId $data
     */
    public function create($data)
    {
        $queryBuilder = $this->getQueryBuilder();
        $lastId = $queryBuilder->insertGetId($data->toArray());
        $data->id = $lastId;
    }

    public function updateById($id, $data)
    {
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