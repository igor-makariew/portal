<?php

namespace backend\controllers;

use common\models\listResorts\ListResorts;
use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\Url;
use common\models\listCountry\ListCountry;
use common\traits\BreadcrumbsTrait;
use Codeception\PHPUnit;

class Test  {

    protected $num;
    public function __construct()
    {
        $this->num = 0;
    }

    public function ride()
    {
        return ' Igor! ';
    }
}

class Test3 extends Test
{
    public function __construct()
    {
        parent::__construct();
    }

    public $num = 7;
    public function ride()
    {
        return ' Igor - Katay = son! ';
    }
}

class Test2 extends Test
{
    public function __construct()
    {
        parent::__construct();
    }


    public function updParentNum()
    {
        return $this->num = 12;
    }

    public function ride()
    {
        return 'Igor - Katay! ';
    }
}

abstract class Ferrari
{

}

class Porsche extends Ferrari
{
    public static $parentNameClass;

    public function __construct()
    {
        self::$parentNameClass = get_parent_class($this);
    }

    public function printText()
    {
        return 'PORSCHE cool car!';
    }

    public static function parentClass()
    {
        return new static();
    }
}

interface Mercedes
{
    const TOOL = 5;
    const REAL = 7;
    const CARTOON = 12;
}

class Red
{
    public function getConst()
    {
        return Mercedes::TOOL + Mercedes::CARTOON;
    }
}

class MyException extends \yii\db\Exception
{
    public function __construct($message, $errorInfo = [], $code = '', $previous = null)
    {
        parent::__construct($message, $errorInfo, $code, $previous);
    }
}

class Except
{
    public function inverse($znamenatel)
    {
        if ($znamenatel == 0) {
            throw new MyException('Деление на 0 запрещено');
        }

        return 1/$znamenatel;
    }

    public function myException()
    {
        try {
            var_dump($this->inverse(5));
            var_dump($this->inverse(3));
            var_dump($this->inverse(1));
            var_dump($this->inverse(0));
        } catch (MyException $exception) {
            $response = $exception->getMessage();
            var_dump($response);
        }
        /*
        finally {
            var_dump($response);
        }*/
    }
}


class CountriesController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    public $test, $test2, $test3;
    public $ferrari;
    public $red;
    public $except;

    public function __construct($id, $module, $config = [])
    {
        $this->test = new Test();
        $this->test2 = new Test2();
        $this->test3 = new Test3();
        $this->ferrari = new Porsche();
        $this->red = new Red();
        $this->except = new Except();

        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->sessionUrl();
        $this->sessionBreadcrumbs();
        list($route, $param) = Yii::$app->request->resolve();
        $this->createBreadcrumbs($this->routes, $route);

//        изучить очереди
        
//        $resorts = new ListResorts();
//        $listResorts = $resorts->handlerListResorts();
//        $spl = new \SplQueue();
//        $spl->enqueue('test1');
//        $spl->enqueue('test2');
//        $spl->enqueue('test3');
//        $spl->enqueue('test4');

        return $this->render('index', [
            'breadcrumbs' => $this->breadcrumbs,
//            'listResorts' => $listResorts
        ]);
    }

    public function car(string $str, int $number)
    {
        $this->cars( $number,  $str);
        $integer = new Integer(5);
        return $integer;
        return $str . ' - ' . $number;
    }

    public function cars(int $number, string $str)
    {
        return $number. ' - ' . $str;
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
     * редактирование строк
     *
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
        $isModelListCountry = ListCountry::find()->where(['country_id' => $data['item']['country_id']])->count();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($isModelListCountry == 1) {
                if ( $modelListResorts->validate() && $modelListResorts->save()) {
                    $response['response'] = true;
                    $transaction->commit();
                }
            } else {
                if ( $modelListCountry->validate() && $modelListCountry->save() && $modelListResorts->validate() && $modelListResorts->save()) {
                    $response['response'] = true;
                    $transaction->commit();
                }
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            $response['message'] = $exception->getMessage();
            $response['errorCountry'] = $modelListCountry->errors;
            $response['errorResorts'] = $modelListResorts->errors;
        }

        return $response;
    }

    public function actionDelete()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
//        $response = [
//            'response' => false,
//            'message' => '',
//            'errorCountry' => '',
//            'errorResorts' => ''
//        ];
//        $modelListCountry = ListCountry::find()->where(['country_id' => $data['item']['country_id']])->one();
//        $countResorts = ListResorts::find()->where(['resort_country_id' => $data['item']['country_id']])->count();
//        $modelListResort = ListResorts::find()->where(['resorts_id' => $data['item']['resorts_id']])->one();
//        $transaction = Yii::$app->db->beginTransaction();
//        try {
//            if ($countResorts > 1) {
//                if ($modelListResort->delete()) {
//                    $response['response'] = true;
//                    $transaction->commit();
//                }
//            } else {
//                if ($modelListCountry->delete() && $modelListResort->delete()) {
//                    $response['response'] = true;
//                    $transaction->commit();
//                }
//            }
//        } catch (Exception $exception) {
//            $transaction->rollBack();
//            $response['message'] = $exception->getMessage();
//            $response['errorCountry'] = $modelListCountry->errors;
//            $response['errorCountry'] = $modelListResort->errors;
//        }

        return $data;

    }
}




