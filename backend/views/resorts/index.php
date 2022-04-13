<?php
/* @var $this yii\web\View */

$this->title = 'Курорты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/hotels.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<div id="appHotels">
    <v-app id="inspire">
        <div class="width-select">
            <v-col>
                <div v-if="loaderCountry">
                    <div class="text-center mt-3">
                        <v-progress-circular
                                :size="30"
                                :width="3"
                                color="primary"
                                indeterminate
                        ></v-progress-circular>
                    </div>
                </div>
                <div v-if="!loaderCountry">
                    <v-select
                            v-model="selectedCountries"
                            :items="itemsCountry"
                            item-value="key"
                            item-text="value"
                            label="Список стран"
                            dense
                            outlined
                            class="font-weight-bolder"
                            @blur="getResorts"
                            hint="Выберите страны"
                            persistent-hint
                            multiple
                    >
                        <template v-slot:prepend-item>
                            <v-list-item
                                    ripple
                                    @mousedown.prevent
                                    @click="toggle"
                            >
                                <v-list-item-action>
                                    <v-icon :color="selectedCountries.length > 0 ? 'indigo darken-4' : ''">
                                        {{ icon }}
                                    </v-icon>
                                </v-list-item-action>
                                <v-list-item-content>
                                    <v-list-item-title>
                                        Выбрать все
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider class="mt-2"></v-divider>
                        </template>
                        <template v-slot:append-item>
                            <v-divider class="mb-2"></v-divider>
                            <v-list-item disabled>
                                <v-list-item-content v-if="likesAllCountries">
                                    <v-list-item-title>
                                        Holy smokes, someone call the countries police!
                                    </v-list-item-title>
                                </v-list-item-content>

                                <v-list-item-content v-else-if="likesSomeCountries">
                                    <v-list-item-title>
                                        Countries Count
                                    </v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ selectedCountries.length }}
                                    </v-list-item-subtitle>
                                </v-list-item-content>
                            </v-list-item>
                        </template>
                    </v-select>
                </div>
            </v-col>
        </div>

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

        <template>
            <div class="container-radio">
                <v-radio-group v-model="allResorts" mandatory row>
                    <v-radio label="Акутуальные курорты" :value=false></v-radio>
                    <v-radio label="Не актуальные курорты" :value=true></v-radio>
                </v-radio-group>
            </div>
        </template>

        <div v-if="loaderResorts">
            <div class="text-center mt-3">
                <v-progress-circular
                        :size="150"
                        :width="5"
                        color="primary"
                        indeterminate
                ></v-progress-circular>
            </div>
        </div>
        <div v-if="!loaderResorts">
            <div class="container-table" v-if="desserts.length > 0">
                <v-data-table
                        :headers="headers"
                        :items="desserts"
                        :page.sync="page"
                        :items-per-page="itemsPerPage"
                        hide-default-footer
                        class="elevation-1"
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
                                <v-card>
                                    <v-card-title>
                                        <span class="text-h5 mb-3">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-row>
                                            <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="4"
                                            >
                                                <v-text-field
                                                        v-model="editedItem.resorts_id"
                                                        label="Идентификатор"
                                                        disabled
                                                ></v-text-field>
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                sm="6"
                                                md="4"
                                            >
                                                <v-text-field
                                                    v-model="editedItem.name"
                                                    label="Курорт"
                                                ></v-text-field>
                                            </v-col>
                                            <v-col
                                                cols="12"
                                                sm="6"
                                                md="4"
                                            >
                                                <v-select
                                                    :items="popularResort"
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
                                                <p class="text-paragraph">Рейтинг курорта</p>
                                                <v-rating
                                                    v-model="typeof editedItem.rating == 'string' ? Number(editedItem.rating) : editedItem.rating"
                                                    background-color="orange lighten-3"
                                                    color="orange"
                                                    color="yellow"
                                                    medium
                                                    empty-icon="$ratingFull"
                                                    half-increments
                                                ></v-rating>
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
                        </v-card-title>
                        <v-spacer></v-spacer>
                        <v-dialog v-model="dialogDelete" max-width="430">
                            <v-card>
                                <v-card-title class="text-h5">Вы уверены, что хотите удалить выбранный курорт?</v-card-title>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="closeDelete">Отмена</v-btn>
                                    <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

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
                        <div v-if="loaderUpdate && crtSelectedItem == item.resorts_id" class="text-center ">
                            <v-overlay z-index="1100">
                                <span class="loader-wrap text-center">
                                    <v-progress-circular
                                            :size="50"
                                            :width="5"
                                            color="primary"
                                            indeterminate
                                    >
                                    </v-progress-circular>
                                </span>
                            </v-overlay>
                        </div>
                        <div v-if="loaderReestablishResort && crtSelectedItem == item.resorts_id" class="text-center ">
                            <span class="loader-wrap text-center">
                                <v-progress-circular
                                        :size="25"
                                        :width="5"
                                        color="primary"
                                        indeterminate
                                >
                                </v-progress-circular>
                            </span>
                        </div>
                        <span v-if="!loaderUpdate && crtSelectedItem == item.resorts_id">
                            <v-icon
                                    small
                                    class="mr-2"
                                    color="success"
                                    @click="editItem(item, true)"
                                    v-if="item.del_resort == 0"
                            >mdi-pencil
                            </v-icon>
                            <span v-if="!loaderReestablishResort">
                                <v-btn
                                        color="success"
                                        @click="reestablishResort(item)"
                                        v-if="item.del_resort == 1"
                                >Востановить курорт</v-btn>
                            </span>
                        </span>
                        <span v-if="crtSelectedItem != item.resorts_id">
                                <v-icon
                                        small
                                        class="mr-2"
                                        color="success"
                                        @click="editItem(item, true)"
                                        v-if="item.del_resort == 0"
                                >
                                    mdi-pencil
                                </v-icon>
                            <v-btn
                                    color="success"
                                    @click="reestablishResort(item, false)"
                                    v-if="item.del_resort == 1"
                            >Востановить курорт</v-btn>
                            </span>
                        <span v-if="loaderDelete && crtSelectedDelItem == item.resorts_id">
                                <span class="loader-wrap text-center">
                                    <v-progress-circular
                                            :size="25"
                                            :width="5"
                                            color="primary"
                                            indeterminate
                                    >
                                    </v-progress-circular>
                                </span>
                            </span>
                        <span v-if="!loaderDelete && crtSelectedDelItem == item.resorts_id">
                                <v-icon
                                        small
                                        color="red"
                                        @click="deleteItem(item)"
                                        v-if="item.del_resort == 0"
                                >
                                    mdi-delete
                                </v-icon>
                            </span>
                        <span v-if="crtSelectedDelItem != item.resorts_id">
                                <v-icon
                                        small
                                        color="red"
                                        @click="deleteItem(item)"
                                        v-if="item.del_resort == 0"
                                >
                                    mdi-delete
                                </v-icon>
                            </span>
                    </template>
                </v-data-table>
                <div class="text-center pt-2">
                    <v-pagination
                            v-model="page"
                            :length="pageCount"
                    ></v-pagination>
                    <v-text-field
                            :value="itemsPerPage"
                            label="Items per page"
                            type="number"
                            min="-1"
                            max="15"
                            @input="itemsPerPage = parseInt($event, 10)"
                    ></v-text-field>
                </div>
            </div>
        </div>

    </v-app>
</div>
<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueHotels.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
