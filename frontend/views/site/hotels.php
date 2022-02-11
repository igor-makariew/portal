<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Hotels';
$this->params['breadcrumbs'][] = $this->title;
?>

<?//= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/fonts.googleapis.css', ['depends' => ['frontend\assets\AppAsset']])); ?>
<?//= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/materialdesignicons.min.css', ['depends' => ['frontend\assets\AppAsset']])); ?>
<?//= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/vuetify.min.css', ['depends' => ['frontend\assets\AppAsset']])); ?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/hotels.css', ['depends' => ['frontend\assets\AppAsset']])); ?>

<div class="hero-wrap js-fullheight" style="background-image: url('images/bg_5.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
            <div class="col-md-9 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                    <span class="mr-2"><a href="<?= Url::to(['/site/index'])?>">Home</a></span> <span>Hotel</span>
                </p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Hotels</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-degree-bg">
    <div id="appHotels">
        <v-app class="height-form" >
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

            <v-row justify="center">
                <v-dialog
                        v-model="dialogHotel"
                        persistent
                        max-width="700px"
                ><v-card>
                        <v-card-title>
                            <span class="text-h5">{{dialogHotelLabel}}</span>
                        </v-card-title>
                        <v-card-text>
                            <div> <h5 class="text--darken-1 mb-3">Название отеля - {{dialogHotelName}}. </h5></div>
                            <v-spacer></v-spacer>
                            <div><h5 class="text--darken-1 mb-3"> Локация в базе - {{dialogHotelLocationId}}. </h5></div>
                            <v-spacer></v-spacer>
                            <div><h5 class="text--darken-1 mb-3"> Номер отеля в базе - {{dialogHotelId}}. </h5></div>
                            <v-spacer></v-spacer>
                            <div><h5 class="text--darken-1 mb-3"> Локация отеля - {{dialogHotelLocationName}}. </h5></div>
                            <v-spacer></v-spacer>
                            <div>
                                <v-rating
                                        v-model="dialogHotelStars"
                                        background-color="orange lighten-3"
                                        color="orange"
                                        large
                                ></v-rating>
                            </div>
                            <v-spacer></v-spacer>
                            <div><h5 class="text--darken-1 mb-3"> Цена номера отеля (средняя) - {{dialogHotelPriceAvg}}. </h5></div>
                            <v-spacer></v-spacer>
                            <div><h5 class="text--darken-1 mb-3"> Цена номера отеля (на момент заселения) - {{dialogHotelPriceFrom}}. </h5></div>
                            <v-spacer></v-spacer>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <div class="text-right">
                                <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="dialogHotel = false"
                                >Закрыть</v-btn>
                            </div>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-row>

            <v-item-group mandatory>
                <v-container>
                    <v-form ref="formValid"  v-model="valid">
                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Выберите город</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-text-field
                                        label="City"
                                        v-model="query"
                                        prefix="г."
                                        :rules="fieldCity"
                                ></v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Выберите язык</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-select
                                        v-model="lang"
                                        :items="langs"
                                        item-text="title"
                                        item-value="title"
                                        label="Language"
                                ></v-select>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Объекты, выводимые в результатах</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-select
                                        v-model="lookFor"
                                        :items="lookFors"
                                        item-text="name"
                                        label="Params"
                                ></v-select>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Выберите валюту</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-select
                                        v-model="currency"
                                        :items="currencies"
                                        item-text="name"
                                        label="Currency"
                                ></v-select>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Выберите дату заселения</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-date-picker
                                        color="success"
                                        v-model="dateStart"
                                        full-width
                                        class="mt-4"
                                        @input="validateDate"
                                ></v-date-picker>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Выберите дата выселения</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-date-picker
                                        color="success"
                                        v-model="dateEnd"
                                        full-width
                                        class="mt-4"
                                        @input="validateDate"
                                ></v-date-picker>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="4">
                                <v-subheader>Ограничение количества выводимых результатов</v-subheader>
                            </v-col>
                            <v-col cols="8">
                                <v-text-field
                                        label="Limits"
                                        v-model="limit"
                                        type=""
                                        suffix=""
                                        :rules="fieldLimit"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-col class="text-right" >
                            <v-btn :disabled="!valid" color="success" class="mr-4" @click="getHotels" @click="validate">
                                Запрос
                            </v-btn>
                        </v-col>
                    </v-form>

                    <template v-if="visiblyHotels">
                        <div class="mb-7"></div>
                        <v-row>
                            <v-col
                                    v-for="(hotel, index) in hotels"
                                    :key="index"
                                    cols="12"
                                    md="4"
                                    crtSelectedItem="index"
                            ><v-item v-slot="{ active, toggle }">
                                    <v-card
                                            class="mx-auto"
                                            max-width="344"
                                    >
                                        <v-img
                                                src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                                                height="200px"
                                        ></v-img>

                                        <v-card-title>
                                            <div class="card-text fix-height">
                                                {{hotel.label != '' ? hotel.label : hotel.hotelName}}
                                            </div>
                                        </v-card-title>

                                        <v-card-subtitle>
                                            {{hotel.locationName != '' ? hotel.locationName : `${hotel.location.name}, ${hotel.location.country}`}}
                                        </v-card-subtitle>

                                        <v-card-actions>
                                            <v-btn
                                                color="orange lighten-2"
                                                text
                                                :href="'/favorite/index?id=' + links[index]"
                                            >
                                                Information
                                            </v-btn>

                                            <v-spacer></v-spacer>

                                            <v-btn
                                                    icon
                                                    @click="show = !show; crtSelectedItem = index"
                                            >
                                                <v-icon>{{ show &&  index == crtSelectedItem ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
                                            </v-btn>
                                        </v-card-actions>

                                        <v-expand-transition>
                                            <div v-show="show && crtSelectedItem == index">
                                                <v-divider></v-divider>

                                                <v-card-text>
                                                    <p> Название отеля - {{hotel.fullName != '' ? hotel.fullName : hotel.hotelName}}. </p>
                                                    <p> Локация в базе - {{hotel.locationId}}. </p>
                                                    <p> Локация: долгота - {{hotel.location.lat != '' ? hotel.location.lat : hotel.location.geo.lat}}, широта - {{hotel.location.lon != '' ? hotel.location.lon : hotel.location.geo.lon}}. </p>
                                                    <p> Номер отеля в базе - {{hotel.id != '' ? hotel.id : hotel.hotelId}}. </p>
                                                    <template v-if="hotel.stars != ''">
                                                        <v-rating
                                                                v-model="hotel.stars"
                                                                background-color="orange lighten-3"
                                                                color="orange"
                                                                @input="stopClick"
                                                                large
                                                        ></v-rating>
                                                    </template>
                                                    <template v-else>
                                                        <p> Количество звёзд - не указано </p>
                                                    </template>
                                                    <template>
                                                        Добавить в избранное -  <v-icon
                                                                :color="hotel.favorite != null ? 'green' : ''"
                                                                @click="addFavorite(hotel)"
                                                        >{{ 'mdi-star-circle' }}</v-icon>
                                                    </template>
                                                    <v-col class="text-right" >
                                                        <v-btn
                                                                color="success"
                                                                @click="addToBasket(hotel.id != '' ? hotel.id : hotel.hotelId, <?= Yii::$app->user->identity->id?>)"
                                                        >Заказать</v-btn>
                                                    </v-col>
                                                </v-card-text>
                                            </div>
                                        </v-expand-transition>
                                    </v-card>
                                </v-item>
                            </v-col>
                        </v-row>
                    </template>
                    <template v-if="showMessage">
                        <v-alert
                                icon="mdi-information"
                                border="bottom"
                                color="info"
                                dark
                                transition="scale-transition"
                                class="text-xl-center"
                        >
                            {{message}}
                        </v-alert>
                    </template>

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

                    <div class="mb-7"></div>
                    <!-- Start pagination                   -->
                    <template v-if="visiblyHotels">
                        <div class="text-center">
                            <v-pagination
                                    v-model="page"
                                    :length="countPage"
                                    circle
                            ></v-pagination>
                        </div>
                    </template>
                    <!-- End pagination                   -->
                </v-container>
            </v-item-group>

            <div class="container">
                <template v-if="loaderListViewed">
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
                <template v-show="!loaderListViewed && items > 0">
                    <div class="home-demo">
                        <h3 v-if="!loaderListViewed && items > 0">Вы смотрели:</h3>
                        <div class="owl-carousel owl-theme">
                            <div class="item" v-for="viewHotel in items" :viewSelectedItem="viewHotel" :key="viewHotel">
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
                                            {{listViewedHotels[viewHotel - 1].label != '' ? listViewedHotels[viewHotel - 1].label : listViewedHotels[viewHotel - 1].hotel_name}}
                                        </div>
                                    </v-card-title>

                                    <v-card-subtitle>
                                        Месторасположение - {{listViewedHotels[viewHotel - 1].location_name != '' ? listViewedHotels[viewHotel - 1].location_name : listViewedHotels[viewHotel - 1].location.country + '. ' + listViewedHotels[viewHotel - 1].location.name}}
                                    </v-card-subtitle>

                                    <v-card-actions>
                                        <v-btn
                                                color="orange lighten-2"
                                                text
                                        >
                                            Информация:
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
                                                    <div> <h6 class="text--darken-1 mb-3">Полное название - {{listViewedHotels[viewHotel - 1].full_name != '' ? listViewedHotels[viewHotel - 1].full_name : listViewedHotels[viewHotel - 1].hotel_name + '. ' + listViewedHotels[viewHotel - 1].location.name + '. ' + listViewedHotels[viewHotel - 1].location.country}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                    <div> <h6 class="text--darken-1 mb-3">Геолокация отеля - lat :  {{listViewedHotels[viewHotel - 1].location.lat != null ? listViewedHotels[viewHotel - 1].location.lat : listViewedHotels[viewHotel - 1].location.geo.lat}}, lon : {{listViewedHotels[viewHotel - 1].location.lon != null ? listViewedHotels[viewHotel - 1].location.lon : listViewedHotels[viewHotel - 1].location.geo.lon}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                    <div><h6 class="text--darken-1 mb-3"> Номер отеля в базе - {{listViewedHotels[viewHotel - 1].hotel_id != '' ? listViewedHotels[viewHotel - 1].hotel_id : 'Не установлено'}}. </h6></div>
                                                    <v-spacer></v-spacer>
                                                    <div><h6 class="text--darken-1 mb-3"> Локация отеля - {{listViewedHotels[viewHotel - 1].location_id}}. </h6></div>
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
            </div>

        </v-app>
    </div>
</section>




<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueHotels.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
