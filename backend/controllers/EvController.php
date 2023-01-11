<?php

namespace backend\controllers;

use backend\models\Ev;
use yii\web\Controller;
use yii\base\Event;
use yii\db\ActiveRecord;
use Yii;

class EvController extends Controller
{
    public $stroka = 'makarev igor';
    public $arrListener = [];


    public function actionIndex()
    {
        $ev = new Ev();
        array_push($this->arrListener, Ev::STR_END, Ev::STR_END, Ev::STR_END);

        $inputFilter = array('invoiceNo' => FILTER_SANITIZE_NUMBER_INT);

        var_dump($inputFilter);

        $input = filter_input_array(INPUT_POST, $inputFilter);

        var_dump($input);

        $ev->on($ev::STR_END, [$ev, 'uppperStr'], $this->stroka, false);

        foreach ($this->arrListener as $listener) {
            $ev->trigger($listener);
        }

//        $ev->trigger(Ev::STR_END);

//        Method №1 callback
        $ev->on(Ev::USER_REGISTERED, function ($event) {
            var_dump('user registered');
        });

//        Method №2 function model
        $ev->on(Ev::USER_REGISTERED, [$ev, 'methodFromObject'], ['param' => 'igor makariew second'], false);

//        Method №3 static
        $ev->on(Ev::USER_REGISTERED, ['backend\models\Yandex', 'staticOtherModel']);

//        Method №4 global function php
        $ev->on(Ev::USER_REGISTERED, 'get_class');

        Event::on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
            Yii::debug(get_class($event));
        });

        Event::trigger(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT);

//        Yii::$app->on('bar', function ($event) {
//            echo get_class($event->sender);
//        });
//
//        Yii::$app->trigger('bar', new Event(['sender' => new Ev]));

        $ev->trigger(Ev::USER_REGISTERED);

        die();


        return $this->render('index');
    }

}
