<?php


/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'RabbitMQ-Send';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/rabbit-send.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/materialdesignicons.min.css', ['depends' => ['backend\assets\AppAsset']])); ?>
<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/vuetify.min.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<nav aria-label="breadcrumb">
    <!--    --><? //= Breadcrumbs::widget(
    //        $breadcrumbs
    //    );?>
</nav>

<div id="appRabbitSend">
    <v-app id="inspire">
        <template v-if="!storage">
            <div class="ml-3 mr-3 mt-3">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success" @click="createStorage">createStorage</button>
                </span>
            </div>
        </template>
        <template v-else>
            <p class="ml-3 mr-3 mt-3">
                <p class="ml-3 mr-3 mt-3">Root directory - {{rootDir}}
                    <span class="ml-5">

                            <v-dialog
                                v-model="dialogRename"
                                persistent
                                max-width="300px"
                            >
                                <v-card>
                                    <v-card-text>
                                        <v-row>
                                            <v-col>
                                                <v-text-field
                                                    label="Rename folder"
                                                    v-model="renameFolder"
                                                ></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="dialogRename = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="action(currentDirDialog)"
                                        >
                                            Rename
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>


                            <v-dialog v-model="dialogCopy" persistent max-width="700px">
                                    <v-card>
                                    <v-card-title>
                                        <template v-if="rootDir == currentDirDialog">
                                            <div class="ml-3 mr-3 mt-3">
                                                <p class="headline">Current directory - {{currentDirDialog}}</p>
                                            </div>
                                        </template>

                                        <template v-else>
                                            <div class="ml-3 mr-3 mt-3">
                                                <p> class="headline"Current directory - <a href="" @click.prevent="dirSelectionParent(currentDirDialog, false)">{{currentDirDialog}}</a></p>
                                            </div>
                                        </template>

                                    </v-card-title>
                                            <v-card-text>
                                                  <v-row>
                                                      <template v-if="dialogCopySiteMaps">
                                                          <div class="row ml-3 mr-3 mt-3">
                                                              <div class="col-sm-3" v-for="(dialogCopySiteMap, index ) in dialogCopySiteMaps">
                                                                  <div class="card card-block">
                                                                      <template v-if="dialogCopySiteMap.catalog">
                                                                            <div class="card-block">
                                                                                <p class="card-text align-center">
                                                                                    <a href=""
                                                                                       @click.prevent="dirSelectionChildren(dialogCopySiteMap.name, false)"
                                                                                    >
                                                                                        <p :ref="index">
                                                                                            <v-icon
                                                                                                    default
                                                                                                    class="mr-2"
                                                                                                    color="grey"
                                                                                            >mdi-folder</v-icon>
                                                                                            {{shortTitle(dialogCopySiteMap.name)}}
                                                                                        </p>
                                                                                    </a>
                                                                                </p>
                                                                            </div>
                                                                      </template>

                                                                      <template v-else>
                                                                        <div class="card-block">
                                                                            <p class="card-text align-center">
                                                                                    <img class="param-img"
                                                                                         v-bind:src="dialogCopySiteMap.link"
                                                                                         v-bind:alt="dialogCopySiteMap.name"
                                                                                    >
                                                                                    <p :ref="index">{{shortTitle(dialogCopySiteMap.name)}}</p>
                                                                            </p>
                                                                        </div>
                                                                    </template>
                                                                  </div>
                                                              </div>
                                                        </div>
                                                      </template>
                                                  </v-row>
                                          </v-card-text>
                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="dialogCopy = false">Cancel</v-btn>
                                    <v-btn color="blue darken-1" text @click="action(currentDirDialog)">Copy</v-btn>
                                  </v-card-actions>
                                    </v-card>
                              </v-dialog>

                            <v-dialog
                                    v-model="dialog"
                                    persistent
                                    max-width="300px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                            color="#008d4c"
                                            v-bind="attrs"
                                            v-on="on"
                                            dark
                                    >
                                        <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-card-text>
                                        <v-row>
                                            <v-col>
                                                <v-form
                                                    ref="form"
                                                    v-model="valid"
                                                >
                                                    <v-text-field
                                                            label="Name folder"
                                                            v-model="nameFolder"
                                                            :rules="nameFolderRules"
                                                            required
                                                    ></v-text-field>
                                                </v-form>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                                color="blue darken-1"
                                                text
                                                @click="dialog = false"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                                :disabled="!valid"
                                                color="blue darken-1"
                                                text
                                                @click="createDir()"
                                        >
                                            Create
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                    </span>
                </p>
            </div>

            <div class="ml-3 mr-3 mt-3">
                <v-file-input
                        v-model="files"
                        ref="files"
                        color="deep-purple accent-4"
                        counter
                        label="File input"
                        multiple
                        placeholder="Select your files"
                        prepend-icon="mdi-paperclip"
                        outlined
                        :show-size="1000"
                        @change="handleFileUploads()"
                >
                    <template v-slot:selection="{ index, text }">
                        <v-chip
                                v-if="index < 2"
                                color="deep-purple accent-4"
                                dark
                                label
                                small
                        >
                            {{ text }}
                        </v-chip>

                        <span
                                v-else-if="index === 2"
                                class="grey--text text--darken-3 mx-4"
                        >
                                  +{{ files.length - 2 }} File(s)
                                </span>
                    </template>
                </v-file-input>
            </div>

            <div class="ml-3 mr-3 mt-3">
                <p >Get busy disk space - {{dirSize}}</p>
                    <span class="col-sm-3">
                        <v-progress-linear
                                v-model="dirSize"
                                height="25"
                        >
                                <strong>{{ Math.ceil(dirSize) }}%</strong>
                          </v-progress-linear>
                    </span>
            </div>

            <template v-if="rootDir == currentDir">
                <div class="ml-3 mr-3 mt-3">
                    <p>Current directory - {{currentDir}}</p>
                </div>
            </template>

            <template v-else>
                <div class="ml-3 mr-3 mt-3">
                    <p>Current directory - <a href="" @click.prevent="dirSelectionParent(currentDir, true)">{{currentDir}}</a></p>
                </div>
            </template>

            <div class="row ml-3 mr-3 mt-3" v-if="siteMaps">
                <div class="col-sm-3" v-for="(siteMap, index ) in siteMaps">
                    <div class="card card-block">
<!--                        <div>-->
<!--                            <button type="button" class="close-button font-weight-light callback-close" @click.prevent="removeFile( index )" data-dismiss="modal" aria-label="Close">-->
<!--                                <span aria-hidden="true">&times;</span>-->
<!--                            </button>-->
<!--                        </div>-->
                        <template v-if="siteMap.catalog">
                            <div class="card-block">
                                <p class="card-text align-center">
                                    <a href=""
                                       @click.prevent="dirSelectionChildren(siteMap.name, true)"
                                       @contextmenu="show($event, index)"
                                    >
                                        <p :ref="index">
                                            <v-icon
                                                default
                                                class="mr-2"
                                                color="grey"
                                            >mdi-folder</v-icon>
                                            {{shortTitle(siteMap.name)}}
                                        </p>

                                <v-menu
                                    v-model="showMenu"
                                    :position-x="x"
                                    :position-y="y"
                                    absolute
                                    offset-y
                                >
                                    <v-list>
                                        <v-list-item
                                            v-for="(item, keyMenu) in items"
                                            :key="keyMenu"
                                            link
                                        >
                                            <v-list-item-title @click="taskMenu(keyMenu, currentDir, siteMaps[countIndex].name)">
                                                <p class="subtitle-1">
                                                    <v-icon
                                                        small
                                                        class="mr-2"
                                                        color="grey"
                                                    >
                                                        {{mdiIcons[keyMenu]}}
                                                    </v-icon>
                                                    {{ item.title }}
                                                </p>
                                            </v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                                    </a>
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            <div class="card-block">
                                <p class="card-text align-center">
                                    <a
                                            v-bind:href="siteMap.link"
                                            target="_blank"
                                            @contextmenu="show($event, index)"
                                    >
                                        <img class="param-img" v-bind:src="siteMap.link" v-bind:alt="siteMap.name"
                                             v-on:mouseover="fullTitle(siteMap.name, index)"
                                             v-on:mouseleave="shortiesTitle(index)"
                                        >
                                        <p :ref="index">{{shortTitle(siteMap.name)}}</p>

                                        <v-menu
                                                v-model="showMenu"
                                                :position-x="x"
                                                :position-y="y"
                                                absolute
                                                offset-y
                                        >
                                            <v-list>
                                                <v-list-item
                                                        v-for="(item, keyMenu) in items"
                                                        :key="keyMenu"
                                                        link
                                                >
                                                    <v-list-item-title @click="taskMenu(keyMenu, currentDir, siteMaps[countIndex].name)">
                                                        <p class="subtitle-1">
                                                            <v-icon
                                                                small
                                                                class="mr-2"
                                                                color="grey"
                                                        >
                                                                {{mdiIcons[keyMenu]}}
                                                        </v-icon>
                                                            {{ item.title }}
                                                        </p>
                                                    </v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

            <div class="ml-3 mr-3">
                <v-text-field
                        label="Message"
                        v-model="message"
                ></v-text-field>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success" @click="Send">Send</button>
                </span>
            <div>

                <hr>
                <p class="text-xl-center font-weight-bolder">GOOGLE MAPS</p>
            <div>
<!--                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2036.9390338419885!2d38.57107025907795!3d52.617656467114806!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sru!4v1675088603640!5m2!1sru!2sru" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>-->
            </div>
<!--        <template>-->
    </v-app>
</div>

<hr>
<p class="text-xl-center font-weight-bolder">YANDEX MAPS</p>
<!--<div id="map" style="width: 100%; height: 50%; padding: 0; margin: 0"></div>-->

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueRabbitSend.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
<?//= $this->registerJsFile("https://api-maps.yandex.ru/2.1/?lang=en_RU&amp&apikey=548e2cf4-66a7-4dc8-b8b0-b9ee2aea7d3a"), ['depends' => ['backend\assets\AppAsset']]; ?>
<?//= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/multirouteViewOptions.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
