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
            <div class="row">
                <div class="col-8">
                    <div class="text-center" v-if="loaderUserDatas">
                        <v-progress-circular
                                :size="160"
                                :width="5"
                                color="primary"
                                indeterminate
                        ></v-progress-circular>
                    </div>
                    <div v-if="!loaderUserDatas">
                        <v-form>
                            <v-text-field
                                    v-model="userDatas.username"
                                    :counter="15"
                                    :rules="usernameRule"
                                    label="Имя"
                            ></v-text-field>
                            <v-text-field
                                    v-model="userDatas.email"
                                    :rules="emailRule"
                                    label="E-mail"
                            ></v-text-field>
                            <v-text-field
                                    v-model="userDatas.phone"
                                    :rules="phoneRule"
                                    label="Телефон"
                            ></v-text-field>
                            <div class="mb-3">
                                <div class="container-row">
                                    <div class="col-6">
                                        <v-checkbox
                                            v-model="validUserData"
                                            label="Редактирование данных пользователя"
                                        ></v-checkbox>
                                    </div>
                                    <div class="col-6 text-right pt-5">
                                        <v-btn
                                            color="success"
                                            :disabled="!validUserData"
                                            @click="updateUserData"
                                        >Редактировать</v-btn>
                                    </div>
                                </div>
                            </div>
                        </v-form>
                    </div>
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
                <div class="col-4">

                </div>
            </div>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vuePersonalArea.js'), ['depends' => ['backend\assets\AppAsset']]); ?>


