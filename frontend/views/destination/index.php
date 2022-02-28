<?php
/* @var $this yii\web\View */

use  yii\widgets\Breadcrumbs;

$this->title = 'Country';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/destination.css', ['depends' => ['frontend\assets\AppAsset']])); ?>

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
                    <template v-if="listResorts != null">
                        <v-expansion-panels
                                focusable
                                flat
                                hover
                        ><v-expansion-panel
                                    v-for="(resort, index) in listResorts"
                                    :key="index"
                            >
                                <v-expansion-panel-header
                                        v-slot="{ open }"

                                >
                                    <span @click="getHotels(resort, open)">
                                        Курорт - {{resort.name}}
                                    </span>
                                    <span v-if="!open" class="text-right ml-5">
                                       <v-rating
                                               v-model="resort.rating"
                                               icon-label="custom icon label text {0} of {1}"
                                               background-color="yellow lighten-3"
                                               color="yellow"
                                               readonly
                                               medium
                                               empty-icon="$ratingFull"
                                               half-increments
                                               hover
                                       ></v-rating>
                                    </span>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
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
                                        <template v-if="resort">
                                            <div class="mb-5 mt-5 ml-0 pl-0">
                                                <v-row justify="start">
                                                    <v-dialog
                                                            v-model="dialogComment"
                                                            persistent
                                                            max-width="700px"
                                                            :retain-focus="false"
                                                    >
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-row class="row-parent">
                                                                <v-btn
                                                                    color="success"
                                                                    dark
                                                                    v-bind="attrs"
                                                                    v-on="on"
                                                                    class="mt-5 mb-5"
                                                                >
                                                                    Оставить комментарий
                                                                </v-btn>
                                                                <span>
                                                                    <span class="h5 mr-5">Комментарии:</span>
                                                                    <!-- start preloader not work!!!-->
                                                                    <template v-if="loaderCountComments">
                                                                        <span class="loader-wrap text-center" >
                                                                        <v-progress-circular
                                                                                :rotate="-90"
                                                                                :size="20"
                                                                                :width="5"
                                                                                :value="value"
                                                                                :indeterminate="true"
                                                                                color="info"
                                                                        >
                                                                        </v-progress-circular>
                                                                    </span>
                                                                    </template>
                                                                    <!-- end preloader -->
                                                                    <template v-if="!loaderCountComments">
                                                                        <v-btn
                                                                                color="info"
                                                                                dark
                                                                                class="mt-5 mb-5"
                                                                                @click="trigComment"
                                                                        >
                                                                        {{countComment}}
                                                                    </v-btn>
                                                                    </template>
                                                                </span>
                                                            </v-row>
                                                        </template>
                                                        <v-card>
                                                            <v-card-title>
                                                                <span class="text-h5">Комментарий пользователя - {{nameUser}}</span>
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <v-container>
                                                                    <v-form v-model="validCommentUser">
                                                                        <!--                                                                            add class !!!!-->
                                                                        <v-row style="width: 650px">
                                                                            <v-col cols="12">
                                                                                <v-textarea
                                                                                        background-color="grey lighten-3"
                                                                                        color="success"
                                                                                        label="Комментарий"
                                                                                        v-model="commentUser"
                                                                                        :rules="commentTextRules"
                                                                                ></v-textarea>
                                                                            </v-col>

                                                                            <v-col col="12">
                                                                                <span class="text-h5 ml-2 mb-5">Выставите рейтинг туру:</span>
                                                                                <v-rating
                                                                                        v-model="resort.user_rating"
                                                                                        background-color="yellow lighten-3"
                                                                                        color="yellow"
                                                                                        medium
                                                                                        half-increments
                                                                                        hover
                                                                                ></v-rating>
                                                                            </v-col>
                                                                        </v-row>
                                                                    </v-form>
                                                                </v-container>
                                                            </v-card-text>
                                                            <v-card-actions>
                                                                <v-spacer></v-spacer>
                                                                <v-btn
                                                                        color="blue darken-1"
                                                                        text
                                                                        @click="dialogComment = false"
                                                                >
                                                                    Закрыть
                                                                </v-btn>
                                                                <v-btn
                                                                        color="blue darken-1"
                                                                        text
                                                                        :disabled="!validCommentUser"
                                                                        @click="submitComment(commentUser, userId, nameUser)"
                                                                >
                                                                    Отправить
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-dialog>
                                                </v-row>

                                            </div>
                                            <span class="ml-3 h4">Отели:</span>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item" v-for="hotel of listHotels" :key="hotel.id">{{hotel.name}}</li>
                                            </ul>
                                            <div v-show="triggerComments">
                                                <div class="comments">
                                                    <ul class="media-list">
                                                        <!-- Комментарий (уровень 1) -->
                                                        <li class="media">
                                                            <div class="media-body">
                                                                <div class="p-3 mb-2 bg-secondary text-white background-border" v-for="comment in comments">
                                                                    <div class="media-heading">
                                                                        <div class="author">{{comment.name}}</div>
                                                                        <div class="metadata">
                                                                            <span class="date ml-12 text-white">{{comment.created_at}}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="media-text text-justify">{{comment.comment}}</div>
                                                                </div>
                                                            </div>
                                                        </li><!-- Конец комментария (уровень 1) -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <v-alert
                                                    dense
                                                    type="info"
                                            >
                                                Ничего нет.
                                            </v-alert>
                                        </template>
                                    </template>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </template>
                    <template v-else>
                        <v-alert
                                dense
                                type="info"
                        >
                            Туров в данной стране нет.
                        </v-alert>
                    </template>
                </template>
            </div>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueCountry.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
