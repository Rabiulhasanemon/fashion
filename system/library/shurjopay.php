<?php


class ShurjoPay {

    private $domainName ;
    private $apiUsername;
    private $apiPassword;
    private $log;
    public function __construct($config)
    {
        $this->domainName = $config['url'];
        $this->apiUsername = $config['username'];
        $this->apiPassword = $config['password'];

        $this->token_url = $this->domainName . "api/get_token";
        $this->payment_url = $this->domainName . "api/secret-pay";
        $this->verification_url = $this->domainName . "api/verification/";

        $this->log = new Log("shurjo.log");
    }


    public function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if (is_numeric($data))
            return $data;
    }

    public function generate_shurjopay_form($payload)
    {
        $token = json_decode($this->getToken(), true);
        $createpaybody = json_encode(
            array(
                // store information
                'token' => $token['token'],
                'store_id' => $token['store_id'],
                'prefix' => $payload['prefix'],
                'currency' => $payload['currency'],
                'return_url' => $payload['return_url'],
                'cancel_url' => $payload['cancel_url'],
                'amount' => $payload['amount'],
                // Order information
                'order_id' => $payload['order_id'],
                'discsount_amount' => $payload['discsount_amount'],
                'disc_percent' => $payload['disc_percent'],
                // Customer information
                'client_ip' => $payload['client_ip'],
                'customer_name' => $payload['customer_name'],
                'customer_phone' => $payload['customer_phone'],
                'customer_email' => $payload['email'],
                'customer_address' => $payload['customer_address'],
                'customer_city' => $payload['customer_city'],
                'customer_state' => $payload['customer_state'],
                'customer_postcode' => $payload['customer_postcode'],
                'customer_country' => $payload['customer_country'],
                'value1' => $payload['value1'],
                'value2' => $payload['value2'],
                'value3' => $payload['value3'],
                'value4' => $payload['value4']
            )
        );

        $header = array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $token['token']
        );

        // var_dump($createpaybody);exit;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->payment_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $createpaybody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if ($response === false) {
            echo json_encode(curl_error($ch));
        }

        $this->log->write($response);

        $urlData = json_decode($response);
        curl_close($ch);
        return $urlData;
    }


    public function getToken()
    {
        $postFields = array(
            'username' => $this->apiUsername,
            'password' => $this->apiPassword,
        );
        if (empty($this->token_url) || empty($postFields)) return null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->token_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if ($response === false) {
            echo json_encode(curl_error($ch));
        }
        curl_close($ch);

        $this->log->write($response);

        return $response;

    }

    public function decrypt_and_validate($order_id)
    {
        // echo $order_id;exit;

        $token = json_decode($this->getToken(), true);
        $header = array(
            'Content-Type:application/json',
            'Authorization: Bearer ' . $token['token']
        );
        $postFields = json_encode(
            array(
                'order_id' => $order_id
            )
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->verification_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        $response = curl_exec($ch);
        if ($response === false) {
            echo json_encode(curl_error($ch));
        }
        curl_close($ch);
        $this->log->write($response);
        return $response;
    }

}
