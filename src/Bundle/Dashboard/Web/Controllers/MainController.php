<?php

namespace App\Bundle\Dashboard\Web\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function index()
    {
        return $this->render('main/index.html.twig', [
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
                    'title' => 'API - messenger-chat (PHP)',
                    'url' => '/php/v1/messenger-chat',
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
