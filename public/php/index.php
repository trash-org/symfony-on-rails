<?php

use PhpLab\Sandbox\Article\Api\Controllers\ArticleController;
use PhpLab\Sandbox\Article\Domain\Repositories\Eloquent\CategoryRepository;
use PhpLab\Sandbox\Article\Domain\Repositories\Eloquent\PostRepository;
use PhpLab\Sandbox\Article\Domain\Repositories\Eloquent\TagPostRepository;
use PhpLab\Sandbox\Article\Domain\Repositories\Eloquent\TagRepository;
use PhpLab\Sandbox\Article\Domain\Services\PostService;
use PhpLab\Eloquent\Db\Helpers\Manager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/../../vendor/php7lab/domain/src/Php/bootstrap.php';

// init DB
$eloquentConfigFile = $_ENV['ELOQUENT_CONFIG_FILE'];
$capsule = new Manager(null, $eloquentConfigFile);

// create service
$categoryRepository = new CategoryRepository($capsule);
$tagRepository = new TagRepository($capsule);
$tagPostRepository = new TagPostRepository($capsule);
$postRepository = new PostRepository($capsule, $categoryRepository, $tagRepository, $tagPostRepository);
$postService = new PostService($postRepository);

// define routes
$routes = new RouteCollection;

$route = new Route('/v1/article/{id}', ['_controller' => ArticleController::class, '_action' => 'view'], ['id'], [], null,[], ['GET']);
$routes->add('article_view', $route);

$route = new Route('/v1/article/{id}', ['_controller' => ArticleController::class, '_action' => 'delete'], ['id'], [], null,[], ['DELETE']);
$routes->add('article_delete', $route);

$route = new Route('/v1/article/{id}', ['_controller' => ArticleController::class, '_action' => 'update'], ['id'], [], null,[], ['PUT']);
$routes->add('article_update', $route);

$route = new Route('/v1/article', ['_controller' => ArticleController::class, '_action' => 'index'], [], [], null,[], ['GET']);
$routes->add('article_index', $route);

$route = new Route('/v1/article', ['_controller' => ArticleController::class, '_action' => 'create'], [], [], null,[], ['POST']);
$routes->add('article_create', $route);

$context = new RequestContext('/');
$matcher = new UrlMatcher($routes, $context);
try {
    $request = Request::createFromGlobals();
    $parameters = $matcher->match($request->getPathInfo());
    $controllerClass = $parameters['_controller'];
    $controllerMethod = $parameters['_action'];
    $controllerInstance = new $controllerClass($postService);
    if(in_array($controllerMethod, ['view', 'update', 'delete'])) {
        $id = $parameters['id'];
        $params = [$id, $request];
    } elseif (in_array($controllerMethod, ['index', 'create'])) {
        $params = [$request];
    }
    $callback = [$controllerInstance, $controllerMethod];
    $response = call_user_func_array($callback, $params);
} catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    $response = new JsonResponse(['message' => 'ResourceNotFound'], 404);
}catch (\Symfony\Component\Routing\Exception\MethodNotAllowedException $e) {
    $response = new JsonResponse(['message' => 'MethodNotAllowed'], 405);
}

$response->send();
