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

        $game2048 = new Game2048();

        return $game2048->values;
    }

    public function actionControl()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $game2048 = new Game2048();
        $game2048->createNewArr($data['data']['valuesSquares'], $data['data']['param']);

        return $game2048->values;
    }

}
