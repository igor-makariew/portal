<?php

namespace console\models;

use phpseclib3\Math\PrimeField\Integer;
use yii\base\Model;
use mihaildev\ckeditor\CKEditor;
use inquid\pdf\FPDF;
use common\models\User;
use Yii;
use PhpAmqpLib\Message\AMQPMessage;

class Commands extends Model
{
    public $connection;
    public $channel;
    public $callbackQueue;
    public $corrId;
    public $response;


    public function createPdfFile() {
        echo 'createPdfFile  - ' . date('d-m-Y') . PHP_EOL;
        $this->sendMail();

    }

    public function createTxtFile() {
        echo 'createTxtFile - igor makariew' . PHP_EOL;
    }

    /**
     * оздание pdf файла
     *
     * @param string $name
     */
    public function createFPDF(string $name): void
    {
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $logo = Yii::getAlias('@console/files/logo/') . 'krasivye-kupalniki-64-foto-6.jpg';
        // Логотип
        $pdf->Image( $logo, '10', '10', 50 );

        // Название отчета
        $pdf->SetFont( 'Arial', 'B', 24 );
        $pdf->Ln( 15 );
        $reportName = 'Makarev Igor';
        $pdf->Cell( 0, 15, $reportName, 0, 0, 'C' );

        // header text
        $pdf->SetTextColor( 70, 70, 70 );
        $pdf->SetFont( 'Arial', '', 18 );
        $pdf->Ln( 25 );
        $reportNameText = 'Report';
        $pdf->Cell( 0, 15, $reportNameText, 0, 0, 'C' );

        // text
        $pdf->SetTextColor( 75, 75, 75 );
        $pdf->SetFont( 'Arial', '', 14 );
        $pdf->Ln( 15 );
        $text = " Dear " . $name . ", ";
        $pdf->Write(6, $text);
        $pdf->Ln(10);
        $text = " The 3D Technologies Seminar held at the Moscow Crocus Congress Centre on June 13 will feature lectures by several key programmers and designers in the field of 3D modeling, with topics including trilinear filtering, anti-aliasing and mipmapping.";
        $pdf->Write( 6, $text );
        $pdf->Ln(10);
        $text = " A shareholder letter can be a good first step towards getting a broad overview of the firm you are considering investing in. However, it is important to understand that the letter to the shareholder, like many other parts of the annual report, is usually written in such a way as to present the company's activities in the best possible light. Investors will want to take the information contained in the letter to shareholders with a grain of salt and will definitely take a closer look at the company's financial results and conduct independent research on the company and its industry before drawing conclusions. The letter may address certain elements of a company's financial statements or records, such as a 10-K or 10-Q, so it may be a good idea to look in those documents for information that supports the claims made in the letter to the shareholder.";
        $pdf->Write( 6, $text );
        $pdf->Ln(10);
        $text = " Even with potential distortions or positive developments in a company's letter to shareholders, the letter to shareholders is still a valuable resource for gaining insight into how executives - most notably the CEO - evaluate the company's success. Often, investors delve into a shareholder letter to predict or explain why a firm is doing better or worse than expected.";
        $pdf->Write( 6, $text );
        $pdf->Ln(10);
        $text = " Igor Petrov , Managing Director Ltd. The company \"Center\" Tel: +7 999 999 99 99";
        $pdf->Write( 6, $text );
        $pdf->Ln(20);
        $text = " Current date - " . date('d-m-Y');
        $pdf->Write( 6, $text );
        $pdf->Ln(20);
        $text = " Name  " . $name . "               signature ";
        $pdf->Write( 6, $text );

        $path = Yii::getAlias('@console/files/');
        $pdf->Output( $path ."report.pdf", "F" );
    }

    /**
     * отправка сообщений
     */
    public function sendMail(): void
    {
        $users = User::find()
            ->select('username, email')
            ->where(['mailing_list' => 1])
            ->all();
        $path = Yii::getAlias('@console/files/') . 'report.pdf';

        foreach ($users as $index => $user) {
            $this->createFPDF($user['username']);

            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'rabbitmq-html', 'text' => 'rabbitmq-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($user['email'])
                ->setSubject('Dear ' . $user['username'] )
                ->attach($path)
                ->send();
        }

    }

    public function onResponse($res)
    {
        if ($res->get('correlation_id') == $this->corrId) {
            $this->response = $res->body();
        }
    }

    /**
     * ответ от сервера
     *
     * @param $number
     * @return int
     */
    public function call($number)
    {
        $this->response = null;
        $this->corrId = uniqid();;

        $msg = new AMQPMessage(
            (string) $number,
            array(
                'correlation_id' => $this->corrId,
                'reply_to' => $this->callbackQueue
            )
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');

        while(!$this->response) {
            $this->channel->wait();
        }

        return intval($this->response);
    }

    /**
     * fibonachi
     *
     * @param $int
     * @return int
     */
    public function fibonachi($int)
    {
        if ($int == 0) {
            return 0;
        }
        if ($int == 1) {
            return 1;
        }

        return round(pow((sqrt(5)+1)/2, $int) / sqrt(5));
    }


}