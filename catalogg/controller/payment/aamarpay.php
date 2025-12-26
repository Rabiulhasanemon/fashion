<?php

class ControllerPaymentAamarpay extends Controller {

    public function index() {
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['confirm'] = $this->url->link('payment/aamarpay/confirm', '', "SSL");

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/aamarpay.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/aamarpay.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/aamarpay.tpl', $data);
        }
    }

    public function confirm() {
        $this->load->model('checkout/order');
        $this->load->model('checkout/coupon');
        $this->load->model('total/coupon');
        $this->load->model('checkout/payment');

        $order_id = $this->session->data['order_id'];

        if (!$order_id || $this->session->data['payment_method']['code'] != 'aamarpay') {
            $this->response->redirect($this->url->link("checkout/success"), '', 'SSL');
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);
        if (!$order_info) {
            $this->response->redirect($this->url->link("cart/cart", '', 'SSL'));
        }

        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

        $tran_id = $this->model_checkout_payment->addPayment(array(
            'order_id' => $order_id,
            'total' => $due,
            'status' => "Pending",
            'gateway_code' => "aamarpay",
            'gateway_title' => "Aamarpay",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => ''
        ));

        $total_amount = $due;

        if( $this->config->get('aamarpay_merchant') && $order_info['emi']) {
            $emi_option = 1;
            $emi_selected_inst = $order_info['emi_tenure'];
            $emi_max_inst_option = $order_info['emi_tenure'];
        } else {
            $emi_option = 0;
            $emi_selected_inst = 0;
            $emi_max_inst_option = 0;
        }

        $data = array(
            'store_id' => $this->config->get('aamarpay_merchant'),
            'signature_key' => $this->config->get('aamarpay_password'),
            'amount' => $this->currency->format($total_amount, $order_info['currency_code'], $order_info['currency_value'], false),
            'tran_id' => $tran_id,
            'success_url' => $this->url->link('payment/aamarpay/callback', '', 'SSL'),
            'fail_url' => $this->url->link('payment/aamarpay/callback', '', 'SSL'),
            'cancel_url' => $this->url->link('checkout/cart', '', 'SSL'),
            'cus_name' => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
            'cus_add1' => trim($order_info['payment_address_1'], ','),
            'cus_country' => $order_info['payment_country'],
            'cus_state' => $order_info['payment_zone'],
            'cus_city' => $order_info['payment_city'],
            'cus_postcode' => $order_info['payment_postcode'],
            'cus_phone' => $order_info['telephone'],
            'cus_email' => $order_info['email'],
            'desc' => 'Order Id#'. $order_id,
            'currency' => $order_info['currency_code'],
            'type' => 'json'
        );

        if($emi_option) {
            $data['opt_e'] = $emi_option;
            $data['opt_f'] = $emi_selected_inst;
            $data['opt_g'] = 'Order Id#'. $order_id;

        } else  {
            $data['opt_e'] = 2;
        }

        if ($this->config->get('aamarpay_mode') == 'live') {
            $gateway_url = 'https://secure.aamarpay.com/jsonpost.php';
        } else {
            $gateway_url = 'https://sandbox.aamarpay.com/jsonpost.php';
        }

//        $data['process_url'] = $gateway_url;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $gateway_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $this->response->redirect($response['payment_url']);

    }

    public function callback()  {
        $this->load->language('payment/aamarpay');

        if (isset($this->request->post['mer_txnid'])) {
            $payment_id = $this->request->post['mer_txnid'];
        } else {
            $payment_id = 0;
        }

        if($payment_id) {
            $this->load->model("checkout/payment");
            $payment_info = $this->model_checkout_payment->getPayment($payment_id);
        } else {
            $payment_info = null;
        }

        if(!$payment_info || $payment_info['status'] != "Pending") {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }


        if($this->config->get('aamarpay_mode')=='live') {
            $requested_url = ("https://secure.aamarpay.com/api/v1/trxcheck/request.php?request_id=$payment_id&store_id=" . $this->config->get('aamarpay_merchant') . "&signature_key=" . $this->config->get('aamarpay_password') . "&type=json");
        } else{
            $requested_url = ("https://sandbox.aamarpay.com/api/v1/trxcheck/request.php?request_id=$payment_id&store_id=" . $this->config->get('aamarpay_merchant') . "&signature_key=" . $this->config->get('aamarpay_password') . "&type=json");
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $requested_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);


        $result = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle))) {
            $result = json_decode($result);
            $status = $result->pay_status;

            $amount = $result->amount;
            $bank_tran_id = $result->bank_trxid;
            $card_type = $result->payment_type;

            # ISSUER INFO
            $card_no = $result->cardnumber;
            $card_issuer = $result->bin_issuer;
            $card_issuer_country = $result->bin_country;
            //Payment Risk Status
            $risk_level = $result->risk_level;
            $risk_title = $result->risk_title;
            if($status=='Successful') {
                if($risk_level==0){ $status = 'Approved';}
                if($risk_level==1){ $status = 'Risk';}
            } elseif($status=='VALIDATED'){
                if($risk_level==0){ $status = 'Approved';}
                if($risk_level==1){ $status = 'Risk';}
            } else {
                $status = 'Failed';
            }
        } else {
            $amount = '';
            $bank_tran_id = '';
            $card_type = '';

            # ISSUER INFO
            $card_no = '';
            $card_issuer = '';
            $card_issuer_country = '';

            $status = 'Failed';
        }



        $comment = "Result: ${status}, Card: ${card_no}, ${card_issuer}, ${card_issuer_country}, ${card_type}";

        $this->model_checkout_payment->editPayment(array(
            'payment_id' => $payment_info['payment_id'],
            'status' => $status,
            'total' => $payment_info['total'],
            'transaction_id' => $bank_tran_id,
            'tracking_no' => '',
            'payer_info' => $card_no,
            'comment' => $comment
        ));

        if($status === "Approved" && $payment_info['total'] == $amount) {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('aamarpay_order_status_id'), "Payer Info: $card_no, Gateway Status: $status", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } elseif($status === "Risk") {
            $this->load->model("checkout/order");
            $this->model_checkout_order->addOrderHistory($payment_info['order_id'], $this->config->get('aamarpay_order_risk_id'), "Payer Info: $card_no, Gateway Status: $status", true);
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            $this->session->data['error'] = $this->language->get('error_decline');
            $this->response->redirect($this->url->link("checkout/payment", 'order_id=' . $payment_info['order_id'], 'SSL'));
        }
    }

}
