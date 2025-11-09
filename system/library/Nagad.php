<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 9/13/2020
 * Time: 4:05 PM
 */

class Nagad {

    public function __construct($config) {
        $this->config = $config;
        $this->handle = fopen(DIR_LOGS . 'nagad.log', 'a');
    }

    public function log($message) {
        fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }

    function generateRandomString($length = 40)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function EncryptDataWithPublicKey($data)
    {
        $public_key = "-----BEGIN PUBLIC KEY-----\n" . $this->config['public_key'] . "\n-----END PUBLIC KEY-----";
        $key_resource = openssl_get_publickey($public_key);
        openssl_public_encrypt($data, $crypttext, $key_resource);
        return base64_encode($crypttext);
    }

    function SignatureGenerate($data)
    {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . $this->config['private_key'] . "\n-----END RSA PRIVATE KEY-----";
        openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    function HttpPostMethod($PostURL, $PostData)
    {
        $url = curl_init($PostURL);
        $posttoken = json_encode($PostData);
        $header = array(
            'Content-Type:application/json',
            'X-KM-Api-Version:v-0.2.0',
            'X-KM-IP-V4:' . $this->get_client_ip(),
            'X-KM-Client-Type:PC_WEB'
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, 0);

        $resultdata = curl_exec($url);
        $this->log($PostURL);
        $this->log($posttoken);
        $this->log($resultdata);
        $ResultArray = json_decode($resultdata, true);
        curl_close($url);
        return $ResultArray;

    }

    function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function DecryptDataWithPrivateKey($crypttext) {
        $private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .  $this->config['private_key']  . "\n-----END RSA PRIVATE KEY-----";
        openssl_private_decrypt(base64_decode($crypttext), $plain_text, $private_key);
        return $plain_text;
    }

    function process($data){

        $invoice_no = $data['payment_id'];
        $amount = $data['amount'];

        $MerchantID = $this->config['merchant_id'];
        $DateTime = Date('YmdHis');
        $random = $this->generateRandomString();

        $PostURL = $this->config['base_url'] . "/api/dfs/check-out/initialize/" . $MerchantID . "/" . $invoice_no;

        $merchantCallbackURL = $data['callback_url'];

        $SensitiveData = array(
            'merchantId' => $MerchantID,
            'datetime' => $DateTime,
            'orderId' => $invoice_no,
            'challenge' => $random
        );

        $PostData = array(
            'dateTime' => $DateTime,
            'sensitiveData' => $this->EncryptDataWithPublicKey(json_encode($SensitiveData)),
            'signature' => $this->SignatureGenerate(json_encode($SensitiveData))
        );

        $Result_Data = $this->HttpPostMethod($PostURL, $PostData);

        if (!empty($Result_Data['sensitiveData']) && !empty($Result_Data['signature'])) {
            $PlainResponse = json_decode($this->DecryptDataWithPrivateKey($Result_Data['sensitiveData']), true);
            if (isset($PlainResponse['paymentReferenceId']) && isset($PlainResponse['challenge'])) {

                $paymentReferenceId = $PlainResponse['paymentReferenceId'];
                $randomserver = $PlainResponse['challenge'];

                $SensitiveDataOrder = array(
                    'merchantId' => $MerchantID,
                    'orderId' => $invoice_no,
                    'currencyCode' => '050',
                    'amount' => $amount,
                    'challenge' => $randomserver
                );

                $PostDataOrder = array(
                    'sensitiveData' => $this->EncryptDataWithPublicKey(json_encode($SensitiveDataOrder)),
                    'signature' => $this->SignatureGenerate(json_encode($SensitiveDataOrder)),
                    'merchantCallbackURL' => $merchantCallbackURL
                );


                $OrderSubmitUrl = $this->config['base_url'] . "/api/dfs/check-out/complete/" . $paymentReferenceId;
                $Result_Data_Order = $this->HttpPostMethod($OrderSubmitUrl, $PostDataOrder);
                return $Result_Data_Order;
            } else {
                echo json_encode($PlainResponse);
            }
        }
        return array('status' => "error");
    }

    function getPaymentData($paymentReferenceId) {
        $url = $this->config['base_url'] . "/api/dfs/verify/payment/" . $paymentReferenceId;
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $file_contents = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        return json_decode($file_contents, true);
    }

    public function __destruct() {
        fclose($this->handle);
    }
}