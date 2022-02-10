<?php

namespace common\models\listFilterHotel;

use yii\base\Model;
use Yii;

class ListFilterHotel extends Model
{
    public $listFilterHotels;
    public $session;

    public function __construct(array $array = [])
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if (!$this->session->has('listFilterHotels')) {
            $this->session->set('listFilterHotels', []);
        } else {
            $this->listFilterHotels = $this->session->get('listFilterHotels');
        }
        if (count($array) > 0) {
            $this->addList($array);
        }
    }

    /**
     * Получение списка отелей
     *
     * @return mixed
     */
    public function getList()
    {
        return $this->session->get('listFilterHotels');
    }


    /**
     * Создание списка отелей
     *
     * @param array $array
     * @return bool
     */
    public function addList(array $array)
    {
        if ($this->deleteList()) {
            $this->session->set('listFilterHotels', $array);
            return true;
        }

        return false;
    }

    /**
     * Удаление списка отелей
     *
     * @return bool
     */
    public function deleteList()
    {
        if (count($this->listFilterHotels) > 0) {
            $this->session->set('listFilterHotels', []);
            return true;
        }

        return true;
    }
}
