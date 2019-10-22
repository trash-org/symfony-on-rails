<?php

namespace App\Rails\Domain\Service;

use App\Rails\Domain\Interfaces\CrudServiceInterface;
use App\Rails\Eloquent\Db\Repository\BaseDbCrudRepository;
use php7extension\core\helpers\ClassHelper;
use php7rails\domain\data\Query;

/**
 * Class BaseCrudService
 * @package App\Rails\Domain\Service
 *
 * @method BaseDbCrudRepository getRepository()
 */
abstract class BaseCrudService extends BaseService implements CrudServiceInterface
{

    public function all(Query $query = null)
    {
        $query = Query::forge($query);
        $collection = $this->getRepository()->all($query);
        return $collection;
    }

    public function count(Query $query = null) : int
    {
        $query = Query::forge($query);
        return $this->getRepository()->count($query);
    }

    public function oneById($id, Query $query = null)
    {
        return $this->getRepository()->oneById($id, $query);
    }

    public function create($data)
    {
        $entityClass = $this->getEntityClass();
        $entity = new $entityClass;
        ClassHelper::configure($entity, $data);
        $this->getRepository()->create($entity);
        return $entity;
    }

    public function updateById($id, $data)
    {
        return $this->getRepository()->updateById($id, $data);
    }

    public function deleteById($id)
    {
        return $this->getRepository()->deleteById($id);
    }

}