<?php

namespace common\models\favoriteProducts;

use Yii;
use yii\redis\ActiveRecord;

class FavoriteProducts extends ActiveRecord
{
    /**
     * идентификатор пользователя userId
     *
     * @var string
     */
    protected $userId;
    /**
     * хранилище данных типа ключ-значение redis
     *
     * @var array
     */
    protected $redis;
    public $favoritesId;

    public function __construct()
    {
        $this->userId = Yii::$app->user->identity->id;
        $this->redis = Yii::$app->redis;
        $this->favoritesId = $this->get();
    }

    /**
     * @return mixed
     */
    public function get() {
        return $this->redis->hvals($this->userId);
    }

    /**
     * @param $productId
     * @param $date
     */
    public function set($productId, $date) {
        return $this->redis->hset($this->userId, $productId, $date);
    }

    /**
     * @param $key
     * @param $field
     * @return mixed
     */
    public function getProduct($key, $field) {
        if ($this->redis->hexists($key, $field)) {
            return $this->redis->hget($key, $field);
        }
        return null;
    }

    /**
     * @param $key
     * @param $field
     */
    public function deleteProduct($key, $field) {
        if ($this->redis->hexists($key, $field)) {
            return $this->redis->hdel($key, $field);
        }
        return null;
    }

    /**
     * @param $key
     * @param $field
     * @return bool
     */
    public function existsProduct($key, $field) {
        if ($this->redis->hexists($key, $field)) {
            return true;
        }

        return false;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getKeys($key) {
        return $this->redis->hkeys($key);
    }
}
