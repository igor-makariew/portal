<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use  yii\widgets\Breadcrumbs;

$this->title = 'Hotel';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/hotels.css', ['depends' => ['frontend\assets\AppAsset']])); ?>

<div class="hero-wrap js-fullheight" style="background-image: url('/images/hotels.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
            <div class="col-md-9 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
<!--                    <span class="mr-2"><a href="--><?//= Url::to(['/site/index'])?><!--" style="color:#56ff00">Home</a></span> <span style="color:#56ff00d9">Hotel</span>-->
                    <?= Breadcrumbs::widget([
                        'options' => ['class' => 'breadcrumbs'],
                        'homeLink' => [
                            'label' => 'HOME',
                            'url' => ['/site/index'],
                            'template' => "<span class='mr-2'><a>{link}</a></span>",
                        ],
                        'links' => [
                            [
                                'label' => 'HOTELS',
                                'url' => ['/site/hotels'],
                                'template' => "<span class='mr-2'><a>{link}</a></span>", // template for this link only
                            ],
                            [
                                'label' => 'HOTEL',
                                'template' => "<span class='mr-2'><a>{link}</a></span>",
                            ],
                        ],
                    ]) ?>
                </p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Hotel</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-degree-bg">
    <div id="appHotel">
        <v-app class="height-form" >
            <div class="container">
                <template v-if="loaderSlider">
                    <!-- start preloader not work!!!-->
                    <div class="loader-wrap text-center" >
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
                </template>
                <template v-if="!loaderSlider && items > 0">
                    <h3 class="mb-3">Слайдер из отфильтрованных вами отелей</h3>
                    <div class="home-demo">
                        <div class="owl-carousel owl-theme">
                            <div class="item" v-for="(viewHotel, index) in items" :viewSelectedItem="viewHotel" :key="viewHotel">
                                <v-card
                                        class="mx-auto mb-5"
                                        max-width="344"
                                >
                                    <v-img
                                            src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                                            height="200px"
                                    ></v-img>

                                    <v-card-title>
                                        <div class="card-text fix-height">
                                            {{sliders[index].label != '' ? sliders[index].label : sliders[index].hotelName}}
                                        </div>
                                    </v-card-title>

                                    <v-card-subtitle>
                                        Месторасположение - {{sliders[index].locationName != '' ? sliders[index].locationName : sliders[index].location.country + '. ' + sliders[index].location.name}}
                                    </v-card-subtitle>

                                    <v-card-actions>
                                        <v-btn
                                                color="orange lighten-2"
                                                text
                                                @click="loadHotel(sliders[index].id, sliders[index].hotelId)"
                                        >
                                            Карточка товара
                                        </v-btn>

                                        <v-spacer></v-spacer>

                                        <v-btn
                                                icon
                                                @click = "show = !show; viewSelectedItem = viewHotel"
                                        ><v-icon>{{ show && viewHotel == viewSelectedItem ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                        </v-btn>
                                    </v-card-actions>

                                    <v-expand-transition>
                                        <div v-show="show && viewSelectedItem == viewHotel">
                                            <v-divider></v-divider>

                                            <v-card-text>
                                                <div class="h-100">
                                                    <div> <h6 class="text--darken-1 mb-3">Полное название - {{sliders[index].fullName != '' ? sliders[index].fullName : sliders[index].hotelName + '. ' + sliders[index].location.name + '. ' + sliders[index].location.country}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                    <div> <h6 class="text--darken-1 mb-3">Геолокация отеля - lat :  {{sliders[index].location.lat != null ? sliders[index].location.lat : sliders[index].location.geo.lat}}, lon : {{sliders[index].location.lon != null ? sliders[index].location.lon : sliders[index].location.geo.lon}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                    <div><h6 class="text--darken-1 mb-3"> Номер отеля в базе - {{sliders[index].hotelId != '' ? sliders[index].hotelId : 'Не установлено'}}, lon - {{paramHotel.location.lon}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                    <div><h6 class="text--darken-1 mb-3"> Локация отеля - {{sliders[index].locationId}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                </div>
                                            </v-card-text>
                                        </div>
                                    </v-expand-transition>
                                </v-card>
                            </div>
                        </div>
                    </div>
                </template>
                <div class="mb-7"></div>
                <template v-if="loaderHotel">
                    <!-- start preloader not work!!!-->
                    <div class="loader-wrap text-center" >
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
                </template>
                <template v-if="!loaderHotel">
                    <template v-if="information != ''">
                        <div>
                            <h3 style="color:darkred">{{information}}</h3>
                        </div>
                    </template>
                    <template v-else>
                        <div class="card mb-3">
                            <img class="card-img-top" :src="defaultImg" height="400" :alt="paramHotel.hotelLabel">
                            <div class="card-body">
                                <h4 class="card-title">{{paramHotel.hotelLabel}}</h4>
                                <v-spacer></v-spacer>
                                <div> <h5 class="text--darken-1 mb-3">Месторасположение(город, страна) - {{paramHotel.hotelLocationName}}. </h5></div>
                                <v-spacer></v-spacer>
                                <div> <h5 class="text--darken-1 mb-3">Полное название - {{paramHotel.hotelName}}. </h5></div>
                                <v-spacer></v-spacer>
                                <div><h5 class="text--darken-1 mb-3"> Геолокация отеля - lat - {{paramHotel.location.lat != null ? paramHotel.location.lat : paramHotel.location.geo.lat}}, lon - {{paramHotel.location.lon != null ? paramHotel.location.lon : paramHotel.location.geo.lon }}. </h5></div>
<!--                                <div><h5 class="text--darken-1 mb-3"> Геолокация отеля - lat - {{paramHotel.location.geo.lat }}, lon - {{paramHotel.location }}. </h5></div>-->
                                <v-spacer></v-spacer>
                                <div><h5 class="text--darken-1 mb-3"> Номер отеля в базе - {{paramHotel.hotelId}}. </h5></div>
                                <v-spacer></v-spacer>
                                <div><h5 class="text--darken-1 mb-3"> Локация отеля - {{paramHotel.hotelLocationId}}. </h5></div>
                                <v-spacer></v-spacer>
                                <div>
                                    <v-rating
                                            v-model="paramHotel.hotelStars"
                                            background-color="orange lighten-3"
                                            color="orange"
                                            large
                                            readonly
                                    ></v-rating>
                                </div>
                                <v-spacer></v-spacer>
                                <div><h5 class="text--darken-1 mb-3"> Цена номера отеля (средняя) - {{paramHotel.hotelPriceAvg}}. </h5></div>
                                <v-spacer></v-spacer>
                                <div><h5 class="text--darken-1 mb-3"> Цена номера отеля (на момент заселения) - {{paramHotel.hotelPriceFrom}}. </h5></div>
                            </div>
                            <div class="text-right mr-6 mb-6">
                                <v-btn
                                    color="success"
                                    >Заказать
                                </v-btn>
                                <v-spacer></v-spacer>
                            </div>
                        </div>
                    </template>
                </template>
            </div>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueHotel.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>