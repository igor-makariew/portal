<?php

namespace backend\models;

use phpDocumentor\Reflection\Types\Boolean;
use yii\base\Model;

class Game2048 extends Model
{
    const ROW = 4;
    const COLUMN = 4;

    public $values = [
        [2, 4, 0, 8],
        [0, 4, 2, 0],
        [2, 2, 0, 8],
        [2, 2, 2, 4]
    ];
    public $itemsColumns = [
        0 => [],
        1 => [],
        2 => [],
        3 => [],
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

//        $array = array(1, 2, 3, 4);
//        foreach ($this->values as &$array) {
//            foreach ($array as &$value) {
//                $value = $value * 2;
//            }
//        }


        return $this->values;

    }

    public function changedPositionElementsInArr(array $arr, $param)
    {
        $emptyElem = [];
        $numberElem = [];

        if ($param == 'left') {
            foreach ($arr as $index => $array) {
                foreach ($array as $index => $number) {
                    if ($number == 0) {
                        $emptyElem[] = $number;
                    } else {
                        $numberElem[] = $number;
                    }
                }
            }

            if (count($numberElem) > 0) {
                $arr = array_merge($numberElem, $emptyElem);
            }

        } else if ($param == 'right') {

            foreach ($arr as $index => $array) {
                foreach ($array as $index => $number) {
                    if ($number == 0) {
                        $emptyElem[] = $number;
                    } else {
                        $numberElem[] = $number;
                    }
                }
            }

            if (count($numberElem) > 0) {
                $arr = array_merge($emptyElem, $numberElem);
            }
        } else if ($param == 'up') {
            $newArr = $this->createArrFromKeyArray($arr, true);
            $newArr = $this->delNullBetweenValueInArr($newArr, true);
            $newArr = $this->setKeys($newArr);
            $newArr = $this->sumElementArray($newArr, true);
            $newArr = $this->setKeys($newArr);
            $arr = $this->createArrFromKeyArray($newArr, true);
        } else if ($param == 'down') {
            $newArr = $this->createArrFromKeyArray($arr, true);
            $newArr = $this->delNullBetweenValueInArr($newArr, false);
            $newArr = $this->setKeys($newArr);
            $newArr = $this->sumElementArray($newArr, false);
            $newArr = $this->setKeys($newArr);
            $arr = $this->createArrFromKeyArray($newArr, true);
        }

        return $arr;
    }

    /**
     * get items columns and value
     *
     * @param array $arr
     * @return array
     */
    public function getItemsColumns(array $arr): array
    {
        $valueParams = [];
        for ($row = 0; $row < count($arr); $row++) {
            for ($column = 0; $column < count($arr[$row]); $column++) {
                if ($arr[$row][$column] != 0) {
                    $valueParams[] = [$column, $arr[$row][$column]];
                }
            }
        }

        return $valueParams;
    }

    /**
     * передвигает ячейки наверх игрового поля
     *
     * @param array $arr
     */
    public function moveUp(array $arr): array
    {
        for ($index = 0; $index < count($arr); $index++ ) {

            if ($arr[$index][0] == 0) {
                array_push($this->itemsColumns[0], $arr[$index][1]);
            } else if ($arr[$index][0] == 1) {
                array_push($this->itemsColumns[1], $arr[$index][1]);
            } else if ($arr[$index][0] == 2) {
                array_push($this->itemsColumns[2], $arr[$index][1]);
            } else if ($arr[$index][0] == 3) {
                array_push($this->itemsColumns[3], $arr[$index][1]);
            }
        }

         $values = [
            [0, 0, 0, 0],
            [0, 0, 0, 0],
            [0, 0, 0, 0],
            [0, 0, 0, 0]
        ];

        for($row = 0; $row < count($values); $row++) {
            for($column = 0; $column < count($values[$row]); $column++) {
                if (empty($this->itemsColumns[$column][$row])) {
                    $values[$row][$column] = 0;
                } else {
                    $values[$row][$column] = $this->itemsColumns[$column][$row];
                }
            }
        }

        return $values;
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
            for ($row = 0;  $row < count($arr); $row++ ) {
                for ($column = 0; $column < count($arr[$row]); $column++) {
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
            for ($row = (count($arr) - 1);  $row > -1; $row-- ) {
                for ($column = (count($arr[$row]) - 1); $column > -1; $column--) {
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
//                        array_unshift($arr[$row], 0);
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
                        unset($arr[$row][$column + 1]);
                        array_unshift($arr[$row], 0);
                    }
                }
            }
        }


        return $arr;
    }
}