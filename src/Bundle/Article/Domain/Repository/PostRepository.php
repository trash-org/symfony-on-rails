<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\Post;
use App\Bundle\Article\Domain\Interfaces\PostRepositoryInterface;
use App\Rails\Domain\Interfaces\GetEntityClassInterface;
use App\Rails\Eloquent\Repository\BaseDbCrudRepository;

class PostRepository extends BaseDbCrudRepository implements PostRepositoryInterface, GetEntityClassInterface
{

    public $tableName = 'article_post';
    public $entityClass = Post::class;

}