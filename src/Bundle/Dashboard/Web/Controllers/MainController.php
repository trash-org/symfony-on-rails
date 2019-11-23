<?php

namespace App\Bundle\Dashboard\Web\Controllers;

use php7rails\domain\data\Query;
use PhpExample\Bundle\Article\Domain\Interfaces\PostServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{

    private $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        //$user = $this->container->get('security.token_storage')->getToken()->getUser();
        //dd($user);

        $query = new Query;
        $query->with('category');
        $query->perPage(5);
        $postCollection = $this->postService->all($query);
        return $this->render('dashboard/index.html.twig', [
            'postCollection' => $postCollection,
            'links' => [
                [
                    'title' => 'API - auth',
                    'url' => '/api/v1/auth',
                ],
                [
                    'title' => 'API - rbac',
                    'url' => '/api/v1/rbac',
                ],
                [
                    'title' => 'API - article',
                    'url' => '/api/v1/article',
                ],
                [
                    'title' => 'API - messenger-chat',
                    'url' => '/api/v1/messenger-chat',
                ],
                [
                    'title' => 'API - article (PHP)',
                    'url' => '/php/v1/article',
                ],

                [
                    'title' => 'rails',
                    'url' => '/rails',
                ],
                [
                    'title' => 'SPA',
                    'url' => '/spa',
                ],
                [
                    'title' => 'FOS - register',
                    'url' => '/register',
                ],
                [
                    'title' => 'FOS - login',
                    'url' => '/login',
                ],
            ],
        ]);
    }

}
