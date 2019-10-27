<?php

/** Подключение рельсов */
include_once(__DIR__ . '/../../vendor/php7rails/app/src/libs/Boot.php');
$boot = new \php7rails\app\libs\Boot;
$boot->appName = 'frontend';
$boot->init();
$boot->loadConfig([
    'config/rails/env',
    'config/rails/env-local',
]);
$boot->setAliases([
    '@yubundle/bundle' => 'vendor/yubundle/bundle/src',
]);
/** Подключение рельсов */


use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/../config/bootstrap.php';

\php7extension\core\web\helpers\CorsHelper::autoload();

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
