<?php

namespace frontend\controllers;

use common\models\User;
use Yii;

class UserController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }

    /**
     * получение парметров пользователя
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionGetUser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response = [
            'res' => false,
            'user' => []
        ];
        if (!Yii::$app->user->isGuest) {
            $response['res'] = true;
            $response['user'] = User::find()->select('username, phone, email')->where(['id' => Yii::$app->user->identity->id])->one();
        }

        return $response;
    }

    /**
     * Редактироване личных данных пользователем
     *
     * @return array
     */
    public function actionUpdate()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
            'error' => [],
            'personalDate' => []
        ];
        $modelUser = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $modelUser->username = $data['data']['name'];
        $modelUser->email = $data['data']['email'];
        $modelUser->phone = $data['data']['phone'];
        if ($modelUser->validate() && $modelUser->save()) {
            $response['personalDate'] = User::find()->select('username, email, phone')->where(['id' => Yii::$app->user->identity->id])->one();
            $response['res'] = true;
        } else {
            $response['error'] = $modelUser->getErrors();
        }

        return $response;
    }

    public function actionEditPassword()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
            'message' => ''
        ];

        if (!Yii::$app->user->isGuest) {
            $modelUser = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
            if (!$modelUser->validatePassword($data['data']['editPassword'])) {
                $response['message'] = 'Вы ввели не корректный пароль.';
            } else {
                $modelUser->setPassword($data['data']['newEditPassword']);
                $modelUser->save();
                $response['res'] = true;
            }
        }

        return $response;
    }

}
