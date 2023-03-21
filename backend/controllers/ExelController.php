<?php

namespace backend\controllers;

use yii\web\Controller;
use PHPExcel_IOFactory;
use scotthuangzl\export2excel\Export2ExcelBehavior;
use common\traits\BreadcrumbsTrait;
use common\traits\ListModelsTrait;
use common\models\User;
use common\models\listResorts\ListResorts;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\workTimeUsers\WorkTimeUsers;
use yiiplus\websocket\ChannelInterface;
use backend\models\Excel;

class ExelController extends Controller implements ChannelInterface
{
    public $enableCsrfValidation = false;
    public $User;
    public $fileName;
    use BreadcrumbsTrait;
    use ListModelsTrait;

//    public function __construct($id, $module, $config = [])
//    {
//        parent::__construct($id, $module, $config);
//        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/common/models/';
//        $this->getParamsFiles();
//        $this->User = \common\models\User;
//        foreach ($this->listModels['listNames'] as $index => $modelName) {
//            $className = \common\models\$modelName;
//            "\common\models\\".$modelName;
//        }
//    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'download', 'get-names-models', 'get-model', 'start-day', 'start', 'stop', 'stop-day', 'upload-file'],
                        'roles' => [User::ROLE_ADMIN],

                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'get-names-models', 'get-model'],
                        'roles' => [User::ROLE_MODER],
                    ],

                ],
            ],

            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'get-names-models' => ['POST'],
                    'get-model' => ['POST'],
                    'start-day' => ['POST'],
                    'start' => ['POST'],
                    'stop' => ['POST'],
                    'stop-day' => ['POST']
                ],
            ],
            //above is your existing behaviors
            //new add export2excel behaviors
            'export2excel' => [
                'class' => Export2ExcelBehavior::class,
                //'prefixStr' => yii::$app->user->identity->username,
                //'suffixStr' => date('Ymd-His'),
            ],
        ];
    }

//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
            //new add download action
//            'download' => [
//                'class' => 'scotthuangzl\export2excel\DownloadAction',
//            ],
//        ];
//    }

    public function actionIndex()
    {
        $this->sessionUrl();
        $this->sessionBreadcrumbs();
        list($route, $param) = Yii::$app->request->resolve();
        $this->createBreadcrumbs($this->routes, $route);

        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/common/models/';
        $nameDir = $this->getParamsFiles();


//        $phpExcel = PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT'] . '/backend/web/images/uploadExcel/testing.xlsx');
//        $sheetData = $phpExcel->getActiveSheet()->toArray(null, true, true, true);
//
//        $excel_data = Export2ExcelBehavior::excelDataFormat(User::find()->asArray()->all());
//        $excel_title = $excel_data['excel_title'];
//        $excel_ceils = $excel_data['excel_ceils'];
//        $excel_content = array(
//            array(
//                'sheet_name' => 'User',
//                'sheet_title' => $excel_title,
//                'ceils' => $excel_ceils,
//                'freezePane' => 'B2',
//                'headerColor' => Export2ExcelBehavior::getCssClass("header"),
//                'headerColumnCssClass' => array(
//                    'id' => Export2ExcelBehavior::getCssClass('blue'),
//                    'Status_Description' => Export2ExcelBehavior::getCssClass('grey'),
//                ), //define each column's cssClass for header line only.  You can set as blank.
//                'oddCssClass' => Export2ExcelBehavior::getCssClass("odd"),
//                'evenCssClass' => Export2ExcelBehavior::getCssClass("even"),
//            ),
//            array(
//                'sheet_name' => 'Important Note',
//                'sheet_title' => array("Important Note For Region Template"),
//                'ceils' => array(
//                    array("1.Column Platform,Part,Region must need update.")
//                , array("2.Column Regional_Status only as Regional_Green,Regional_Yellow,Regional_Red,Regional_Ready.")
//                , array("3.Column RTS_Date, Master_Desc, Functional_Desc, Commodity, Part_Status are only for your reference, will not be uploaded into NPI tracking system."))
//            ),
//        );
//        $excel_file = "testing";
//        $this->export2excel($excel_content, $excel_file);
        return $this->render('index', [
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }

    /**
     * Запись файла на сервер и
     *
     * @return array
     */
    public function actionUploadFile(): array
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response = [
            'data' => [],
            'response' => false,
            'error' => '',
        ];

        $rootDir = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/images/uploadExcel/';

        try {
            if ($_FILES['file'] != 0) {
                $this->fileName = $_FILES['file']['name'];
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $rootDir . $_FILES['file']['name'])) {
                    $response['error'] = 'Error upload file - ' . $_FILES['file']['name'];
                    return $response;
                }

                $model = new Excel();

                $response['data'] = $model->getDataFile($rootDir . $this->fileName);
                $model->delFile($rootDir . $this->fileName);
                $response['response'] = true;
                return $response;
            }
        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
        }
    }


    /**
     * получение списка моделей
     *
     * @return mixed
     */
    public function actionGetNamesModels()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/common/models/';
        $nameDir = $this->getParamsFiles();
        return $nameDir['listNames'];
    }

    /**
     * получение модели
     *
     * @return mixed
     */
    public function actionGetModel()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/common/models/';
        $nameDir = $this->getParamsFiles();
        $nameModel = $nameDir['listNames'][$data['selectedModel']];
        ListResorts::find()->all();
//        return Comments::find()->all();
//        $test = $nameModel::find()->all();
        return __NAMESPACE__;
        return $nameModel::find()->all();
    }




    public function actionStartDay()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $workTimeUser = new WorkTimeUsers();
        $workTimeUser->add();
        $workTimeUser->fullname = $data['fullname'];
        $workTimeUser->user_param = $workTimeUser->get()[0];
        $workTimeUser->start_day = time();
        $workTimeUser->start = 0;
        $workTimeUser->stop = 0;
        $workTimeUser->stop_day = 0;
        if($workTimeUser->save()) {
            return true;
        }

        return false;
    }

    public function actionStart()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $workTimeUser = new WorkTimeUsers();
        $workTimeUser->add();
        $workTimeUser->fullname = $data['fullname'];
        $workTimeUser->user_param = $workTimeUser->get()[0];
        $workTimeUser->start_day = 0;
        $workTimeUser->start = time();
        $workTimeUser->stop = 0;
        $workTimeUser->stop_day = 0;
        if($workTimeUser->save()) {
            return true;
        }

        return false;
    }

    public function actionStop()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $workTimeUser = new WorkTimeUsers();
        $workTimeUser->add();
        $workTimeUser->fullname = $data['fullname'];
        $workTimeUser->user_param = $workTimeUser->get()[0];
        $workTimeUser->start_day = 0;
        $workTimeUser->start = 0;
        $workTimeUser->stop = time();
        $workTimeUser->stop_day = 0;
        if($workTimeUser->save()) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function actionStopDay()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $stopTime = 0;
        $startTime = 0;
        $breakTime = 0;
        $allTime = 0;
        $response = [];
        $workTimeUser = new WorkTimeUsers();
        $workTimeUser->add();
        $workTimeUser->fullname = $data['fullname'];
        $workTimeUser->user_param = $workTimeUser->get()[0];
        $workTimeUser->start_day = 0;
        $workTimeUser->start = 0;
        $workTimeUser->stop = 0;
        $workTimeUser->stop_day = time();
        if($workTimeUser->save()) {
            $workDay = WorkTimeUsers::find()->where(['user_param' => $workTimeUser->get()[0]])->all();
            $startDay = $workDay[0]['start_day'];
            $stopDay = $workDay[count($workDay) - 1]['stop_day'];
            foreach ($workDay as $time) {
                if($time['stop'] > 0) {
                    $stopTime = $time['stop'];
                }

                if($time['start'] > 0) {
                    $startTime = $time['start'];
                }

                if($startTime != 0 &&  $stopTime != 0){
                    $breakTime += ($startTime - $stopTime);
                }
            }

            $allTime = $stopDay - $startDay - $breakTime;
            $workTimeUser->clear();
            $test = Yii::$app->websocket->send(['channel' => 'push-message', 'message' => 'User xxx sent a plane! ']);

            //wss://ws.kraken.com/

            return $response = [
                'breakTime' => $breakTime,
                'allTime' => $allTime
            ];
        }
    }

    public function execute($fd, $data)
    {
        // TODO: Implement execute() method.
        return [
            $fd,  // The first parameter returns the client ID, multiple returns as an array
            $data->message  // The second parameter returns the message that needs to be returned to the client
        ];

    }

    public function close($fd)
    {
        return;
    }


}
