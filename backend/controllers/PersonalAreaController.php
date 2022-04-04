<?php

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use common\models\User;

class PersonalAreaController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Загрузка файла
     *
     * @return array
     */
    public function actionUploadFile()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response = [
            'res' => false,
            'error' => '',
            'createDir' => ''
        ];
        try {
            $userModel = User::find()->select('id, email')->where(['id' => Yii::$app->user->identity->id])->one();
            $id = $userModel['id'];
            $email = substr($userModel['email'], 0, strpos($userModel['email'], '@'));
            $path = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/images/uploadFiles/';
            $nameDir = $email.'_'.$id;
            if (!$this->isDir($nameDir, $path)) {
                $response['createDir'] = $this->createDir($nameDir, $path);
            }

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $path.$nameDir.'/'.$_FILES['file']['name'])) {
                $response['error'] = 'Ошибка записи файла ' . $_FILES['file']['name'];
                return $response;
            }

            $response['res'] = true;

            return $response;
        }catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            return $response;
        }
    }

    /**
     * @param $nameDir
     * @param $path
     * @return bool
     */
    public function isDir($nameDir, $path)
    {
        if (file_exists($path . $nameDir)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $nameDir
     * @param $path
     * @return bool
     */
    public function createDir($nameDir, $path)
    {
        $dir = $path.$nameDir;
        return mkdir($dir, 0777, true);
    }


}
