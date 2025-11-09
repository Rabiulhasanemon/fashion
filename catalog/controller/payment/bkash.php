<?php
class ControllerPaymentBkash extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->bkash = new Bkash($registry);
    }


    private function getConfig() {
        $data = array(
            'username' => $this->config->get("bkash_user"),
            'password' => $this->config->get("bkash_pass"),
            'app_key' => $this->config->get("bkash_app_key"),
            'app_secret' => $this->config->get("bkash_app_secret"),
            'token_url' => $this->config->get("bkash_token_url"),
            'create_url' => $this->config->get("bkash_create_url"),
            'execute_url' => $this->config->get("bkash_execute_url"),
            'query_url' => $this->config->get("bkash_query_url")
        );
        return $data;
    }

    public function index() {
		$data['text_loading'] = $this->language->get('text_loading');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['confirm'] = $this->url->link('payment/city_bank/confirm', '', "SSL");

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bkash.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/bkash.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/bkash.tpl', $data);
		}
	}

	public function confirm() {
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
        if(!$order_id || $this->session->data['payment_method']['code'] != 'bkash') {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $this->load->model("checkout/order");
        $this->load->model("checkout/payment");

        $order_info = $this->model_checkout_order->getOrder($order_id);
        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

		$data = array();

        $this->load->language('payment/bkash');

        $data['heading_title'] = $this->language->get('heading_title');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['button_continue'] = $this->language->get("button_continue");
        $data['button_cancel'] = $this->language->get("button_cancel");

        $data['home'] = $this->url->link('/');
        $data['success'] = $this->url->link("checkout/success");
        $data['cancel'] = $this->url->link('checkout/cart');

		$data["script_url"] = $this->config->get("bkash_script_url");
		$data["create_payment_url"] = $this->url->link('payment/bkash/create_payment');
		$data["execute_payment_url"] = $this->url->link('payment/bkash/execute_payment');
		$data["query_payment_url"] = $this->url->link('payment/bkash/query_payment');

		$data["order_id"] = $order_id;
		$data["amount"] = round($due);


		$data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bkash_confirm.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/bkash_confirm.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/bkash_confirm.tpl', $data));
        }
	}

	public function create_payment() {
        $this->response->addHeader('Content-Type: application/json');
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;

        if(!$order_id || $this->session->data['payment_method']['code'] != 'bkash') {
            http_response_code(400);
            $this->response->setOutput(json_encode(array("error" => "Invalid Request")));
            return;
        }

        $this->load->model("checkout/order");
        $this->load->model("checkout/payment");

        $order_info = $this->model_checkout_order->getOrder($order_id);
        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

        $data = $this->getConfig();
        $token_data = $this->bkash->token($data);
        $this->cache->set("bkash_token_data", $token_data);
        $data["token"] = $token_data["id_token"];
        $data["amount"] = round($due);

        $payment_id = $this->model_checkout_payment->addPayment(array(
            'order_id' => $order_id,
            'total' => $due,
            'status' => "Pending",
            'gateway_code' => "bkash",
            'gateway_title' => "bKash",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => ''
        ));

        $data["payment_id"] = $payment_id . "";
        $bkash_payment = $this->bkash->createPayment($data);

        $this->model_checkout_payment->editPayment(array(
            'payment_id' => $payment_id,
            'total' => $due,
            'status' => "Pending",
            'transaction_id' => $bkash_payment["paymentID"],
            'tracking_no' => "",
            'payer_info' => '',
            'comment' => ''
        ));
        $this->response->setOutput(json_encode($bkash_payment));
    }

    public function execute_payment() {
        $this->response->addHeader('Content-Type: application/json');
        if(isset($this->request->get["paymentID"])) {
            $transaction_id =  $this->request->get["paymentID"];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        if($payment_info) {
            $token_data = $this->cache->get("bkash_token_data");
            $data = $this->getConfig();
            $data["token"] = $token_data["id_token"];
            $data["paymentID"] = $this->request->get["paymentID"];
            $result = $this->bkash->executePayment($data);
            $approved = isset($result["paymentID"]);
            if ($approved) {
                $this->model_checkout_payment->editPayment(array(
                    'payment_id' => $payment_info['payment_id'],
                    'status' => "Approved",
                    'total' => $payment_info['total'],
                    'transaction_id' => $this->request->get["paymentID"],
                    'tracking_no' => "",
                    'payer_info' => '',
                    'comment' => 'trxID: ' . $result['trxID'] . ' Gateway Status: ' . $result["transactionStatus"]
                ));
                $this->load->model("checkout/order");
                $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('bkash_order_status_id'), "Gateway: bKash, Payment Status: " . $result["transactionStatus"] . ", TrxID:" . $result['trxID'], true);
            } elseif(isset($result["errorMessage"])) {
                $this->model_checkout_payment->editPayment(array(
                    'payment_id' => $payment_info['payment_id'],
                    'status' => $payment_info["status"],
                    'total' => $payment_info['total'],
                    'transaction_id' => $payment_info["transaction_id"],
                    'tracking_no' => "",
                    'payer_info' => '',
                    'comment' => $result["errorMessage"]
                ));
                $this->session->data['error'] = $result["errorMessage"];
            }
            $this->response->setOutput(json_encode($result));
        } else {
            http_response_code(400);
            $this->response->setOutput("");
        }

    }

    public function query_payment() {
        $this->response->addHeader('Content-Type: application/json');
        if(isset($this->request->get["paymentID"])) {
            $transaction_id =  $this->request->get["paymentID"];
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPaymentByRefId($transaction_id);
        } else {
            $payment_info = null;
        }

        if($payment_info) {
            $token_data = $this->cache->get("bkash_token_data");
            $data = $this->getConfig();
            $data["token"] = $token_data["id_token"];
            $data["paymentID"] = $this->request->get["paymentID"];
            $result = $this->bkash->queryPayment($data);
            $approved = isset($result["paymentID"]);
            if ($approved) {
                $this->model_checkout_payment->editPayment(array(
                    'payment_id' => $payment_info['payment_id'],
                    'status' => 'Approved',
                    'total' => $payment_info['total'],
                    'transaction_id' => $this->request->get["paymentID"],
                    'tracking_no' => "",
                    'payer_info' => '',
                    'comment' =>  'trxID: ' . $result['trxID'] . ' Gateway Status: ' . $result["transactionStatus"]
                ));
                $this->load->model("checkout/order");
                $order_info = $this->model_checkout_order->getOrder($payment_info["order_id"]);
                if($this->config->get('bkash_order_status_id') != $order_info["order_status_id"]) {
                    $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('bkash_order_status_id'), "Gateway: bKash, Payment Status: " . $result["transactionStatus"] . ", TrxID:" . $result['trxID'], true);
                }
            }
            $this->response->setOutput(json_encode($result));
        } else {
            http_response_code(400);
            $this->response->setOutput("");
        }

    }

}