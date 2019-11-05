<?php

namespace App\Bundle\Article\Domain\Entity;

use App\Rails\Domain\Entity\BaseEntity;
use DateTime;

class PostEntity extends BaseEntity
{

    protected $id;
    protected $category_id;
    protected $title;
    protected $created_at;
    protected $category;

    public function setCreatedAt($value) {
        $this->created_at = new DateTime($value);
    }

}
