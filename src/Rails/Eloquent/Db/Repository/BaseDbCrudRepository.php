<?php

namespace App\Rails\Eloquent\Db\Repository;

use App\Rails\Domain\Interfaces\CrudServiceInterface;
use App\Rails\Eloquent\Db\Helper\QueryBuilderHelper;
use php7extension\core\helpers\ClassHelper;
use php7rails\domain\BaseEntityWithId;
use php7rails\domain\data\Query;

abstract class BaseDbCrudRepository extends BaseDbRepository implements CrudServiceInterface
{

    public function count(Query $query = null) : int
    {
        $query = Query::forge($query);
        $queryBuilder = $this->getQueryBuilder();
        QueryBuilderHelper::setWhere($query, $queryBuilder);
        return $queryBuilder->count();
    }

    public function all(Query $query = null)
    {
        $query = Query::forge($query);
        $queryBuilder = $this->getQueryBuilder();
        QueryBuilderHelper::setWhere($query, $queryBuilder);
        QueryBuilderHelper::setSelect($query, $queryBuilder);
        QueryBuilderHelper::setPaginate($query, $queryBuilder);
        return $this->allByBuilder($queryBuilder);
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