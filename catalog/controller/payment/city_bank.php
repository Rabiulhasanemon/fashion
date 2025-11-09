<?php
class ControllerPaymentCityBank extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->city_bank = new CityBank($registry);
    }


    public function index() {
		$data['text_loading'] = $this->language->get('text_loading');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['confirm'] = $this->url->link('payment/city_bank/confirm', '', "SSL");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/city_bank.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/city_bank.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/city_bank.tpl', $data);
		}
	}

	public function confirm() {

        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
		if ($order_id && $this->session->data['payment_method']['code'] == 'city_bank') {
		    $this->load->model("checkout/order");
		    $this->load->model("checkout/payment");
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

            $xml = $this->city_bank->createOrder($this->config->get("city_bank_merchant_id"), $order_id, $due, $order_info['emi'], $order_info['emi_tenure']);
            $OrderID = $xml->Response->Order->OrderID;
            $SessionID = $xml->Response->Order->SessionID;
            $URL = $xml->Response->Order->URL;

            $this->model_checkout_payment->addPayment(array(
                'order_id' => $order_id,
                'total' => $due,
                'status' => "Pending",
                'gateway_code' => "city_bank",
                'gateway_title' => "City Bank",
                'transaction_id' => $OrderID,
                'tracking_no' => $SessionID,
                'payer_info' => '',
                'comment' => ''
            ));
            $this->response->redirect($URL . "?ORDERID=" . $OrderID. "&SESSIONID=" . $SessionID );
		} else {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }
	}

	public function approve()  {
        $this->load->language('payment/city_bank');

        if(isset($_REQUEST['xmlmsg']) && $_REQUEST['xmlmsg']) {
            $xmlResponse = simplexml_load_string($_REQUEST['xmlmsg']);
            $json = json_encode($xmlResponse);
            $response_array = json_decode($json, TRUE);
        } else {
            $response_array = array();
        }

        if(isset($response_array['OrderID'])) {
            $transaction_id = $response_array['OrderID'];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        $order_status = '';
        $PAN = '';
        if($payment_info) {
            $xml = $this->city_bank->getOrderStatus($this->config->get("city_bank_merchant_id"), $payment_info['transaction_id'], $payment_info['tracking_no']);
            $order_status = $xml->Response->Order->row->Orderstatus;
            foreach($xml->Response->Order->row->OrderParams->row as $item) {
                if($item->PARAMNAME == "PAN")
                    $PAN = $item->VAL;
            }
            $comment = '';
            if(isset($response_array['ResponseCode']) && isset($response_array['ResponseDescription'])) {
                $comment = "Response Code: " . $response_array['ResponseCode'] . ", Response Description: " . $response_array['ResponseDescription'] . "\n";
            }
            $comment .= "Order Status: " . $order_status;
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => ($order_status == "APPROVED" ? "Approved" : "Failed"),
                'total' => $payment_info['total'],
                'transaction_id' => $payment_info['transaction_id'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => $PAN,
                'comment' => $comment
            ));
        }

        if($order_status == "APPROVED") {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('city_bank_order_status_id'), "Payer Info: $PAN, Gateway Status: $order_status", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            $this->session->data['error'] = $this->language->get('error_decline');
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }
    }

    public function decline()  {
        $this->load->language('payment/city_bank');

        if(isset($_REQUEST['xmlmsg']) && $_REQUEST['xmlmsg']) {
            $xmlResponse = simplexml_load_string($_REQUEST['xmlmsg']);
            $json = json_encode($xmlResponse);
            $response_array = json_decode($json, TRUE);
        } else {
            $response_array = array();
        }

        if(isset($response_array['OrderID'])) {
            $transaction_id = $response_array['OrderID'];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        if($payment_info) {
            $xml = $this->city_bank->getOrderStatus($this->config->get("city_bank_merchant_id"), $payment_info['transaction_id'], $payment_info['tracking_no']);
            $Orderstatus = $xml->Response->Order->row->Orderstatus;
            $PAN = '';
            $comment = '';
            if(isset($response_array['ResponseCode']) && isset($response_array['ResponseDescription'])) {
                $comment = "Response Code: " . $response_array['ResponseCode'] . ", Response Description: " . $response_array['ResponseDescription'] . "\n";
            }
            $comment .= "Order Status: " . $Orderstatus;
            foreach($xml->Response->Order->row->OrderParams->row as $item) {
                if($item->PARAMNAME == "PAN")
                    $PAN = $item->VAL;
            }
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Declined",
                'total' => $payment_info['total'],
                'transaction_id' => $payment_info['transaction_id'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => $PAN,
                'comment' => $comment
            ));
            $this->session->data['error'] = $this->language->get('error_decline');
        }
        $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
    }

	public function cancel() {
        $this->load->language('payment/city_bank');

        if(isset($_REQUEST['xmlmsg']) && $_REQUEST['xmlmsg']) {
            $xmlResponse = simplexml_load_string($_REQUEST['xmlmsg']);
            $json = json_encode($xmlResponse);
            $response_array = json_decode($json, TRUE);
        } else {
            $response_array = array();
        }

        if(isset($response_array['OrderID'])) {
            $transaction_id = $response_array['OrderID'];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        if($payment_info) {
            $this->load->model("checkout/payment");
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Cancelled",
                'total' => $payment_info['total'],
                'transaction_id' => $payment_info['transaction_id'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => '',
                'comment' => ''
            ));
            $this->session->data['error'] = $this->language->get('error_cancel');
        }
        $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
    }
}