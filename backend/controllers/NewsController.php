<?php

namespace backend\controllers;

use yii\web\Controller;

class NewsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}