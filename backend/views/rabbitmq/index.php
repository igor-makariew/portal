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
                <div>
                    <h4 class="h4">Рассылка сообщений с вложенными файлами</h4>
                </div>

                <div class="form-rabbitmq align-self-start">
                    <v-form
                            ref="form"
                            v-model="valid"
                    >
                        <v-text-field
                                v-model="command"
                                :counter="20"
                                :rules="commandRules"
                                label="Command"
                                required
                        ></v-text-field>

                        <v-btn
                                :disabled="!valid"
                                color="success"
                                class="mr-4"
                                @click="connectionRabbitmq()"
                        >
                            Send command
                        </v-btn>
                    </v-form>
                </div>

                <div>
                    <h4 class="h4">RPC</h4>
                </div>

                <div class="form-rabbitmq align-self-start">

                    <h4 class="text-primary"> Value Fibonachi - {{fibonachiValue}}</h4>

                    <v-form
                            ref="formRpc"
                            v-model="validRpc"
                    >
                        <v-text-field
                                v-model="rpcValue"
                                :rules="rpcRules"
                                label="RPC value"
                                required
                        ></v-text-field>

                        <v-btn
                                :disabled="!validRpc"
                                color="success"
                                class="mr-4"
                                @click="connectionRabbitmqRpc()"
                        >
                            value RPC
                        </v-btn>
                    </v-form>
                </div>
            </div>
        </v-app>
    </div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueRabbitmq.js'), ['depends' => ['backend\assets\AppAsset']]); ?>