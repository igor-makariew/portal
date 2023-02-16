<?php

namespace console\models;

use yii\base\Model;

class Commands extends Model
{
    public function createPdfFile() {
        echo 'createPdfFile - igor makariew' . PHP_EOL;
    }

    public function createTxtFile() {
        echo 'createTxtFile - igor makariew' . PHP_EOL;
    }
}