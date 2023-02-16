<?php

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use common\traits\BreadcrumbsTrait;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqController extends Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRabbitmq()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
//        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $connection_params = array(
            'host' => 'localhost',
            'port' => 5672,
            'vhost' => '/',
            'login' => 'igor',
            'password' => 'admin'
        );

        $connection = new AMQPStreamConnection(
            $connection_params['host'],
            $connection_params['port'],
            $connection_params['login'],
            $connection_params['password'],
            $connection_params['vhost'],
        );

        $channel = $connection->channel();

        return $channel;
    }

}
