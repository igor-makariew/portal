<?php

namespace backend\controllers;

use yii\web\Controller;

class PersonalAreaController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
