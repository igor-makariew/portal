<?php

namespace backend\models;

use yii\base\Model;

class Game2048 extends Model
{
    public $values = [
        [0, 0, 0, 0],
        [0, 0, 0, 0],
        [0, 2, 0, 0],
        [0, 0, 0, 4]
    ];
}