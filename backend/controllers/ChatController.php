<?php

namespace backend\controllers;

use common\traits\BreadcrumbsTrait;
use yii\web\Controller;
use Yii;
use common\models\User;
use common\models\chat\Chat;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use function React\Promise\all;

class ChatController extends Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    /**
     * diplay page index
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->breadcrumbs['links'][] = [
            'label' => 'CHAT',
        ];

        return $this->render('index', [
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }

    /**
     * get all users except registered
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetUsers()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $users = User::find()
            ->select('id, username, email, phone')
            ->where(['!=', 'id', Yii::$app->user->id])
            ->all();

        return $users;
    }

    /**
     * get message writing users
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetMessagesWriteUser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $modelChat = new Chat();
        return $modelChat->getMessagesWriteUsers(Yii::$app->user->id, $data['id']);
    }

    /**
     * Record message in db
     *
     * @return bool
     */
    public function actionSendMessage()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $idAuthUser = Yii::$app->user->id;
        $nameAuthUser = User::find()->select('username')->where(['id' => $idAuthUser])->one();
        $user = User::find()->select('id, username')->where(['id' => $data['id']])->one();
        $dataChat = [
            'id_from' => $idAuthUser,
            'name_from' => $nameAuthUser['username'],
            'message_from' => $data['message'],
            'id_to' => $user['id'],
            'name_to' => $user['username']
        ];

        $modelChat = new Chat();
        $modelChat->attributes = $dataChat;
        if ($modelChat->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function actionGetAuthUsername()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $modelUser = User::find()->select('id, username')->where(['id' => Yii::$app->user->id])->one();

        return $modelUser;
    }


}