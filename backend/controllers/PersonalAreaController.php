<?php

namespace backend\controllers;

use yii\web\Controller;
use Yii;
use common\models\User;
use common\traits\ImageTrait;

class PersonalAreaController extends Controller
{
    public $enableCsrfValidation = false;
    use ImageTrait;

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
            'createDir' => '',
            'nameImage' => '',
            'delImage' => ''
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

            $this->getNameFile($path, $nameDir);
            $this->getParamsImage($path.$nameDir, $this->nameImage[2]);
            $this->createImage($path, $nameDir, $this->type);

            if ($this->delFile($nameDir, $path)) {
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $path.$nameDir.'/'.$_FILES['file']['name'])) {
                    $response['error'] = 'Ошибка записи файла ' . $_FILES['file']['name'];
                    return $response;
                }
            }
            $this->getNameFile($path, $nameDir);
            $response['delImage'] = $this->delImage;
            $response['nameImage'] = $this->nameImage[2];
            $response['res'] = true;

            return $response;
        }catch (\Exception $e) {
            $response['error'] = $e->getMessage();
            return $response;
        }
    }

    public function actionGetAvatarUser()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response = [
            'avatar' => '',
            'default' => '',
            'error' => ''
        ];
        $userModel = User::find()->select('id, email')->where(['id' => Yii::$app->user->identity->id])->one();
        $id = $userModel['id'];
        $email = substr($userModel['email'], 0, strpos($userModel['email'], '@'));
        $path = $_SERVER['DOCUMENT_ROOT'] . '/backend/web/images/uploadFiles/';
        $nameDir = $email.'_'.$id;
        if ( count($this->getNameFile($path, $nameDir)) < 3) {
            $response['dafault'] = '/backend/web/images/uploadFiles/default.jpg';
        } else {
            $response['avatar'] = '/backend/web/images/uploadFiles/' . $nameDir . '/' . $this->nameImage[2];
        }

        return $response;
    }

    /**
     * Редактирование персогнальных данных
     *
     * @return mixed
     */
    public function actionUpdateUserData()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $response = [
            'res' => false,
            'error' => [],
            'userDatas' => []
        ];
        $modelUser = User::find()->where(['id' => Yii::$app->user->identity->id])->one();
        $modelUser->username = $data['userDatas']['username'];
        $modelUser->email = $data['userDatas']['email'];
        $modelUser->phone = $data['userDatas']['phone'];
        if ($modelUser->validate() && $modelUser->save()) {
            $response['userDatas'] = User::find()->select('username, email, phone')->where(['id' => Yii::$app->user->identity->id])->one();
            $response['res'] = true;
        } else {
            $response['error'] = $modelUser->getErrors();
        }

        return $response;
    }

    /**
     * Получение персональных данных
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function actionGetUserData()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $modelUser = User::find()
            ->select('username, email, phone')
            ->where(['id' => Yii::$app->user->identity->id])
            ->one();
        return $modelUser;
    }

    /**
     * @param $nameDir
     * @param $path
     * @return bool
     */
//    public function isDir($nameDir, $path)
//    {
//        if (file_exists($path . $nameDir)) {
//            return true;
//        } else {
//            return false;
//        }
//    }

    /**
     * @param $nameDir
     * @param $path
     * @return bool
     */
//    public function createDir($nameDir, $path)
//    {
//        $dir = $path.$nameDir;
//        return mkdir($dir, 0777, true);
//    }
}
