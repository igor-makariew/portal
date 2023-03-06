<?php

namespace console\controllers;

use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use console\models\Commands;

class ServerrabbitmqrpcController extends Controller
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
        $channel->queue_declare('rpc_queue', false, false, false, false);

        echo " [x] Awaiting RPC requests\n";

        $callback = function($request)
        {
            $num = intval($request->body);
            $commands = new Commands();

            echo 'correlation_id - ' . $request->get('correlation_id') . ' [.] fibonachi(', $num, ")\n";

            $msg = new AMQPMessage(
                (string) $commands->fibonachi($num),
                array(
                    'correlation_id' => $request->get('correlation_id')
                )
            );

            /*
             * Публикация в тот же канал, из которого пришло входящее сообщение
             */
            $request->delivery_info['channel']->basic_publish(
                $msg,
                '',
                $request->get('reply_to')
            );

            /*
             * Acknowledging the message
             */
            $request->ack();
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

        while($channel->is_open()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}