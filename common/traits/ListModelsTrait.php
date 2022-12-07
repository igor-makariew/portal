<?php
namespace common\traits;


trait ListModelsTrait
{
    public $path = '';
    public $listModels = [
        'listPaths' => [],
        'listNames' => []
    ];

    /**
     * получаем параметры файлов (моделей)
     *
     * @return array
     */
    public function getParamsFiles()
    {
        $namesDir = scandir($this->path);
        foreach ($namesDir as $index => $nameDir) {
            if(!is_dir($this->path.'/'.$nameDir)) {
                if($this->isModelTable($this->path.$nameDir)) {
                    $this->listModels['listNames'][$index] = $this->getNameModel($nameDir);
                    $this->listModels['listPaths'][$index] = $this->path;
                }
            } else {
                $model = scandir($this->path.$nameDir);

                if($this->isModelTable($this->path.'/'.$nameDir.'/'.$model[2])) {
                    $this->listModels['listNames'][$index] = $this->getNameModel($model[2]);
                    $this->listModels['listPaths'][$index] = $this->path.$nameDir;
                }
            }
        }

        return $this->listModels;
    }

    /**
     * проверяет модель на наличие таблицы
     *
     * @param $namefile
     * @return bool
     */
    public function isModelTable($namefile)
    {
        if(strpos(file_get_contents($namefile), 'tableName()')) {
            return true;
        }

        return false;
    }

    /**
     * пполучение иммени файла
     *
     * @param $filename
     * @return mixed
     */
    public function getNameModel($filename)
    {
        $name = explode(".", $filename);
        return $name[0];
    }
}