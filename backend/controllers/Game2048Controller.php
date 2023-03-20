<?php

namespace backend\controllers;

use backend\models\Game;
use yii\web\Controller;
use Yii;
use backend\models\Game2048;

class Game2048Controller extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * start game
     *
     * @return array
     */
    public function actionStart() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $game2048 = new Game2048($data['data']['total']);

        return $game2048->values;
    }

    /**
     * control game
     *
     * @return array
     */
    public function actionControl()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $response = [
            'value' => [],
            'total' => 0,
            'gameover' => false,
        ];

        $game2048 = new Game2048($data['data']['total']);

        $response['value'] = $game2048->createNewArr(
            $data['data']['valuesSquares'],
            $data['data']['param']
        );
        $response['total'] = $game2048->getTotalPoint();
        $response['gameover'] = $game2048->getResultGameover();

        return $response;
    }

    public function actionRestart()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
    }

}
