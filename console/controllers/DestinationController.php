<?php
namespace console\controllers;

use yii\console\Controller;
use common\models\listCountry\ListCountry;
use common\models\listResorts\ListResorts;


class DestinationController extends Controller
{

    public function actionIndex()
    {
        $response = [
            'country' => [],
            'resorts' => [],
            'error' => ''
        ];
        $urlsSer = [
            'country' => 'http://api-gateway.travelata.ru/directory/countries',
            'resorts' => 'http://api-gateway.travelata.ru/directory/resorts'
        ];
        // Скачивание сразу нескольких страниц
        // инициализируем "контейнер" мультизапросов (мультикурл)
        $multi_init = curl_multi_init();

        // массив отдельных заданий
        $jobs = [];

        // проходим по каждому URL-адресу
        foreach ($urlsSer as $urlSer) {
            // подключаем отдельный поток (URL-адрес)
            $init = curl_init($urlSer);

            // если произойдёт перенаправление, то перейти по нему
            curl_setopt($init, CURLOPT_FOLLOWLOCATION, 1);

            // curl_exec вернёт результат
            curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);

            // таймаут соединения 10 секунд
            curl_setopt($init, CURLOPT_CONNECTTIMEOUT, 10);

            // таймаут ожидания также 10 секунд
            curl_setopt($init, CURLOPT_TIMEOUT, 10);

            // HTTP-заголовок ответа не будет возвращён
            curl_setopt($init, CURLOPT_HEADER, 0);

            // добавляем дескриптор потока в массив заданий
            $jobs[$urlSer] = $init;

            // добавляем дескриптор потока в мультикурл
            curl_multi_add_handle($multi_init, $init);
        }

        // кол-во активных потоков
        $thread = null;

        // запускаем исполнение потоков
        do {
            $thread_exec = curl_multi_exec($multi_init, $thread);
        }
        while ($thread_exec == CURLM_CALL_MULTI_PERFORM);

        // исполняем, пока есть активные потоки
        while ($thread && ($thread_exec == CURLM_OK)) {

            // если поток готов к взаимодествию
            if (curl_multi_select($multi_init) != -1) {

                // ждем, пока что-нибудь изменится
                do {
                    $thread_exec = curl_multi_exec($multi_init, $thread);

                    // читаем информацию о потоке
                    $info = curl_multi_info_read($multi_init);

                    // если поток завершился
                    if ($info['msg'] == CURLMSG_DONE) {

                        $init = $info['handle'];

                        // ищем URL страницы по дескриптору потока в массиве заданий
                        $page = array_search($init, $jobs);

                        // скачиваем содержимое страницы
                        $jobs[$page] = curl_multi_getcontent($init);

                        // распарисиваем и сохряняем ее
                        $key = array_search($page, $urlsSer);
                        $response[$key] = json_decode($jobs[$page], true);

                        // удаляем поток из мультикурла
                        curl_multi_remove_handle($multi_init, $init);

                        // закрываем отдельный поток
                        curl_close($init);

                    }
                }
                while ($thread_exec == CURLM_CALL_MULTI_PERFORM);
            }
        }
        // закрываем мультикурл
        curl_multi_close($multi_init);

        if ($response['country']['success'] && $response['country']['status'] == 200) {
            foreach ($response['country']['data'] as $index => $country) {
                $modelListCountry = new ListCountry();
                $value = [
                    'country_id' => $country['id'],
                    'name' => $country['name'],
                    'popular' => $country['popular'],
                ];
                $modelListCountry->attributes = $value;
                $modelListCountry->save();
            }
        } else {
            $response['error'] = $response['country']['message'];
        }

        if ($response['resorts']['success'] && $response['resorts']['status'] == 200) {
            foreach ($response['resorts']['data'] as $index => $resorts) {
                $modelListResorts = new ListResorts();
                $value = [
                    'resorts_id' => $resorts['id'],
                    'name' => $resorts['name'],
                    'is_popular' => $resorts['isPopular'],
                    'resort_country_id' => $resorts['countryId'],
                    'at_filtering' => $resorts['atFiltering'],
                ];
                $modelListResorts->attributes = $value;
                $modelListResorts->save();
            }
        } else {
            $response['error'] = $response['resorts']['message'];
        }
    }
}