<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\favoriteProducts\FavoriteProducts;
use common\models\hotels\Hotels;
use common\models\historyUser\HistoryUser;
use common\models\listFilterHotel\ListFilterHotel;

class FavoriteController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Добавление избранных продуктов
     */
    public function actionAddFavorite() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
        ];
        $modelFavorite = new FavoriteProducts();
        if (!$modelFavorite->existsProduct(Yii::$app->user->identity->id, $data['data']['id'])) {
            $response['res'] = true;
            $modelFavorite->set($data['data']['id'], $data['data']['date']);
        }

        return $response;
    }

    /**
     * Получение списка избранных продуктов
     *
     * @return array
     */
    public function actionGetProducts() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'modelHotel' => [],
            'listFavoriteProducts' => []
        ];

        $modelFavorite = new FavoriteProducts();
        $listFavoriteProducts = $modelFavorite->getKeys(Yii::$app->user->identity->id);
        $response['modelHotel'] = Hotels::find()
            ->select('full_name, hotel_id, hotel_name, label, location, location_id, location_name, stars')
            ->where(['in', 'hotel_id', $listFavoriteProducts])
            ->all();
        $response['listFavoriteProducts'] = $listFavoriteProducts;


        return $response;

    }

    /**
     * Удаление избранных отелей из личного кабинета
     *
     * @return array
     */
    public function actionDeleteFavorite() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response= [
            'res' => false,
        ];
        $modelFavorite = new FavoriteProducts();
        if ($modelFavorite->deleteProduct(Yii::$app->user->identity->id, $data['data']['hotel_id']) > 0) {
            $response['res'] = true;
        }

        return $response;
    }

    public function actionViewedHotel()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $modelHistoryUser = new HistoryUser($data['data']['id']);
        return $modelHistoryUser->getIds();
    }

    public function actionGetListViewedHotels()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $list = [];
        if (is_array($data['data']['viewedHotel'])) {
            if (count($data['data']['viewedHotel']) > 0) {
                $list = $data['data']['viewedHotel'];
            }
        } else {
            $modelHistoryUser = new HistoryUser(null);
//            $modelHistoryUser->clearIds();
            $list = $modelHistoryUser->getIds();
        }

        $modelHotel = Hotels::find()
            ->select('full_name, hotel_id, hotel_name, label, location, location_id, location_name, stars')
            ->where(['in', 'hotel_id', $list])
            ->all();
        return $modelHotel;
    }

    /**
     * получение карточки товара
     *
     * @return array
     */
    public function actionGetHotel()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
            'dataHotel' => [],
        ];
        new HistoryUser($data['data']['id']);

        $dataHotel = Hotels::find()->where(['hotel_id' => $data['data']['id']])->one();
        if (!empty($dataHotel)) {
            $response['res'] = true;
            $response['dataHotel'] = $dataHotel;
        }

        return $response;
    }

    /**
     * получение картинок слайдера
     *
     * @return mixed
     */
    public function actionGetSliders()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;

        $modelListFilterHotel = new ListFilterHotel([]);
        $response['filterHotel'] = $modelListFilterHotel->getList();
        return $response;
    }
}
