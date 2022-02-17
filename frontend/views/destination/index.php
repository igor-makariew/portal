<?php
/* @var $this yii\web\View */

use  yii\widgets\Breadcrumbs;

$this->title = 'Country';
$this->params['breadcrumbs'][] = $this->title;
?>

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
                                'label' => 'Tours',
                                'url' => ['/site/tour'],
                                'template' => "<span class='mr-2'><a>{link}</a></span>",
                            ],
                            [
                                'label' => 'Country',
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
    <div id="appCountry">
        <v-app class="height-form" id="inspire">
            <div class="container">
                <template v-if="loaderCountry">
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
                <template v-if="!loaderCountry">
                    <v-expansion-panels
                            focusable
                    ><v-expansion-panel
                                v-for="(resort, index) in listResorts"
                                :key="index"
                                @click="getHotels(resort.id)"
                        >
                            <v-expansion-panel-header>Курорт - {{resort.name}}</v-expansion-panel-header>
                            <v-expansion-panel-content>
                                Отели:
                                <template v-if="loaderHotels">
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
                                <template v-if="!loaderHotels">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" v-for="hotel in listHotels" :key="hotel.id">{{hotel.name}}</li>
                                    </ul>
                                </template>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </template>
            </div>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueCountry.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
