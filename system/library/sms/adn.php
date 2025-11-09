<?php
namespace Sms;
class ADN {

    public function send($to, $text) {
        $data = [
            'api_key' => "KEY-eflog078h6560yej8zgd9araq85rjjt8",
            'api_secret' => "PM@UotfJzo1kP0ju",
            'request_type' => "SINGLE_SMS",
            'message_type' => "TEXT",
            'mobile' => $to,
            'message_body' => $text
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, "https://portal.adnsms.com/api/v1/secure/send-sms");
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

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