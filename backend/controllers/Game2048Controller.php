<?php

namespace backend\controllers;

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

    public function actionStart() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

        $game2048 = new Game2048();

        return $game2048->values;
    }

}
