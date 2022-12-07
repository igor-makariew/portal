<?php


namespace console\controllers;

use backend\daemons\ChatServer;
use yii\console\Controller;
use consik\yii2websocket\WebSocketServer;
use backend\daemons\TwitterServer;


class ServerController extends Controller
{
    public function actionStart($port = null)
    {
//        $server = new ChatServer();
//        $server->port = 8080;
        $server = new TwitterServer();

        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN_ERROR, function ($event) use ($server) {
            echo "Error opening port " . $server->port . PHP_EOL;
            $server->port += 1;
            $server->start();
        });


        $server->on(WebSocketServer::EVENT_WEBSOCKET_OPEN, function ($event) use($server) {
            echo "Server started at port " . $server->port . PHP_EOL;
            echo \Yii::$app->language;
        });

        $server->start();
    }
}