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

    <?php Modal::begin(['header'=>'My modal data', 'id'=>'mymodal'])?>
    <?php Modal::end()?>

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
