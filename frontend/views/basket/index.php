<?php
/* @var $this yii\web\View */
    use yii\helpers\Url;

    $this->title = 'Корзина';
    $this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/basket.css', ['depends' => ['frontend\assets\AppAsset']])); ?>

<div class="hero-wrap js-fullheight" style="background-image: url('/images/reception.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
            <div class="col-md-9 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="<?= Url::to(['/site/index'])?>">Home</a></span> <span>Корзина</span></p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Корзина</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section contact-section ftco-degree-bg">
    <div id="appBasket">
        <v-app class="v-application--is-ltr ml-2" id="inspire">
            <div class="container">
                <div class="mt-5">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Название</th>
                            <th scope="col">Отель</th>
                            <th scope="col">Рейтинг</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Подтверждение</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        <tr v-for="(hotel, index) in listHotels" scope="row">
                            <td class="align-middle">{{hotel.name}}</td>
                            <td class="align-middle">{{hotel.label}}</td>
                            <td class="align-middle">
                                <v-rating
                                    v-model="hotel.stars"
                                    background-color="orange lighten-3"
                                    color="orange"
                                    small
                                    readonly
                                ></v-rating>
                            </td>
                            <td class="align-middle">{{hotel.price}}</td>
                            <td>
                                <v-checkbox
                                    v-model="hotel.check"
                                    @click="countCheckedRows(hotel.check, hotel.name, hotel.price, hotel.raiting)"
                                ></v-checkbox>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

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
                <div class="mt-9"></div>

                <div class="text-right">
                    <v-row>
                        <v-dialog
                                v-model="modalWindow"
                                persistent
                                max-width="390"
                        >
                            <v-card>
                                <v-card-title class="text-h5">
                                    Удаление выбранных отелей.
                                </v-card-title>
                                <v-card-text>Вы действительно хотите удалить выбранные отели?</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                            color="primary"
                                            text
                                            @click="closeModal(false)"
                                    >
                                        Отмена
                                    </v-btn>
                                    <v-btn
                                            color="error"
                                            text
                                            @click="closeModal(true)"
                                    >
                                        Удалить
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-row>
                    <div v-if="listHotels.length > 0">
                        <v-btn
                                color="error"
                                @click="modalWindow = true"
                                :disabled="!validHotel"
                        >Удалить</v-btn>
                        <v-btn
                                color="success"
                                @click="buyHotels()"
                                :disabled="!validHotel"
                        >Заказать</v-btn>
                    </div>
                </div>
            </div>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueBasket.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>

