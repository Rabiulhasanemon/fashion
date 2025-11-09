<?php
namespace Sms;
class SSL {

    public function send($to, $text) {

        $url = "https://smsplus.sslwireless.com/api/v3/send-sms?api_token=e3f1a242-0e60-43d9-bf29-fc5b79c4c57f&sid=LOTUSCOMAPI&msisdn=88${to}&csms_id=" . time() . "&sms=" . urlencode($text);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


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