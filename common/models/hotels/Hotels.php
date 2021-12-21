<?php

namespace common\models\hotels;

use yii\db\ActiveRecord;
use Yii;

class Hotels extends ActiveRecord
{
    /**
     * @param string $key
     * @param array $array
     * @return bool
     */
    public static function isValueInArray(string $key, array $array): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        }

        return false;
    }
}
