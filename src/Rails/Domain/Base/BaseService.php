<?php

namespace App\Rails\Domain\Base;

use App\Rails\Domain\Db\BaseRepository;
use App\Rails\Domain\Interfaces\GetEntityClassInterface;

abstract class BaseService implements GetEntityClassInterface
{

    protected $repository;

    /**
     * @return BaseRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    public function getEntityClass() : string
    {
        return $this->getRepository()->getEntityClass();
    }
}