<?php

namespace console\controllers;

use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use console\models\Commands;

class ServerrabbitmqController extends Controller
{
    public function actionStart()
    {
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
        list($queueName, , ) = $channel->queue_declare('', false, false, true, false);
        $channel->queue_bind($queueName, 'commands');

        echo  " [*] Ожидание команды. Для выхода нажмите CTRL+C\n" ;

        $callback = function ($msg)
        {
            $nameCommand = $msg->body;
            $commands = new Commands();
            if (method_exists($commands, $msg->body)) {
                $commands->$nameCommand();
            } else {
                echo "Данного метода не существует" . PHP_EOL;
            }
        };

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);
        while($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}