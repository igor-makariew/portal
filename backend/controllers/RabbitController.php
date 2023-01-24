<?php

namespace backend\controllers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use yii\web\Controller;
use Yii;

class RabbitController extends Controller
{
    public $enableCsrfValidation = false;
    public $date = '';
    public $temps = [];
    public $weeks = [];
    public $months = [];

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionServer()
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(Yii::$app->request->getRawBody());
        $param = $data['data'];

        $path = $_SERVER['DOCUMENT_ROOT'] . "/backend/controllers/weather_statistics.csv";

        $rows = [];
        $handle = fopen($path, "r");
        fgetcsv($handle, 10000, ",");
        while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
            $row = [];
            array_push($row, $data[0], $data[1]);
            $rows[] = $row;
        }

        switch ($param) {
            case 'Days':
                return $this->getDay($rows);
            case 'Months':
                return $this->getMonth($rows);
            case 'Weeks':
                return $this->getWeek($rows);
            default:
                return $this->getDay($rows);
        }
    }

    public function getDay($paramsOnYear)
    {
        $days = [];
        foreach ($paramsOnYear as $index => $param) {
            if (empty($this->date)) {
                $this->date = explode(' ', $param[0])[0];
            }

            if (!empty($this->date) && $this->date != explode(' ', $param[0])[0]) {
                $this->date = explode(' ', $param[0])[0];
            }

            if (strpos($param[0], $this->date) === 0) {
                if (empty($days[$this->date])) {
                    $days[$this->date] = $param[1];
                } else {
                    $days[$this->date] .= ', ' . $param[1];
                }
            }
        }

        return $this->avgTempDays($days);
    }

    public function getMonth($paramsOnYear): array
    {
        $days = $this->getDay($paramsOnYear);
        $months = [];
        foreach ($days as $day) {
            if (!array_key_exists($this->getNumberMonth($day['date']), $this->months)) {
                $months[$this->getNumberMonth($day['date'])][] = $day['temp'];
            } else {
                $months[$this->getNumberMonth($day['date'])][] = $day['temp'];
            }
        }

        return $this->avgTempMonth($months);
    }

    public function getWeek($paramsOnYear)
    {
        $days = $this->getDay($paramsOnYear);
        for ($index = 0; $index < (365 / 7); $index++) {
            $this->weeks[] = [
                'temp' => $this->avgTempWeeks(array_slice($days, ($index * 7), 7, true)),
                'date' => 'Week №' . ($index + 1)
            ];
        }

        return $this->weeks;
    }

    public function avgTempDays($days)
    {
        foreach ($days as $index => $temps) {
            $this->temps[] = [
                'temp' => round(array_sum(explode(',', $temps)) / count(explode(',', $temps)), 1),
                'date' => $index
            ];
        }

        return $this->temps;
    }

    public function avgTempWeeks($weeks)
    {
        $temp = 0;
        foreach ($weeks as $tempWeek) {
            $temp += $tempWeek['temp'];
        }

        return round($temp / 7, 1);
    }

    public function getNumberMonth($day)
    {
        return explode('.', $day)[1];
    }

    public function avgTempMonth($months): array
    {
        foreach ($months as $index => $month) {
            $this->months[] = [
                'temp' => round((array_sum($month) / count($month)), 1),
                'date' => 'Month №' . $index
            ];
        }

        return $this->months;
    }

}