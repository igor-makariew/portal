<?php

namespace backend\models;

use yii\base\Model;

class Game extends Model
{
    public $randomArr = [];

    public function getRandomArr($countValue) {
        for($index = 1; $index <= $countValue; $index++) {
            $number = rand(1, $countValue * $countValue);
            if ($this->uniqueArray($number) == false) {
                $this->randomArr[] = $number;
            } else {
                $this->randomArr[] = $number + 1;
            }
        }

        return $this->randomArr;
    }

    /**
     * проверка массива на уникальность
     *
     * @param value $
     * @return false|int|string
     */
    public function uniqueArray($value)
    {
        return array_search($value, $this->randomArr);
    }

    /**
     * create array
     *
     * @param $sizeArr
     * @return array
     */
    public function createIds($sizeArr): array
    {
        $ids = array();
        for($index = 1; $index <= ($sizeArr * $sizeArr); $index++) {
            $ids[$index] = $index;
        }

        return $ids;
    }

    /**
     * выбор значения по ключю
     *
     * @param $array
     * @param $step
     * @return mixed
     */
    public function selectId($array, $step): int
    {
        return $array[$step];
    }
}