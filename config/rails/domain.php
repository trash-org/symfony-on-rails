<?php

use php7rails\app\helpers\Env;

$domains = [
    'rbac' => 'php7extension\bundle\rbac\domain\Domain',
    'package' =>  'php7extension\core\package\domain\Domain',
    'vendor' =>  'php7tool\vendor\domain\Domain',
    'guide' => 'php7extension\bundle\guide\domain\Domain',

    'notify' => 'php7extension\bundle\notify\domain\Domain',
    'lang' => 'php7extension\bundle\lang\domain\Domain',
    'navigation' => 'php7extension\bundle\navigation\domain\Domain',
    'model' => 'php7extension\bundle\model\domain\Domain',
    'settings' => 'php7extension\bundle\settings\domain\v1\Domain',
    //'account' => 'php7extension\bundle\account\domain\v3\Domain',

    'account' => 'php7extension\bundle\account\domain\v3\Domain',
    'user' => 'yubundle\bundle\user\domain\v1\Domain',

    //'i18n' => 'php7extension\bundle\i18n\domain\Domain',

    'log' => 'php7extension\psr\log\Domain',

    /*'account' => \yubundle\bundle\account\domain\v2\helpers\DomainHelper::config(),
    'geo' => 'php7extension\bundle\geo\domain\Domain',
    'storage' => 'yubundle\bundle\storage\domain\v1\Domain',
    'reference' => 'yubundle\bundle\reference\domain\Domain',
    'user' => 'yubundle\bundle\user\domain\v1\Domain',
    'partner' => 'yubundle\bundle\common\partner\Domain',
    'staff' => 'yubundle\bundle\staff\domain\v1\Domain',*/

    // deprecated:
    //'jwt' => 'php7extension\core\jwt\Domain',

];

$domainsNew =  [
    'component' => [
        /*'session' => [
            'class' => 'Symfony\Component\HttpFoundation\Session\Session',
            '@call_methods' => [
                'start',
            ],
        ],*/
        'request' => \Symfony\Component\HttpFoundation\Request::createFromGlobals(),
        //'response' => 'Symfony\Component\HttpFoundation\Response',
        /*'cache' => [
            'class' => 'Symfony\Component\Cache\Adapter\FilesystemAdapter',
            '@construct_params' => ['', 0, ROOT_DIR . '/common/runtime/symfonyCache'],
        ],*/
        'user' => [
            'class' => 'php7extension\bundle\account\domain\v3\web\User',
            'enableSession' => false,
        ],
        'i18n' => [
            'class' => 'php7extension\core\i18n\i18n\I18N',
            'aliases' => [
                '*' => '@common/messages',
            ],
        ],
        //'security' => 'php7extension\yii\base\Security',
    ],

    'db' => [
        'class' => 'php7extension\core\db\domain\services\ConnectionService',
        'profiles' => Env::get('servers.db'),
    ],

    /*'crypt' => [
        'jwt' => [
            'class' => 'php7extension\crypt\domain\services\JwtService',
            'profiles' => Env::get('encrypt.profiles'),
        ],
    ],*/
];

$domains = \php7rails\domain\helpers\DomainHelper::normalizeDefinitions($domains);
/*d($domains['log']);
\App::$container->register('log', $domains['log']);*/

return array_merge($domains, $domainsNew);
