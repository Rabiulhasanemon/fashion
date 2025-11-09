<?php
namespace Sms;
class Shiram {

    public function send($to, $text) {
        $url = "https://smsapi.shiramsystem.com/user_api/";

        $post = array(
            "email" => "pcgarden@gmail.com",
            "password" => "123456",
            "method" => "send_sms",
            "mobile" => array("88" . $to),
            "mask" => "PC GARDEN",
            "message"=> $text,
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

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