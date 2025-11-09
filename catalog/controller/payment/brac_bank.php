<?php
class ControllerPaymentBracBank extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
    }


    public function index() {
		$data['text_loading'] = $this->language->get('text_loading');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['confirm'] = $this->url->link('payment/brac_bank/confirm', '', "SSL");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/brac_bank.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/brac_bank.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/brac_bank.tpl', $data);
		}
	}

	public function confirm() {
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
        if(!$order_id || $this->session->data['payment_method']['code'] != 'brac_bank') {
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
            'gateway_code' => "brac_bank",
            'gateway_title' => "Brac Bank",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => ''
        ));

        try {
            $resourcePath = $this->config->get("brac_bank_resource_path");
            $aliasName = $this->config->get("brac_bank_merchant_id");
            $iPayPipe = new iPayPipe();
            $iPayPipe->setResourcePath(trim($resourcePath));
            $iPayPipe->setKeystorePath(trim($resourcePath));
            $iPayPipe->setAlias(trim($aliasName));
            $iPayPipe->setAction(1);
            $iPayPipe->setCurrency("050");
            $iPayPipe->setLanguage("USA");
            $iPayPipe->setResponseURL(trim($this->url->link("payment/brac_bank/callback", '', 'SSL')));
            $iPayPipe->setErrorURL(trim($this->url->link("payment/brac_bank/callback", '', 'SSL')));
            $iPayPipe->setAmt(number_format($due, 2, '.', ''));
            $iPayPipe->setTrackId($payment_id);
            $iPayPipe->setUdf1($order_id);

//            $iPayPipe->setUdf3($order_info['telephone']);
//            $iPayPipe->setUdf4("FC"); //Transaction mode for faster checkout

            if($order_info['emi']) {
                $iPayPipe->setUdf13($order_info['emi_tenure']); // EMI duration , 3,6
                $iPayPipe->setUdf14(number_format(($due / $order_info['emi_tenure']), 2, '.', '')); // Amount
            }

            if($iPayPipe->performPaymentInitializationHTTP() != 0) {
                throw new Exception("ERROR OCCURED! SEE CONSOLE FOR MORE DETAILS");
            }

            $url = trim($iPayPipe->getWebAddress());
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
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
        $this->load->language('payment/brac_bank');

        $resourcePath = $this->config->get("brac_bank_resource_path");
        $aliasName = $this->config->get("brac_bank_merchant_id");
        $iPayPipe = new iPayPipe();
        $iPayPipe->setResourcePath(trim($resourcePath));
        $iPayPipe->setKeystorePath(trim($resourcePath));
        $iPayPipe->setAlias(trim($aliasName));
        $trandata =  isset($_GET["trandata"]) ? $_GET["trandata"] : (isset($_POST["trandata"]) ? $_POST["trandata"] : "");
        $result = $iPayPipe->parseEncryptedRequest(trim($trandata));

        if($trandata && $result != 0) {
            $this->session->data['error'] = $this->language->get('error_gateway');
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $tracking_id = $iPayPipe->gettrackId();

        if($tracking_id) {
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPayment($tracking_id);
        } else {
            $payment_info = null;
        }

        if(!$payment_info || $payment_info['status'] != "Pending") {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $auth_code = $iPayPipe->getAuthRespCode();
        $result = $iPayPipe->getresult();
        $PAN =  $iPayPipe->getCardNumber();

        $comment = "Result: ${result}, Auth Code: " . $auth_code;
        if($iPayPipe->geterror_text()) {
            $comment .= ". " . $iPayPipe->geterror_text();
        }
        $this->model_checkout_payment->editPayment(array(
            'payment_id' => $payment_info['payment_id'],
            'status' => ($auth_code === "00" ? "Approved" : "Failed"),
            'total' => $payment_info['total'],
            'transaction_id' => $iPayPipe->gettransId(),
            'tracking_no' => $iPayPipe->getpaymentId(),
            'payer_info' => $PAN,
            'comment' => $comment
        ));

        if($auth_code === "00") {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('brac_bank_order_status_id'), "Payer Info: $PAN, Gateway Status: $auth_code", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            $this->session->data['error'] = $this->language->get('error_decline');
            $this->response->redirect($this->url->link("checkout/payment", 'order_id=' . $payment_info['order_id'], 'SSL'));
        }
    }
}