<?php
    /* @var $this yii\web\View */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Страны';
    $this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/countries.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<div class="">
    <div id="appCountries">
        <v-app id="inspire">
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
                <v-card>
                    <v-card-title>
                        <v-spacer></v-spacer>
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-12">
                            </div>
                            <div class="col-xl-6 col-md-6 col-12">
                                <v-text-field
                                        v-model="search"
                                        append-icon="mdi-magnify"
                                        label="Поиск"
                                        single-line
                                        hide-details
                                        placeholder="Поиск по странам"
                                        @input="searchList($event)"
                                        class="hidden-xs-only"
                                ></v-text-field>
                            </div>
                        </div>
                    </v-card-title>
                    <v-data-table
                        :headers="headers"
                        :items="listCountries.length == 0 ? desserts : listCountries"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        hide-default-footer
                        class="ml-5 mr-5"
                        @page-count="pageCount = $event"
                    ><template v-slot:item.popular="{ item }">
                            <v-chip
                                :color="getColor(item.popular)"
                            >{{item.popular == 1 ? 'ДА' : 'НЕТ'}}</v-chip>
                        </template>
                        <template v-slot:item.is_popular=" { item } ">
                            <v-chip
                                :color="getColor(item.is_popular)"
                            >{{item.is_popular != null ? 'ДА' : 'НЕТ'}}
                            </v-chip>
                        </template>
                        <template v-slot:item.rating="{ item }">
                            <v-rating
                                    v-model="typeof item.rating == 'string' ? Number(item.rating) : item.rating"
                                    background-color="orange lighten-3"
                                    color="orange"
                                    color="yellow"
                                    readonly
                                    medium
                                    empty-icon="$ratingFull"
                                    half-increments
                            ></v-rating>
                        </template>
                    </v-data-table>

                    <div class="text-center pt-2">
                        <v-pagination
                            v-model="page"
                            :length="pageCount"
                        ></v-pagination>

                        <div class="row ml-5">
                            <div class="col-xl-6 col-md-6 col-12">
                                <div class="col-xl-4 col-md-6 col-sm-6">
                                    <v-text-field
                                        :value="itemsPerPage"
                                        label="Количество стран на старнице"
                                        type="number"
                                        :rules="intervalCountriesRuls"
                                        hint="Максимальное значение 50"
                                        @input="itemsPerPage = parseInt($event, 10)"
                                    ></v-text-field
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 col-12"></div>
                        </div>
                    </div>
                </v-card>
            </div>
        </v-app>
    </div>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueCountries.js'), ['depends' => ['backend\assets\AppAsset']]); ?>