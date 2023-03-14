<?php

namespace backend\controllers;

class RabbitmqController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
