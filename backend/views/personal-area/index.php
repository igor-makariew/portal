<?php
/* @var $this yii\web\View */

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

?>

<div id="personal-area">
    <v-app id="inspire">
        <div class="container">
            <v-file-input
                accept="image/*"
                label="Загрузка файла"
            ></v-file-input>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vuePersonalArea.js'), ['depends' => ['backend\assets\AppAsset']]); ?>


