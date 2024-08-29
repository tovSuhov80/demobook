<?php

use app\components\Notificator;
use app\components\senders\SmsPilotSenderService;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'Demobook',
    'name' => 'DemoBook',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\components\SetUp'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'MCzS058mBb8zelP1D0yNdwJqEJG10pZV',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
                'report/<year:\d+>' => 'report/index',
                'book/<year:\d+>' => 'book/index',
                'subscribe/<id:\d+>' => 'subscribe/add',
            ],
        ],

        'notificator' => [
            'class' => Notificator::class,
            'senders' => [
                SmsPilotSenderService::class,
            ]
        ],

        'userService' => [
            'class' => 'app\services\UserService',
        ],
        'authorService' => [
            'class' => 'app\services\AuthorService',
        ],
        'bookService' => [
            'class' => 'app\services\BookService',
        ],
        'subscribeService' => [
            'class' => 'app\services\SubscribeService',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
