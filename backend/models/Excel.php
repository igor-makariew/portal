<?php

namespace backend\models;

use yii\base\Model;
use PHPExcel_IOFactory;

class Excel extends Model
{
    /**
     * считывание данных с файла
     *
     * @param $file
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function getDataFile($file)
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
}