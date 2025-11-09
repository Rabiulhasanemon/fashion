<?php
class BkashV2 {

    public function token($data) {
        $post_data=array(
            'app_key'=>$data["app_key"],
            'app_secret'=>$data["app_secret"]
        );

        $url = curl_init($data["base_url"] . "/checkout/token/grant");
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

        $post_data = array(
            'mode' => '0011',
            'amount' =>  $data['amount'],
            'payerReference' => $data['order_id'],
            'callbackURL' => $data['callback'],
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $data['payment_id']
        );

        $url = curl_init($data["base_url"] . "/checkout/create");

        $post_data = json_encode($post_data);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $this->token($data)['id_token'],
            'x-app-key:' . $data["app_key"]
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($url);
        curl_close($url);
        (new Log("bkash.log"))->write("Create Payment" . $result);
        return json_decode($result, true);
    }

    public function executePayment($data) {

        $url = curl_init($data["base_url"] ."/checkout/execute");

        $post_token = array(
            'paymentID' => $data['paymentID']
        );
        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $this->token($data)['id_token'],
            'X-APP-Key:' . $data["app_key"]
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS,  json_encode($post_token));
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($url);
        curl_close($url);
        (new Log("bkash.log"))->write("Exe Payment" . $result);
        return json_decode($result, true);
    }

}
