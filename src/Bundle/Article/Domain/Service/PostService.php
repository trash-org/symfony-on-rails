<?php

namespace App\Bundle\Article\Domain\Service;

use App\Bundle\Article\Domain\Interfaces\PostRepositoryInterface;
use App\Bundle\Article\Domain\Interfaces\PostServiceInterface;
use App\Rails\Domain\Base\BaseCrudService;
use App\Rails\Domain\Interfaces\GetEntityClassInterface;

/**
 * Class PostService
 * @package App\Bundle\Article\Domain\Service
 *
 * @property PostRepositoryInterface | GetEntityClassInterface $repository
 */
class PostService extends BaseCrudService implements PostServiceInterface
{

    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

}