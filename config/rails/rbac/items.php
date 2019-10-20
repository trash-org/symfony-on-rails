<?php
return [
    'rAdministrator' => [
        'type' => 1,
        'description' => 'Администратор системы',
        'children' => [
            'rUser',
            'rGuest',
            'rUnknownUser',
            'rDeveloper',
            'oRestClientAll',
            'oGiiManage',
            'oLangManage',
            'oRbacManage',
            'oBackendAll',
            'oLogreaderManage',
            'oOfflineManage',
            'oCleanerManage',
            'oNotifyManage',
            'oEncryptManage',
            'oVendorManage',
            'oFileManage',
            'oGuideModify',
            'oStorageManage',
            'oArticlePostManage',
            'oModelManage',
            'oAccountManage',
        ],
    ],
    'rUser' => [
        'type' => 1,
        'description' => 'Идентифицированный пользователь',
        'children' => [
            'oStorageFileUpload',
            'rGuest',
        ],
    ],
    'rGuest' => [
        'type' => 1,
        'description' => 'Гость системы',
        'children' => [
            'oStorageFileDownload',
            'oArticlePostRead',
        ],
    ],
    'rUnknownUser' => [
        'type' => 1,
        'description' => 'Не идентифицированный пользователь',
        'children' => [
            'rGuest',
            'oStorageFileUpload',
            'rUser',
        ],
    ],
    'rDeveloper' => [
        'type' => 1,
        'description' => 'Разработчик',
        'children' => [
            'oRestClientAll',
            'oGiiManage',
            'oRbacManage',
            'oBackendAll',
        ],
    ],
    'oRestClientAll' => [
        'type' => 2,
        'description' => 'Доступ к REST-клиенту',
    ],
    'oGiiManage' => [
        'type' => 2,
        'description' => 'Доступ к Yii генератору',
    ],
    'oLangManage' => [
        'type' => 2,
        'description' => 'Управление языками',
    ],
    'oRbacManage' => [
        'type' => 2,
        'description' => 'Управление RBAC',
    ],
    'oBackendAll' => [
        'type' => 2,
        'description' => 'Доступ в админ панель',
    ],
    'oLogreaderManage' => [
        'type' => 2,
        'description' => 'Управление логами',
    ],
    'oOfflineManage' => [
        'type' => 2,
        'description' => 'Управление статусом оффлайн',
    ],
    'oCleanerManage' => [
        'type' => 2,
        'description' => 'Управление чистильщиком',
    ],
    'oNotifyManage' => [
        'type' => 2,
        'description' => 'Управление уведомлениями',
    ],
    'oEncryptManage' => [
        'type' => 2,
        'description' => 'Шифрование данных',
    ],
    'oVendorManage' => [
        'type' => 2,
        'description' => 'Управление композер-пакетами',
    ],
    'oFileManage' => [
        'type' => 2,
        'description' => 'Управление файлами',
    ],
    'oGuideModify' => [
        'type' => 2,
        'description' => 'Редактирование руководства',
    ],
    'oStorageManage' => [
        'type' => 2,
        'description' => 'Управление файловым хранилищем',
    ],
    'oArticlePostManage' => [
        'type' => 2,
        'description' => 'Управление статьями сайта',
    ],
    'oStorageFileDownload' => [
        'type' => 2,
        'description' => 'Скачивание файлов',
    ],
    'oStorageFileUpload' => [
        'type' => 2,
        'description' => 'Загрузка файлов',
    ],
    'oStaffManage' => [
        'type' => 2,
        'description' => 'Управление корпоративной частью почты',
    ],
    'oModelManage' => [
        'type' => 2,
        'description' => 'Управление мета-моделями',
    ],
    'oArticleCategoryManage' => [
        'type' => 2,
        'description' => 'Управление категориями',
    ],
    'oArticleCategoryRead' => [
        'type' => 2,
        'description' => 'Просмотр категорий статей',
    ],
    'oArticlePostRead' => [
        'type' => 2,
        'description' => 'Чтение статьи',
    ],
    'oAccountManage' => [
        'type' => 2,
        'description' => 'Управление пользователями',
    ],
];
