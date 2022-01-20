<?php

namespace common\models\basket;

use yii\base\Model;
use Yii;

class Basket extends Model {
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
        if (!$this->session->has('basket')) {
            $this->session->set('basket', []);
            $this->basket = [];
        } else {
            $this->basket = $this->session->get('basket');
        }

    }

    /**
     * @param $paramUser
     * @param $hotel
     * @param int $count
     * @return mixed
     */
    public function addToBasket($paramUser, $hotel, int $count = 1)
    {
        if($this->basket == null) {
            $this->basket[$paramUser['id']]['user'] = [
                'email' => $paramUser['email'],
                'username' => $paramUser['username']
            ];
            $this->basket[$paramUser['id']][$hotel['hotel_id']] = [
                'fullName' => $hotel['full_name'],
                'hotelId' => $hotel['hotel_id'],
                'hotelName' => $hotel['hotel_name'],
                'label' => $hotel['label'],
                'locationName' => $hotel['location_name'],
                'priceAvg' => $hotel['price_avg'],
                'priceForm' => $hotel['price_form'],
                'pricePercentile' => $hotel['price_percentile'],
                'stars' => $hotel['stars'],
            ];
        } else if (!$this->validateHotel($paramUser['id'], $hotel['hotel_id'], $this->basket)) {
            $this->basket[$paramUser['id']][$hotel['hotel_id']] = [
                'fullName' => $hotel['full_name'],
                'hotelId' => $hotel['hotel_id'],
                'hotelName' => $hotel['hotel_name'],
                'label' => $hotel['label'],
                'locationName' => $hotel['location_name'],
                'priceAvg' => $hotel['price_avg'],
                'priceForm' => $hotel['price_form'],
                'pricePercentile' => $hotel['price_percentile'],
                'stars' => $hotel['stars'],
            ];
        }
        $this->session->set('basket',  $this->basket);
        return $this->session->get('basket');
    }

    /**
     * @return mixed
     */
    public function getBasket()
    {
        return $this->session->get('basket');
    }

    /**
     * Удаление товаров из корзины
     *
     * @param array $hotels
     * @return bool|mixed
     */
    public function removeFromBasket(array $hotels)
    {
        $this->session = Yii::$app->session;
        $this->session->open();
        if ( !$this->session->has('basket')) {
            return false;
        }

        $this->basket = $this->session->get('basket');
        foreach ($hotels as $order) {
            foreach ($this->basket[Yii::$app->user->identity->id] as $index => $hotel) {
                if ($index == 'user') {
                    continue;
                }
                if($order['name'] == $hotel['fullName'] || $order['name'] == $hotel['hotelName']) {
                    unset($this->basket[Yii::$app->user->identity->id][$index]);
                }
            }
        }

        $this->session->set('basket', $this->basket);
        return true;

    }

    /**
     * Подсчитываем количество заказов в корзине
     *
     * @return int
     */
    public function getCountBasket() {
        $basket = $this->getBasket();
        $count = [];
        foreach ($basket[Yii::$app->user->identity->id] as $index => $countItem) {
            if ($index !== 'user') {
                $count[$index] = $countItem;
            }
        }

        return count($count);
    }

    /**
     * @param int $id
     * @param int $hotelId
     * @param array $hotels
     * @return bool
     */
    public function validateHotel(int $id, int $hotelId, array $hotels ): bool
    {
        return array_key_exists($hotelId, $hotels[$id]);
    }

    public function sendUserEmail($nameUser, $email, $countHotels)
    {
        return Yii::$app
            ->mailer
            ->compose(
                'order',
                ['nameUser' => $nameUser, 'countHotels' => $countHotels],
            )
            ->setFrom([Yii::$app->params['senderEmail'] => 'www.disigner@yandex.ru' . ' robot'])
            ->setTo($email)
            ->setSubject('Спасибо что выбрали нас' . $nameUser)
            ->send();
    }
}

