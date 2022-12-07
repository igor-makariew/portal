<?php

namespace backend\daemons;

use consik\yii2websocket\events\WSClientEvent;
use Ratchet\ConnectionInterface;
use consik\yii2websocket\WebSocketServer;
use yii\helpers\Console;

class ChatServer extends WebSocketServer
{
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function (WSClientEvent $event) {
            $event->client->name = null;
        });
    }

    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandChat(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => ''];

        if (!$client->name) {
            $result['message'] = 'Set your name';
        } elseif (!empty($request['message']) && $message = trim($request['message']) ) {
            foreach ($this->clients as $chatClient) {
                $chatClient->send( json_encode([
                    'type' => 'chat',
                    'from' => $client->name,
                    'message' => $message
                ]) );
            }
        } else {
            $result['message'] = 'Enter message';
        }

        $client->send( json_encode($result) );
    }

    public function commandSetName(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = [
            'message' => 'Username updated',
        ];

        if (!empty($request['name']) && $name = trim($request['name'])) {
            $usernameFree = true;
            foreach ($this->clients as $chatClients) {
                if ($chatClients != $client && $chatClients->name == $name) {
                    $result['message'] = 'This name is used by other user';
                    $usernameFree = false;
                    break;
                }
            }

            if ($usernameFree) {
                $client->name = $name;
            }

        } else {
            $result['message'] = 'Invalid username';
        }

        $client->send( json_encode($result) );
    }



}