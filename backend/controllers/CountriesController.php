<?php

namespace backend\controllers;

use Yii;
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

    public function actionGetCountries() {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $query = new \yii\db\Query;
        $modelListCountry = $query->select('`list_country`.`country_id`, `list_country`.`popular`, `list_country`.`name` AS `country_name`, `list_resorts`.`name` AS `name_resort`, `list_resorts`.`is_popular`, `list_resorts`.`rating`')
            ->from('`list_country`')
            ->leftJoin('`list_resorts`', '`list_resorts`.`resort_country_id` = `list_country`.`country_id`')
            ->all();

        return $modelListCountry;
    }


}
