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
            <div class="text-center" v-if="loaderUploadFile">
                <v-progress-circular
                        :size="40"
                        :width="3"
                        color="primary"
                        indeterminate
                ></v-progress-circular>
            </div>
            <div v-if="!loaderUploadFile">
                <p class="text--darken-1">Загрузка аватарки</p>
                <v-file-input
                        accept="image/*"
                        label="Загрузка файла"
                        show-size
                        outlined
                        dense
                        v-model="uplFile"
                        @change="uploadFile"
                ></v-file-input>
            </div>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vuePersonalArea.js'), ['depends' => ['backend\assets\AppAsset']]); ?>


