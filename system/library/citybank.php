<?php
class CityBank {
    private $handle;

    public function __construct($registry) {
        $this->url = $registry->get("url");
        $this->handle = fopen(DIR_LOGS . 'city_bank', 'a');
    }

    public function log($message) {
        fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
    }

    public function securePost($data) {
        $hostname = '127.0.0.1'; // Address of the server with servlet used to work with orders
        $port="743"; // Port
        $path = '/Exec';
        $content = '';
        $fp = fsockopen($hostname, $port, $errno, $errstr, 30);
        if (!$fp) die('<p>'.$errstr.' ('.$errno.')</p>');
        $headers = 'POST '.$path." HTTP/1.0\r\n";
        $headers .= 'Host: '.$hostname."\r\n";
        $headers .= "Content-type: text/xml\r\n";
        $headers .= 'Content-Length: '.strlen($data)."\r\n\r\n";
        fwrite($fp, $headers.$data);
        while ( !feof($fp) ){
            $inStr= fgets($fp, 1024);
            $content .= $inStr;
        }
        fclose($fp);
        $this->log($content);
        $content = substr($content, strpos($content, "<TKKPG>"));
        $xml = simplexml_load_string($content);
        return ($xml);
    }

    public function createOrder($merchant_id, $order_id, $amount, $is_emi_cart = false, $emi_tenure = 0) {
        $data='<?xml version="1.0" encoding="UTF-8"?>';
        $data.="<TKKPG>";
        $data.="<Request>";
        $data.="<Operation>CreateOrder</Operation>";
        $data.="<Language>EN</Language>";
        $data.="<Order>";
        $data.="<OrderType>Purchase</OrderType>";
        $data.="<Merchant>". $merchant_id ."</Merchant>";
        $data.="<Amount>". $amount * 100 ."</Amount>";
        $data.="<Currency>050</Currency>";
        if($is_emi_cart) {
            $data.="<Description>26-" . $emi_tenure . "-Order#". $order_id ."</Description>";
            $data.= "<AddParams><SPANBegin>3</SPANBegin><SPANLen>15</SPANLen></AddParams>";
        } else {
            $data.="<Description>Order#". $order_id ."</Description>";
        }
        $data.="<ApproveURL>".htmlentities($this->url->link("payment/city_bank/approve", '', 'SSL'))."</ApproveURL>";
        $data.="<CancelURL>".htmlentities($this->url->link("payment/city_bank/cancel", '', 'SSL'))."</CancelURL>";
        $data.="<DeclineURL>".htmlentities($this->url->link("payment/city_bank/decline", '', 'SSL'))."</DeclineURL>";
        $data.="</Order></Request></TKKPG>";
        return $this->securePost($data);
    }

    public function getOrderStatus($merchant_id, $transaction_id, $session_id) {
        $data='<?xml version="1.0" encoding="UTF-8"?>';
        $data.="<TKKPG>";
        $data.="<Request>";
        $data.="<Operation>GetOrderInformation</Operation>";
        $data.="<Language>EN</Language>";
        $data.="<Order>";
        $data.="<Merchant>" . $merchant_id . "</Merchant>";
        $data.="<OrderID>". $transaction_id ."</OrderID>";
        $data.="</Order>";
        $data.="<SessionID>". $session_id ."</SessionID>";
        $data.="<ShowParams>true</ShowParams>";
        $data.="<ShowOperations>false</ShowOperations>";
        $data.="<ClassicView>true</ClassicView>";
        $data.="</Request></TKKPG>";
        return $this->securePost($data);
    }

    public function __destruct() {
        fclose($this->handle);
    }
}