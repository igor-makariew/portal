<?php
/* @var $this yii\web\View */

$this->title = 'WebSocket';
?>


<div class="container">
    Username:<br /><br />
    <input id="username" type="text" placeholder="username" style="background-color: lightgreen">
    <br>
    <br>
    <button id="btnSetUsername" class="btn btn-success">Set username</button>
    <br>
    <br>
    <div id="chat" style="width:400px; height: 250px; overflow: scroll;"></div>

    Message:<br /><br />
    <input id="message" type="text" placeholder="message" style="background-color: lightgreen">
    <br>
    <br>
    <button id="btnSend" class="btn btn-success">Send</button>
    <br>
    <br>
    <div id="response" style="color:darkgray"></div>
</div>


<?= $this->registerJsFile(Yii::$app->urlManager->createUrl('/js/websocket.js'), ['depends' => ['backend\assets\AppAsset']]); ?>



