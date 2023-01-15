<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Basket';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="hero-wrap js-fullheight" style="background-image: url('/images/tour.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
            <div class="col-md-9 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                    <span class="mr-2"><a href="<?= Url::to(['/site/index'])?>">Home</a></span> <span>Shop</span>
                </p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Shop</h1>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="<?= Url::to(['/site/shop'])?>">Shop</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['/site/basket'])?>">Basket <span class="sr-only">(current)</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section contact-section ftco-degree-bg">
    <div id="appBasketGood">
        <v-app class="v-application--is-ltr ml-2" id="inspire">
            <div class="container">
                <div class="mt-5">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Название</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Количество</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(good, index) in basket" scope="row">
                            <td class="align-middle">{{good.title}}</td>
                            <td class="align-middle">{{good.price}}</td>
                            <td class="align-middle">
                                <v-text-field
                                        hide-details="auto"
                                        type="number"
                                        :min="1"
                                        :max="10"
                                        v-model="good.count"
                                ></v-text-field>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-9">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                Итого - {{ total }}
                            </div>
                            <div class="col text-right">
                                    <v-dialog
                                        v-model="dialog"
                                        width="500"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                color="success"
                                                type="button"
                                                v-bind="attrs"
                                                v-on="on"
                                            >
                                                Оформить заказ
                                            </v-btn>
                                        </template>

                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">Оформление заказа</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field
                                                                    label="Fullname"
                                                                    v-model="fullname"
                                                                    required
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field
                                                                    label="Phone"
                                                                    v-model="phone"
                                                                    required
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field
                                                                    label="Email"
                                                                    v-model="email"
                                                                    required
                                                            ></v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field
                                                                    label="Address"
                                                                    v-model="address"
                                                                    required
                                                            ></v-text-field>
                                                        </v-col>
                                                    </v-row>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="dialog = false"
                                                >
                                                    Позже
                                                </v-btn>
                                                <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="toOrder(basket)"
                                                >
                                                    Заказать
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueBasketGood.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>