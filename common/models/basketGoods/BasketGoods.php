<?php

namespace common\models\basketGoods;
use Yii;
use yii\base\Model;

class BasketGoods extends Model
{
    /**
     * @property array $basket;
     * @property array $session;
     */
    private $basket;
    private $session;

    public function __construct()
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if (!$this->session->has('basketGoods')) {
            $this->session->set('basketGoods', []);
            $this->basket = [];
        } else {
            $this->basket = $this->session->get('basketGoods');
        }
    }

    public function addBasket($idsGoods)
    {
        if ($this->basket == null) {
            $this->basket[Yii::$app->user->id]['ids'][] = $idsGoods;
        } else {
            $this->basket[Yii::$app->user->id]['ids'][] = $idsGoods;
        }

        $this->session->set('basketGoods',  $this->basket);
    }

    public function getBasket()
    {
        return $this->session->get('basketGoods');
    }

    public function remove()
    {
        $this->session->remove('basketGoods');
    }

    public function removeFromBasket(array $goods)
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if ( !$this->session->has('basketGoods')) {
            return false;
        }

        $this->basket = $this->session->get('basketGoods');

        foreach ($goods as $order) {
            foreach ($this->basket[Yii::$app->user->id]['ids'] as $index => $good) {
                if($order['id'] == $good) {
                    unset($this->basket[Yii::$app->user->id]['ids'][$index]);
                }
            }
        }

        $this->session->set('basketGoods', $this->basket);
        return true;

    }
}