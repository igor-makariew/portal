<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'SHOP';
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
                                <a class="nav-link" href="<?= Url::to(['/site/shop'])?>">Shop <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['/site/basket'])?>">Basket</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-degree-bg">
    <div id="appGoods">
        <v-app class="height-form" id="inspire">
            <template v-if="loaderGoods">
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
            <template v-if="!loaderGoods">
                <div class="container">
                    <v-row>
                        <v-col
                            v-for="(good, index) in goods"
                            :key="index"
                            class="d-flex child-flex"
                            cols="4"
                        >
                            <div class="item">
                                <div class="destination">
                                    <a :href="'/good/index?id=' + good['id']" class="img d-flex justify-content-center align-items-center" :style="{backgroundImage: 'url(' + `images/goods/${good['images']}` + ')'}">
                                        <div class="icon d-flex justify-content-center align-items-center">
                                            <span class="icon-search2"></span>
                                        </div>
                                    </a>
                                    <div class="text p-3">
                                        <h3>Название - {{good['title']}}</h3>
                                        <h5>Цена - {{good['price']}}</h5>
                                        <button type="button" class="btn btn-success" @click="buy(good['id'])">Купить</button>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </template>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueGoods.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>

