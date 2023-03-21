<?php

namespace backend\controllers;

class Game2048Controller extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
