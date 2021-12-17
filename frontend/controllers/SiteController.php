<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\helpers\ArrayHelper;
use common\models\hotels\Hotels;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup', 'get'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        // работа с получением данных с сайта и http или https запросом
        $url = 'https://api.monobank.ua/bank/currency';
        $info = file_get_contents($url);
        $info = json_decode($info, true);

        // получение данных по api
        $key = 'a17270d9f0faea27bbb038861d796120';
        //$urlSer = 'https://api.openweathermap.org/data/2.5/weather';
        $urlSer = 'http://engine.hotellook.com/api/v2/lookup.json';
        $options = [
            'query' => 'moscow',
            'lang' => 'ru',
            'lookFor' => 'both',
            'limit' => 10
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $urlSer . '?' . http_build_query($options));
        $res = curl_exec($ch);
        $data = json_decode($res, true);
        curl_close($ch);

        return $this->render('about', [
            'info' => $info,
            'json' => $data
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionHotels()
    {

        return $this->render('hotels', [

        ]);
    }

    /**
     * comment
     */
    public function actionGet()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $urlsSer = [
            'param1' => 'http://engine.hotellook.com/api/v2/lookup.json',
            'param2' => 'https://engine.hotellook.com/api/v2/cache.json'
        ];
        $options = [
            'param1' => [
                'query' => $data['filter']['query'],
                'lang' => $data['filter']['lang'],
                'lookFor' => $data['filter']['lookFor'],
                'limit' => $data['filter']['limit']
            ],
            'param2' => [
                'location' => $data['filter']['query'],
                'currency' => 'rub',
                'checkIn' => '2021-12-20',
                'checkOut' => '2021-12-25',
                'limit' => $data['filter']['limit']
            ]
        ];
        // изменить на не последовательное а на синхронное!!!
        foreach ($urlsSer as $index => $urlSer) {
            $ch[$index] = curl_init();
            curl_setopt($ch[$index], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch[$index], CURLOPT_URL, $urlsSer[$index] . '?' . http_build_query($options[$index]));
            $res[$index] = curl_exec($ch[$index]);
            $response[$index] = json_decode($res[$index], true);
            curl_close($ch[$index]);
        }

        $list = [];
        foreach ($response as $listHotels) {
            if ($listHotels['status'] == 'ok') {
                $list = array_values($listHotels['results']['hotels']);
            } else {
                $result = array_values($listHotels);
                $list = array_merge($list, $result);
            }
        }

        $hotels = [];
        $keysList = [];
        $keys = [];
        foreach ($list as $index => $hotel) {
            $keysList = array_merge_recursive($keysList, array_keys($hotel));
            $keys = array_unique($keysList);
    }

        $keysHotels = array_pad([], count($keys), '');
        $paramsHotel = array_combine($keys, $keysHotels);
        foreach ($list as $index => $hotel) {
            foreach ($paramsHotel as $paramHotel => $value)
                $hotels[$index][$paramHotel] =
                     Hotels::isValueInArray($paramHotel, $hotel) ? $hotel[$paramHotel] : '';
        }

//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_URL, $urlsSer.'?'.http_build_query($options));
//        $res = curl_exec($ch);
//        $response = json_decode($res, true);
//        curl_close($ch);


        return $hotels;
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
