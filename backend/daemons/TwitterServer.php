<?php


namespace backend\daemons;

use common\models\chat\Chat;
use common\models\User;
use consik\yii2websocket\events\WSClientEvent;
use Ratchet\ConnectionInterface;
use consik\yii2websocket\WebSocketServer;
use Yii;


class TwitterServer extends WebSocketServer
{
    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function (WSClientEvent $event) {
            $event->client->name = null;
            $event->client->message = null;
            $event->client->trigger = false;
        });
    }

    protected function getCommand(ConnectionInterface $from, $msg)
    {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
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
//                убрать проверку на поаторяющиеся имена && $chatClients->name == $name
//                if ($chatClients != $client) {
//                    $result['message'] = 'This name is used by other user';
//                    $usernameFree = false;
//                    break;
//                }
            }

            if ($usernameFree) {
                $client->name = $name;
                $client->message = null;
                $client->trigger = true;
            }

        } else {
            $result['message'] = 'Invalid username';
        }

        $client->send( json_encode($result) );
    }

    public function commandChat(ConnectionInterface $client, $msg)
    {
        $request = json_decode($msg, true);
        $result = ['message' => ''];
        $mess = [
            'id_from' => (string) $request['idAuthUser'],
            'name_from' => $request['nameFrom'],
            'message_from' => $request['message'],
//            'id_to' => $request['idUser'],
            'name_to' => $request['nameTo'],
            'date' => $request['date'],
        ];

//        if ($client->name && $client->trigger) {
//
//            if ($client->trigger) {
//                $client->messages = $this->getMessagesWriteUser($request['idAuthUser'], $request['idUser']);
//            }
//
//            $client->trigger = false;
//        }

        if (!$client->name) {
            $result['message'] = 'Set your name';
        } elseif (!empty($request['message']) && $message = trim($request['message']) ) {
            $this->sendMessage($request['idUser'], $request['message'], $request['idAuthUser'], $request['date']);

            foreach ($this->clients as $chatClient) {
                $chatClient->send( json_encode([
                    'type' => 'chat',
                    'from' => $client->name,
                    'messages' => $mess,
                ]) );
            }
        } else {
            $result['message'] = 'Enter message';
        }

        $client->send( json_encode($result) );
    }

    /**
     * save message in db
     *
     * @param $id
     * @param $message
     * @return bool
     */
    public function sendMessage($id, $message, $idAuthUser, $date = null)
    {
        $idAuthUser = $idAuthUser;
        $nameAuthUser = User::find()->select('username')->where(['id' => $idAuthUser])->one();
        $user = User::find()->select('id, username')->where(['id' => $id])->one();
        $dataChat = [
            'id_from' => $idAuthUser,
            'name_from' => $nameAuthUser['username'],
            'message_from' => $message,
            'id_to' => $user['id'],
            'name_to' => $user['username'],
            'date' => $date
        ];

        $modelChat = new Chat();
        $modelChat->attributes = $dataChat;
        if ($modelChat->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get messages write users
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getMessagesWriteUser($id, $idAuthUser)
    {
        $modelChat = new Chat();
        $chat = [];
        foreach ($modelChat->getMessagesWriteUsersss($idAuthUser, $id) as $index => $message) {
            $chat[$index] = $message;
        }

        return $chat;
    }

    public function arrayPush($array_1, $array_2)
    {
        return $array_1[] = $array_2;
    }

}