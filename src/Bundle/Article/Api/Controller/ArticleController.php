<?php

namespace App\Bundle\Article\Api\Controller;

use App\Bundle\Article\Domain\Interfaces\PostServiceInterface;
use PhpLab\Rest\Controller\BaseCrudApiController;

class ArticleController extends BaseCrudApiController
{

    public function __construct(PostServiceInterface $postService)
    {
        $this->service = $postService;
    }

}
