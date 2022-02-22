<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use common\models\listCountry\ListCountry;
use common\models\listResorts\ListResorts;
use common\models\User;
use common\models\comments\Comments;

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

    /**
     * @return array
     */
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

    public function actionCreateComment()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
        ];
        $modelListResort = ListResorts::findOne($data['data']['resort']['resorts_id']);
        $modelListResort->rating = $data['data']['resort']['rating'];
        $modelComments = new Comments();
        $value = [
            'comment_resort_id' => $data['data']['resort']['resorts_id'],
            'comment' => $data['data']['comment']
        ];
        $modelComments->attributes = $value;
        if ($modelComments->save() && $modelListResort->update()) {
            $response['res'] = true;
        }

        return $response;
    }

}
