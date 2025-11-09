<?php
namespace Sms;
class Robi {

    public function send($to, $text) {
        $data = [
            'Username' => "etforce",
            'Password' => "@Babycare123",
            'From' => "adreach",
            'To' => "88" . $to,
            'Message' => urlencode($text)
        ];

        $curl = curl_init();

        $url = "https://api.mobireach.com.bd/SendTextMessage?Username=etforce&Password=@Babycare123&From=PQS&To=88${to}&Message=" . urlencode($text);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, "?Username=etforce&Password=@Babycare123&From=adreach&To=88${to}&Message=" . urlencode($text));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return $response;
        }

    }
}