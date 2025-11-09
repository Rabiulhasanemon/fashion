<?php
class SMS {
    protected $sms;

    public function __construct($registry, $driver = 'ssl') {
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->handle = fopen(DIR_LOGS . 'sms.log', 'a');

        $class = 'Sms\\' . $driver;

        if (class_exists($class)) {
            $this->sms = new $class();
        } else {
            exit('Error: Could not load sms driver ' . $driver . ' cache!');
        }
    }

    public function log($message) {
        fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }

    public function send($to, $text) {
        if(!$to || !$text) return false;
        $response = $this->sms->send($to, $text);
        if($response) {
            $this->log("Success $to '$text' '$response'");
        } else {
            $this->log("Error $to '$text'");
        }
        return $response;
    }

    public function sendPin($telephone) {
        if(true || !isset($this->session->data["pin_time"]) || time() - $this->session->data["pin_time"] > 20){
            $pin  = rand(1000, 9999);
            $this->send($telephone, "Dear Customer, your pin code for login is " . $pin);
            $this->session->data["pin"] = $pin;
            $this->session->data["pin_time"] = time();
            $this->session->data["pin_telephone"] = $telephone;
        } else {
            $pin = $this->session->data["pin_time"];
        }
        return  $pin;
    }
}