<?php
class ControllerPaymentBkashV2 extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
    }

    private function getConfig() {
        $data = array(
            'username' => $this->config->get("bkash_v2_user"),
            'password' => html_entity_decode($this->config->get("bkash_v2_pass")),
            'app_key' => html_entity_decode($this->config->get("bkash_v2_app_key")),
            'app_secret' => html_entity_decode($this->config->get("bkash_v2_app_secret")),
            'base_url' => $this->config->get("bkash_v2_base_url"),
        );
        return $data;
    }


    public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['confirm'] = $this->url->link('payment/bkash_v2/confirm', '', "SSL");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bkash_v2.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/bkash_v2.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/bkash_v2.tpl', $data);
		}
	}

	public function confirm() {
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
        if(!$order_id || $this->session->data['payment_method']['code'] != 'bkash_v2') {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $this->load->model("checkout/order");
        $this->load->model("checkout/payment");
        $order_info = $this->model_checkout_order->getOrder($order_id);

        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

        $payment_id = $this->model_checkout_payment->addPayment(array(
            'order_id' => $order_id,
            'total' => $due,
            'status' => "Pending",
            'gateway_code' => "bkash_v2",
            'gateway_title' => "bKash V2",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => ''
        ));

        try {
            $bkash = new BkashV2();
            $data = $this->getConfig();
            $data['order_id'] = $order_id;
            $data['payment_id'] = $payment_id;
            $data["amount"] = round($due);
            $data["callback"] = $this->url->link("payment/bkash_v2/callback", '', 'SSL');
            $response = $bkash->createPayment($data);

            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_id,
                'total' => $due,
                'status' => "Pending",
                'transaction_id' => $response['paymentID'],
                'tracking_no' => "",
                'payer_info' => '',
                'comment' => ''
            ));

            $this->response->redirect($response['bkashURL']);

        } catch(Exception $e) {
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_id,
                'status' => 'Failed',
                'total' => $due,
                'transaction_id' => "",
                'tracking_no' => "",
                'payer_info' => "",
                'comment' => $e->getMessage()
            ));
            $this->session->data['error'] = $this->language->get('error_gateway');
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

	}

	public function callback()  {
        $this->load->language('payment/bkash_v2');

        $transaction_id = isset($this->request->get['paymentID']) ? $this->request->get['paymentID'] : null ;
        $status = isset($this->request->get['status']) ? $this->request->get['status'] : '' ;

        if($transaction_id) {
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        if(!$payment_info || $payment_info['status'] != "Pending") {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }


        $bkash = new BkashV2();
        $data = $this->getConfig();
        $data['paymentID'] = $transaction_id;

        if($status == 'success') {
            $response = $bkash->executePayment($data);
        } else {
            $response = array();
        }

        $status_code = isset($response['statusCode']) ? $response['statusCode'] : "";

        $comment = "Result: " . (isset($response['statusMessage']) ? $response['statusMessage'] : $status) . ", Auth Code: " . $status_code . ' trxID: ' . (isset($response['trxID']) ? $response['trxID'] : "");

        $PAN = isset($response['customerMsisdn']) ? $response['customerMsisdn'] : "";

        $this->model_checkout_payment->editPayment(array(
            'payment_id' => $payment_info['payment_id'],
            'status' => ($status_code === "0000" ? "Approved" : "Failed"),
            'total' => $payment_info['total'],
            'transaction_id' =>(isset($response['paymentID']) ? $response['paymentID'] : ""),
            'tracking_no' => isset($response['trxID']) ? $response['trxID'] : "",
            'payer_info' => $PAN,
            'comment' => $comment
        ));

        if($status_code === "0000") {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('bkash_v2_order_status_id'), "Payer Info: $PAN, Gateway Status: 0000", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            if(isset($response['statusMessage'])) {
                $error = $response['statusMessage'];
            } elseif ($status == 'cancel') {
                $error = $this->language->get('error_cancel');
            } elseif ($status == 'failure') {
                $error = $this->language->get('error_failure');
            } else {
                $error = $this->language->get('error_decline');
            }

            $this->session->data['error'] = $error;
            $this->response->redirect($this->url->link("checkout/payment", 'order_id=' . $payment_info['order_id'], 'SSL'));
        }
    }
}