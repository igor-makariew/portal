<?php

namespace backend\controllers;

use common\models\User;
use common\models\calendarEvent\CalendarEvents;
use common\traits\BreadcrumbsTrait;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    public $enableCsrfValidation = false;
    use BreadcrumbsTrait;

    /**
     * @inheritDoc
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'actions' => ['index', 'create', 'update', 'view','delete', 'create-role-permission', 'create-events' ],
//                        'roles' => [User::ROLE_ADMIN],
//
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['index', 'view', 'create-role-permission', 'create-events' ],
//                        'roles' => [User::ROLE_MODER],
//                    ],
//
//                ],
//            ],
//
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'delete' => ['POST'],
//                    'create-events' => ['POST'],
//                ],
//            ],
//        ];
//    }

    /**
     * Lists all Users models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateRolePermission()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
//        создание новой роли плоьзоавтеля
        $role = Yii::$app->authManager->createRole($data['data']['newRole']);
        $role->description = $data['data']['descriptionRole'];
        Yii::$app->authManager->add($role);
//        создание разрешения
        $permit = Yii::$app->authManager->createPermission($data['data']['newPermission']);
        $permit->description = $data['data']['descriptionPermission'];
        Yii::$app->authManager->add($permit);
//        наследование роли и разрешения
        $userRole = Yii::$app->authManager->getRole($data['data']['newRole']);
        $userPermit = Yii::$app->authManager->getPermission($data['data']['newPermission']);
        Yii::$app->authManager->addChild($userRole, $userPermit);
//        привязка роли к пользователю
        Yii::$app->authManager->assign($userRole, $data['data']['userId']);

        return $data;
    }


    /**
     * создание событий в календаоре
     *
     * @return bool
     */
    public function actionCreatecalendarevent()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());

        $modelCalendarEvent = new CalendarEvents();
        $valueCalendarEvent = [
            'name' => $data['data']['descriptionEvent'],
            'event' => $data['data']['event'],
            'created_at' => $data['data']['date'],
        ];
        $modelCalendarEvent->attributes = $valueCalendarEvent;
        if ($modelCalendarEvent->save()) {
            return true;
        }

        return false;
    }
}
