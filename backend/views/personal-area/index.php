<?php
/* @var $this yii\web\View */

//use Yii;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/personal.css'), ['depends' => ['backend\assets\AppAsset']]); ?>
<?= $this->registerCssFile('https://use.fontawesome.com/releases/v5.0.13/css/all.css') ?>

<div id="personal-area">
    <v-app id="inspire">
        <div class="container-personal">
            <v-file-input
                accept="image/*"
                label="Загрузка файла"
                show-size
                outlined
                dense
                @change="uploadFile"
            ></v-file-input>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vuePersonalArea.js'), ['depends' => ['backend\assets\AppAsset']]); ?>


