<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\favoriteProducts\FavoriteProducts;
use common\models\hotels\Hotels;

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
}
