<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\users\Users */

$this->title = 'Редактирование пользователя №: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="users-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
