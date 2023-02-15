<?php

namespace backend\controllers;

use yii\web\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use backend\models\Storage;

use AMQPEnvelope;
use yiiplus\websocket\workerman\WebSocket;

class RabbitSendController extends Controller
{
    public $enableCsrfValidation = false;
    public static $currentDir;
    public $keyHset = 'key_user_id_';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionClient()
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

        $argv = ['info', 'error', 'warning'];

        $channel = $connection->channel();
        //tutorial one
        /**
         * create queue
         */
        //$channel->queue_declare('test-yii2', false, false, false, false);
        /**
         * create message
         */
        //$msg = new AMQPMessage($data['data']);
        /**
         * publish message
         */
        //$channel->basic_publish($msg, '', 'test-yii2');

        //tutorial two
        /**
         * create queue
         * Во-первых, нам нужно убедиться, что очередь переживет перезапуск узла RabbitMQ.
         * Для этого нам нужно объявить его прочным .
         * Для этого мы передаем третий параметр в queue_declare как true :
         */
//        $channel->queue_declare('yii-task-test', false, true, false, false);
        /**
         * create message
         */
//        $msg = new AMQPMessage(
//            $data['data'],
//            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT) // пометить наши сообщения как постоянные — установив свойство сообщения delivery_mode = 2
//        );
        /**
         * publish message
         */
//        $channel->basic_publish($msg, '', 'yii-task-test');

        //tutorial three Опубликовать/подписаться
        /**
         * create exchange
         */
//        $channel->exchange_declare('logs', 'fanout', false, false, false);

        /**
         * create message
         */
//        $msg = new AMQPMessage($data['data']);
        /**
         * publish message
         */
//        $channel->basic_publish($msg, 'logs');

        //tutorial four Маршрутизация
//        $severity = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'info';

        /**
         * create exchange
         */
        $channel->exchange_declare('direct_logs', 'direct', false, false, false);
        $msg = new AMQPMessage($data['data']);
        /**
         * publish message
         * Мы предоставим серьезность журнала в качестве ключа маршрутизации .
         * Таким образом, принимающий сценарий сможет выбрать $severity, которую он хочет получить.
         * Получим несколько обменов с ключами маршрутизации и их сообщениями
         */
        foreach ($argv as $severity) {
            $channel->basic_publish($msg, 'direct_logs', $severity);
        }

//        $channel->basic_publish($msg, 'direct_logs', $severity);

        $channel->close();
        $connection->close();
        return $severity;
    }

    /**
     * create storage
     *
     * @return array
     */
    public function actionStorage()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'response' => '',
            'data' => false,

        ];

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/user_id_';
        $userId = Yii::$app->user->identity->id;
        $storage = new Storage();
        if (!$storage->isDir($dir . $userId)) {
            if ($storage->makeDir($dir . $userId)) {
                $response['response'] = 'Create virtual storage';
                $response['data'] = true;

                return $response;
            }

            $response['response'] = 'Error create virtual storage';
            $response['data'] = false;

            return $response;
        } else {
            $response['response'] = 'Virtual storage already repository';
            $response['data'] = true;

            return $response;
        }
    }

    /**
     * Проверяет являится ли каталог директорией
     *
     * @return array
     */
    public function actionIsDir()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'isDir' => false,
            'rootDir' => ''
        ];

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/user_id_';
        $userId = Yii::$app->user->identity->id;
        $storage = new Storage();
        $response['isDir'] = $storage->isDir($dir . $userId);
        $response['rootDir'] = $storage->rootDir($userId);
        $storage->setDir($this->keyHset . Yii::$app->user->identity->id, 'storage', $storage->currentDir($dir . $userId));
        $storage->setDir($this->keyHset . Yii::$app->user->identity->id, 'path', $storage->currentDir($dir . $userId));
        $storage->setDir($this->keyHset . Yii::$app->user->identity->id, 'pathDialog', $storage->currentDir($dir . $userId));

        return $response;
    }

    /**
     * создвние директории
     *
     * @return bool
     */
    public function actionCreateDir()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $storage = new Storage();
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/';
        $currentDir = $storage->getDir($this->keyHset . Yii::$app->user->identity->id, 'path');

        return mkdir($dir . $currentDir . '/' . $data['data'], 0777);
    }

    /**
     * получение списка файлов и каталогов
     *
     * @return array
     */
    public function actionGet()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $response = [];
        $storage = new Storage();
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/';
        if ($data['data']) {
            $currentDir = $storage->getDir($this->keyHset . Yii::$app->user->identity->id, 'path');
        } else {
            $currentDir = $storage->getDir($this->keyHset . Yii::$app->user->identity->id, 'pathDialog');
        }

        $files = array_diff( scandir($dir . $currentDir), array('.', '..'));
        foreach ($files as $file) {
            if ($storage->isDir($dir . $currentDir . '/' . $file)) {
                $response[] = [
                    'name' => $file,
                    'catalog' => true
                ];
            } else {
                $response[] = [
                    'name' => $file,
                    'link' => Yii::getAlias('/admin/storage/') . $currentDir . '/' . $file,
                    'catalog' => false
                ];
            }
        }

        return $response;
    }


    public function actionDirSelectionChildren()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'path' => '',
            'pathDialog' => '',
            'trigger' => false
        ];

        $storage = new Storage();
        if ($data['data']['trigger']) {
            $response['trigger'] = $data['data']['trigger'];
            $response['path'] = $storage->createPath($this->keyHset . Yii::$app->user->identity->id, 'path', $data['data']['dir'], 'add');
        } else {
            $response['trigger'] = $data['data']['trigger'];
            $response['pathDialog'] = $storage->createPath($this->keyHset . Yii::$app->user->identity->id, 'pathDialog', $data['data']['dir'], 'add');
        }

        return $response;
    }

    /**
     * переход в родительский каталог
     *
     * @return array
     */
    public function actionDirSelectionParent()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $response = [
            'path' => '',
            'pathDialog' => ''
        ];

        $storage = new Storage();
        $currentDir = $storage->getParentDir($data['data']['dir']);
        if ($data['data']['check']) {
            $response['path'] = $storage->createPath($this->keyHset . Yii::$app->user->identity->id, 'path', $currentDir, 'del');
        } else {
            $response['pathDialog'] = $storage->createPath($this->keyHset . Yii::$app->user->identity->id, 'pathDialog', $currentDir, 'del');
        }

        return $response;
    }

    /**
     * загрузка файлов на сервер
     *
     * @return array
     */
    public function actionUploadFiles()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

        $response = [
            'response' => false,
            'error' => '',
            'message' => '',
            'data' => ''
        ];

        $storage = new Storage();
        $rootDir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/';
        $storageDir = $storage->getDir($this->keyHset . Yii::$app->user->identity->id,'storage');
        $currentDir = $storage->getDir($this->keyHset . Yii::$app->user->identity->id, 'path');


        try {
            if ($_FILES['files'] != 0) {
                $files = array_combine($_FILES['files']['name'], $_FILES['files']['tmp_name']);
                $size = array_combine($_FILES['files']['name'], $_FILES['files']['size']);
                foreach ($files as $name => $tmp_name) {
                    if ($storage->isMaxSize($size[$name], $rootDir . $storageDir)) {
                        if(!move_uploaded_file($tmp_name, $rootDir . $currentDir . '/' . $name)) {
                            $response['error'] = 'Ошибка записи файла ' . $name;
                            return $response;
                        }
                    } else {
                        unset($_FILES['files']);
                        $response['message'] = 'Вы использовали все свое облачное хранилище';
                        return $response;
                    }
                }

                $response['response'] = true;
            }

            return $response;
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            return $response;
        }
    }

    /**
     * получение занятого пространства
     *
     * @return string
     */
    public function actionFreeDiskSpace()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $rootDir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/';
        $storage = new Storage();
        $currentDir = $storage->getDir($this->keyHset . Yii::$app->user->identity->id, 'storage');

        return ($storage->formatSize( $storage->GetDirectorySize($rootDir . $currentDir)) / Storage::MAX_SIZE) * 100;
    }

    /**
     * удаление файла или директрии
     *
     * @return bool
     */
    public function actionDelete()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/';
        $storage = new Storage();


        return $storage->delete(
            $dir . $data['data']['currentDir'] . DIRECTORY_SEPARATOR . $data['data']['filename'],
                $storage->isDir($dir . $data['data']['currentDir'] . DIRECTORY_SEPARATOR . $data['data']['filename']),
            $dir . $data['data']['currentDir']
            );
    }

    public function actionEffect()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/storage/';
        $storage = new Storage();

        switch ($data['data']['action']) {
            case 'content':
                return $storage->copyFile(
                    $dir . $data['data']['from'] . DIRECTORY_SEPARATOR . $data['data']['filecopy'],
                    $dir . $data['data']['to'] . DIRECTORY_SEPARATOR . $data['data']['filecopy']
                );
            case 'folder':
                return $storage->fileMove(
                    $dir . $data['data']['from'] . DIRECTORY_SEPARATOR . $data['data']['filecopy'],
                    $dir . $data['data']['to'] . DIRECTORY_SEPARATOR . $data['data']['filecopy']
                );
            case 'rename':
                return $storage->fileRename(
                    $dir . $data['data']['from'] . DIRECTORY_SEPARATOR . $data['data']['filecopy'],
                    $dir . $data['data']['from'] . DIRECTORY_SEPARATOR . $data['data']['rename']
                );
        }


    }
}