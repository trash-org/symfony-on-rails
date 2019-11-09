<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\CategoryEntity;
use PhpLab\Domain\Interfaces\GetEntityClassInterface;
use PhpLab\Eloquent\Db\Repository\BaseDbCrudRepository;

class CategoryRepository extends BaseDbCrudRepository implements /*PostRepositoryInterface,*/ GetEntityClassInterface
{

    protected $tableName = 'article_category';
    protected $entityClass = CategoryEntity::class;

}