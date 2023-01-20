<?php

namespace common\modules\models\postauto;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post_auto".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $post
 * @property string|null $author
 * @property string|null $image
 * @property string|null $date
 */
class PostAuto extends ActiveRecord
{
    public $img;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_auto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post'], 'string'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['author', 'image'], 'string', 'max' => 64],
            // не отрабатывает правило
            [['img'], 'file', 'extensions' => 'jpg, gif, png, jpeg']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'post' => 'Post',
            'author' => 'Author',
            'image' => 'Image',
            'date' => 'Date',
        ];
    }

    /**
     * upload image
     */
    public function upload()
    {
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/backend/web/images/uploadImages/";
        if ($this->validate()) {
            $this->img->saveAs("{$dir}/{$this->img->baseName}.{$this->img->extension}");
        } else {
            return false;
        }
    }

    /**
     * @param $model
     * @param $image
     * @return array
     */
    public function createAttributesModel($model, $image)
    {
        return $attributes = [
            'title' => $model['title'],
            'post' => $model['post'],
            'author' => $model['author'],
            'image' => $image->baseName.'.'.$image->extension,
            'date' => date('Y-m-d')
            ];
    }
}
