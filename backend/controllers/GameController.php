<?php

namespace backend\controllers;

use common\traits\BreadcrumbsTrait;
use Yii;
use backend\models\Game;

class GameController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * start game
     *
     * @return array
     */
    public function actionStart()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $response = [
            'game' => '',
            'begin' => false,
            'story' => [],
        ];

        $modelGame = new Game();
        $step = 1;
        $ids = $modelGame->createIds($data['data']['paramsGame']);

        for ($row = 1; $row <= $data['data']['paramsGame']; $row++) {
            if (empty($response['game'])) {
                $response['game'] = "<div class='flex-container'>";
            } else {
                $response['game'] .= "<div class='flex-container'>";
            }

            for ($col = 1; $col <= $data['data']['paramsGame']; $col++) {
                $id = $modelGame->selectId($ids, $step);
                $response['game'] .= "<div class='flex-item flex-item-color' id='$id'></div>";
                $step++;
            }
            $response['game'] .= "</div>";
        }

        $response['story'] = $modelGame->getRandomArr($data['data']['paramsGame']);
        $response['begin'] = true;

        return $response;
    }



}
