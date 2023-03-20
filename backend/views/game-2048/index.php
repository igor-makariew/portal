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
            <button class="button" @click="restart"> New Game</button>
        </div>

        <div class="game-container">
            <template v-if="gameover">
                <div class="alert-gameover"><div class="text-gameover">Game over</div></div>
            </template>
            <div class="game-row" v-for="(rowSquares, index) in valuesSquares">
                <div v-for="(square, index) in rowSquares" :class="nameClass(square)">
                    <span class="chislo">{{square}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="control">
        <button
                v-bind:class="{'button-control-vertical': gamestart, 'button-control-vertical-gameover': gameover}"
                v-bind:disabled="gameover" @click="up()">
            <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
        </button>
        <div class="horizontal">
            <button
                    v-bind:class="{'button-control-horizontal': gamestart, 'button-control-horizontal-gameover': gameover}"
                    v-bind:disabled="gameover" @click="left()">
                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
            </button>
            <button
                    v-bind:class="{'button-control-horizontal': gamestart, 'button-control-horizontal-gameover': gameover}"
                    v-bind:disabled="gameover" @click="right()">
                <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
            </button>
        </div>
        <button
                v-bind:class="{'button-control-vertical-down': gamestart, 'button-control-vertical-down-gameover': gameover}"
                v-bind:disabled="gameover" @click="down()">
            <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
        </button>
    </div>
</div>


<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueGame2048.js'), ['depends' => ['backend\assets\AppAsset']]); ?>