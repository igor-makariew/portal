<?php

namespace backend\controllers;

use yii\web\Controller;

class SocketController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}