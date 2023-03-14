<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'Game';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/game.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<div id="appGame" class="mt-3 mr-5 ml-5">
    <v-app id="inspire">
        <p class=" pt-5 pl-3 pr-3 text-h3" >Setting game</p>
        <div class=" pt-5 pl-3 pr-3">
            <v-form
                ref="form"
                v-model="valid"
            >
                <v-row>
                    <v-text-field
                            v-model="paramsGame"
                            label="Params game"
                            :rules="paramsGameRules"
                            type="number"
                            required
                            class="indent"
                    ></v-text-field>

                    <v-btn
                            :disabled="!valid"
                            color="success"
                            class="mr-4"
                            @click="start"
                    >
                        Start game
                    </v-btn>
                </v-row>
            </v-form>

            <template v-if="begin">
                <span v-html="game" ref="elemGame"></span>
            </template>

        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueGame.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
