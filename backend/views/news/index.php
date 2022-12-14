<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/news.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<nav aria-label="breadcrumb">
    <?= Breadcrumbs::widget(
        $breadcrumbs
    );?>
</nav>

<div id="appNews">
    <div id="inspire">
        <!-- start preloader not work!!!-->
        <div class="loader-wrap text-center mt-12" v-if="loader">
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

        <div v-if="!loader">

            <p class="h3"> All article - {{totalArticles}}</p>
            <div class="mb-3">
                <v-col cols="4">
                    <v-form
                            ref="form"
                            v-model="valid"
                    >
                        <v-text-field
                                v-model="countArticles"
                                :rules="countArticlesRules"
                                label="Count Articles"
                                type="number"
                                :max="100"
                                :min="10"
                                required
                        ></v-text-field>

                        <v-btn
                                :disabled="!valid"
                                color="success"
                                class="mr-4"
                                @click="getNews"
                        >
                            New Articles
                        </v-btn>
                    </v-form>
                </v-col>
            </div>

            <div class="mt-3">
                <v-row>
                    <v-col
                            v-for="(article, index) of articles"
                            :key="index"
                            cols="3"
                    >
                        <v-card
                                class="mx-auto"
                                max-width="330"
                        >
                            <v-img
                                    :src="article.image"
                                    height="200px"
                            ></v-img>

                            <v-card-title class="h3">
                                {{article.title}}
                            </v-card-title>

                            <v-card-actions>
                                <v-btn
                                        color="orange lighten-2"
                                        text
                                        :href="article.url"
                                        target="_blank"
                                >
                                    Content
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
                                        <div class="h5">
                                            {{article.description}}
                                        </div>
                                        <div class="text-left h5">Sourse - {{article.source.name}}</div>
                                        <div class="text-left h5">Date - {{article.publishedAt}}</div>
                                    </v-card-text>
                                </div>
                            </v-expand-transition>
                        </v-card>
                    </v-col>
                </v-row>
            </div>
        </div>
    </div>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueNews.js'), ['depends' => ['backend\assets\AppAsset']]); ?>