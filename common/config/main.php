<?php

use common\components\SendMessageConsumer;
use mikemadisonweb\rabbitmq\controllers\RabbitMQController;
use  mikemadisonweb\rabbitmq\components\Consumer;
use yii\i18n;

return [
    'language' => 'en-US',
//    'i18n' => [
//        'translations' => [
//            'app*' => [
//                'class' => 'yii\i18n\PhpMessageSource',
//                'sourceLanguage' => 'ru-RU',
//            ],
//        ],
//    ],
    'charset' => 'utf-8',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=portal',
            'username' => 'admin',
            'password' => 'admin',
            'charset' => 'utf8',
        ],
        'rabbitmq' => require( __DIR__ . '/rabbitmq.php'),

//        'rabbitMQ' => [
//            'class' => 'application.components.RabbitMQ.RabbitMQ',
//            'server' => array(
//                'host' => 'localhost',
//                'port' => '5672',
//                'vhost' => '/',
//                'user' => 'guest',
//                'password' => 'guest'
//            )
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'www.disigner@yandex.ru',
                'password' => 'cpytbivtybyihlre',
                'port' => 465,
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
    ],



//    'controllerMap' => [
//        'rabbitmq-consumer' => mikemadisonweb\rabbitmq\controllers\RabbitMQController::class,
//        'rabbitmq-producer' => mikemadisonweb\rabbitmq\controllers\RabbitMQController::class,
//    ],

    'controllerMap' => [
//        'rabbitmq-consumer' => \mikemadisonweb\rabbitmq\controllers\ConsumerController::className(),
//        'rabbitmq-producer' => \mikemadisonweb\rabbitmq\controllers\ProducerController::className(),
        'rabbitmq-consumer' => \mikemadisonweb\rabbitmq\controllers\ConsumerController::class
    ],
];
