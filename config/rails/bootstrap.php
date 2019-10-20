<?php

use php7rails\app\helpers\Env;

\App::$container->register('security', 'php7extension\yii\base\Security');
\App::$container->register('session', 'Symfony\Component\HttpFoundation\Session\Session')
    ->addMethodCall('start');
\App::$container->register('response', 'Symfony\Component\HttpFoundation\Response');
\App::$container->register('cache', 'Symfony\Component\Cache\Adapter\FilesystemAdapter')
    ->addArgument('')
    ->addArgument(0)
    ->addArgument(ROOT_DIR . '/common/runtime/symfonyCache');

\App::$container->register('jwt', 'php7extension\crypt\domain\services\JwtService')
    ->addMethodCall('setProfiles', [Env::get('encrypt.profiles')]);

/*\App::$container->register('db', 'php7extension\core\db\domain\services\ConnectionService')
    ->addMethodCall('setProfiles', \php7rails\app\helpers\Env::get('servers.db'));*/














/*$domainDefinition = \php7rails\domain\helpers\DomainHelper::getClassConfig('log', 'php7extension\psr\log\Domain');

//d(\php7extension\core\helpers\ClassHelper::createObject($domainDefinition));

//\php7rails\domain\helpers\DomainHelper::getConfigFromDomainClass()

\App::$container->register('log', \php7extension\core\helpers\ClassHelper::createObject($domainDefinition));*/
