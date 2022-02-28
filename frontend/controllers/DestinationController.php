<?php

namespace frontend\controllers;

use yii\db\Query;
use yii\web\Controller;
use Yii;
use common\models\listCountry\ListCountry;
use common\models\listResorts\ListResorts;
use common\models\User;
use common\models\comments\Comments;
use common\models\rating\Rating;

class DestinationController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return mixed
     */
    public function actionGetCountry()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'listResorts' => [],
        ];

//        $models = ListCountry::find()
//            ->select('*')
//            ->with([
//                'listResorts' => function($query) {
//                    $query->with(['ratings']);
//                }
//            ])
//            ->where(['list_country.country_id' => $data['data']['id']])
//            ->one();

        $querys = new \yii\db\Query;
        $userRatingsTour = $querys->select('`list_resorts`.`*`, `rating`.`rating` AS `user_rating`, `rating`.`user_id`')
            ->from('`list_country`')
            ->leftJoin('`list_resorts`', '`list_resorts`.`resort_country_id` = `list_country`.`country_id`')
            ->leftJoin('`rating`', '`rating`.`resorts_id` = `list_resorts`.`resorts_id` AND `rating`.`user_id` = ' .Yii::$app->user->identity->id)
            ->where(['list_country.country_id' => $data['data']['id']])
            ->all();
        $userRatings = [];
        foreach($userRatingsTour as  $index => $userRatingTour) {
            $userRatings[$index]['at_filtering'] = $userRatingTour['at_filtering'];
            $userRatings[$index]['id'] = (int) $userRatingTour['id'];
            $userRatings[$index]['is_popular'] = $userRatingTour['is_popular'];
            $userRatings[$index]['name'] = $userRatingTour['name'];
            $userRatings[$index]['rating'] = (float) $userRatingTour['rating'];
            $userRatings[$index]['resort_country_id'] = (int) $userRatingTour['resort_country_id'];
            $userRatings[$index]['resorts_id'] = (int) $userRatingTour['resorts_id'];
            $userRatings[$index]['user_rating'] = (float) $userRatingTour['user_rating'];
        }

        $response['listResorts'] = $userRatings;
        return $response;
    }

    /**
     * @return array
     */
    public function actionGetUser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response = [
            'res' => false,
            'user' => [],
        ];
        if (!Yii::$app->user->isGuest) {
            $response['res'] = true;
            $response['user'] = User::find()->select('id, username, phone, email')->where(['id' => Yii::$app->user->identity->id])->one();
        }

        return $response;
    }

    public function actionCreateComment()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
            'listResorts' => [],
            'error' => ''
        ];
        $modelUserRating = Rating::find()->where(['user_id' => $data['data']['user_id'], 'resorts_id' => $data['data']['resort']['resorts_id']])->one();
        if (!isset($modelUserRating)) {
            $modelRating = new Rating();
            $valueRating = [
                'user_id' => $data['data']['user_id'],
                'rating' => $data['data']['resort']['user_rating'],
                'resorts_id' => $data['data']['resort']['resorts_id']
            ];
            $modelRating->attributes = $valueRating;
            $modelRating->save();
        } else {
            $modelRating = Rating::find()->where(['user_id' => $data['data']['user_id'], 'resorts_id' => $data['data']['resort']['resorts_id']])->one();
            $modelRating->rating = $data['data']['resort']['user_rating'];
            $modelRating->update();
        }

        $modelOverallRating = Rating::find()->where(['resorts_id' => $data['data']['resort']['resorts_id']])->sum('rating');
        $modelOverallUsers = Rating::find()->select('user_id')->where(['resorts_id' => $data['data']['resort']['resorts_id']])->count();
        $average = $modelOverallRating / $modelOverallUsers;

        $modelListResort = ListResorts::find()->where(['resorts_id' => $data['data']['resort']['resorts_id']])->one();
        $modelListResort->rating = $average;
        $modelComments = new Comments();
        $valueComment = [
            'comment_resort_id' => $data['data']['resort']['resorts_id'],
            'comment' => $data['data']['comment'],
            'name' => $data['data']['name']
        ];
        $modelComments->attributes = $valueComment;
        if ($modelComments->save() && $modelListResort->update() == 0 ? true : $modelListResort->update()) {
            $response['res'] = true;
            $querys = new \yii\db\Query;
            $userRatingsTour = $querys->select('`list_resorts`.`*`, `rating`.`rating` AS `user_rating`, `rating`.`user_id`')
                ->from('`list_country`')
                ->leftJoin('`list_resorts`', '`list_resorts`.`resort_country_id` = `list_country`.`country_id`')
                ->leftJoin('`rating`', '`rating`.`resorts_id` = `list_resorts`.`resorts_id` AND `rating`.`user_id` = ' .Yii::$app->user->identity->id)
                ->where(['list_country.country_id' => $data['data']['id']])
                ->all();
            $userRatings = [];
            foreach($userRatingsTour as  $index => $userRatingTour) {
                $userRatings[$index]['at_filtering'] = $userRatingTour['at_filtering'];
                $userRatings[$index]['id'] = (int) $userRatingTour['id'];
                $userRatings[$index]['is_popular'] = $userRatingTour['is_popular'];
                $userRatings[$index]['name'] = $userRatingTour['name'];
                $userRatings[$index]['rating'] = (float) $userRatingTour['rating'];
                $userRatings[$index]['resort_country_id'] = (int) $userRatingTour['resort_country_id'];
                $userRatings[$index]['resorts_id'] = (int) $userRatingTour['resorts_id'];
                $userRatings[$index]['user_rating'] = (float) $userRatingTour['user_rating'];
            }
            $response['listResorts'] = $userRatings;
        }

        return $response;
    }

}
