<?php

namespace backend\controllers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use yii\web\Controller;
use Yii;

class RabbitController extends Controller
{
    public $enableCsrfValidation = false;
    public $messages = [];

    public function actionIndex()
    {
        return $this->render('index', [
//            'app' => Yii::getAlias('@vendor'),
            'app' => Yii::getAlias('/admin/images/uploadImages/'),
        ]);
    }
    
    /**
     * get messages RabbitMQ
     *
     * @return string
     * @throws \Exception
     */
    public function actionServer()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

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
        $channel->queue_declare('test-yii2', false, false, false, false);

//        $result = ($channel->basic_get('test-yii2', true, null)->body);

        $callback = function ($msg) {
            print_r($msg->body);
//            return $this->messages = $msg->body;
        };

        $channel->basic_consume('test-yii2', '', false, true, false, false, $callback);

//        return $channel->wait();
        return ;


    }

}
