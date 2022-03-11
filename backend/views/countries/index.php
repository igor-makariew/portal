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
            <div v-if="!loader">
                <v-card>
                    <v-data-table
                        :headers="headers"
                        :items="listCountries.length == 0 ? desserts : listCountries"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        hide-default-footer
                        class="ml-5 mr-5"
                        @page-count="pageCount = $event"
                    >
                        <template v-slot:top>
                                <v-card-title>
                                    <v-dialog
                                            v-model="dialog"
                                            max-width="800px"
                                            persistent
                                            scrollable
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                                    color="primary"
                                                    dark
                                                    class="mb-2"
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    @click="newItem"
                                            >
                                                Создание новой записи
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">{{ formTitle }}</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-row>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                            v-if="editedIndex < 0"
                                                    >
                                                        <v-text-field
                                                                v-model="editedItem.country_name"
                                                                label="Страна"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                            v-if="editedIndex < 0"
                                                    >
                                                        <v-text-field
                                                                v-model="editedItem.country_id"
                                                                label="Идентификатор"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                    >
                                                        <v-select
                                                                :items="popularCountry"
                                                                item-value="key"
                                                                item-text="value"
                                                                v-model="editedItem.popular"
                                                                label="Популярность страны"
                                                                dense
                                                                solo
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                    >
                                                        <v-text-field
                                                                v-model="editedItem.resort_name"
                                                                label="Тур"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                    >
                                                        <v-select
                                                            :items="popularTour"
                                                            item-value="key"
                                                            item-text="value"
                                                            v-model="editedItem.is_popular"
                                                            label="Популярность тура"
                                                            dense
                                                            solo
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="4"
                                                    >
                                                        <v-rating
                                                            v-model="typeof editedItem.rating == 'string' ? Number(editedItem.rating) : editedItem.rating"
                                                            background-color="orange lighten-3"
                                                            color="orange"
                                                            color="yellow"
                                                            medium
                                                            empty-icon="$ratingFull"
                                                            half-increments
                                                            label="Рейтинг"
                                                        ></v-rating>
                                                    </v-col>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                            v-if="editedIndex < 0"
                                                    >
                                                        <v-text-field
                                                                v-model="editedItem.resort_country_id"
                                                                label="Внешний идентификатор"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                            cols="12"
                                                            sm="6"
                                                            md="4"
                                                            v-if="editedIndex < 0"
                                                    >
                                                        <v-text-field
                                                                v-model="editedItem.resorts_id"
                                                                label="Идентификатор тура"
                                                        ></v-text-field>
                                                    </v-col>
                                                </v-row>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="close"
                                                >
                                                    Отмена
                                                </v-btn>
                                                <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="selectRequest()"
                                                >
                                                    Сохранить
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-spacer></v-spacer>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-12"></div>
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <v-col
                                                class="d-flex"
                                                cols="12"
                                                sm="6"
                                            >
                                                <v-select
                                                    :items="selectField"
                                                    item-value="key"
                                                    item-text="value"
                                                    v-model="field"
                                                    label="Выберите поле"
                                                    dense
                                                    solo
                                                ></v-select>
                                            </v-col>
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
                                <v-spacer></v-spacer>
                                <v-dialog v-model="dialogDelete" max-width="430">
                                    <v-card>
                                        <v-card-title class="text-h5">Вы уверены, что хотите удалить выбранный раздел?</v-card-title>
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="blue darken-1" text @click="closeDelete">Отмена</v-btn>
                                            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                            <v-spacer></v-spacer>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>

                        </template>
                        <template v-slot:item.resort_country_id="{ item }" style="display: none">
                            <span style="display: none">{{item.resorts_id}}</span>
                        </template>
                        <template v-slot:item.popular="{ item }">
                            <v-chip
                                :color="getColor(item.popular)"
                            >{{item.popular == 1 ? 'ДА' : 'НЕТ'}}</v-chip>
                        </template>
                        <template v-slot:item.resort_name="{ item }">
                            {{item.resort_name}}
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
                        <template v-slot:item.actions="{ item }">
                            <span v-if="loaderUpdate && crtSelectedItem == item.resorts_id">
                                <span class="loader-wrap text-center">
                                    <v-progress-circular
                                            :rotate="-90"
                                            :size="20"
                                            :width="5"
                                            :value="value"
                                            :indeterminate="true"
                                            color="success"
                                    >
                                    </v-progress-circular>
                                </span>
                            </span>
                            <span v-if="!loaderUpdate && crtSelectedItem == item.resorts_id">
                                <v-icon
                                        small
                                        class="mr-2"
                                        color="success"
                                        @click="editItem(item)"
                                >
                                mdi-pencil
                            </v-icon>
                            </span>
                            <span v-if="crtSelectedItem != item.resorts_id">
                                <v-icon
                                        small
                                        class="mr-2"
                                        color="success"
                                        @click="editItem(item)"
                                >
                                mdi-pencil
                            </v-icon>
                            </span>
                            <v-icon
                                small
                                color="red"
                                @click="deleteItem(item)"
                            >
                                mdi-delete
                            </v-icon>
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