<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\CategoryEntity;
use PhpLab\Domain\Interfaces\GetEntityClassInterface;
use PhpLab\Eloquent\Db\Repository\BaseEloquentCrudRepository;

class CategoryRepository extends BaseEloquentCrudRepository implements GetEntityClassInterface
{

    protected $tableName = 'article_category';
    protected $entityClass = CategoryEntity::class;

}