<?php

class ControllerPaymentBracBankV2 extends Controller {

    public function index() {
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['confirm'] = $this->url->link('payment/brac_bank_v2/confirm', '', "SSL");

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/brac_bank_v2.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/brac_bank_v2.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/brac_bank_v2.tpl', $data);
        }
    }

    private function sign ($params) {
        return $this->signData($this->buildDataToSign($params), $this->config->get('brac_bank_v2_secret_key'));
    }

    private function signData($data, $secretKey) {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    private function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        $dataToSign = array();
        foreach ($signedFieldNames as $field) {
            $dataToSign[] = $field . "=" . $params[$field];
        }
        return implode(",", $dataToSign);
    }

    private function verifySign($params) {
        if(!isset($params["signature"])) return false;
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        $signedData = array();
        foreach ($signedFieldNames as $field) {
            $signedData[$field] =  $params[$field];
        }
        if (strcmp($params["signature"], $this->sign($params)) === 0) {
            return $signedData;
        } else {
            return false;
        }
    }

    public function confirm() {
        $this->load->model('checkout/order');
        $this->load->model('checkout/payment');
        $this->load->model("payment/brac_bank_v2");

        $order_id = $this->session->data['order_id'];

        if (!$order_id || $this->session->data['payment_method']['code'] != 'brac_bank_v2') {
            $this->response->redirect($this->url->link("checkout/cart"), '', 'SSL');
        }

        $order_info = $this->model_checkout_order->getOrder($order_id);
        if (!$order_info || !in_array($order_info['order_status_id'], $this->config->get('config_processing_status'))) {
            $this->response->redirect($this->url->link("cart/cart", '', 'SSL'));
        }

        $bank = $this->session->data['payment_method']['ext'] ?? '';
        $emi_banks = $this->model_payment_brac_bank_v2->getEMIBanks();

        if($order_info['emi'] && !in_array($bank, $emi_banks)) {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);
        $emi_tenure = (int) ($this->session->data["emi_tenure"] ?? $order_info['emi_tenure']);

        $uuid = uniqid();
        $payment_id = $this->model_checkout_payment->addPayment(array(
            'order_id' => $order_id,
            'total' => $due,
            'status' => "Pending",
            'gateway_code' => "brac_bank_v2",
            'gateway_title' => "Brac Bank V2",
            'transaction_id' => '',
            'tracking_no' => '',
            'emi' => $order_info['emi'],
            'tenure' => $emi_tenure,
            'process' => $order_info['emi'],
            'bank' => $bank,
            'payer_info' => '',
            'comment' => ''
        ));

        $post_data = array(
            'access_key' => $this->config->get('brac_bank_v2_access_key'),
            'profile_id' => $this->config->get('brac_bank_v2_profile_id'),
            'transaction_uuid' => $uuid,
            'signed_field_names' => 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,auth_trans_ref_no,override_custom_receipt_page',
            'unsigned_field_names' => 'bill_to_forename,bill_to_surname,bill_to_address_city,bill_to_address_country,bill_to_address_line1,bill_to_address_postal_code,bill_to_address_state,bill_to_email,override_custom_cancel_page',
            'signed_date_time' => gmdate("Y-m-d\TH:i:s\Z"),
            'locale' => 'en',
            'bill_to_forename' => 'NOREAL',
            'bill_to_surname' => 'NAME',
            'bill_to_address_city' => 'Mountain View',
            'bill_to_address_country' => "US",
            'bill_to_address_line1' => '1295 Charleston Road',
            'bill_to_address_postal_code' => '94043',
            'bill_to_address_state' => 'CA',
            'bill_to_email' => 'null@cybersource.com',
            'transaction_type' => 'sale',
            'reference_number' => $payment_id,
            'auth_trans_ref_no' => $payment_id,
            'override_custom_receipt_page' => $this->url->link("payment/brac_bank_v2/callback", '', 'SSL'),
            'override_custom_cancel_page' => $this->url->link("payment/brac_bank_v2/callback", '', 'SSL'),
            'amount' => $due,
            'currency' => 'BDT'
        );

        $data = array('params' => $post_data, 'sign' => $this->sign($post_data));

        $data['gateway_url'] = $this->config->get('brac_bank_v2_gateway_url');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/brac_bank_v2_confirm.tpl')) {
            return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/brac_bank_v2_confirm.tpl', $data));
        } else {
            return $this->response->setOutput($this->load->view('default/template/payment/brac_bank_v2_confirm.tpl', $data));
        }
    }

    private function updateOrder($order_info, $payment_info, $data) {
        $message = '';
        $message .= 'Payment Status = ' . ($data['decision'] ?? '') . "\n";
        $message .= 'Bank txnid = ' . ($data['transaction_id']  ?? '') . "\n";
        $message .= 'Card Number = ' . ($data['req_card_number'] ?? '') . "\n";
        $message .= 'Reason Code = ' . ($data['reason_code'] ?? '') . "\n";
        $message .= 'Auth Response = ' . ($data['auth_response'] ?? '') . "\n";
        $message .= 'Card Type = ' . ($data['card_type_name'] ?? '') . "\n";
        $message .= 'message = ' . ($data['message'] ?? '') . "\n";

        if (isset($data['decision']) && $data['decision'] == 'ACCEPT' && $data['reason_code'] == '100' && $data['auth_response'] == '00') {
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Approved",
                'total' => $payment_info['total'],
                'transaction_id' => $data['transaction_id'],
                'tracking_no' => $data['auth_code'],
                'payer_info' => $data['req_card_number'],
                'comment' => $message
            ));

            $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('brac_bank_v2_order_status_id'), "Payer Info: " . $data['req_card_number'] . ", Gateway Status: "  . $data['decision'], true);

        } else {
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Failed",
                'total' => $payment_info['total'],
                'transaction_id' => $data['transaction_id'],
                'tracking_no' => '',
                'payer_info' => $data['card_no'] ?? null,
                'comment' => $message
            ));
        }
    }

    public function callback() {
        $log = new Log("brac_bank_v2.log");
        $log->write(json_encode($this->request->post));
        $data = $this->verifySign($this->request->post);

        $this->load->model('checkout/order');
        $this->load->model('checkout/payment');

        $payment_id =  $data['req_reference_number'] ?? null;
        $payment_info = $payment_id ? $this->model_checkout_payment->getPayment($payment_id) : null;
        $order_info = $payment_info ? $this->model_checkout_order->getOrder($payment_info['order_id']) : null;

        if(!$order_info || $payment_info['gateway_code'] != 'brac_bank_v2') {
            $this->response->redirect($this->url->link("common/home", '', 'SSL'));
        }

        if($payment_info['status'] == "Pending" && $payment_info['total'] !== $data['req_amount']) {
            $this->updateOrder($order_info, $payment_info, $data);
        }

        $this->language->load('payment/brac_bank_v2');
        if (isset($data['decision']) && $data['decision'] == 'ACCEPT' && $data['reason_code'] == '100' && $data['auth_response'] == '00') {
            $this->response->redirect($this->url->link("checkout/success", '', 'SSL'));
        } else {
            $this->session->data['error'] = $this->language->get('text_failure');
            $this->response->redirect($this->url->link("checkout/payment", 'order_id=' . $payment_info['order_id'], 'SSL'));
        }
    }
}
