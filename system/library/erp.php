<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10/30/2019
 * Time: 4:53 PM
 */

class ERP {
    protected $registry;
    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function getOrderIdByInvoice($invoice) {
        $query = $this->db->query("SELECT order_id FROM " . DB_PREFIX . "erp_order WHERE invoice = '" . $this->db->escape($invoice)  . "'");
        if($query->row) {
            return $query->row['order_id'];
        }
    }

    public function addERPOrder($order_id, $invoice) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "erp_order SET order_id = '" . (int)$order_id . "', invoice = '" . $this->db->escape($invoice) . "'");
    }

    public function addProduct($data) {

    }

    public function addOrderToERP($order_id) {
        $json = array();
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($order_id);
        $json["address_1"] = $order_info["first_name"] . ' ' . $order_info["lastname"];
        $json["address_2"] = $order_info["telephone"];
        $json["address_3"] = $order_info["payment_address_1"];


        $json["items"] = array();
        $order_products = $this->model_checkout_order->getOrderProducts($order_id);
        foreach ($order_products as $order_product) {
            $query = $this->db->query("SELECT sku FROM " . DB_PREFIX . "product WHERE product_id = '" . (int) $order_product['product_id'] . "'");
            $code = $query->row['sku'];
            $json['items'][] = array(
                'code' => $code,
                'quantity' => $order_product['quantity'],
                'price' => $order_product['price'],
                'discount' => 0.0,
                'total' => $order_product['price']
            );
        }



       try {
           $data = [
               'data' => json_encode($json)
           ];

           $curl = curl_init();

           curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
           curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
           curl_setopt($curl, CURLOPT_URL, "http://192.168.11.218/ords/syp/drongo/inv_issue");
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
       } catch (Exception $ignore) {}

    }

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }
}