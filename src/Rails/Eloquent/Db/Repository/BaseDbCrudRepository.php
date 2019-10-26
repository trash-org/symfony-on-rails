<?php

namespace App\Rails\Eloquent\Db\Repository;

use App\Rails\Domain\Interfaces\CrudRepositoryInterface;
use App\Rails\Domain\Interfaces\CrudServiceInterface;
use App\Rails\Domain\Interfaces\RelationConfigInterface;
use App\Rails\Eloquent\Db\Helper\QueryBuilderHelper;
use App\Rails\Eloquent\Db\Helper\QueryFilter;
use Illuminate\Database\Capsule\Manager;
use php7extension\core\exceptions\NotFoundException;
use php7extension\core\helpers\ClassHelper;
use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\BaseEntityWithId;
use php7rails\domain\data\Query;
use App\Rails\Domain\Helper\Repository\RelationWithHelper;

abstract class BaseDbCrudRepository extends BaseDbRepository implements CrudRepositoryInterface
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
        /*$query = Query::forge($query);
        $queryBuilder = $this->getQueryBuilder();
        QueryBuilderHelper::setWhere($query, $queryBuilder);
        QueryBuilderHelper::setSelect($query, $queryBuilder);
        return $this->oneByBuilder($queryBuilder);*/
    }

    /**
     * @param BaseEntityWithId $entity
     */
    public function create($entity)
    {
        $schema = $this->getSchema();
        $columnList = $schema->getColumnListing($this->tableName());

        //print_r();die();

        $queryBuilder = $this->getQueryBuilder();

        $lastId = $queryBuilder->insertGetId($entity->toArray($columnList));
        $entity->id = $lastId;
    }

    public function updateById($id, $data)
    {
        $schema = $this->getSchema();
        $columnList = $schema->getColumnListing($this->tableName());

        $data = ArrayHelper::extractByKeys($data, $columnList);

        //print_r($data);die();

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