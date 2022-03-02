<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
                'label' => 'Дата регистрации пользователя',
                'attribute' => 'created_at',
                'format' =>  'datetime'
            ],
            [
                'class' => ActionColumn::className(),
                'header' => 'Действие',
                'template' => '{view}&nbsp&nbsp {update}&nbsp&nbsp {delete}',
            ],
        ],
    ]); ?>
</div>
