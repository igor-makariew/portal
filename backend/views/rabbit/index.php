<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'RabbitMQ';
$this->params['breadcrumbs'][] = $this->title;
?>


<nav aria-label="breadcrumb">
<!--    --><?//= Breadcrumbs::widget(
//        $breadcrumbs
//    );?>
</nav>

<div id="appRabbit">
    <v-app id="inspire">
        <div>
            <v-col
                    class="d-flex"
                    cols="12"
                    sm="4"
            >
                <v-select
                        v-model="selectInterval"
                        :items="items"
                        label="Select interval"
                        dense
                        outlined
                        @change="get"
                ></v-select>
            </v-col>
        </div>

        <v-card>
            <v-card-title>
                <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                    :headers="headers"
                    :items="desserts"
                    :search="search"
            ></v-data-table>
        </v-card>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueRabbit.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
