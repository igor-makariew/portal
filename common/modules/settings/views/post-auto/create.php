<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\models\postauto\PostAuto */

$this->title = 'Create Post Auto';
$this->params['breadcrumbs'][] = ['label' => 'Post Autos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-auto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
