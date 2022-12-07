<?php

namespace backend\controllers;

use yii\web\Controller;
use common\traits\BreadcrumbsTrait;
use Yii;
use common\models\User;
use common\models\mailing\Mailing;

class MailingController extends Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    /**
     * display index
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * mailing
     *
     * @return bool
     */
    public function actionMailingList()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = false;

        $listUsers = User::find()
            ->select('id, username, email, phone')
            ->where(['mailing_list' => 1])
            ->all();

        $mailing = new Mailing();
        $mailing->saveManyData($listUsers, $data);
        $mailingList = Mailing::find()
            ->where(['sent' => 0])
            ->all();

        foreach ($mailingList as $sent) {
            if ($mailing->sendEmail($sent) ) {
                $sent['sent'] = 1;
                $sent->save();
                $response = true;
            }
        }

        return $response;
    }

}
