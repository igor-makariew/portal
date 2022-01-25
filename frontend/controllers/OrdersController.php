<?php

namespace frontend\controllers;

use common\models\customers\Customers;
use Yii;

class OrdersController extends \yii\web\Controller
{

    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * отображение заказов покупателя
     *
     * @return array
     */
    public function actionOrdersUser() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
//        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'customer' => []
        ];
        $response['customer'] = Customers::find()->select('id, title, price, date, raiting')->where(['user_id' => Yii::$app->user->identity->id])->all();

        return $response;
    }

    /**
     * Удаление выбранного заказа в списке заказы пользователя
     *
     * @return array
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteItem() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
        ];
        $modelCustomer = Customers::find()->where(['id' => $data['data']['deleteItem']])->one();
        if ($modelCustomer->delete()) {
            $response['res'] = true;
        }

        return $response;
    }
}
