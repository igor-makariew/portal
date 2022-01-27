<?php

use frontend\assets\AppAsset;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Url;
use frontend\widgets\countBasket;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <?= $this->registerLinkTag(['rel' => 'icon', 'href' => Url::to(['images/favicons/favicon.ico'])]); ?>
        <?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/personal-area.css'), ['depends' => ['frontend\assets\AppAsset']]); ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= Url::to(['/site/index']) ?>">dirEngine.</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                    aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
<!--            <div id="appRegistration" data-guest="--><?//= (int) Yii::$app->user->isGuest; ?><!--" class="text-left">-->
                <div class="collapse navbar-collapse" id="ftco-nav">
                    <ul class="navbar-nav ml-auto" id="active_menu">
                        <li class="nav-item"><a href="<?= Url::to(['/site/index']) ?>" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="<?= Url::to(['/site/about']) ?>" class="nav-link">About</a></li>
                        <li class="nav-item"><a href="<?= Url::to(['/site/tour']) ?>" class="nav-link">Tour</a></li>
                        <li class="nav-item"><a href="<?= Url::to(['/site/hotels']) ?>" class="nav-link">Hotels</a></li>
                        <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
                        <li class="nav-item"><a href="<?= Url::to(['/basket/index'])?>" class="nav-link">Contact</a></li>
                        <li class="nav-item cta"><a href="contact.html" class="nav-link"><span>Add listing</span></a>
                        </li>
                    </ul>

                        <!--  Подключение идет не через v-app а через div                       -->
                    <div id="appRegistration" data-guest="<?= (int) Yii::$app->user->isGuest; ?>">
                        <div data-app="true" class="v-application height-form v-application--is-ltr ml-2" id="inspire">

                            <v-dialog v-model="dialogAlert" max-width="700px">
                                <v-card>
                                    <v-card-title class="text-h5 text-justify">{{dialogAlertTitle}}</v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="dialogAlert = false">OK</v-btn>
                                        <v-spacer></v-spacer>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                            <template v-if="isGuest">
                                <v-icon
                                        color="blue"
                                        style="cursor: pointer"
                                        @click="windowRegistration"
                                >{{ 'mdi-account'}}
                                </v-icon>
                                <div class="text-center">
                                    <v-dialog v-model="dialog" width="100%">
                                        <v-card>
                                            <template v-if="checked === 'authorization'">
                                                <v-card-title class="text-h5 grey lighten-2">
                                                    Авторизация
                                                </v-card-title>
                                            </template>
                                            <template v-else>
                                                <v-card-title class="text-h5 grey lighten-2">
                                                    Регистрация
                                                </v-card-title>
                                            </template>

                                            <template v-if="checked === 'authorization'">
                                                <v-card-text>
                                                    <div class="mt-5"></div>
                                                    <v-form v-model="validAuto">
                                                        <v-text-field
                                                                v-model="emailAuto"
                                                                :rules="emailAutoRules"
                                                                label="E-mail"
                                                                required
                                                        ></v-text-field>
                                                        <v-text-field
                                                                type="password"
                                                                v-model="passwordAuto"
                                                                :rules="passwordAutoRules"
                                                                label="Пароль"
                                                                required
                                                        ></v-text-field>
                                                        <v-container fluid>
                                                            <v-radio-group
                                                                    v-model="checked"
                                                                    row
                                                            >
                                                                <v-radio
                                                                        label="Регистрация"
                                                                        value="registration"
                                                                ></v-radio>
                                                                <v-radio
                                                                        label="Авторизация"
                                                                        value="authorization"
                                                                ></v-radio>
                                                            </v-radio-group>
                                                        </v-container>
                                                        <v-col class="text-right">
                                                            <v-btn
                                                                    :disabled="!validAuto"
                                                                    color="success"
                                                                    class="mr-4"
                                                                    @click="authorization"
                                                            >Авторизация
                                                            </v-btn>
                                                        </v-col>
                                                    </v-form>
                                                </v-card-text>
                                            </template>

                                            <template v-else>
                                                <v-card-text>
                                                    <div class="mt-5"></div>
                                                    <v-form v-model="valid">
                                                        <v-text-field
                                                                v-model="name"
                                                                label="Имя"
                                                                :rules="nameRules"
                                                                required
                                                        ></v-text-field>
                                                        <v-text-field
                                                                v-model="phone"
                                                                label="Телефон"
                                                        ></v-text-field>
                                                        <v-text-field
                                                                v-model="email"
                                                                label="E-mail"
                                                                :rules="emailRules"
                                                                required
                                                        ></v-text-field>
                                                        <v-text-field
                                                                type="password"
                                                                v-model="password"
                                                                label="Пароль"
                                                                :rules="passwordRules"
                                                                required
                                                        ></v-text-field>
                                                        <v-container fluid>
                                                            <v-radio-group
                                                                    v-model="checked"
                                                                    row
                                                            >
                                                                <v-radio
                                                                        label="Регистрация"
                                                                        value="registration"
                                                                ></v-radio>
                                                                <v-radio
                                                                        label="Авторизация"
                                                                        value="authorization"
                                                                ></v-radio>
                                                            </v-radio-group>
                                                        </v-container>
                                                        <v-col class="text-right">
                                                            <v-btn
                                                                    :disabled="!valid"
                                                                    color="success"
                                                                    class="mr-4"
                                                                    @click="registration"
                                                            >Регистрация
                                                            </v-btn>
                                                        </v-col>
                                                    </v-form>
                                                </v-card-text>
                                            </template>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                </template>
                            <template v-else>
                                <v-icon
                                        color="success"
                                        style="cursor: pointer"
                                        @click="windowRegistration"
                                >{{ 'mdi-account'}}
                                </v-icon>
                                <div class="text-center">
                                    <v-dialog v-model="dialog" width="100%">
                                        <v-card >
                                            <template>
                                                <v-card-title class="text-h5 grey lighten-2">
                                                    Личный кабинет
                                                </v-card-title>
                                            </template>

                                            <template>
                                                <v-col cols="12">
                                                    <v-card class="no-border">
                                                        <v-row no-gutters align="stretch" justify="center">
                                                            <v-col cols="2" align="center" class="black--text pa-4">
                                                                <v-list rounded >
                                                                    <v-list-item-group color="primary">
                                                                        <v-list-item v-for="(item, index) in items" :key="index" @click="menuActionClick(item.action)">
                                                                            <v-list-item-icon>
                                                                                <v-icon v-text="item.icon"></v-icon>
                                                                            </v-list-item-icon>
                                                                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                                                                        </v-list-item>
                                                                    </v-list-item-group>
                                                                </v-list>
                                                            </v-col>
                                                            <v-col cols="10" align="center">
                                                                <v-img class="img-rounded d-flex align-center pa-4">
                                                                    <div class="white--text">
                                                                        <h1>{{titleMenu}}</h1>
                                                                    </div>
                                                                </v-img>
                                                                <div v-if="titleMenu == 'Аккаунт'">
                                                                    <!-- start preloader not work!!!-->
                                                                    <div class="loader-wrap text-center" v-if="loader">
                                                                        <v-progress-circular
                                                                                :rotate="-90"
                                                                                :size="100"
                                                                                :width="15"
                                                                                :value="value"
                                                                                :indeterminate="true"
                                                                                color="success"
                                                                        >
                                                                        </v-progress-circular>
                                                                    </div>
                                                                    <!-- end preloader -->

                                                                    <v-dialog v-model="dialogEdit" max-width="620px">
                                                                        <v-card>
                                                                            <v-card-title class="text-h5">Ваши персональные данные успешно обновлены.</v-card-title>
                                                                            <v-card-actions>
                                                                                <v-spacer></v-spacer>
                                                                                <v-btn color="blue darken-1" text @click="dialogEdit = false">OK</v-btn>
                                                                                <v-spacer></v-spacer>
                                                                            </v-card-actions>
                                                                        </v-card>
                                                                    </v-dialog>

                                                                    <div v-if="!loader" class="form-width-parent">
                                                                        <v-form
                                                                                ref="formEdit"
                                                                                class="form-width-child"
                                                                        >
                                                                            <v-text-field
                                                                                    v-model="personalDate.nameEdit"
                                                                                    :counter="15"
                                                                                    :rules="nameEditRules"
                                                                                    label="Имя"
                                                                            ></v-text-field>

                                                                            <v-text-field
                                                                                    v-model="personalDate.emailEdit"
                                                                                    :rules="emailEditRules"
                                                                                    label="E-mail"
                                                                            ></v-text-field>

                                                                            <v-text-field
                                                                                    v-model="personalDate.phoneEdit"
                                                                                    :rules="phoneEditRules"
                                                                                    label="Телефон"
                                                                            ></v-text-field>

                                                    <!--    start диалоговое окно для редактирования пароля пользователя    -->
                                                                            <v-dialog
                                                                                    v-model="dialogEditPassword"
                                                                                    persistent
                                                                                    max-width="900px"
                                                                            >
                                                                                <v-card>
                                                                                    <v-card-title>
                                                                                        <span class="text-h5">Редактирование пароля пользователя</span>
                                                                                    </v-card-title>
                                                                                    <v-card-text>
                                                                                        <v-form v-model="validEdiPassword">
                                                                                            <v-container>
                                                                                                <v-row>
                                                                                                    <v-col cols="12">
                                                                                                        <v-text-field
                                                                                                                v-model="editPassword"
                                                                                                                label="Пароль"
                                                                                                                type="password"
                                                                                                                :rules="editPasswordRules"
                                                                                                        ></v-text-field>
                                                                                                    </v-col>

                                                                                                    <v-col cols="12">
                                                                                                        <v-text-field
                                                                                                                v-model="newEditPassword"
                                                                                                                label="Новый пароль"
                                                                                                                require
                                                                                                                type="password"
                                                                                                                :rules="newEditPasswordRules"
                                                                                                        ></v-text-field>
                                                                                                    </v-col>
                                                                                                    <v-col cols="12">
                                                                                                        <v-text-field
                                                                                                                v-model="repeatNewEditPassword"
                                                                                                                label="Новый пароль еще раз"
                                                                                                                require
                                                                                                                type="password"
                                                                                                                :rules="[(newEditPassword == repeatNewEditPassword) || 'Пароли должны совпадать']"
                                                                                                        ></v-text-field>
                                                                                                    </v-col>
                                                                                                    <v-btn
                                                                                                            color="blue darken-1"
                                                                                                            text
                                                                                                            @click="dialogEditPassword = false"
                                                                                                    >
                                                                                                        Close
                                                                                                    </v-btn>
                                                                                                    <v-btn
                                                                                                            color="blue darken-1"
                                                                                                            text
                                                                                                            :disabled="!validEdiPassword"
                                                                                                            @click="editPasswordUser"
                                                                                                    >
                                                                                                        Save
                                                                                                    </v-btn>
                                                                                                </v-row>
                                                                                            </v-container>
                                                                                        </v-form>
                                                                                    </v-card-text>
                                                                                </v-card>
                                                                            </v-dialog>
                                                                <!--    end диалоговое окно для редактирования пароля пользователя    -->

                                                                            <div class="mb-5 mr-4">
                                                                                <a href="#" class="text-xl-center" @click="dialogEditPass">Изменить пароль</a>
                                                                            </div>
                                                                            <v-spacer></v-spacer>
                                                                            <v-btn
                                                                                    :disabled="!validEdit"
                                                                                    color="success"
                                                                                    class="mr-4"
                                                                                    @click="updateEdit"
                                                                            >
                                                                                Сохранить
                                                                            </v-btn>
                                                                        </v-form>
                                                                    </div>
                                                                </div>
                                                                <div v-if="titleMenu == 'Заказы'">
                                                                    <!-- start preloader not work!!!-->
                                                                    <div class="loader-wrap text-center" v-if="loader">
                                                                        <v-progress-circular
                                                                                :rotate="-90"
                                                                                :size="100"
                                                                                :width="15"
                                                                                :value="value"
                                                                                :indeterminate="true"
                                                                                color="success"
                                                                        >
                                                                        </v-progress-circular>
                                                                    </div>
                                                                    <!-- end preloader -->
                                                                    <div v-if="!loader">
                                                                        <v-data-table
                                                                                :headers="headers"
                                                                                :items="desserts"
                                                                                sort-by="number"
                                                                                class="elevation-1"
                                                                                :footer-props="{
                                                                                    itemsPerPageText: 'Количество заказов на странице',
                                                                                    itemsPerPageOptions:[30, 60, 90, -1]
                                                                                }"
                                                                                :search="search"
                                                                        >
                                                                            <template v-slot:top>
                                                                                <v-toolbar
                                                                                        flat
                                                                                >
                                                                                    <v-toolbar-title>Мои заказы</v-toolbar-title>
                                                                                    <v-spacer></v-spacer>

                                                                                        <v-text-field
                                                                                                v-model="search"
                                                                                                append-icon="mdi-magnify"
                                                                                                label="Поиск"
                                                                                                single-line
                                                                                                hide-details
                                                                                                class="hidden-xs-only"
                                                                                        ></v-text-field>

                                                                                    <v-spacer></v-spacer>
                                                                                    <v-dialog v-model="dialogDelete" max-width="600px">
                                                                                        <v-card>
                                                                                            <v-card-title class="text-h5">Вы уверены, что хотите удалить этот элемент?</v-card-title>
                                                                                            <v-card-actions>
                                                                                                <v-spacer></v-spacer>
                                                                                                <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                                                                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                                                                                <v-spacer></v-spacer>
                                                                                            </v-card-actions>
                                                                                        </v-card>
                                                                                    </v-dialog>
                                                                                    <v-dialog
                                                                                            v-model="dialogView"
                                                                                            persistent
                                                                                            max-width="600px"
                                                                                    >
                                                                                        <v-card>
                                                                                            <v-card-title class="text-h5">{{cardViewItem.title}}</v-card-title>
                                                                                            <v-card-actions>
                                                                                                <v-spacer></v-spacer>
                                                                                                <v-card-text class="text--primary">
                                                                                                    <div><v-rating
                                                                                                                v-model="cardViewItem.raiting"
                                                                                                                background-color="orange lighten-3"
                                                                                                                color="orange"
                                                                                                                small
                                                                                                                readonly
                                                                                                        ></v-rating></div>
                                                                                                    <div><h3>Дата - {{cardViewItem.date}}</h3></div>
                                                                                                    <div><h3>Цена - {{cardViewItem.price}}</h3></div>
                                                                                                    <v-spacer></v-spacer>
                                                                                                    <v-spacer></v-spacer>
                                                                                                    <div class="text-right">
                                                                                                        <v-btn
                                                                                                                color="blue darken-1"
                                                                                                                text
                                                                                                                @click="dialogView = false"
                                                                                                        >
                                                                                                            Закрыть
                                                                                                        </v-btn>
                                                                                                    </div>
                                                                                                </v-card-text>
                                                                                                <v-spacer></v-spacer>
                                                                                            </v-card-actions>
                                                                                        </v-card>
                                                                                    </v-dialog>
                                                                                </v-toolbar>
                                                                            </template>
                                                                            <template v-slot:item.raiting="{ item }">
                                                                                <v-rating
                                                                                        v-model="item.raiting"
                                                                                        background-color="orange lighten-3"
                                                                                        color="orange"
                                                                                        small
                                                                                        readonly
                                                                                ></v-rating>
                                                                            </template>
                                                                            <template v-slot:item.actions="{ item }">
                                                                                <v-icon
                                                                                        small
                                                                                        @click="viewItem(item)"
                                                                                        color="primary"
                                                                                >
                                                                                    mdi-eye-outline
                                                                                </v-icon>
                                                                                <v-icon
                                                                                        small
                                                                                        @click="deleteItem(item)"
                                                                                        color="red"
                                                                                >
                                                                                    mdi-delete
                                                                                </v-icon>
                                                                            </template>
                                                                            <template v-slot:no-data>
                                                                                <v-btn
                                                                                        color="primary"
                                                                                        @click="initialize"
                                                                                >
                                                                                    Reset
                                                                                </v-btn>
                                                                            </template>
                                                                        </v-data-table>
                                                                    </div>
                                                                </div>
                                                            </v-col>
                                                        </v-row>
                                                    </v-card>
                                                </v-col>
                                            </template>
                                        </v-card>
                                    </v-dialog>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
<!--            </div>-->
            <div id="appIconBasket">
                <div data-app="true" class="v-application height-form v-application--is-ltr ml-2" id="inspire">
                        <a href="<?= Url::to(['/basket/index'])?>">
                            <v-icon
                                color="success"
                                style="cursor: pointer"
                            >{{'mdi-cart-outline'}}
                            </v-icon>
                        </a>
                    <span class="basket-counter" id="countBasket" data-userid="<?= Yii::$app->user->identity->id;?>"><?= countBasket::widget() > 0 ? countBasket::widget() : ''?></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- END nav -->

    <main role="main" class="flex-shrink-0">
        <div>
            <!--            --><?//= Breadcrumbs::widget([
            //                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            //            ]) ?>
            <?= Alert::widget() ?>
            <?= $content; ?>
        </div>
    </main>

    <footer class="ftco-footer ftco-bg-dark ftco-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">dirEngine</h2>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia,
                            there live the blind texts.</p>
                        <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                            <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md">
                    <div class="ftco-footer-widget mb-4 ml-md-5">
                        <h2 class="ftco-heading-2">Information</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">About</a></li>
                            <li><a href="#" class="py-2 d-block">Service</a></li>
                            <li><a href="#" class="py-2 d-block">Terms and Conditions</a></li>
                            <li><a href="#" class="py-2 d-block">Become a partner</a></li>
                            <li><a href="#" class="py-2 d-block">Best Price Guarantee</a></li>
                            <li><a href="#" class="py-2 d-block">Privacy and Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Customer Support</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">FAQ</a></li>
                            <li><a href="#" class="py-2 d-block">Payment Option</a></li>
                            <li><a href="#" class="py-2 d-block">Booking Tips</a></li>
                            <li><a href="#" class="py-2 d-block">How it works</a></li>
                            <li><a href="#" class="py-2 d-block">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Have a Questions?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><span class="icon icon-map-marker"></span><span class="text">203 Fake St. Mountain View, San Francisco, California, USA</span>
                                </li>
                                <li><a href="#"><span class="icon icon-phone"></span><span
                                                class="text">+2 392 3929 210</span></a></li>
                                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@yourdomain.com</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">

                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                        All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i>
                        by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                </div>
            </div>
        </div>
    </footer>

    <?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueRegisration.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
    <?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueIconBasket.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();









