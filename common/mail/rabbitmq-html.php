<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="verify-email">
    <p>Привет <?= Html::encode($user->username) ?>,</p>

    <p>Вам был выслан отчет:</p>
</div>
