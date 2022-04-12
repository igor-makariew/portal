<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php //var_dump(Yii::$app->getAuthManager()->getRoles()); ?>
<?//= var_dump(Yii::$app->authManager->roles); ?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/users.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<div id="appUsers">
    <v-app id="inspire">
        <v-card color="basil">
            <v-tabs
                v-model="tab"
                background-color="transparent"
                color="basil"
                grow
                class="mb-9"
            >
                <v-tab
                    v-for="item in items"
                    :key="item"
                    @click="pageNavigation($event)"
                >
                    {{ item }}
                </v-tab>
            </v-tabs>

            <v-tabs-items
                    v-model="tab"
                    class="container-personal"
            >
                <v-tab-item
                    v-for="item in items"
                    :key="item"
                >
                    <template v-if="links.users">
                        <div class="users-index">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'tableOptions' => [
                                    'class' => 'table table-striped table-bordered'
                                ],
                                'rowOptions' => function($model, $key, $index, $grid) {
                                    $class = $index % 2 ? 'odd' : 'even';
                                    return [
                                        'key' => $key,
                                        'index' => $index,
                                        'class' => $class,
                                    ];
                                },
                                'layout' => "{summary}\n{items}\n{pager}",
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        // скрытие счетчика
                                        'visible' => false

                                    ],
                                    'id',
                                    'username',
                                    'email:email',
                                    'phone',
                                    'status',
                                    'role',
                                    [
                                        'label' => 'Photo',
                                        'format' => 'raw',
                                        'value' => function ($data) {
                                            return '<a type="button" class="" data-toggle="modal" data-target="#exampleModalLong'.$data['id'].'">
                          <img src="/admin/images/my_girl'.$data['id'].'.jpg" style="width: 20px" alt="Кнопка «button»">
                        </a>
                        <div class="modal fade" id="exampleModalLong'.$data['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">My_girl'.$data['id'].'</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="/admin/images/my_girl'.$data['id'].'.jpg" style="width: 500px; height: 800px" alt="my_girl"/>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>                                      
                                </div>
                            </div>
                        </div>
                    </div>';

//                    return Html::img(Url::toRoute(['images/my_girl.jpg']), [
//                       'alt' => 'фото',
//                       'style' => 'width:20px',
//                       'onclick' => 'alert("test")'
//                    ]);
                                        }
                                    ],
                                    [
                                        'label' => 'Дата регистрации пользователя',
                                        'attribute' => 'created_at',
                                        'format' =>  ['date', 'HH:mm:ss dd.MM.yyyy']
                                    ],
                                    [
                                        'class' => ActionColumn::class,
                                        'header' => 'Действие',
//                'headerOptions' => ['width' => '80'],
                                        'template' => '{view}&nbsp&nbsp {update}&nbsp&nbsp {delete}',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a('<span class="glyphicon glyphicon-eye-open text-blue"></span>', $url);
                                            },
                                            'update' => function ($url, $model, $key) {
                                                return Html::a('<span class="glyphicon glyphicon-pencil text-green"></span>', $url);
                                            },
                                            'delete' => function ($url, $model, $key) {
                                                return Html::a('<span class="glyphicon glyphicon-trash text-red"></span>', $url, [
                                                    'data' => [
                                                        'confirm' => 'Вы уверены что хотите удалить строку?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>

                            <?php
                            echo '<label class="form-label">Birth Date</label>';
                            echo DatePicker::widget([
                                'name' => 'dp_5',
                                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                                'value' => date('d-M-Y'),
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-M-yyyy',
                                    'calendarWeeks' => true, // номер недели
                                    'clearBtn' => true, // очистить календарь
                                    'language' => 'ru'

                                ],
                                'options' => [
                                    'style' => 'color:green'
                                ],
                                'pluginEvents' => [
                                    "show" => "function(e) {
                console.log(e);
             }",
                                    "hide" => "function(e) {  
                console.log(e);
             }",
                                    "clearDate" => "function(e) { 
                console.log(e);    
            }",
//                "changeDate" => "function(e) {  # `e` here contains the extra attributes }",
//                "changeYear" => "function(e) {  # `e` here contains the extra attributes }",
//                "changeMonth" => "function(e) {  # `e` here contains the extra attributes }",
                                ],
                            ]);
                            ?>
                        </div>
                    </template>
                <?php if (Yii::$app->user->can('administrator')):?>
                    <template v-if="links.roles">
                        <div class="text-center" v-if="loaderRoles">
                            <v-progress-circular
                                    :size="160"
                                    :width="5"
                                    color="primary"
                                    indeterminate
                            ></v-progress-circular>
                        </div>
                        <div v-if="!loaderRoles">
                            <v-form v-model="validRoles">
                                <v-text-field
                                        v-model="createRolePermission.newRole"
                                        :rules="newRoleRule"
                                        :counter="15"
                                        label="Новая роль"
                                ></v-text-field>
                                <v-text-field
                                        v-model="createRolePermission.descriptionRole"
                                        :rules="descriptionRoleRule"
                                        label="Описание роли"
                                ></v-text-field>
                                <v-text-field
                                        v-model="createRolePermission.newPermission"
                                        :rules="newPermissionRule"
                                        label="Разрешение роли"
                                ></v-text-field>
                                <v-text-field
                                        v-model="createRolePermission.descriptionPermission"
                                        :rules="descriptionPermissionRule"
                                        label="Описание разрешения"
                                ></v-text-field>
                                <v-text-field
                                        v-model="createRolePermission.userId"
                                        :rules="descriptionPermissionRule"
                                        label="Идентификатор пользователя"
                                ></v-text-field>
                                <div class="mb-3">
                                    <div class="container-row text-right">
                                            <v-btn
                                                color="success"
                                                :disabled="!validRoles"
                                                @click="createRoles"
                                            >Создать</v-btn>
                                    </div>
                                </div>
                            </v-form>
                        </div>
                    </template>
                <?php else:?>
                    <template v-if="links.roles">
                        <div>
                            <h3 class="red--text">Только у администратора есть права доступа на эту вкладку!</h3>
                        </div>
                    </template>
                <?php endif; ?>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueUsers.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
