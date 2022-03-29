<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;

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
//            'id',
            'username',
            'email:email',
            'phone',
            'status',
            [
                'label' => 'Дата регистрации пользователя',
                'attribute' => 'created_at',
                'format' =>  'datetime'
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
