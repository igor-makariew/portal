<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use common\traits\BreadcrumbsTrait;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'urls'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            // объявляет "error" действие с помощью названия класса
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            // объявляет "view" действие с помощью конфигурационного массива
//            'view' => [
//                'class' => 'yii\web\ViewAction',
//                'viewPrefix' => '',
//            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->sessionUrl();
        $this->sessionBreadcrumbs();
        list($route, $param) = Yii::$app->request->resolve();
        $this->getKeys();
        $this->removeRouteInSession($route);
        $this->addRoute($route);
        $this->getBreadcrumbs($route);
        return $this->render('index', [
            'urls' => $this->getUrls(),
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->username = Yii::$app->request->post()['LoginForm']['username'];
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * запись данных меню в сессию
     */
    public function actionUrls()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $this->sessionUrl();
        $this->clearSession('urls');
        $this->setUrls($data['listNameUrls']);
    }
}
