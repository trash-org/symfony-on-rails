<?php

namespace App\Bundle\Article\Api\Controller;

use PhpLab\Rest\Controller\BaseCrudApiController;
use App\Bundle\Article\Domain\Interfaces\PostServiceInterface;

class ArticleController extends BaseCrudApiController
{

    public function __construct(PostServiceInterface $postService)
    {
        $this->service = $postService;
    }

}
