<?php

namespace backend\models;
use phpDocumentor\Reflection\File;
use yii\base\Model;
use Yii;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use yii\base\ErrorException;

class Storage extends Model
{
    public $redis;
    const MAX_SIZE = 5;

    public function __construct()
    {
        $this->redis = Yii::$app->redis;
    }

    /**
     * проверка является ли каталог директорией
     *
     * @param $path
     * @return bool
     */
    public function isDir($path): bool
    {
        return is_dir($path);
    }

    /**
     * создание дирректории
     *
     * @param $path
     * @return bool
     */
    public function makeDir($path): bool
    {
        return mkdir($path, 0777);
    }

    /**
     * Корневая директория
     *
     * @param $userId
     * @return string
     */
    public function rootDir($userId): string
    {
        return 'user_id_' . $userId;
    }

    /**
     * определение текущей директории
     *
     * @param $path
     * @return string
     */
    public function currentDir($path): string
    {
        return basename($path);
    }

    /**
     * set redis hset
     *
     * @param $key
     * @param $field
     * @param $value
     */
    public function setDir($key, $field, $value)
    {
        $this->redis->hset($key, $field, $value);
    }

    /**
     * get redis hget
     *
     * @param $key
     * @param $field
     * @return mixed
     */
    public function getDir($key, $field)
    {
        return $this->redis->hget($key, $field );
    }

    /**
     * создание пути каталогов
     *
     * @param $key
     * @param $field
     * @param $catalogName
     * @param $param
     * @return mixed
     */
    public function createPath($key, $field, $catalogName, $param)
    {
        if ($param == 'add') {
            $currentDir = $this->getDir($key, $field);
            $currentDir .= '/' . $catalogName;
            $this->setDir($key, $field, $currentDir);
        } else {
            $this->setDir($key, $field, $catalogName);
        }

        return $this->getDir($key, $field);
    }

    /**
     * получение родительской дирестории
     *
     * @param $path
     * @return string
     */
    public function getParentDir($path)
    {
        return dirname($path);
    }

    /**
     * Функция перевода байтов в MB
     *
     * @param $bytes
     * @return string
     */
    public function formatSize($bytes): float
    {
        return number_format($bytes / 1048576, 2);
    }

    /**
     * подсчет объема директории
     *
     * @param $param
     * @return float
     */
    public function GetDirectorySize($path): int
    {
        $bytestotal = 0;
        $path = realpath($path);
        if($path !== false && $path != '' && file_exists($path)){
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

    /**
     * ограничение по максимальному размеру
     *
     * @param $sizeImage
     * @param $path
     * @return bool
     */
    public function isMaxSize($sizeImage, $path): bool
    {
        $byteTotal = $this->formatSize($this->GetDirectorySize($path));
        $currentFile = $this->formatSize($sizeImage);
        if (self::MAX_SIZE > ($byteTotal + $currentFile)) {
            return true;
        }

        return false;
    }

    /**
     * удаление файлов и категорий
     *
     * @param $dir
     * @param $filename
     * @param $param
     * @return bool
     */
    public function delete($filename, $param, $dir = null)
    {
        $response = [
            'response' => false,
            'errorException' => [
                'error' => '',
                'line' => ''
            ],
            'tree' => []
        ];

        try {
            if ($param == true) {
                if ($this->isDirNotEmpty($filename)) {
                    $response['tree'] = $this->getTrees($filename);
                    foreach ($this->getTrees($filename) as $filename) {
                        if ($this->isDir($filename)) {
                            rmdir($filename);
                        } else {
                            unlink($filename);
                        }
                    }

                    $response['response'] = !file_exists($dir);
                } else {
                    $response['response'] = rmdir($filename);
                }

                return $response;
            } else if ($param == false) {
                $response['response'] = unlink($filename);

                return $response;
            }
        } catch ( ErrorException $e ) {
            $response['errorException']['error'] = $e->getMessage();
            $response['errorException']['line'] = $e->getLine();

            return $response;
        }
    }

    /**
     * создвние дерева каталогов и файлов
     *
     * @param $filename
     * @return array
     */
    public function getTrees($filename): array
    {
        $trees = [];
        foreach (array_diff( scandir($filename), array('.', '..')) as $tree) {
            $path = $filename . DIRECTORY_SEPARATOR . $tree;

            if ($this->isDir($path)) {
                foreach ($this->getTrees($path) as $child) {
                    $trees[] =  $child;
                }

                $trees[] = $path;
            } else {
                $trees[] = $path;
            }
        }

        return $trees;
    }

    /**
     * проверяем директорию н пустоту
     *
     * @param $path
     * @return bool
     */
    public function isDirNotEmpty($path): bool
    {
        return count(array_diff( scandir($path), array('.', '..'))) > 0 ? true : false;
    }

    /**
     * копирование файла
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function copyFile($from, $to): bool
    {
        return copy($from, $to);
    }

    /**
     * перемещение файла
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function fileMove($from, $to): bool
    {
        return rename($from, $to);
    }

    public function fileRename($from, $to): bool
    {
        return rename($from, $to);
    }
}