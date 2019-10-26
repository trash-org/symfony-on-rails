<?php

namespace App\Bundle\Article\Domain\Entity;

use php7rails\domain\BaseEntity;

class PostEntity extends BaseEntity
{

    protected $id;
    protected $category_id;
    protected $title;
    protected $category;

}
