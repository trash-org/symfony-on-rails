<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\PostEntity;
use App\Bundle\Article\Domain\Interfaces\PostRepositoryInterface;
use PhpLab\Domain\Interfaces\GetEntityClassInterface;
use PhpLab\Eloquent\Db\Repository\BaseDbCrudRepository;
use php7rails\domain\enums\RelationEnum;

class PostRepository extends BaseDbCrudRepository implements PostRepositoryInterface, GetEntityClassInterface
{

    protected $tableName = 'article_post';
    protected $entityClass = PostEntity::class;
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function relations() {
        return [
            'category' => [
                'type' => RelationEnum::ONE,
                'field' => 'category_id',
                'foreign' => [
                    'model' => $this->categoryRepository,
                    'field' => 'id',
                ],
            ],
        ];
    }

}