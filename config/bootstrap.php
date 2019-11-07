<?php

use App\Rails\Eloquent\Db\Helper\ManagerFactory;
use php7rails\app\helpers\Constant;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Load cached env vars if the .env.local.php file exists
// Run "composer dump-env prod" to create it (requires symfony/flex >=1.2)
if (is_array($env = @include dirname(__DIR__).'/.env.local.php')) {
    foreach ($env as $k => $v) {
        $_ENV[$k] = $_ENV[$k] ?? (isset($_SERVER[$k]) && 0 !== strpos($k, 'HTTP_') ? $_SERVER[$k] : $v);
    }
} elseif (!class_exists(Dotenv::class)) {
    throw new RuntimeException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
} else {
    // load all the .env files
    (new Dotenv(false))->loadEnv(dirname(__DIR__).'/.env');
}

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $_SERVER['APP_DEBUG'] || filter_var($_SERVER['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';

// todo: выпилить костыли (менеджер соединений создавать с помощью контейнера DI)
// читаем конфиг БД и создаем менеджер соединений Eloquent (глобально)
$connectionConfig = include (__DIR__ . '/../config/eloquent/main.php');
ManagerFactory::createManager($connectionConfig);

/** Подключение рельсов */
include_once(__DIR__ . '/../vendor/php7rails/app/src/libs/Boot.php');
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
Constant::init();