<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'PDF send';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/rabbitmq.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/materialdesignicons.min.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/vuetify.min.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<div id="appRabbitmq">
    <v-app id="inspire">
        <div class="container-rabbitmq">
            <div class="align-self-start">
                <v-btn
                        small
                        class="success"
                        @click="connectionRabbitmq"
                >Connection RABBIT_MQ</v-btn>
            </div>

            <template v-if="connectionRabbit">
                <div class="form-rabbitmq align-self-start">
                    <v-form
                            ref="form"
                            v-model="valid"
                            lazy-validation
                    >
                        <v-text-field
                                v-model="command"
                                :counter="10"
                                :rules="commandRules"
                                label="Command"
                                required
                        ></v-text-field>

                        <v-btn
                                color="success"
                                class="mr-4"
                                @click="validate"
                        >
                            Send command
                        </v-btn>
                    </v-form>
                </div>
            </template>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueRabbitmq.js'), ['depends' => ['backend\assets\AppAsset']]); ?>