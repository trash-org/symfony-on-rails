<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'links' => [
                [
                    'title' => 'Fist controller',
                    'url' => '/lucky/number',
                ],
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
                    'title' => 'rails',
                    'url' => '/rails',
                ],
            ],
        ]);
    }

}