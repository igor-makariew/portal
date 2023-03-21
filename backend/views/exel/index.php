<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\grid\GridView;

$this->title = 'Excel';
?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/excel.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<!--<nav aria-label="breadcrumb">-->
<!--    --><?//= Breadcrumbs::widget(
//        $breadcrumbs
//    );?>
<!--</nav>-->

<div id="appExcel">
    <v-app id="inspire">

        <div class="width-input position-input">
            <v-file-input
                    v-model="file"
                    accept=".xls"
                    placeholder="Upload your file"
                    label="File input"
                    prepend-icon="mdi-paperclip"
                    @change="uploadFile"
            >
            </v-file-input>
        </div>

        <!--   start day     -->
        <div class="width-select mb-12">
            <v-text-field
                v-model="fullName"
            ></v-text-field>
            <v-row
                    align="center"
                    justify="space-around"
            >
                <v-btn
                        color="success"
                        @click="startDay"
                >
                    Начало дня
                </v-btn>
                <v-btn
                        depressed
                        color="primary"
                        @click="start"
                >
                    Продолжение
                </v-btn>
                <v-btn
                        depressed
                        color="error"
                        @click="stop"
                >
                    Пауза
                </v-btn>
                <v-btn
                        depressed
                        @click="stopDay"
                >
                    Окончание дня
                </v-btn>
            </v-row>

            <template v-if="sotrudnic != ''">
                <p class="mt-12">Сотрудник - {{sotrudnic}} приступил к работе</p>
            </template>
            <template v-if="allTime > 0">
                <p class="mt-12">Вы работали - {{allTime}} часов</p>
            </template>

            <template v-if="breakTime > 0">
                <p class="mt-12">Вы отдыхали - {{breakTime}} минут</p>
            </template>
        </div>

        <div class="width-select">
            <v-col>
                <div v-if="loaderSelected">
                    <div class="text-center mt-3">
                        <v-progress-circular
                                :size="30"
                                :width="3"
                                color="primary"
                                indeterminate
                        ></v-progress-circular>
                    </div>
                </div>
                <div v-if="!loaderSelected">
                    <v-select
                            v-model="selectedModel"
                            :items="itemsModel"
                            item-value="key"
                            item-text="value"
                            label="Выбор модели"
                            dense
                            outlined
                            @change="getModel"
                    ></v-select>
                </div>
            </v-col>
        </div>
        <div class="" v-if="tableHidden">
            <h3>Таблица - {{tableName}}</h3>

            <v-data-table
                    :headers="headers"
                    :items="dataProvider"
                    :page.sync="page"
                    :items-per-page="itemsPerPage"
                    hide-default-footer
                    class="ml-5 mr-5"
                    @page-count="pageCount = $event"
            >
                <!--                <template v-slot:top>-->
                <!--                    <v-card-title>-->
                <!--                        <v-dialog-->
                <!--                            v-model="dialog"-->
                <!--                            max-width="800px"-->
                <!--                            persistent-->
                <!--                            scrollable-->
                <!--                        >-->
                <!--                            <template v-slot:activator="{ on, attrs }">-->
                <!--                                <v-btn-->
                <!--                                    color="primary"-->
                <!--                                    dark-->
                <!--                                    class="mb-2"-->
                <!--                                    v-bind="attrs"-->
                <!--                                    v-on="on"-->
                <!--                                    @click="newItem"-->
                <!--                                >-->
                <!--                                    Создание новой записи-->
                <!--                                </v-btn>-->
                <!--                            </template>-->
                <!--                            <v-card>-->
                <!--                                <v-card-title>-->
                <!--                                    <span class="text-h5">{{ formTitle }}</span>-->
                <!--                                </v-card-title>-->
                <!---->
                <!--                                <v-card-text>-->
                <!--                                    <v-row>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                            v-if="editedIndex < 0"-->
                <!--                                        >-->
                <!--                                            <v-text-field-->
                <!--                                                v-model="editedItem.country_name"-->
                <!--                                                label="Страна"-->
                <!--                                            ></v-text-field>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                            v-if="editedIndex < 0"-->
                <!--                                        >-->
                <!--                                            <v-text-field-->
                <!--                                                v-model="editedItem.country_id"-->
                <!--                                                label="Идентификатор"-->
                <!--                                            ></v-text-field>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                        >-->
                <!--                                            <v-select-->
                <!--                                                :items="popularCountry"-->
                <!--                                                item-value="key"-->
                <!--                                                item-text="value"-->
                <!--                                                v-model="editedItem.popular"-->
                <!--                                                label="Популярность страны"-->
                <!--                                                dense-->
                <!--                                                solo-->
                <!--                                            ></v-select>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                        >-->
                <!--                                            <v-text-field-->
                <!--                                                v-model="editedItem.resort_name"-->
                <!--                                                label="Тур"-->
                <!--                                            ></v-text-field>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                        >-->
                <!--                                            <v-select-->
                <!--                                                :items="popularTour"-->
                <!--                                                item-value="key"-->
                <!--                                                item-text="value"-->
                <!--                                                v-model="editedItem.is_popular"-->
                <!--                                                label="Популярность тура"-->
                <!--                                                dense-->
                <!--                                                solo-->
                <!--                                            ></v-select>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                        >-->
                <!--                                            <v-rating-->
                <!--                                                v-model="typeof editedItem.rating == 'string' ? Number(editedItem.rating) : editedItem.rating"-->
                <!--                                                background-color="orange lighten-3"-->
                <!--                                                color="orange"-->
                <!--                                                color="yellow"-->
                <!--                                                medium-->
                <!--                                                empty-icon="$ratingFull"-->
                <!--                                                half-increments-->
                <!--                                                label="Рейтинг"-->
                <!--                                            ></v-rating>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                            v-if="editedIndex < 0"-->
                <!--                                        >-->
                <!--                                            <v-text-field-->
                <!--                                                v-model="editedItem.resort_country_id"-->
                <!--                                                label="Внешний идентификатор"-->
                <!--                                            ></v-text-field>-->
                <!--                                        </v-col>-->
                <!--                                        <v-col-->
                <!--                                            cols="12"-->
                <!--                                            sm="6"-->
                <!--                                            md="4"-->
                <!--                                            v-if="editedIndex < 0"-->
                <!--                                        >-->
                <!--                                            <v-text-field-->
                <!--                                                v-model="editedItem.resorts_id"-->
                <!--                                                label="Идентификатор тура"-->
                <!--                                            ></v-text-field>-->
                <!--                                        </v-col>-->
                <!--                                    </v-row>-->
                <!--                                </v-card-text>-->
                <!---->
                <!--                                <v-card-actions>-->
                <!--                                    <v-spacer></v-spacer>-->
                <!--                                    <v-btn-->
                <!--                                        color="blue darken-1"-->
                <!--                                        text-->
                <!--                                        @click="close"-->
                <!--                                    >-->
                <!--                                        Отмена-->
                <!--                                    </v-btn>-->
                <!--                                    <v-btn-->
                <!--                                        color="blue darken-1"-->
                <!--                                        text-->
                <!--                                        @click="selectRequest()"-->
                <!--                                    >-->
                <!--                                        Сохранить-->
                <!--                                    </v-btn>-->
                <!--                                </v-card-actions>-->
                <!--                            </v-card>-->
                <!--                        </v-dialog>-->
                <!--                        <v-spacer></v-spacer>-->
                <!--                        <div class="row">-->
                <!--                            <div class="col-xl-6 col-md-6 col-12"></div>-->
                <!--                            <div class="col-xl-6 col-md-6 col-12">-->
                <!--                                <v-col-->
                <!--                                    class="d-flex"-->
                <!--                                    cols="12"-->
                <!--                                    sm="6"-->
                <!--                                >-->
                <!--                                    <v-select-->
                <!--                                        :items="selectField"-->
                <!--                                        item-value="key"-->
                <!--                                        item-text="value"-->
                <!--                                        v-model="field"-->
                <!--                                        label="Выберите поле"-->
                <!--                                        dense-->
                <!--                                        solo-->
                <!--                                    ></v-select>-->
                <!--                                </v-col>-->
                <!--                                <v-text-field-->
                <!--                                    v-model="search"-->
                <!--                                    append-icon="mdi-magnify"-->
                <!--                                    label="Поиск"-->
                <!--                                    single-line-->
                <!--                                    hide-details-->
                <!--                                    placeholder="Поиск по странам"-->
                <!--                                    @input="searchList($event)"-->
                <!--                                    class="hidden-xs-only"-->
                <!--                                ></v-text-field>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </v-card-title>-->
                <!--                    <v-spacer></v-spacer>-->
                <!--                    <v-dialog v-model="dialogDelete" max-width="430">-->
                <!--                        <v-card>-->
                <!--                            <v-card-title class="text-h5">Вы уверены, что хотите удалить выбранный раздел?</v-card-title>-->
                <!--                            <v-card-actions>-->
                <!--                                <v-spacer></v-spacer>-->
                <!--                                <v-btn color="blue darken-1" text @click="closeDelete">Отмена</v-btn>-->
                <!--                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>-->
                <!--                                <v-spacer></v-spacer>-->
                <!--                            </v-card-actions>-->
                <!--                        </v-card>-->
                <!--                    </v-dialog>-->
                <!---->
                <!--                </template>-->
                <!--                <template v-slot:item.resort_country_id="{ item }" style="display: none">-->
                <!--                    <span style="display: none">{{item.resorts_id}}</span>-->
                <!--                </template>-->
                <!--                <template v-slot:item.popular="{ item }">-->
                <!--                    <v-chip-->
                <!--                        :color="getColor(item.popular)"-->
                <!--                    >{{item.popular == 1 ? 'ДА' : 'НЕТ'}}</v-chip>-->
                <!--                </template>-->
                <!--                <template v-slot:item.resort_name="{ item }">-->
                <!--                    {{item.resort_name}}-->
                <!--                </template>-->
                <!--                <template v-slot:item.is_popular=" { item } ">-->
                <!--                    <v-chip-->
                <!--                        :color="getColor(item.is_popular)"-->
                <!--                    >{{item.is_popular != null ? 'ДА' : 'НЕТ'}}-->
                <!--                    </v-chip>-->
                <!--                </template>-->
                <!--                <template v-slot:item.rating="{ item }">-->
                <!--                    <v-rating-->
                <!--                        v-model="typeof item.rating == 'string' ? Number(item.rating) : item.rating"-->
                <!--                        background-color="orange lighten-3"-->
                <!--                        color="orange"-->
                <!--                        color="yellow"-->
                <!--                        readonly-->
                <!--                        medium-->
                <!--                        empty-icon="$ratingFull"-->
                <!--                        half-increments-->
                <!--                    ></v-rating>-->
                <!--                </template>-->
                <!--                <template v-slot:item.actions="{ item }">-->
                <!--                            <span v-if="loaderUpdate && crtSelectedItem == item.resorts_id">-->
                <!--                                <span class="loader-wrap text-center">-->
                <!--                                    <v-progress-circular-->
                <!--                                        :rotate="-90"-->
                <!--                                        :size="20"-->
                <!--                                        :width="5"-->
                <!--                                        :value="value"-->
                <!--                                        :indeterminate="true"-->
                <!--                                        color="success"-->
                <!--                                    >-->
                <!--                                    </v-progress-circular>-->
                <!--                                </span>-->
                <!--                            </span>-->
                <!--                    <span v-if="!loaderUpdate && crtSelectedItem == item.resorts_id">-->
                <!--                                <v-icon-->
                <!--                                    small-->
                <!--                                    class="mr-2"-->
                <!--                                    color="success"-->
                <!--                                    @click="editItem(item)"-->
                <!--                                >-->
                <!--                                mdi-pencil-->
                <!--                            </v-icon>-->
                <!--                            </span>-->
                <!--                    <span v-if="crtSelectedItem != item.resorts_id">-->
                <!--                                <v-icon-->
                <!--                                    small-->
                <!--                                    class="mr-2"-->
                <!--                                    color="success"-->
                <!--                                    @click="editItem(item)"-->
                <!--                                >-->
                <!--                                    mdi-pencil-->
                <!--                                </v-icon>-->
                <!--                            </span>-->
                <!--                    <span v-if="loaderDelete && crtSelectedDelItem == item.resorts_id">-->
                <!--                                <span class="loader-wrap text-center">-->
                <!--                                    <v-progress-circular-->
                <!--                                        :rotate="-90"-->
                <!--                                        :size="20"-->
                <!--                                        :width="5"-->
                <!--                                        :value="value"-->
                <!--                                        :indeterminate="true"-->
                <!--                                        color="success"-->
                <!--                                    >-->
                <!--                                    </v-progress-circular>-->
                <!--                                </span>-->
                <!--                            </span>-->
                <!--                    <span v-if="!loaderDelete && crtSelectedDelItem == item.resorts_id">-->
                <!--                                <v-icon-->
                <!--                                    small-->
                <!--                                    color="red"-->
                <!--                                    @click="deleteItem(item)"-->
                <!--                                >-->
                <!--                                    mdi-delete-->
                <!--                                </v-icon>-->
                <!--                            </span>-->
                <!--                    <span v-if="crtSelectedDelItem != item.resorts_id">-->
                <!--                                <v-icon-->
                <!--                                    small-->
                <!--                                    color="red"-->
                <!--                                    @click="deleteItem(item)"-->
                <!--                                >-->
                <!--                                    mdi-delete-->
                <!--                                </v-icon>-->
                <!--                            </span>-->
                <!--                </template>-->
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

        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueExcel.js'), ['depends' => ['backend\assets\AppAsset']]); ?>

