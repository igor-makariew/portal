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
use common\models\User;
use common\models\favoriteProducts\FavoriteProducts;
use common\models\listFilterHotel\ListFilterHotel;
use common\models\listCountry\ListCountry;
use common\models\listResorts\ListResorts;
use common\models\calendarEvent\CalendarEvents;
use common\models\goods\Goods;
use common\models\basketGoods\BasketGoods;
use common\models\orders\Orders;
use common\models\consumers\Consumers;

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
        $countries = ListCountry::find()->all();
        $ids = ListCountry::find()->select('id')->indexBy('id')->column();
        $results = ListResorts::find()->where(['resort_country_id' => $ids])->all();
        $resorts = [];
        foreach($results as $index => $result) {
            if (is_array($resorts[$result['resort_country_id']])) {
                array_push($resorts[$result['resort_country_id']], $result);
            } else {
                $resorts[$result['resort_country_id']] = [];
                array_push($resorts[$result['resort_country_id']], $result);
            }
        }

        return $this->render('index', [
            'resorts' => $resorts,
            'countries' => $countries
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $response = [
            'res' => false,
            'msg' => 'Неверные данные для авторизации!'
        ];

        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        if (!Yii::$app->user->isGuest) {
            $response['res'] = true;
            $response['msg'] = 'Пользователь уже авторизован.';
            return $response;
        }

        $model = new LoginForm();
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $model->email = $data['data']['email'];
        $model->password = $data['data']['password'];
        if (Yii::$app->request->isPost && $model->login()) {
            $response['res'] = true;
            $response['msg'] = 'Пользователь авторизован.';
        } else {
            $model->password = '';
        }

        return $response;
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            Yii::$app->user->logout();
            $response['res'] = true;
        }

        return $response;
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
     * Displays shop page.
     *
     * @return mixed
     */
    public function actionShop()
    {
        return $this->render('shop', []);
    }

    public function actionGetGoods()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $modelGoods = Goods::find()->all();
        return $modelGoods;

    }

    public function actionBasket()
    {
        return $this->render('basket', []);
    }

    public function actionSetBasket()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $basketGoods = new BasketGoods();
        $basketGoods->addBasket($data['id']);
    }

    public function actionGetBasket()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $backetGood = new BasketGoods();
        $ids = $backetGood->getBasket();
        $modelGoods = Goods::find()->where(['in', 'id', $ids[Yii::$app->user->id]['ids']])->all();
        return $modelGoods;
    }

    public function actionToOrder()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $modelConsumer = new Consumers();
        $modelOrder = new Orders();
        $consumer = [
            'full_name' => $data['data']['fullname'],
            'phone' => $data['data']['phone'],
            'email' => $data['data']['email'],
        ];

        $order = [
            'user_id' => Yii::$app->user->id,
            'address' => $data['data']['address'],
            'phone' => $data['data']['phone'],
            'email' => $data['data']['email'],
            'date' => date('Y-m-d'),
            'number_order' => time(),
            'order' => json_encode($data['data']['goods']),
        ];

        $modelConsumer->attributes = $consumer;
        $modelOrder->attributes = $order;
        if ($modelOrder->save() && $modelConsumer->save()) {
            $basketGoods = new BasketGoods();
            $basketGoods->removeFromBasket($data['data']['goods']);
            return true;
        }

        return false;
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

        $response = [
            'hotels' => [],
            'pagination' => [
                'page' => '',
                'rowPerPage' => '',
                'countPage' => ''
            ]
        ];

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
                'currency' => $data['filter']['currency'],
                'checkIn' => $data['filter']['dateStart'],
                'checkOut' => $data['filter']['dateEnd'],
                'limit' => $data['filter']['limit']
            ]
        ];
        // Скачивание сразу нескольких страниц
        // инициализируем "контейнер" мультизапросов (мультикурл)
        $multi_init = curl_multi_init();

        // массив отдельных заданий
        $jobs = [];

        // проходим по каждому URL-адресу
        foreach ($urlsSer as $index =>  $urlSer) {
            // подключаем отдельный поток (URL-адрес)
            $init = curl_init($urlSer. '?' . http_build_query($options[$index]));

            // если произойдёт перенаправление, то перейти по нему
            curl_setopt($init, CURLOPT_FOLLOWLOCATION, 1);

            // curl_exec вернёт результат
            curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);

            // таймаут соединения 10 секунд
            curl_setopt($init, CURLOPT_CONNECTTIMEOUT, 10);

            // таймаут ожидания также 10 секунд
            curl_setopt($init, CURLOPT_TIMEOUT, 10);

            // HTTP-заголовок ответа не будет возвращён
            curl_setopt($init, CURLOPT_HEADER, 0);

            // добавляем дескриптор потока в массив заданий
            $jobs[$urlSer] = $init;

            // добавляем дескриптор потока в мультикурл
            curl_multi_add_handle($multi_init, $init);
        }

        // кол-во активных потоков
        $thread = null;

        // запускаем исполнение потоков
        do {
            $thread_exec = curl_multi_exec($multi_init, $thread);
        }
        while ($thread_exec == CURLM_CALL_MULTI_PERFORM);

        // исполняем, пока есть активные потоки
        while ($thread && ($thread_exec == CURLM_OK)) {

            // если поток готов к взаимодествию
            if (curl_multi_select($multi_init) != -1) {

                // ждем, пока что-нибудь изменится
                do {
                    $thread_exec = curl_multi_exec($multi_init, $thread);

                    // читаем информацию о потоке
                    $info = curl_multi_info_read($multi_init);

                    // если поток завершился
                    if ($info['msg'] == CURLMSG_DONE) {

                        $init = $info['handle'];

                        // ищем URL страницы по дескриптору потока в массиве заданий
                        $page = array_search($init, $jobs);

                        // скачиваем содержимое страницы
                        $jobs[$page] = curl_multi_getcontent($init);

                        // распарисиваем и сохряняем ее
                        $response[] = json_decode($jobs[$page], true);

                        // удаляем поток из мультикурла
                        curl_multi_remove_handle($multi_init, $init);

                        // закрываем отдельный поток
                        curl_close($init);

                    }
                }
                while ($thread_exec == CURLM_CALL_MULTI_PERFORM);
            }
        }
        // закрываем мультикурл
        curl_multi_close($multi_init);

        // изменить на не последовательное а на синхронное!!!
//        $mch = curl_multi_init();
//        $chs = [];
//        foreach ($urlsSer as $index => $urlSer) {
//            $chs[$index] = ($ch = curl_init());
//            curl_setopt($ch, CURLOPT_URL, $urlsSer[$index] . '?' . http_build_query($options[$index]));
//            curl_setopt($ch, CURLOPT_HEADER, 0 );
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_multi_add_handle($mch, $ch);
//        }
//
//        $prev_running = $running = null;
//        do {
//            curl_multi_exec($mch, $running);
//            if ($running != $prev_running) {
//                $info = curl_multi_info_read($mch);
//                if (is_array($info) && ($ch = $info['handle'])) {
//                    $content = curl_multi_getcontent( $ch );
//                    $response[] = json_decode($content, true);
//                }
//                $prev_running = $running;
//            }
//        } while ($running > 0);
//
//        foreach ( $chs as $ch ) {
//            curl_multi_remove_handle( $mch, $ch );
//            curl_close( $ch );
//
//        }
//        curl_multi_close($mch);

        $list = [];
        foreach ($response as $listHotels) {
            if (array_key_exists('status', $listHotels)) {
                if ($listHotels['status'] == 'ok') {
                    $list = array_values($listHotels['results']['hotels']);
                }
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

        $modelFavorite = new FavoriteProducts();
        $keysHotels = array_pad([], count($keys), '');
        $paramsHotel = array_combine($keys, $keysHotels);
        foreach ($list as $index => $hotel) {
            foreach ($paramsHotel as $paramHotel => $value)
                $hotels[$index][$paramHotel] =
                     Hotels::isValueInArray($paramHotel, $hotel) ? $hotel[$paramHotel] : '';
                    if (!Yii::$app->user->isGuest) {
                        $id = $hotel['id'] != '' ? $hotel['id'] : $hotel['hotelId'];
                        $hotels[$index]['favorite'] = $modelFavorite->getProduct(Yii::$app->user->identity->id, $id);
                    }
        }

        foreach ($hotels as $index => $hotel) {
            $model = new Hotels();
            $model->location_name = $hotel['locationName'];
            $model->label = $hotel['label'];
            $model->location = $model->arrayToJSON($hotel['location']);
            $model->full_name = $hotel['fullName'];
            $model->hotel_id = $hotel['id'] ?  $hotel['id'] :  $hotel['hotelId'];
            $model->location_id =  $hotel['locationId'];
            $model->hotel_name = $hotel['hotelName'];
            $model->price_form =  $hotel['priceFrom'];
            $model->price_percentile = $model->arrayToJSON($hotel['pricePercentile']);
            $model->stars =  $hotel['stars'];
            $model->price_avg =  $hotel['priceAvg'];
            if ($model->validate() && $model->save()) {
                $response['saveHotel'][$index] = true;
            } else {
                $response['saveHotel'][$index] = $model->getErrors();
            }
        }

        $page = $data['filter']['page'];
        $rowPerPage = $data['filter']['rowPerPage'];
        $offset = $page == 1 ? 0 : ($page - 1)*$rowPerPage;
        $countPage = (int) ceil(count($hotels) / $rowPerPage);

        $response['pagination']['page'] = $page;
        $response['pagination']['rowPerPage'] = $rowPerPage;
        $response['pagination']['countPage'] = $countPage;
        $response['hotels'] = array_slice($hotels, $offset, $rowPerPage);
        $modelListFilterHotel = new ListFilterHotel($hotels);

        return $response;
    }

    /**
     * @return string
     */
    public function actionTour()
    {
        return $this->render('tour', [
//            'resorts' => $resorts,
//            'countries' => $countries
        ]);
    }

    public function actionGetTours()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'countries' => [],
            'resorts' => [],
            'pagination' => [
                'page' => '',
                'rowPerPage' => '',
                'countPage' => ''
            ],
            'ids' => []
        ];
        $response['countries'] = ListCountry::find()->all();
        $response['ids'] = ListCountry::find()->select('country_id')->indexBy('country_id')->column();
        $results = ListResorts::find()->where(['resort_country_id' => $response['ids']])->all();
        foreach($results as $index => $result) {
            if (is_array($response['resorts'][$result['resort_country_id']])) {
                array_push($response['resorts'][$result['resort_country_id']], $result);
            } else {
                $response['resorts'][$result['resort_country_id']] = [];
                array_push($response['resorts'][$result['resort_country_id']], $result);
            }
        }

        $page = $data['data']['page'];
        $rowPerPage = $data['data']['rowPerPage'];
        $offset = $page == 1 ? 0 : ($page - 1)*$rowPerPage;
        $countPage = (int) ceil(count($response['countries']) / $rowPerPage);

        $response['pagination']['page'] = $page;
        $response['pagination']['rowPerPage'] = $rowPerPage;
        $response['pagination']['countPage'] = $countPage;
        $response['countries'] = array_slice($response['countries'], $offset, $rowPerPage);

        return $response;
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $message = [];
        $res_status = true;

        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

            if ($res_status != false) {
                $model->attributes = $data['data'];

                if ($model->signup()) {
                    $message = ['result_req' => 'Спасибо за регистрацию! Проверьте свой E-mail.'];
                } else {
                    $res_status = false;
                    $message = ['error' => $model->getErrors()];
                }
            }

            return [
                'message' => $message,
                'res_status' => $res_status,
            ];
        }
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
            Yii::$app->session->setFlash('success', 'Ваше email был подтвержден!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'К сожалению, мы не можем подтвердить вашу учетную запись с помощью предоставленного токена.');
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

    public function actionGetCalendarEvent()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'today' => '',
            'calendarEvent' => []
        ];

        $response['today'] = date("Y-m-d");
        $modelCalendarEvent = CalendarEvents::find()
            ->select('event, created_at')
            ->where(['>=', 'created_at', $response['today']])
            ->all();
        $response['calendarEvent'] = $modelCalendarEvent;

        return $response;
    }
}
