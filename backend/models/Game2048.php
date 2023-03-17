<?php

namespace backend\models;

use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Void_;
use yii\base\Model;

class Game2048 extends Model
{
    const ROW = 4;
    const COLUMN = 4;
    public $total;

    public function __construct($total)
    {
        $this->total = $total;
    }

    public $values = [
        [0, 2, 0, 0],
        [0, 0, 0, 0],
        [0, 0, 2, 0],
        [0, 0, 0, 0]
    ];


    /**
     * create new array
     *
     * @param array $arr
     * @param $param
     * @return array
     */
    public function createNewArr(array $arr, $param): array
    {
        $this->values = $this->changedPositionElementsInArr($arr, $param);

        return $this->values;

    }

    public function changedPositionElementsInArr(array $arr, $param): array
    {
        if ($param == 'left') {
            $newArr = $this->delNullBetweenValueInArr($arr, true);
            $newArr = $this->setKeys($newArr);
            $newArr = $this->sumElementArray($newArr, true);
            $newArr = $this->addNewElemInArr($newArr);
            $arr = $this->setKeys($newArr);
        } else if ($param == 'right') {
            $newArr = $this->delNullBetweenValueInArr($arr, false);
            $newArr = $this->setKeys($newArr);
            $newArr = $this->sumElementArray($newArr, false);
            $newArr = $this->addNewElemInArr($newArr);
            $arr = $this->setKeys($newArr);
        } else if ($param == 'up') {
            $newArr = $this->createArrFromKeyArray($arr, true);
            $newArr = $this->delNullBetweenValueInArr($newArr, true);
            $newArr = $this->setKeys($newArr);
            $newArr = $this->sumElementArray($newArr, true);
            $newArr = $this->addNewElemInArr($newArr);
            $newArr = $this->setKeys($newArr);
            $arr = $this->createArrFromKeyArray($newArr, true);
        } else if ($param == 'down') {
            $newArr = $this->createArrFromKeyArray($arr, true);
            $newArr = $this->delNullBetweenValueInArr($newArr, false);
            $newArr = $this->setKeys($newArr);
            $newArr = $this->sumElementArray($newArr, false);
            $newArr = $this->addNewElemInArr($newArr);
            $newArr = $this->setKeys($newArr);
            $arr = $this->createArrFromKeyArray($newArr, true);
        }

        return $arr;
    }

    /**
     * троим массив пол ключам в прямой и обратной последовательности
     *
     * @param array $arr
     * @param bool $keyParam
     * @return array
     */
    public function createArrFromKeyArray(array $arr, bool $keyParam): array
    {
        $newArr = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
        ];

        if ($keyParam) {
            for ($row = 0;  $row < self::ROW; $row++ ) {
                for ($column = 0; $column < self::COLUMN; $column++) {
                    if ($column == 0) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column] );
                    } else if ($column == 1) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    } else if ($column == 2) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    } else if ($column == 3) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    }
                }
            }
        } else {
            for ($row = (self::ROW - 1);  $row > -1; $row-- ) {
                for ($column = (self::COLUMN - 1); $column > -1; $column--) {
                    if ($column == 3) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    } else if ($column == 2) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    } else if ($column == 1) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    } else if ($column == 0) {
                        array_push($newArr[$column], $arr[$row][$column] == NULL ? 0 : $arr[$row][$column]);
                    }
                }
            }
        }

        return $newArr;
    }

    /**
     * удаляет нули между элементами массива в обе стороны
     *
     * @param array $arr
     * @param bool $keyParam
     * @return array
     */
    public function delNullBetweenValueInArr(array $arr, bool $keyParam): array
    {
        if ($keyParam) {
            for ($row = 0; $row < self::ROW; $row++) {
                for ($column = 0; $column < self::COLUMN; $column++) {
                    if ($arr[$row][$column] == 0) {
                        unset($arr[$row][$column]);
                        array_push($arr[$row], 0);
                    }
                }
            }
        } else {
            for ($row = (self::ROW - 1); $row > -1; $row--) {
                $addValsArr = [];
                for ($column = (self::COLUMN - 1); $column > -1; $column--) {
                    if ($arr[$row][$column] == 0) {
                        unset($arr[$row][$column]);
                        array_unshift($addValsArr, 0);
                    }
                }
                if (count($addValsArr) > 0) {
                    $arr[$row] = array_merge($addValsArr, $arr[$row]);
                }
            }
        }

        return $arr;
    }

    /**
     * меняем значение ключей массива
     *
     * @param array $arr
     * @return array
     */
    public function setKeys(array $arr): array
    {
        $newArr = [];
        foreach ($arr as $indexRow => $array) {
            $key = 0;
            foreach ( $array as $indexColumn => $number) {
                $newArr[$indexRow][$key] = $number;
                $key++;
            }
        }

        return $newArr;
    }

    /**
     * сложение элеиентов массива
     *
     * @param array $arr
     * @param bool $keyParam
     * @return array
     */
    public function sumElementArray(array $arr, bool $keyParam): array
    {
        if ($keyParam) {
            for ($row = 0; $row < count($arr); $row++) {
                for ($column = 0; $column < (count($arr[$row]) - 1); $column++) {
                    if ($arr[$row][$column] == $arr[$row][$column + 1] && $arr[$row][$column] != 0) {
                        $arr[$row][$column] = $arr[$row][$column] * 2;
                        $this->setTotalPoint($arr[$row][$column]);
                        unset($arr[$row][$column + 1]);
                        array_push($arr[$row], 0);
                    }
                }
            }
        } else {
            for ($row = self::ROW; $row > -1; $row--) {
                for ($column = (self::COLUMN - 1); $column > -1; $column--) {
                    if ($arr[$row][$column] == $arr[$row][$column + 1] && $arr[$row][$column] != 0) {
                        $arr[$row][$column] = $arr[$row][$column] * 2;
                        $this->setTotalPoint($arr[$row][$column]);
                        unset($arr[$row][$column + 1]);
                        array_unshift($arr[$row], 0);
                    }
                }
            }
        }

        return $arr;
    }

    /**
     * создание нового значения на поле
     *
     * @param array $arr
     * @return int
     */
    public function addNewElemInArr(array $arr): array
    {
        $arrEmptyVal = [];

        foreach ($arr as $indexRow => $array) {
            foreach ($array as $indexColumn => $number) {
                if ($number == 0) {
                    $arrEmptyVal[] = [$indexRow, $indexColumn];
                }
            }
        }

        $randVal = rand(0, (count($arrEmptyVal) - 1));
        $indexesNewVal =  $arrEmptyVal[$randVal];
        $arr[$indexesNewVal[0]][$indexesNewVal[1]] = $this->newElem();
        return $arr;
    }

    /**
     * случайное число 2 или 4
     *
     * @return int
     */
    public function newElem(): int
    {
        $randNum = rand(2, 4);
        return $randNum == 3 ? 2 : $randNum;
    }

    /**
     * запись набранных очков
     *
     * @param int $total
     * @param int $number
     */
    private function setTotalPoint(int $number): void
    {
        $this->total += $number;
    }

    public function getTotalPoint(): int
    {
        return $this->total;
    }
}