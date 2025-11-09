<?php
class ControllerPaymentShurjoPay extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->shurjoPay = new ShurjoPay(array(
            'username' => $this->config->get("shurjo_pay_merchant_username"),
            'password' => html_entity_decode($this->config->get("shurjo_pay_api_password")),
            'url' => $this->config->get("shurjo_pay_server_url")
        ));
    }


    public function index() {
		$data['text_loading'] = $this->language->get('text_loading');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['confirm'] = $this->url->link('payment/shurjo_pay/confirm', '', "SSL");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/shurjo_pay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/shurjo_pay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/shurjo_pay.tpl', $data);
		}
	}

	public function confirm() {

        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
		if ($order_id && $this->session->data['payment_method']['code'] == 'shurjo_pay') {
		    $this->load->model("checkout/order");
		    $this->load->model("checkout/payment");
            $order_info = $this->model_checkout_order->getOrder($order_id);
            $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

            $payment_id = $this->model_checkout_payment->addPayment(array(
                'order_id' => $order_id,
                'total' => $due,
                'status' => "Pending",
                'gateway_code' => "shurjo_pay",
                'gateway_title' => "Shurjo Pay",
                'transaction_id' => '',
                'tracking_no' => '',
                'payer_info' => '',
                'comment' => ''
            ));

            $payload = array(
                'currency' => 'BDT',
                'return_url' =>  $this->url->link("payment/shurjo_pay/approve", '', 'SSL'),
                'cancel_url' =>  $this->url->link("payment/shurjo_pay/decline", '', 'SSL'),
                'amount' => $due,
                // Order information
                'prefix' => $this->config->get("shurjo_pay_api_order_prefix"),
                'order_id' => $payment_id . "",
                'discsount_amount' => 0,
                'disc_percent' => 0,
                // Customer information
                'client_ip' => '127.0.0.1',
                'customer_name' =>  $order_info['firstname'] . ' ' . $order_info['lastname'],
                'customer_phone' => $order_info['telephone'] ,
                'email' => $order_info['telephone'],
                'customer_address' => 'Dhaka',
                'customer_city' => 'Dhaka',
                'customer_state' => 'Dhaka',
                'customer_postcode' => '1207',
                'customer_country' => 'Bangladesh',
                'value1' => 'value1',
                'value2' => 'value2',
                'value3' => 'value3',
                'value4' => 'value4'
            );

            $urlData = $this->shurjoPay->generate_shurjopay_form($payload);

            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_id,
                'status' => 'Pending',
                'total' => $due,
                'transaction_id' => $urlData->sp_order_id,
                'tracking_no' => "",
                'payer_info' => "",
                'comment' =>''
            ));

            $this->response->redirect($urlData->checkout_url);
		} else {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }
	}

	public function approve()  {
        $this->load->language('payment/shurjo_pay');

        if($_REQUEST['order_id']) {
            $order_id = trim($_REQUEST['order_id']);
            $response = json_decode($this->shurjoPay->decrypt_and_validate($order_id), true);
        } else {
            $response = null;
        }

        if(is_array($response)) {
            $response = array_shift($response);
        }

        if(isset($response['order_id'])) {
            $transaction_id = $response['order_id'];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }


        if($payment_info && $payment_info['status'] == 'Pending') {
            $order_status = $response['sp_code'];
            $PAN = $response['card_number'];
            $comment =  "Message: " . $response['sp_message']
                . "\nCode: " . $response['sp_code']
                . "\nAmount: " . $response['amount']
                . "\nbank_trx_id: " . $response['bank_trx_id']
                . "\nmethod: " . $response['method']
                . "\n Cart No: " . $response['card_number'];
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => ($response['sp_code'] == 1000 ? "Approved" : "Failed"),
                'total' => $payment_info['total'],
                'transaction_id' => $payment_info['transaction_id'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => $PAN,
                'comment' => $comment
            ));
        } else {
            $order_status = '';
            $PAN = '';
        }

        if(isset($response['sp_code']) && $response['sp_code'] == 1000) {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('shurjo_pay_order_status_id'), "Payer Info: $PAN, Gateway Status: $order_status", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            $this->session->data['error'] = $this->language->get('error_decline');
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }
    }

    public function decline()  {
        $this->load->language('payment/shurjo_pay');

        if($_REQUEST['order_id']) {
            $order_id = trim($_REQUEST['order_id']);
            $response = json_decode($this->shurjoPay->decrypt_and_validate($order_id), true);
        } else {
            $response = null;
        }

        if(is_array($response)) {
            $response = array_shift($response);
        }

        if(isset($response['order_id'])) {
            $transaction_id = $response['order_id'];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        if($payment_info) {
            $PAN = $response['card_number'];
            $comment =  "Message: " . $response['sp_message']
                . "\nCode: " . $response['sp_code']
                . "\nAmount: " . $response['amount']
                . "\nbank_trx_id: " . $response['bank_trx_id']
                . "\nmethod: " . $response['method']
                . "\n Cart No: " . $response['card_number'];

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

}