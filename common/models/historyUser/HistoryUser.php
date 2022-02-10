<?php

namespace common\models\historyUser;

use Yii;
use yii\base\Model;

class HistoryUser extends Model
{
    public $hisrtoryUser = [];
    public $session;

    public function __construct($id = null)
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if (!$this->session->has('historyUser')) {
            $this->session->set('historyUser', []);
        } else {
            $this->hisrtoryUser = $this->session->get('historyUser');
        }
        if ($id != null) {
            $this->addId($id);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function addId($id)
    {
        if (!in_array($id, $this->hisrtoryUser)) {
            if (count($this->hisrtoryUser) == Yii::$app->params['MAX_COUNT_VIEWED_HOTELS']) {
                if ($this->deleteId()) {
                    $this->hisrtoryUser = $this->session->get('historyUser');
                    array_push($this->hisrtoryUser, $id);
                    $this->session->set('historyUser', $this->hisrtoryUser);
                    return true;
                }
            } else {
                array_push($this->hisrtoryUser, $id);
                $this->session->set('historyUser', $this->hisrtoryUser);
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function deleteId()
    {
        $firstElem = array_shift($this->hisrtoryUser);
        if (!in_array($firstElem, $this->hisrtoryUser)) {
            $this->session->set('historyUser', $this->hisrtoryUser);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getIds()
    {
        return $this->session->get('historyUser');
    }

    public function clearIds()
    {
        $this->session->set('historyUser', []);
    }
}
