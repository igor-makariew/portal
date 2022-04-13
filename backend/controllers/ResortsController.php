<?php

namespace backend\controllers;

use Yii;
use common\models\listCountry\ListCountry;
use common\models\listResorts\ListResorts;

class ResortsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * получение списка стран
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetCountry()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $modelCountries = ListCountry::find()
            ->select('country_id, name')
            ->all();
        return $modelCountries;
    }

    /**
     * получение списка туров
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetResorts()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $allResorts = $data['allResorts'] == true ? 1 : 0;
        $modelResorts = ListResorts::find()
            ->select('resorts_id, name, is_popular, rating')
            ->where(['in', 'resort_country_id',  $data['selectedCountries']])
            ->andWhere(['del_resort' => $allResorts])
            ->all();

        return $modelResorts;
    }

    /**
     * редактирование строки курорт
     *
     * @return mixed
     */
    public function actionUpdateResort()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response['res'] = false;
        $modelResorts = ListResorts::find()
            ->where(['resorts_id' => $data['editedItem']['resorts_id']])
            ->one();
        $modelResorts->is_popular = $data['editedItem']['is_popular'];
        $modelResorts->rating = $data['editedItem']['rating'];
        $modelResorts->name = $data['editedItem']['name'];
        if ($modelResorts->validate() && $modelResorts->save()) {
            $response['res'] = true;
        }
        return $response;
    }

    /**
     * удаление строки
     *
     * @return mixed
     */
    public function actionDeleteResort()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response['res'] = false;
        $modelResorts = ListResorts::find()
            ->where(['resorts_id' => $data['delItem']['resorts_id']])
            ->one();
        $modelResorts->del_resort = 1;
        if ($modelResorts->validate() && $modelResorts->save()) {
            $response['res'] = true;
        }
        return $response;
    }

}
