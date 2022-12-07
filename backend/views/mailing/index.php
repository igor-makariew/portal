<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'Рассылка';
$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <?= Breadcrumbs::widget(
        $breadcrumbs
    );?>
</nav>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/mailing.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<div id="appMailing">
    <v-app id="inspire">
        <div class="mailing-container">
            <v-form
                ref="form"
                v-model="valid"
            >
                <v-text-field
                    v-model="subject"
                    :counter="50"
                    :rules="subjectRules"
                    label="Subject"
                    required
                ></v-text-field>

                <v-textarea
                    v-model="textBody"
                    :rules="textBodyRules"
                    clearable
                    clear-icon="mdi-close-circle"
                    label="Textbody"
                ></v-textarea>

                <v-btn
                    :disabled="!valid"
                    color="success"
                    class="mr-4"
                    @click="mailingList"
                >
                    Mailing
                </v-btn>
            </v-form>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueMailing.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
