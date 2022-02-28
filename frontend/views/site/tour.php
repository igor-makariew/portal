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
                                    <a :href="'/destination/index?id=' + country['country_id']" class="img d-flex justify-content-center align-items-center" style="background-image: url(images/destination-1.jpg);">
                                        <div class="icon d-flex justify-content-center align-items-center">
                                            <span class="icon-search2"></span>
                                        </div>
                                    </a>
                                    <div class="text p-3">
                                        <h3><a :href="'/destination/index?id=' + country['country_id']">{{country['name']}}</a></h3>
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

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueTours.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
