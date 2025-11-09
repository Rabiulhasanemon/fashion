<?php
namespace Sms;
class Ajura {

    private function execute($url) {
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

    public function send($to, $text) {
        $url = "https://smpp.revesms.com:7790/sendtext?apikey=" . AJURA_KEY . "&secretkey=" . AJURA_SECRET. "&callerID=" . AJURA_CALLER_ID . "&toUser=88${to}&messageContent=" . urlencode($text);
        return $this->execute($url);
    }

}