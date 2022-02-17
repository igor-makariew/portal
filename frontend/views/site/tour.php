<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Tours';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="hero-wrap js-fullheight" style="background-image: url('/images/tour.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
            <div class="col-md-9 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                    <span class="mr-2"><a href="<?= Url::to(['/site/index'])?>">Home</a></span> <span>Tours</span>
                </p>
                <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Tours</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-degree-bg">
    <div id="appTours">
        <v-app class="height-form" id="inspire">
            <template v-if="loaderTours">
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
            <template v-if="!loaderTours">
                <div class="container">
                    <v-row>
                        <v-col
                                v-for="(country, index) in listCountries"
                                :key="index"
                                class="d-flex child-flex"
                                cols="4"
                        >
                            <div class="item">
                                <div class="destination">
                                    <a href="<?= Url::to(['/destination/index', 'id' => 1])?>" class="img d-flex justify-content-center align-items-center" style="background-image: url(images/destination-1.jpg);">
                                        <div class="icon d-flex justify-content-center align-items-center">
                                            <span class="icon-search2"></span>
                                        </div>
                                    </a>
                                    <div class="text p-3">
                                        <h3><a :href="'/destination/index?id=' + country['id']">{{country['name']}}</a></h3>
                                        <template v-if="listResorts[country['id']] != null">
                                            <span class="listing"> {{Object.keys(listResorts[country['id']]).length}} Listing</span>
                                        </template>
                                        <template v-else>
                                            <span class="listing">0 Listing</span>
                                        </template>
                                        <div class="listing">Популярно -
                                            <template v-if="country['popular'] == 0">
                                                <span class="listing"> НЕТ</span>
                                            </template>
                                            <template v-else>
                                                <span class="listing"> ДА</span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                    <!-- Start pagination                   -->
                    <template >
                        <div class="text-center">
                            <v-pagination
                                    v-model="page"
                                    :length="countPage"
                                    circle
                            ></v-pagination>
                        </div>
                    </template>
                    <!-- End pagination                   -->
                </div>
            </template>
        </v-app>
    </div>
</section>



<!--<v-img-->
<!--        :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`"-->
<!--        :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`"-->
<!--        aspect-ratio="1"-->
<!--        class="grey lighten-2"-->
<!-->-->
<!--    <template v-slot:placeholder>-->
<!--        <v-row-->
<!--                class="fill-height ma-0"-->
<!--                align="center"-->
<!--                justify="center"-->
<!--        >-->
<!--            <v-progress-circular-->
<!--                    indeterminate-->
<!--                    color="grey lighten-5"-->
<!--            ></v-progress-circular>-->
<!--        </v-row>-->
<!--    </template>-->
<!--</v-img>-->

<!--<v-item-group mandatory>-->
<!--    <v-container>-->
<!--        <!-- start preloader not work!!!-->-->
<!--        <div class="loader-wrap text-center" v-if="loader">-->
<!--            <v-progress-circular-->
<!--                    :rotate="-90"-->
<!--                    :size="100"-->
<!--                    :width="15"-->
<!--                    :value="value"-->
<!--                    :indeterminate="true"-->
<!--                    color="success"-->
<!--            >-->
<!--            </v-progress-circular>-->
<!--        </div>-->
<!--        <!-- end preloader -->-->
<!---->
<!--        <div class="mb-7"></div>-->
<!--        <!-- Start pagination                   -->-->
<!--        <template v-if="visiblyHotels">-->
<!--            <div class="text-center">-->
<!--                <v-pagination-->
<!--                        v-model="page"-->
<!--                        :length="countPage"-->
<!--                        circle-->
<!--                ></v-pagination>-->
<!--            </div>-->
<!--        </template>-->
<!--        <!-- End pagination                   -->-->
<!--    </v-container>-->
<!--</v-item-group>-->
<!---->
<!--<div class="container">-->
<!--    <template v-if="loaderListViewed">-->
<!--        <!-- start preloader not work!!!-->-->
<!--        <div class="loader-wrap text-center" >-->
<!--            <v-progress-circular-->
<!--                    :rotate="-90"-->
<!--                    :size="100"-->
<!--                    :width="15"-->
<!--                    :value="value"-->
<!--                    :indeterminate="true"-->
<!--                    color="success"-->
<!--            >-->
<!--            </v-progress-circular>-->
<!--        </div>-->
<!--        <!-- end preloader -->-->
<!--    </template>-->
<!--    <template v-show="!loaderListViewed && items > 0">-->
<!--        <div class="home-demo">-->
<!--            <h3 v-if="!loaderListViewed && items > 0">Вы смотрели:</h3>-->
<!--            <div class="owl-carousel owl-theme">-->
<!--                <div class="item" v-for="viewHotel in items" :viewSelectedItem="viewHotel" :key="viewHotel">-->
<!--                    <v-card-->
<!--                            class="mx-auto mb-5"-->
<!--                            max-width="344"-->
<!--                    >-->
<!--                        <v-img-->
<!--                                src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"-->
<!--                                height="200px"-->
<!--                        ></v-img>-->
<!---->
<!--                        <v-card-title>-->
<!--                            <div class="card-text fix-height">-->
<!--                                {{listViewedHotels[viewHotel - 1].label != '' ? listViewedHotels[viewHotel - 1].label : listViewedHotels[viewHotel - 1].hotel_name}}-->
<!--                            </div>-->
<!--                        </v-card-title>-->
<!---->
<!--                        <v-card-subtitle>-->
<!--                            Месторасположение - {{listViewedHotels[viewHotel - 1].location_name != '' ? listViewedHotels[viewHotel - 1].location_name : listViewedHotels[viewHotel - 1].location.country + '. ' + listViewedHotels[viewHotel - 1].location.name}}-->
<!--                        </v-card-subtitle>-->
<!---->
<!--                        <v-card-actions>-->
<!--                            <v-btn-->
<!--                                    color="orange lighten-2"-->
<!--                                    text-->
<!--                            >-->
<!--                                Информация:-->
<!--                            </v-btn>-->
<!---->
<!--                            <v-spacer></v-spacer>-->
<!---->
<!--                            <v-btn-->
<!--                                    icon-->
<!--                                    @click = "show = !show; viewSelectedItem = viewHotel"-->
<!--                            ><v-icon>{{ show && viewHotel == viewSelectedItem ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>-->
<!--                            </v-btn>-->
<!--                        </v-card-actions>-->
<!---->
<!--                        <v-expand-transition>-->
<!--                            <div v-show="show && viewSelectedItem == viewHotel">-->
<!--                                <v-divider></v-divider>-->
<!---->
<!--                                <v-card-text>-->
<!--                                    <div class="h-100">-->
<!--                                        <div> <h6 class="text--darken-1 mb-3">Полное название - {{listViewedHotels[viewHotel - 1].full_name != '' ? listViewedHotels[viewHotel - 1].full_name : listViewedHotels[viewHotel - 1].hotel_name + '. ' + listViewedHotels[viewHotel - 1].location.name + '. ' + listViewedHotels[viewHotel - 1].location.country}}. </h6></div>-->
<!--                                        <v-spacer></v-spacer>-->
<!--                                        <div> <h6 class="text--darken-1 mb-3">Геолокация отеля - lat :  {{listViewedHotels[viewHotel - 1].location.lat != null ? listViewedHotels[viewHotel - 1].location.lat : listViewedHotels[viewHotel - 1].location.geo.lat}}, lon : {{listViewedHotels[viewHotel - 1].location.lon != null ? listViewedHotels[viewHotel - 1].location.lon : listViewedHotels[viewHotel - 1].location.geo.lon}}. </h6></div>-->
<!--                                        <v-spacer></v-spacer>-->
<!--                                        <div><h6 class="text--darken-1 mb-3"> Номер отеля в базе - {{listViewedHotels[viewHotel - 1].hotel_id != '' ? listViewedHotels[viewHotel - 1].hotel_id : 'Не установлено'}}. </h6></div>-->
<!--                                        <v-spacer></v-spacer>-->
<!--                                        <div><h6 class="text--darken-1 mb-3"> Локация отеля - {{listViewedHotels[viewHotel - 1].location_id}}. </h6></div>-->
<!--                                        <v-spacer></v-spacer>-->
<!--                                    </div>-->
<!--                                </v-card-text>-->
<!--                            </div>-->
<!--                        </v-expand-transition>-->
<!--                    </v-card>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </template>-->
<!--</div>-->

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueTours.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
