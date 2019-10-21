<?php

namespace App\Rails\Domain\Service;

use App\Rails\Domain\Repository\BaseRepository;
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