<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use common\models\listCountry\ListCountry;
use common\models\listResorts\ListResorts;
use common\models\User;

class DestinationController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return mixed
     */
    public function actionGetCountry()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $modelCountry = ListCountry::find()->where(['country_id' => $data['data']['id']])->one();
        $modelResorts = $modelCountry->listResorts;

        return $modelResorts;
    }

    public function actionGetUser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response = [
            'res' => false,
            'user' => [],
        ];
        if (!Yii::$app->user->isGuest) {
            $response['res'] = true;
            $response['user'] = User::find()->select('username, phone, email')->where(['id' => Yii::$app->user->identity->id])->one();
        }

        return $response;
    }

}
