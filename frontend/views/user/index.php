<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/personal-area.css'), ['depends' => ['frontend\assets\AppAsset']]); ?>

<div class="hero-wrap js-fullheight" style="background-image: url('/images/personal_area.jpg');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
            <div class="col-md-9 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
                <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="<?= Url::to(['/site/index'])?>" class="text-color">Home</a></span> <span class="text-color">Личный кабинет</span></p>
                <h1 class="mb-3 bread text-color" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Личный кабинет</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section contact-section ftco-degree-bg">
    <div id="appPersonalArea">
        <v-app class="v-application--is-ltr ml-2" id="inspire">
            <div class="container">
                <v-dialog v-model="dialogConfirm" max-width="700px">
                    <v-card>
                        <v-card-title class="text-h5 text-justify">{{dialogConfirmTitle}}</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="dialogConfirm = false">Отмена</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteFavoriteHotel()">Удалить</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-card >
                    <template>
                        <v-card-title class="text-h5 grey lighten-2">
                            Личный кабинет
                        </v-card-title>
                    </template>

                    <template>
                        <v-col cols="12">
                            <v-card class="no-border">
                                <v-row no-gutters align="stretch" justify="center">
                                    <v-col cols="3" align="center" class="black--text pa-4">
                                        <v-list rounded >
                                            <v-list-item-group color="primary">
                                                <v-list-item v-for="(item, index) in items" :key="index" @click="menuActionClick(item.action)">
                                                    <v-list-item-icon>
                                                        <v-icon v-text="item.icon"></v-icon>
                                                    </v-list-item-icon>
                                                    <v-list-item-title>
                                                        {{ item.title }}
                                                    </v-list-item-title>
                                                </v-list-item>
                                            </v-list-item-group>
                                        </v-list>
                                    </v-col>
                                    <v-col cols="9" align="center">
                                        <v-img class="img-rounded d-flex align-center pa-4">
                                            <div class="white--text">
                                                <h1>
                                                    {{titleMenu}}
                                                </h1>
                                            </div>
                                        </v-img>
                                        <div v-if="titleMenu == 'Аккаунт'">
                                            <!-- start preloader not work!!!-->
                                            <div class="loader-wrap text-center" v-if="loader">
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

                                            <v-dialog v-model="dialogEdit" max-width="620px">
                                                <v-card>
                                                    <v-card-title class="text-h5">Ваши персональные данные успешно обновлены.</v-card-title>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="blue darken-1" text @click="dialogEdit = false">OK</v-btn>
                                                        <v-spacer></v-spacer>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>

                                            <div v-if="!loader" class="form-width-parent">
                                                <v-form
                                                        ref="formEdit"
                                                        class="form-width-child"
                                                >
                                                    <v-text-field
                                                            v-model="personalDate.nameEdit"
                                                            :counter="15"
                                                            :rules="nameEditRules"
                                                            label="Имя"
                                                    ></v-text-field>

                                                    <v-text-field
                                                            v-model="personalDate.emailEdit"
                                                            :rules="emailEditRules"
                                                            label="E-mail"
                                                    ></v-text-field>

                                                    <v-text-field
                                                            v-model="personalDate.phoneEdit"
                                                            :rules="phoneEditRules"
                                                            label="Телефон"
                                                    ></v-text-field>

                                                    <!--    start диалоговое окно для редактирования пароля пользователя    -->
                                                    <v-dialog
                                                            v-model="dialogEditPassword"
                                                            persistent
                                                            max-width="900px"
                                                    >
                                                        <v-card>
                                                            <v-card-title>
                                                                <span class="text-h5">Редактирование пароля пользователя</span>
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <v-form v-model="validEdiPassword">
                                                                    <v-container>
                                                                        <v-row>
                                                                            <v-col cols="12">
                                                                                <v-text-field
                                                                                        v-model="editPassword"
                                                                                        label="Пароль"
                                                                                        type="password"
                                                                                        :rules="editPasswordRules"
                                                                                ></v-text-field>
                                                                            </v-col>

                                                                            <v-col cols="12">
                                                                                <v-text-field
                                                                                        v-model="newEditPassword"
                                                                                        label="Новый пароль"
                                                                                        require
                                                                                        type="password"
                                                                                        :rules="newEditPasswordRules"
                                                                                ></v-text-field>
                                                                            </v-col>
                                                                            <v-col cols="12">
                                                                                <v-text-field
                                                                                        v-model="repeatNewEditPassword"
                                                                                        label="Новый пароль еще раз"
                                                                                        require
                                                                                        type="password"
                                                                                        :rules="[(newEditPassword == repeatNewEditPassword) || 'Пароли должны совпадать']"
                                                                                ></v-text-field>
                                                                            </v-col>
                                                                            <v-btn
                                                                                    color="blue darken-1"
                                                                                    text
                                                                                    @click="dialogEditPassword = false"
                                                                            >
                                                                                Close
                                                                            </v-btn>
                                                                            <v-btn
                                                                                    color="blue darken-1"
                                                                                    text
                                                                                    :disabled="!validEdiPassword"
                                                                                    @click="editPasswordUser"
                                                                            >
                                                                                Save
                                                                            </v-btn>
                                                                        </v-row>
                                                                    </v-container>
                                                                </v-form>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-dialog>
                                                    <!--    end диалоговое окно для редактирования пароля пользователя    -->

                                                    <div class="mb-5 mr-4">
                                                        <a href="#" class="text-xl-center" @click="dialogEditPass">Изменить пароль</a>
                                                    </div>
                                                    <v-spacer></v-spacer>
                                                    <v-btn
                                                            :disabled="!validEdit"
                                                            color="success"
                                                            class="mr-4"
                                                            @click="updateEdit"
                                                    >
                                                        Сохранить
                                                    </v-btn>
                                                </v-form>
                                            </div>
                                        </div>
                                        <div v-if="titleMenu == 'Заказы'">
                                            <!-- start preloader not work!!!-->
                                            <div class="loader-wrap text-center" v-if="loader">
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
                                                <v-data-table
                                                        :headers="headers"
                                                        :items="desserts"
                                                        sort-by="number"
                                                        class="elevation-1"
                                                        :footer-props="{
                                                                                    itemsPerPageText: 'Количество заказов на странице',
                                                                                    itemsPerPageOptions:[30, 60, 90, -1]
                                                                                }"
                                                        :search="search"
                                                >
                                                    <template v-slot:top>
                                                        <v-toolbar
                                                                flat
                                                        >
                                                            <v-toolbar-title>Мои заказы</v-toolbar-title>
                                                            <v-spacer></v-spacer>

                                                            <v-text-field
                                                                    v-model="search"
                                                                    append-icon="mdi-magnify"
                                                                    label="Поиск"
                                                                    single-line
                                                                    hide-details
                                                                    class="hidden-xs-only"
                                                            ></v-text-field>

                                                            <v-spacer></v-spacer>
                                                            <v-dialog v-model="dialogDelete" max-width="600px">
                                                                <v-card>
                                                                    <v-card-title class="text-h5">Вы уверены, что хотите удалить этот элемент?</v-card-title>
                                                                    <v-card-actions>
                                                                        <v-spacer></v-spacer>
                                                                        <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                                                        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                                                        <v-spacer></v-spacer>
                                                                    </v-card-actions>
                                                                </v-card>
                                                            </v-dialog>
                                                            <v-dialog
                                                                    v-model="dialogView"
                                                                    persistent
                                                                    max-width="600px"
                                                            >
                                                                <v-card>
                                                                    <v-card-title class="text-h5">{{cardViewItem.title}}</v-card-title>
                                                                    <v-card-actions>
                                                                        <v-spacer></v-spacer>
                                                                        <v-card-text class="text--primary">
                                                                            <div><v-rating
                                                                                        v-model="cardViewItem.raiting"
                                                                                        background-color="orange lighten-3"
                                                                                        color="orange"
                                                                                        small
                                                                                        readonly
                                                                                ></v-rating></div>
                                                                            <div><h3>Дата - {{cardViewItem.date}}</h3></div>
                                                                            <div><h3>Цена - {{cardViewItem.price}}</h3></div>
                                                                            <v-spacer></v-spacer>
                                                                            <v-spacer></v-spacer>
                                                                            <div class="text-right">
                                                                                <v-btn
                                                                                        color="blue darken-1"
                                                                                        text
                                                                                        @click="dialogView = false"
                                                                                >
                                                                                    Закрыть
                                                                                </v-btn>
                                                                            </div>
                                                                        </v-card-text>
                                                                        <v-spacer></v-spacer>
                                                                    </v-card-actions>
                                                                </v-card>
                                                            </v-dialog>
                                                        </v-toolbar>
                                                    </template>
                                                    <template v-slot:item.raiting="{ item }">
                                                        <v-rating
                                                                v-model="item.raiting"
                                                                background-color="orange lighten-3"
                                                                color="orange"
                                                                small
                                                                readonly
                                                        ></v-rating>
                                                    </template>
                                                    <template v-slot:item.actions="{ item }">
                                                        <v-icon
                                                                small
                                                                @click="viewItem(item)"
                                                                color="primary"
                                                        >
                                                            mdi-eye-outline
                                                        </v-icon>
                                                        <v-icon
                                                                small
                                                                @click="deleteItem(item)"
                                                                color="red"
                                                        >
                                                            mdi-delete
                                                        </v-icon>
                                                    </template>
                                                    <template v-slot:no-data>
                                                        <v-btn
                                                                color="primary"
                                                                @click="initialize"
                                                        >
                                                            Reset
                                                        </v-btn>
                                                    </template>
                                                </v-data-table>
                                            </div>
                                        </div>
                                        <div v-if="titleMenu == 'Избранное'">
                                            <!-- start preloader not work!!!-->
                                            <div class="loader-wrap text-center" v-if="loader">
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
                                                <div v-if="favoriteProducts.length == 0">
                                                    <h3>Вы не добавили отелей в избранное!</h3>
                                                </div>
                                                <v-row>
                                                    <v-col
                                                            v-for="(hotel, index) in favoriteProducts"
                                                            :key="index"
                                                            cols="12"
                                                            md="3"
                                                            crtSelectedItem="index"
                                                    ><v-item v-slot="{ active, toggle }">
                                                            <v-card
                                                                    class="mx-auto"
                                                                    max-width="320"
                                                            >
                                                                <v-img
                                                                        src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg"
                                                                        height="200px"
                                                                ></v-img>

                                                                <v-card-title>
                                                                    {{hotel.label != '' ? hotel.label : hotel.hotelName}}
                                                                </v-card-title>

                                                                <v-card-subtitle>
                                                                    {{hotel.locationName != '' ? hotel.locationName : `${hotel.location.name}, ${hotel.location.country}`}}
                                                                </v-card-subtitle>

                                                                <v-card-actions>
                                                                    <v-btn
                                                                            color="orange lighten-2"
                                                                            text
                                                                    >
                                                                        Information
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

                                                                        <v-card-text class="text-left">
                                                                            <p> Название отеля - {{hotel.full_name != '' ? hotel.full_name : hotel.hotel_name}}. </p>
                                                                            <p> Локация в базе - {{hotel.location_id}}. </p>
                                                                            <p> Локация: долгота - {{hotel.location.lat != '' ? hotel.location.lat : hotel.location.geo.lat}}, широта - {{hotel.location.lon != '' ? hotel.location.lon : hotel.location.geo.lon}}. </p>
                                                                            <p> Номер отеля в базе - {{hotel.hotel_id}}. </p>
                                                                            <template v-if="hotel.stars != ''">
                                                                                <v-rating
                                                                                        v-model="hotel.stars"
                                                                                        background-color="orange lighten-3"
                                                                                        color="orange"
                                                                                        @input="stopClick"
                                                                                        large
                                                                                ></v-rating>
                                                                            </template>
                                                                            <template v-else>
                                                                                <p> Количество звёзд - не указано </p>
                                                                            </template>
                                                                            <template>
                                                                                Удалить из избранного -  <v-icon
                                                                                        :color="hotel.hotel_id != null ? 'green' : ''"
                                                                                        @click="deleteConfirmFavorite(hotel.hotel_id)"
                                                                                >{{ 'mdi-star-circle' }}</v-icon>
                                                                            </template>
                                                                            <v-col class="text-right" >
                                                                                <v-btn
                                                                                        color="success"
                                                                                        @click="addToBasket(hotel.id != '' ? hotel.id : hotel.hotelId, <?= Yii::$app->user->identity->id?>)"
                                                                                >Заказать</v-btn>
                                                                            </v-col>
                                                                        </v-card-text>
                                                                    </div>
                                                                </v-expand-transition>
                                                            </v-card>
                                                        </v-item>
                                                    </v-col>
                                                </v-row>

                                            </div>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card>
                        </v-col>
                    </template>
                </v-card>
            </div>
        </v-app>
    </div>
</section>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/personalArea.js'), ['depends' => ['frontend\assets\AppAsset']]); ?>
