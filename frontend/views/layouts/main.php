<?php

use frontend\assets\AppAsset;
use yii\helpers\Html;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\helpers\Url;

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
                            <template v-if="isGuest">
                                <v-icon
                                        color="white"
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
                                                                        <h1>Vuetify</h1>
                                                                    </div>
                                                                </v-img>
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
            <div id="appBasket">
                <div data-app="true" class="v-application height-form v-application--is-ltr ml-2" id="inspire">
                    <v-icon
                            color="success"
                            style="cursor: pointer"
                            @click="windowBasket"
                    >{{'mdi-cart-outline'}}
                    </v-icon>
                    <span class="basket-counter" v-if="countBasket.visible">{{countBasket.count}}</span>
                    <div class="text-center">
                        <v-dialog v-model="dialog" width="100%">
                            <v-card>
                                <v-card-title class="text-h5 grey lighten-2">
                                    Корзина
                                </v-card-title>
                                <v-card-text>
                                    <div class="mt-5">
                                        <v-data-table
                                            :headers="headers"
                                            :items="listHotels"
                                            item-key="name"
                                            class="elevation-1"
                                            :hide-default-header="false"
                                            :hide-default-footer="true"
                                            disabled-pagination
                                        >
                                            <template v-slot:item.stars="{ item }">
                                                <v-rating
                                                    v-model="item.stars"
                                                    background-color="orange lighten-3"
                                                    color="orange"
                                                    small
                                                ></v-rating>
                                            </template>

                                            <template v-slot:item.check="{ item }">
                                                <v-checkbox
                                                    v-model="item.check"
                                                    @click="countRows(item.check, item.name, item.price, item.stars)"
                                                ></v-checkbox>
                                            </template>
                                        </v-data-table>
                                    </div>
                                    <div class="mt-5"></div>
                                    <div class="text-right">
                                        <v-btn
                                                color="success"
                                                @click="buyHotels()"
                                                :disabled="!validBuy"
                                        >Заказать</v-btn>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
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
    <?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueBasket.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();









