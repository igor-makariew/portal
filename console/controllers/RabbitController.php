<?php

namespace console\controllers;

use PhpAmqpLib\Channel\AMQPChannel;
use yii\console\Controller;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Yii;

$path = Yii::getAlias("@vendor/pdf/fpdf.php");
require_once($path);
$helveticab = Yii::getAlias("@vendor/pdf/helveticab.php");
require_once($helveticab);

//$image = Yii::getAlias("@vendor/pdf/logo/1634453891_64-siski-name-p-v-chulkakh-bez-trusov-87.jpg");
//require_once($image);

class RabbitController extends Controller
{
    protected $redis;

    public function actionStart()
    {
        /**
         * params connection
         */
        $connection_params = array(
            'host' => 'localhost',
            'port' => 5672,
            'vhost' => '/',
            'login' => 'igor',
            'password' => 'admin'
        );

        /**
         * connetion
         */
        $connection = new AMQPStreamConnection(
            $connection_params['host'],
            $connection_params['port'],
            $connection_params['login'],
            $connection_params['password'],
            $connection_params['vhost'],
        );

//        $argv = explode(',', $argv);
//        $argv = ['info', 'error', 'warning'];
        $argv = ['error'];


        /**
         * create channel
         */
        $channel = $connection->channel();
        /**
         * create queue
         */
        //$channel->queue_declare('test-yii2', false, false, false, false);
        /**
         * create queue
         * Во-первых, нам нужно убедиться, что очередь переживет перезапуск узла RabbitMQ.
         * Для этого нам нужно объявить его прочным .
         * Для этого мы передаем третий параметр в queue_declare как true :
         */
//        $channel->queue_declare('yii-task-test', false, true, false, false);

        /**
         * create exchange
         */
//        $channel->exchange_declare('logs', 'fanout', false, false, false);
//        list($queue_name,  ,) = $channel->queue_declare("", false, false, true, false);

        $channel->exchange_declare('direct_logs', 'direct', false, false, false);
        list($queue_name,  , ) = $channel->queue_declare("", false, false, true, false);
        $severities = array_slice($argv, 0);

        echo $queue_name . PHP_EOL;

        /**
         * Мы уже создали разветвленный обмен и очередь.
         * Теперь нам нужно указать обмену отправлять сообщения в нашу очередь.
         * Эта связь между обменом и очередью называется привязкой .
         */
//        $channel->queue_bind($queue_name, 'logs');

        foreach ($severities as $severity) {
            $channel->queue_bind($queue_name, 'direct_logs', $severity);
        }

        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $this->redis = Yii::$app->redis;

        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            echo '[x]', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";

            $redis = Yii::$app->redis;
            $userId = 1;

            $field = $msg->body;
            $value = 'ok';
            if (!$this->hexists($userId, $field)) {
                $this->hset($userId, $field, $value);
            } else {
                $this->hdel($userId, $field);
            }

            $hGet = $this->hget($userId, $field);
            $hGetAll = $this->redis->hgetall($userId);
            var_dump($hGetAll);
        };

        $callbacks = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            echo  '[x]' , $msg->delivery_info[ 'routing_key' ], ':' , $msg->body, "\n" ;

            $pdf = new \FPDF('P', 'mm', 'A4');
            $pdf->AddPage();
            $pdf->SetTextColor( 155, 155,155);
            // Logotip
            $pdf->Image(
                Yii::getAlias('@vendor') ."/pdf/logo/krasivye-kupalniki-64-foto-6.jpg",
                10,
                10,
                50
            );
            // Название отчета
            $pdf->SetFont('Arial', 'B', 24);
//            $pdf->Ln(14);
            $pdf->Cell(0, 55, 'Otchet 4', 0, 1, 'C');

            // Создаем колонтитул страницы и вводный текст
            $pdf->Ln( 6 );
            $pdf->SetTextColor( 55, 55,55);
            $pdf->SetFont( 'Arial', 'B', 12 );
            $pdf->Write( 6,
                "Despite the economic downturn, WidgetCo had a strong year. Sales of the HyperWidget in particular exceeded expectations. The fourth quarter was generally the best performing; this was most likely due to our increased ad spend in Q3."
            );
            $pdf->Ln( 6 );
            $pdf->Write( 6, "2010 is expected to see increased sales growth as we expand into other countries." );
            $pdf->Ln(6);
            $pdf->Write( 19, "2009 Was A Good Year" );

            /***
                    Создаем таблицу
             ***/
            $pdf->Ln(15);
            $pdf->SetDrawColor( 177, 77, 199 );
            // Создаем строку заголовков таблицы
            $pdf->SetFont( 'Arial', 'B', 15 );
            // Ячейка "PRODUCT"
            $pdf->SetTextColor( 50, 50, 50 );
            $pdf->SetFillColor( 177, 77, 199  );
            $pdf->Cell( 40, 12, " PRODUCT", 1, 0, 'L', true );

//            $pdf->SetTextColor( 50, 50, 50 );
//            $pdf->SetFillColor( 177, 77, 199  );
//            $pdf->Cell( 40, 12, " PRODUCT2 ", 1, 0, 'L', true );

            echo ' CREATE FIELD', "\n";
            // Остальные ячейки заголовков
            $pdf->SetTextColor( 50, 50, 50 );
            $pdf->SetFillColor( 177, 77, 199 );
            $columnLabels = [
                ' text7 ', ' text2 ', ' text3 ', ' text4 ', ' text5 '
            ];
            for ( $i=0; $i<count($columnLabels); $i++ ) {
                $pdf->Cell( 30, 12, $columnLabels[$i], 1, 0, 'L', true );

            }

            $pdf->Ln( 12 );
            // Создаем строки с данными
            $fill = false;
            $row = 0;
            $data = [1000, 2000, 3000, 4000, 5000];
            $rowLabels = ["FERRARY", "BUGATTI", "LAMBO", "MASSERATI", "AUDI"];
            foreach ( $data as $dataRow) {
                // Создаем левую ячейку с заголовком строки
                $pdf->SetFont( 'Arial', 'B', 15 );
                $pdf->SetTextColor( 120, 120, 120 );
                $pdf->SetFillColor( 50, 50, 50 );
                $pdf->Cell( 40, 12, " " . $rowLabels[$row], 1, 0, 'L', $fill );

                // Создаем ячейки с данными
                $pdf->SetTextColor( 120, 120, 120);
                $pdf->SetFillColor( 50, 50, 50 );
                $pdf->SetFont( 'Arial', 'B', 15 );

                for ( $i=0; $i<count($data); $i++ ) {
                    $pdf->Cell(30, 12, ('$' . number_format($data[$i])), 1, 0, 'L', $fill);
                }

                $row++;
                $fill = !$fill;
                $pdf->Ln(12);
            }


            /***
                    Создаем график
             ***/

            /**
            // Вычисляем масштаб по оси X
            $rowLabelss = [120, 40, 80, 200, 360, 600, 20];
            $chartWidth = 90;
            $xScale = count($rowLabelss) / ( $chartWidth - 40 );
            echo $xScale . PHP_EOL;

            // Вычисляем масштаб по оси Y
            $maxTotal = 0;
            $data = [
                [10, 20, 30, 40, 50],
                [10, 20, 30, 40, 50],
                [10, 20, 30, 40, 50],
                [10, 20, 30, 40, 50],
                [10, 20, 30, 40, 50],
            ];
            foreach ( $data as $dataRow ) {
                $totalSales = 0;
                foreach ( $dataRow as $dataCell ) $totalSales += $dataCell;
                $maxTotal = ( $totalSales > $maxTotal ) ? $totalSales : $maxTotal;
            }

            $chartHeight = 30;
            $yScale = $maxTotal / $chartHeight;
            echo $yScale  . PHP_EOL;

            // Вычисляем ширину столбцов
            $barWidth = ( 1 / $xScale ) / 1.5;

            // Добавляем оси:
            $pdf->SetFont( 'Arial', 'B', 10 );
            $chartXPos = 0;
            $chartYPos = 0;

            // Ось X
            $pdf->Line( $chartXPos + 30, $chartYPos, $chartXPos + $chartWidth, $chartYPos );
            for ( $i=0; $i < count( $rowLabels ); $i++ ) {
                $pdf->SetXY($chartXPos + 40 + $i / $xScale, $chartYPos);
                $pdf->Cell($barWidth, 10, $rowLabels[$i], 0, 0, 'C');
            }

            // Ось Y
            $chartYStep = 30;
            $pdf->Line( $chartXPos + 30, $chartYPos, $chartXPos + 30, $chartYPos - $chartHeight - 8 );
            for ( $i=0; $i <= $maxTotal; $i += $chartYStep ) {
                $pdf->SetXY( $chartXPos + 7, $chartYPos - 5 - $i / $yScale );
                $pdf->Cell( 20, 10, '$' . number_format( $i ), 0, 0, 'R' );
                $pdf->Line( $chartXPos + 28, $chartYPos - $i / $yScale, $chartXPos + 30, $chartYPos - $i / $yScale );
            }

            // Добавляем метки осей
            $pdf->SetFont( 'Arial', 'B', 12 );
            $chartXLabel = ($chartWidth / 2) + 20 + $chartYPos + 8;
            $pdf->SetXY( $chartWidth / 2 + 20, $chartYPos + 8 );
            $pdf->Cell( 30, 10, $chartXLabel, 0, 0, 'C' );
            $pdf->SetXY( $chartXPos + 7, $chartYPos - $chartHeight - 12 );
            $chartYLabel = ($chartXPos + 7) + $chartYPos - $chartHeight - 12;
            $pdf->Cell( 20, 10, $chartYLabel, 0, 0, 'R' );

            // Создаем столбцы
            $xPos = $chartXPos + 40;
            $bar = 0;

            $chartColours = [10, 20, 30, 40, 50];
            foreach ( $data as $dataRow ) {
                  // Вычисляем суммарное значение по строке данных для продукта
                  $totalSales = 0;
                  foreach ( $dataRow as $dataCell ) $totalSales += $dataCell;
                  // Создаем столбец
                  $colourIndex = $bar % count( $chartColours );
                  $pdf->SetFillColor( $chartColours[$colourIndex][0], $chartColours[$colourIndex][1], $chartColours[$colourIndex][2] );
                  $pdf->Rect( $xPos, $chartYPos - ( $totalSales / $yScale ), $barWidth, $totalSales / $yScale, 'DF' );
                  $xPos += ( 1 / $xScale );
                  $bar++;
            }
            */

            $path =  Yii::getAlias('@backend') . "/web/images/uploadImages/";
            $filename = "test.pdf";
            $pdf->Output("F", $path.$filename, true);

//            if ($msg->body == 'test') {
//                echo " [x] Received ", $msg->body, "\n";
//            } else {
//                echo " SISKI " . PHP_EOL;
//            }

            echo " [x] Done\n";
            echo  '[x]' , $msg->delivery_info[ 'routing_key' ], ':' , $msg->body, "\n" ;
//            не отрабатывает при exchange
//            $msg->ack();
        };

        /**
         * Чтобы обойти это, мы можем использовать метод basic_qos с настройкой prefetch_count = 1 .
         * Это говорит RabbitMQ не отправлять более одного сообщения рабочему элементу за раз.
         * Или, другими словами, не отправлять новое сообщение рабочему процессу, пока он не обработает и не подтвердит предыдущее.
         * Вместо этого он отправит его следующему работнику, который еще не занят.
         */
//        $channel->basic_qos(null, 1, null);
//        $channel->basic_consume('yii-task-test', '', false, false, false, false, $callback);
//        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);
        $channel->basic_consume($queue_name, '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    public function actionLearn()
    {
        $connectionParams = [
            'host' => 'localhost',
            'port' => 5672,
            'vhost' => '/',
            'login' => 'igor',
            'passsword' => 'admin'
        ];

        $connection = new \AMQPConnection($connectionParams);
        $connection->connect();

        // Используя коннект можно получить объект для канала
        $channel = new AMQPChannel($connection);

        // На основе полученного канала создаем обменник
        $exchange = new \AMQPExchange($channel);
        $exchange->setName('ex_lacetti');
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declare();

        // и, собственно, саму очередь
        $queue = new \AMQPQueue($channel);
        $queue->setName('lacetti');
        $queue->setFlags(AMQP_IFUNUSED | AMQP_AUTODELETE);
        $queue->declare();

        // Когда обменник и очередь готовы, их можно связать по ключу
        $queue->bind($exchange->getName(), 'foo_key');

    }

    /**
     * @param $key
     * @param $field
     * @param $value
     */
    public function hset($key, $field,$value)
    {
        $this->redis->hset($key, $field,$value);
    }

    /**
     * @param $key
     * @param $field
     * @return bool
     */
    public function hexists($key, $field)
    {
        if ($this->redis->hexists($key, $field)) {
            return true;
        }

        return false;
    }

    public function hget($key, $field)
    {
        return $this->redis->hget($key, $field);
    }

    public function hdel($key, $field)
    {
        return $this->redis->hdel($key, $field);
    }
}