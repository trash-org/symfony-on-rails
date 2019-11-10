<?php

use App\Bundle\Article\Api\Controller\ArticleController;
use App\Bundle\Article\Domain\Repository\CategoryRepository;
use App\Bundle\Article\Domain\Repository\PostRepository;
use App\Bundle\Article\Domain\Service\PostService;
use PhpLab\Eloquent\Db\Helper\Manager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/php7lab/domain/src/Php/bootstrap.php';

$eloquentConfigFile = $_ENV['ELOQUENT_CONFIG_FILE'];
$capsule = new Manager(null, $eloquentConfigFile);

$categoryRepository = new CategoryRepository($capsule);
$postRepository = new PostRepository($capsule, $categoryRepository);
$postService = new PostService($postRepository);

$request = Request::createFromGlobals();
$path = $request->getPathInfo();
$path = trim($path, '/');

if (preg_match('/api\/v1\/article\/([\s\S]+)$/i', $path, $matches) && $request->isMethod('get')) {
    $controller = new ArticleController($postService);
    $response = $controller->view($matches[1], $request);
} elseif (preg_match('/api\/v1\/article$/i', $path, $matches) && $request->isMethod('get')) {
    $controller = new ArticleController($postService);
    $response = $controller->index($request);
} else {
    $response = new JsonResponse(['message' => 'not found'], 404);
}

$response->send();
