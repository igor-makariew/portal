<?php

namespace backend\models;

use yii\base\Model;
use PHPExcel_IOFactory;
use Yii;

class Excel extends Model
{
    public $session;
    public $excelTitle = [];
    public $excelСeils = [];

    public function __construct()
    {
        $this->session = Yii::$app->session;
        $this->session->open();

        if (!$this->session->has('headers') && !$this->session->has('desserts')) {
            $this->session->set('headers', []);
            $this->session->set('desserts', []);
        }
    }

    /**
     * считывание данных с файла
     *
     * @param $file
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getDataFile($file): array
    {
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        return $sheetData;
    }

    /**
     * удаление файла
     *
     * @param $file
     * @return bool
     */
    public function delFile($file): bool
    {
        return unlink($file);
    }

    /**
     * возвращает массив имен полей
     *
     * @param $array
     * @return array
     */
    public function getNameColumn($array): array
    {
        return array_keys($array);
    }

    /**
     * Получение название столбцов
     *
     * @param $array
     * @return array
     */
    public function getExcelTitle($array): array
    {
        foreach ($array as $titleColumn) {
            if ($titleColumn['text'] != 'Actions') {
                array_push($this->excelTitle, $titleColumn['text']);
            }
        }

        return $this->excelTitle;
    }

    /**
     * преобразование массива к нужному формату
     *
     * @param $array
     * @return array
     */
    public function getExcelСeils($array): array
    {
        foreach ($array as $valuesRow) {
            if ($valuesRow != null) {
                $array = array_values($valuesRow);
                array_push($this->excelСeils, $array);
            }
        }

        return $this->excelСeils;
    }
}