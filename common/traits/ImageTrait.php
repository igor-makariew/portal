<?php

namespace common\traits;


trait ImageTrait
{
    public $width;
    public $height;
    public $type;
    public $nameImage;
    public $delImage;

    /**
     * @param $path
     * @param $fileName
     * @return array
     */
    public function getParamsImage($path, $fileName)
    {
        return list($this->width, $this->height, $this->type) = getimagesize($path.'/'.$fileName);
    }

    /**
     * @param $path
     * @param $nameDir
     * @return array|false
     */
    public function getNameFile($path, $nameDir)
    {
        return $this->nameImage = scandir($path.$nameDir);
    }

    /**
     * @param $path
     * @param $nameDir
     * @param $type
     */
    public function createImage($path, $nameDir,  $type)
    {
        if ($type == IMAGETYPE_JPEG) {
            $newImage = imagecreatefromjpeg($path.$nameDir.'/'.$this->nameImage[2]);
            $newImg = imagescale($newImage, 160, 160);
            $this->delFile($nameDir, $path);
            header("Content-type: image/jpeg");
            imagejpeg($newImg, $path.$nameDir.'/'.$this->nameImage[2]);
        } elseif($type == IMAGETYPE_PNG) {
            $newImage = imagecreatefrompng($path.$nameDir.'/'.$this->nameImage[2]);
            $newImg = imagescale($newImage, 160, 160);
            $this->delFile($nameDir, $path);
            header("Content-type: image/png");
            imagepng($newImg, $path.$nameDir.'/'.$this->nameImage[2]);
        }
    }

    /**
     * @param $nameDir
     * @param $path
     * @return bool
     */
    public function delFile($nameDir, $path)
    {
        $file = scandir($path.$nameDir);
        $this->delImage = $file[2];
        return unlink($path.$nameDir.'/'.$file[2]);
    }
}