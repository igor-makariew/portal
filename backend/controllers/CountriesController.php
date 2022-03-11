<?php

namespace backend\controllers;

use common\models\listResorts\ListResorts;
use Yii;
use yii\db\Exception;
use yii\helpers\Url;
use common\models\listCountry\ListCountry;

class CountriesController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * получение стран и связанных с ним туров
     *
     * @return array
     */
    public function actionGetCountries() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $query = new \yii\db\Query;
        $modelListCountry = $query->select('`list_country`.`country_id`, `list_resorts`.`resorts_id`, `list_country`.`popular`, `list_country`.`name` AS `country_name`, `list_resorts`.`name` AS `resort_name`, `list_resorts`.`is_popular`, `list_resorts`.`rating`')
            ->from('`list_country`')
            ->leftJoin('`list_resorts`', '`list_resorts`.`resort_country_id` = `list_country`.`country_id`')
            ->all();

        return $modelListCountry;
    }

    /**
     * @return array
     */
    public function actionUpdateRow()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'response' => false
        ];

        $modelListresort = ListResorts::find()
            ->innerJoin('list_country', '`list_resorts`.`resort_country_id` = `list_country`.`country_id`')
            ->where(['list_resorts.resort_country_id' => $data['item']['country_id']])
            ->andWhere(['list_resorts.resorts_id' => $data['item']['resorts_id']])
            ->one();
        $modelListresort->is_popular = $data['item']['is_popular'];
        $modelListresort->name = $data['item']['resort_name'];
        $modelListresort->rating = $data['item']['rating'];
        $modelListCountry = ListCountry::find()->where(['country_id' => $data['item']['country_id']])->one();
        $modelListCountry->popular = $data['item']['popular'];
        if ($modelListresort->validate() && $modelListCountry->validate() && $modelListresort->save() && $modelListCountry->save()) {
            $response['response'] = true;
        }

        return $response;
    }

    /**
     * @return array
     */
    public function actionCreateRow()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'response' => false,
            'message' => '',
            'errorCountry' => '',
            'errorResorts' => ''
        ];
        $modelListCountry = new ListCountry();
        $countryValues = [
            'country_id' => $data['item']['country_id'],
            'name' => $data['item']['country_name'],
            'popular' => $data['item']['popular']
        ];
        $modelListCountry->attributes = $countryValues;
        $modelListResorts = new ListResorts();
        $resortValues = [
            'resorts_id' => $data['item']['resorts_id'],
            'name' => $data['item']['resort_name'],
            'is_popular' => $data['item']['is_popular'],
            'resort_country_id' => $data['item']['resort_country_id'],
            'rating' => $data['item']['rating'],
        ];
        $modelListResorts->attributes = $resortValues;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ( $modelListCountry->validate() && $modelListCountry->save() && $modelListResorts->validate() && $modelListResorts->save()) {
                $response['response'] = true;
                $transaction->commit();
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            $response['message'] = $exception->getMessage();
            $response['errorCountry'] = $modelListCountry->errors;
            $response['errorResorts'] = $modelListResorts->errors;
        }

        return $response;
    }
}
