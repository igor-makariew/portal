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
        <v-app class="height-form" id="inspire">
<!--            <div class="text-center">-->
<!--                <v-dialog-->
<!--                        v-model="dialog"-->
<!--                        width="500"-->
<!--                >-->
<!--                    <v-card>-->
<!--                        <v-card-title class="text-h5 grey lighten-2">-->
<!--                            Privacy Policy-->
<!--                        </v-card-title>-->
<!---->
<!--                        <v-card-text>-->
<!--                            {{hotels}}-->
<!--                        </v-card-text>-->
<!---->
<!--                        <v-divider></v-divider>-->
<!---->
<!--                        <v-card-actions>-->
<!--                            <v-spacer></v-spacer>-->
<!--                            <v-btn-->
<!--                                    color="primary"-->
<!--                                    text-->
<!--                                    @click="dialog = false"-->
<!--                            >-->
<!--                                OK-->
<!--                            </v-btn>-->
<!--                        </v-card-actions>-->
<!--                    </v-card>-->
<!--                </v-dialog>-->
<!--            </div>-->

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
                            {{dateStart}}
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
                                >
                                <v-item v-slot="{ active, toggle }">
                                    <v-card
                                            class="mx-auto"
                                            max-width="344"
                                    >
                                        <v-img
                                                src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                                                height="200px"
                                        ></v-img>

                                        <v-card-title>
                                            {{hotel.label != '' ? hotel.label : hotel.hotelName}}
                                        </v-card-title>

                                        <v-card-subtitle>
                                            {{hotel.locationName != '' ? hotel.locationName : `${hotel.location.name}, ${hotel.location.country}`}}
                                        </v-card-subtitle>

                                        <v-card-actions>
                                            <v-btn
                                                    color="orange lighten-2"
                                                    text
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
                                                    <div id="appBasket">
                                                        <v-col class="text-right" >
                                                            <v-btn
                                                                    color="success"
                                                                    @click="addToBasket(hotel.id != '' ? hotel.id : hotel.hotelId, <?= Yii::$app->user->identity->id?>)"
                                                            >Заказать</v-btn>
                                                        </v-col>
                                                    </div>
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
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueHotels.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueBasket.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>