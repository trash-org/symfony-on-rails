<?php

namespace App\Rails\Domain\Traits;

use App\Rails\Domain\Helper\EntityHelper;

trait ForgeEntityTrait
{

    public $entityClass;

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    protected function forgeEntityCollection($array) {
        return EntityHelper::createEntityCollection($this->getEntityClass(), $array);
    }

    protected function forgeEntity($item) {
        return EntityHelper::createEntity($this->getEntityClass(), $item);
    }

}