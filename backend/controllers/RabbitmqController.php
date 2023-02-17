<?php

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use common\traits\BreadcrumbsTrait;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use console\models\Commands;

class RabbitmqController extends Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * connection rabbit
     *
     * @return int
     * @throws \Exception
     */
    public function actionRabbitmq()
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
        $channel->exchange_declare('commands', 'fanout', false, false, false);
        $msg = new AMQPMessage($data['data']['command']);
        $channel->basic_publish($msg, 'commands');
        if ($channel->close() === null && $connection->close() === null) {
            return 1;
        }

        return 0;
    }

    public function actionPdf()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        return $data;
    }

}
