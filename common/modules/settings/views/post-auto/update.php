<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\models\postauto\PostAuto */

$this->title = 'Update Post Auto: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Post Autos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="post-auto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
