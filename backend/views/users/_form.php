<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\users\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'role')->dropDownList([
            'user' => 'user',
            'moder' => 'moder',
            'admin' => 'admin',
    ]) ?>

    <?= $form->field($model, 'mailing_list')->dropDownList([
            '0' => 'No',
            '1' => 'Yes',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
