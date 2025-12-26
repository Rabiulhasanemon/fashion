<?php
class ControllerPaymentNagad extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->nagad = new Nagad($this->getConfig());
    }


    private function getConfig() {
        $data = array(
            'merchant_id' => $this->config->get("nagad_merchant_id"),
            'private_key' => $this->config->get("nagad_private_key"),
            'public_key' => $this->config->get("nagad_public_key"),
            'base_url' => $this->config->get("nagad_base_url"),
        );
        return $data;
    }

    public function index() {
		$data['text_loading'] = $this->language->get('text_loading');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['confirm'] = $this->url->link('payment/nagad/confirm', '', "SSL");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/nagad.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/nagad.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/nagad.tpl', $data);
		}
	}

	public function confirm() {
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
        if(!$order_id || $this->session->data['payment_method']['code'] != 'nagad') {
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
            'gateway_code' => "nagad",
            'gateway_title' => "Nagad",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => ''
        ));

        $data = $this->nagad->process(array(
            "payment_id" => $payment_id,
            'order_id' => $order_id,
            'amount' => number_format($due, 2, '.', ''),
            'callback_url' => $this->url->link("payment/nagad/callback", '', 'SSL')
        ));

        if($data['status'] !== 'Success') {
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_id,
                'status' => 'Failed',
                'total' => $due,
                'transaction_id' => "",
                'tracking_no' => "",
                'payer_info' => "",
                'comment' => ''
            ));
            $this->session->data['error'] = $this->language->get('error_gateway');
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $data['url'] = json_encode($data['callBackUrl']);
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/nagad_confirm.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/nagad_confirm.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/nagad_confirm.tpl', $data));
        }
	}

    public function callback()  {
        $this->load->language('payment/nagad');

        if(isset($_REQUEST['payment_ref_id'])) {
            $response_array = $this->nagad->getPaymentData($_REQUEST['payment_ref_id']);
        } else {
            $response_array = array();
        }

        if(isset($response_array['orderId'])) {
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPayment($response_array['orderId']);
        } else {
            $payment_info = null;
        }

        if(!$payment_info || $payment_info['status'] != "Pending") {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $order_status = '';
        $clientMobileNo = '';
        $config = $this->getConfig();

        if($payment_info && $response_array['merchantId'] == $config["merchant_id"]) {
            $order_status = isset($response_array['status']) ? $response_array['status'] : "Failed";
            $comment = '';


            if(isset($response_array['statusCode'])) {
                $comment = "Response Code: " . $response_array['statusCode'] . "\n";
            }
            if(isset($response_array['message'])) {
                $comment = "Message: " . $response_array['message'] . "\n";
            }
            $comment .= "Order Status: " . $order_status;

            if(isset($response_array['clientMobileNo'])) {
                $clientMobileNo = $response_array['clientMobileNo'];
            }

            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => ($order_status == "Success" ? "Approved" : "Failed"),
                'total' => $payment_info['total'],
                'transaction_id' => $response_array['paymentRefId'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => $clientMobileNo,
                'comment' => $comment
            ));
        } else {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        if($order_status == "Success") {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('nagad_order_status_id'), "Payer Info: $clientMobileNo, Gateway Status: $order_status", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            $this->session->data['error'] = $this->language->get('error_decline');
            $this->response->redirect($this->url->link("checkout/payment", 'order_id=' . $payment_info['order_id'], 'SSL'));
        }
    }
}