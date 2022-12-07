<?php


namespace console\controllers;

use yii\console\Controller;

class HelloController extends Controller
{
    public function actionIndex() {
        echo "Hello i'm index \n";
    }

    public function actionUpdate() {
        echo "Hello i'm update\n";
    }

    public function actionGet($name, $address) {
        echo "I'm " . $name . " and live in " . $address . "\n";
    }

    public function actionArithmetic($a, $b)
    {
        $c = ($a + $b);
        echo "a + b = " . $c . "\n";
    }
}