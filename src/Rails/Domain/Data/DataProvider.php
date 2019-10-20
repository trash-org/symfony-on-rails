<?php

namespace App\Rails\Domain\Data;

use App\Rails\Domain\Interfaces\ReadAllServiceInterface;
use php7extension\yii\helpers\ArrayHelper;
use php7rails\domain\data\Query;

class DataProvider
{

    /** @var ReadAllServiceInterface */
    private $service;

    /** @var Query */
    private $query;

    /** @var DataProviderEntity */
    private $entity;

    public function __construct(array $config)
    {
        $this->service = ArrayHelper::getValue($config, 'service');
        $this->query = ArrayHelper::getValue($config, 'query', Query::forge());

        $this->entity = new DataProviderEntity;
        $this->entity->page = intval(ArrayHelper::getValue($config, 'page', 1));
        $this->entity->pageSize = intval(ArrayHelper::getValue($config, 'pageSize', 10));
        $this->entity->maxPageSize = intval(ArrayHelper::getValue($config, 'maxPageSize', 50));
    }

    public function getAll() : DataProviderEntity
    {
        $this->entity->totalCount = $this->getTotalCount();
        $this->entity->collection = $this->getCollection();
        return $this->entity;
    }

    private function getCollection() : array {
        if( ! isset($this->entity->collection)) {
            $query = clone $this->query;
            $query->limit($this->entity->pageSize);
            $query->offset($this->entity->pageSize * ($this->entity->page - 1));
            $this->entity->collection = $this->service->all($query);
        }
        return $this->entity->collection;
    }

    private function getTotalCount() : int {
        if( ! isset($this->entity->totalCount)) {
            $query = clone $this->query;
            $query->removeParam(Query::PER_PAGE);
            $query->removeParam(Query::LIMIT);
            $query->removeParam(Query::ORDER);
            $this->entity->totalCount = $this->service->count($query);
        }
        return $this->entity->totalCount;
    }

}