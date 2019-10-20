<?php

$config = [
	'servers' => [
		'db' => [
			'main' => [
				'map' => [

                    'reference_book' => 'reference.book',
                    'reference_item' => 'reference.item',
                    'reference_item_localization' => 'reference.item_localization',

                    'geo_country' => 'geo.country',
                    'geo_region' => 'geo.region',
                    'geo_city' => 'geo.city',
                    'geo_currency' => 'geo.currency',
                    'geo_currency_value' => 'geo.currency_value',

                    'rest_collection' => 'dev_yutpl.rest',

                    'company' => 'staff.company',
                    'staff_company' => 'staff.company',
                    'staff_division' => 'staff.divisions',
                    'staff_worker' => 'staff.workers',

                    'migration' => 'common.migration',

                    'language' => 'i18n.language',
                    'localization_domain' => 'i18n.domain',
                    'localization_entity' => 'i18n.entity',
                    'localization_field' => 'i18n.field',
                    'localization_content' => 'i18n.content',

                    //'portal_client' => 'staff.client',

                    'user_assignment' => 'user.assignment',
                    'user_confirm' => 'user.confirm',
                    'user_person' => 'user.person',
                    'user_login' => 'user.identity',
                    'user_identity' => 'user.identity',
                    'user_security' => 'user.security',
                    'settings_personal' => 'user.settings',

                    //'user' => '_trash.user',
                    //'user_activity' => '_trash.user_activity',
                    //'user_security' => '_trash.user_security',
                    //'user_token' => '_trash.user_token',

				],
			],
        ],
	],
	'client' => [
		'defaultTimeZone' => 'Asia/Almaty',
	],
    'config' => [
        'map' => [
            'params' => [
                'config/rails/params',
                'config/rails/params-local',
                //'{app}/config/params',
                //'{app}/config/params-local',
            ],
            'domain' => [
                'config/rails/domain',
                //'{app}/config/domain',
            ],
            'main' => [
                'config/rails/main',
                'config/rails/main-local',
                //'{app}/config/main',
                //'{app}/config/main-local',
            ],
            'modules' => [
                //'{app}/config/modules',
                //'{app}/config/modules-local',
            ],
            'bootstrap' => [
                'config/rails/bootstrap',
                //'{app}/config/bootstrap',
            ],
        ],
        'filters' => [
            'migration' => [
                'path' => [
                    '@vendor/yii2bundle/yii2-geo/src/domain/migrations',
                    '@vendor/yii2bundle/yii2-i18n/src/domain/migrations',
                    '@vendor/yii2bundle/yii2-model/src/domain/migrations',
                    '@vendor/yii2bundle/yii2-settings/src/domain/v1/migrations',
                ],
            ],
        ]
    ]
];

//return $config;

$configFile = __DIR__ . '/../../vendor/php7rails/app/src/config/env.php';
return \php7extension\core\helpers\Helper::includeConfig($configFile, $config);