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
<?php var_dump($app);?>
<div id="appRabbit">
    <v-app id="inspire">

        <div class="ml-3 mr-3">
            <span class="input-group-btn">
                <button type="button" class="btn btn-success" @click="Send">Send</button>
            </span>
        </div>
    </v-app>
</div>

<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueRabbit.js'), ['depends' => ['backend\assets\AppAsset']]); ?>
