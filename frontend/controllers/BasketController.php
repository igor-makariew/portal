<?php

namespace frontend\controllers;

use common\models\User;
use yii\web\Controller;
use Yii;
use common\models\basket\Basket;
use common\models\hotels\Hotels;
use common\models\customers\Customers;

class BasketController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Добавление продуктов в карзину
     *
     * @return mixed
     */
    public function actionAddBasket() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'hotel' => [],
            'userParam' => [],
        ];
        $response['hotel'] = Hotels::find()->where(['hotel_id' => $data['data']['hotelId']])->asArray()->one();
        $response['userParam'] = User::find()->select(['id', 'username', 'email'])->where(['id' => $data['data']['userId']])->asArray()->one();

        $model = new Basket();
        $response['basket'] = $model->addToBasket($response['userParam'], $response['hotel']);


        return $response;
    }

    /**
     * Получение заказов из карзины
     *
     * @return mixed
     */
    public function actionGetBasket() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $model = new Basket();
        $response['basket'] = $model->getBasket();
        $response['userId'] = Yii::$app->user->identity->id;
        return  $response;
    }

    public function actionBuy()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'buy'=>[
                'hotel' => '',
                'order' => '',
                'error' => ''
            ],
        ];
        $countHotels = $data['data']['countHotels'];
        $customer = $data['data']['customer'];
        foreach ($countHotels as $index => $hotel) {
            $countHotels[$index]['username'] = $customer['username'];
            $countHotels[$index]['email'] = $customer['email'];
        }

        foreach ($countHotels as $index => $order) {
            $modelCustomer = new Customers();
            $modelCustomer->name = $order['username'];
            $modelCustomer->email = $order['email'];
            $modelCustomer->title = $order['name'];
            $modelCustomer->hotel = $order['name'];
            $modelCustomer->raiting = $order['raiting'];
            $modelCustomer->price = $order['price'];
            if ($modelCustomer->save() && $modelCustomer->validate()) {
                $response['buy'][$index]['order'] = true;
                $response['buy'][$index]['hotel'] = $order['name'];
            } else {
                $response['buy'][$index]['error'] = $modelCustomer->getErrors();
            }
        }
        $modelBasket = new Basket();
//        $response['basket'] = $modelBasket->removeFromBasket($countHotels);
        $response['basket'] = $modelBasket->removeFromBasket($countHotels);

        return $response;
    }

}

