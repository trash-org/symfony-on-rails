<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\CategoryEntity;
use App\Rails\Domain\Interfaces\GetEntityClassInterface;
use App\Rails\Eloquent\Db\Repository\BaseDbCrudRepository;

class CategoryRepository extends BaseDbCrudRepository implements /*PostRepositoryInterface,*/ GetEntityClassInterface
{

    public $tableName = 'article_category';
    public $entityClass = CategoryEntity::class;

}