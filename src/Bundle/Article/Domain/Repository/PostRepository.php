<?php

namespace App\Bundle\Article\Domain\Repository;

use App\Bundle\Article\Domain\Entity\PostEntity;
use App\Bundle\Article\Domain\Interfaces\PostRepositoryInterface;
use php7rails\domain\enums\RelationEnum;
use PhpLab\Domain\Interfaces\GetEntityClassInterface;
use PhpLab\Eloquent\Db\Repository\BaseEloquentCrudRepository;

class PostRepository extends BaseEloquentCrudRepository implements PostRepositoryInterface, GetEntityClassInterface
{

    protected $tableName = 'article_post';
    protected $entityClass = PostEntity::class;
    private $categoryRepository;

    public function __construct(\PhpLab\Eloquent\Db\Helper\Manager $capsule, CategoryRepository $categoryRepository)
    {
        parent::__construct($capsule);
        $this->categoryRepository = $categoryRepository;
    }

    public function relations()
    {
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