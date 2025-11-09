<?php
class Bkash {

    public function token($data) {
        $post_data=array(
            'app_key'=>$data["app_key"],
            'app_secret'=>$data["app_secret"]
        );

        $url = curl_init($data["token_url"]);
        $post_data = json_encode($post_data);
        $header=array(
            'Content-Type:application/json',
            'password:'.$data["password"],
            'username:'.$data["username"]
        );

        curl_setopt($url,CURLOPT_HTTPHEADER, $header);
        curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url,CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($url);
        curl_close($url);
        return json_decode($result, true);
    }

    public function createPayment($data) {
        $post_data = array('amount' => $data['amount'], 'currency' => 'BDT', 'merchantInvoiceNumber' => $data["payment_id"], 'intent' => 'sale');
        $url = curl_init($data["create_url"]);

        $post_data = json_encode($post_data);

        $header = array(
            'Content-Type:application/json',
            'authorization:' . $data["token"],
            'x-app-key:' . $data["app_key"]
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($url);
        curl_close($url);
        return json_decode($result, true);
    }

    public function executePayment($data) {

        $url = curl_init($data["execute_url"] . $data["paymentID"]);

        $header = array(
            'Content-Type:application/json',
            'authorization:' . $data["token"],
            'x-app-key:' . $data["app_key"]
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($url);
        curl_close($url);
        return json_decode($result, true);
    }

    public function queryPayment($data) {

        $url = curl_init($data["query_url"] . $data["paymentID"]);

        $header = array(
            'Content-Type:application/json',
            'authorization:' . $data["token"],
            'x-app-key:' . $data["app_key"]
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($url);
        curl_close($url);
        return json_decode($result, true);
    }

}
