<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'Game 2048';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/game2048.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<div class="container" id="appGame2048">
    <div class="game-interface">
        <div class="header">
            <div class="title-name">
                <h1>2048</h1>
            </div>
            <div class="game-params">
                <div class="game-check">
                    <span>Check <br> <strong>{{total}}</strong> </span>
                </div>
                <div class="game-best-player">
                    <span>Best <br> <strong>0</strong> </span>
                </div>
            </div>
        </div>

        <div class="game-button">
            <button class="button"> New Game</button>
        </div>

        <div class="game-container">
            <div class="game-row" v-for="(rowSquares, index) in valuesSquares">
                <div v-for="(square, index) in rowSquares" :class="nameClass(square)">
                    <span class="chislo">{{square}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="control">
        <button class="button-control-vertical" @click="up()"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></button>
        <div class="horizontal">
            <button class="button-control-horizontal" @click="left()"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></button>
            <button class="button-control-horizontal" @click="right()"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
        </div>
        <button class="button-control-vertical-down" @click="down()"><i class="fa fa-long-arrow-down" aria-hidden="true"></i></button>
    </div>
</div>


<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueGame2048.js'), ['depends' => ['backend\assets\AppAsset']]); ?>