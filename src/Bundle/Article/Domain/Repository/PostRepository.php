<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\Post;
use App\Bundle\Article\Domain\Interfaces\PostRepositoryInterface;
use App\Rails\Domain\Db\BaseDbCrudRepository;
use App\Rails\Domain\Interfaces\GetEntityClassInterface;

class PostRepository extends BaseDbCrudRepository implements PostRepositoryInterface, GetEntityClassInterface
{

    public $tableName = 'post';
    public $entityClass = Post::class;

}