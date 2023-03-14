<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;

$this->title = 'Game 2048';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->registerCssFile(Yii::$app->urlManager->createUrl('/css/game2048.css', ['depends' => ['backend\assets\AppAsset']])); ?>

<div class="container">
    <div class="game-interface" id="appGame2048">
        <div class="header">
            <div class="title-name">
                <h1>2048</h1>
            </div>
            <div class="game-params">
                <div class="game-check">
                    <span>Check <br> <strong>0</strong> </span>
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
            <div class="game-row">
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
            </div>

            <div class="game-row">
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
            </div>

            <div class="game-row">
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
            </div>

            <div class="game-row">
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
                <div class="game-cell"></div>
            </div>
        </div>
    </div>
</div>


<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/vueGame2048.js'), ['depends' => ['backend\assets\AppAsset']]); ?>