<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'main'
        ]
    ],
    'components' => [
        'request' => [
            'baseUrl' => '/admin',
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            // настройка красиваого рест апи
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,

//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'comment',
                    'pluralize' => false
                ],
                // REST patterns
//                array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
//                array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
//                array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
//                array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
//                array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
            ],
        ],

        'urlManagerBackend' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/backend/web',
        ],
//        подключение websocket
        'websocket' => [
            'class' => '\yiiplus\websocket\<dirver>\WebSocket',
            'host' => '127.0.0.1',
            'port' => 9501,
            'channels' => [
                'push-message' => '\socket\channels\PushMessageChannel',  // Configure the execution class corresponding to channel
            ],
        ],
    ],
    'params' => $params,
];
