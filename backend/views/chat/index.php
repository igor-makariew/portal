<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'Chat';
//$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/chat.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<nav aria-label="breadcrumb">
    <?= Breadcrumbs::widget(
        $breadcrumbs
    );?>
</nav>

<div class="content">
    <div class="row" id="appFriends">
            <div class="col-3">
            <div v-if="loaderUsers">
                <div class="text-center mt-3">
                    <v-progress-circular
                            :value="20"
                            indeterminate
                            color="#66BB6A"
                    ></v-progress-circular>
                </div>
            </div>

            <v-card
                    class="mx-auto"
                    max-width="250"
                    tile
            >
                <v-list>
                    <v-subheader class="h3">Users</v-subheader>
                    <v-list-item-group
                            color="primary"
                    >
                        <v-list-item
                                v-for="(item, i) in itemsUsers"
                        >
                            <v-list-item-content>
                                <v-list-item-title v-text="item.username" class="h4" @click="getMessagesUser(item.id, item.username)"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-card>
        </div>
            <div class="col-9">
            <div v-if="loaderMessages">
                <div class="text-center mt-3">
                    <v-progress-circular
                            :value="20"
                            indeterminate
                            color="#66BB6A"
                    ></v-progress-circular>
                </div>
            </div>
                <v-app id="inspire">
                    <div id="response" class="title-messenger"></div>
                    <p class="title-messenger">Messenger</p>
                    <div >
                        <v-list
                                id="scroll-target"
                                style="max-height: 720px"
                                class="overflow-y-auto"
                                ref="messageScroll"
                        >
                                <div v-for="(message, index) in itemsMessages" :key="index"
                                     style="height: 100px"
                                     v-scroll:#scroll-target="onScroll"
                                >
                                    <div v-if="message.id_from == idUser" class="block-left">
                                        <div class="message-from">
                                            <p>{{message.name_from}} - {{message.date}}</p>
                                            <p>{{message.message_from}}</p>
                                        </div>
                                    </div>
                                    <div class="block-right" v-else>
                                        <div class="message-to">
                                            <p>{{message.name_from}} - {{message.date}}</p>
                                            <p>{{message.message_from}}</p>
                                        </div>
                                    </div>
                                </div>

                        </v-list>
                    </div>

                    <div class="container" v-if="idUser">
                        <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                        >
                            <v-text-field
                                    v-model="message"
                                    :rules="nameRules"
                                    label="Message"
                                    required
                            ></v-text-field>

                            <v-btn
                                    :disabled="!valid"
                                    color="success"
                                    class="mr-4 mb-3"
                                    @click="sendMessage"
                            >
                                Message
                            </v-btn>
                        </v-form>
                    </div>
            </v-app>
        </div>
    </div>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/Date.format.min.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueFriends.js'), ['depends' => ['backend\assets\AppAsset']]); ?>

