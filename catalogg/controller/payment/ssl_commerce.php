<?php

class ControllerPaymentSSLCommerce extends Controller
{

    private function verifyHash() {
        $store_passwd = urldecode($this->config->get('ssl_commerce_password'));
        if(isset($this->request->post['verify_sign']) && isset($this->request->post['verify_key'])) {
            $pre_define_key = explode(',', $_POST['verify_key']);
            $new_data = array();
            foreach($pre_define_key as $value) {
                if(isset($this->request->post[$value])) {
                    $new_data[$value] = ($_POST[$value]);
                }
            }
            $new_data['store_passwd'] = md5($store_passwd);

            ksort($new_data);

            $hash_string="";
            foreach($new_data as $key=>$value) {
                $hash_string .= $key.'='.($value).'&';
            }
            $hash_string = rtrim($hash_string,'&');

            if(md5($hash_string) == $this->request->post['verify_sign']) {
                return true;
            }
        }
        return false;
    }

    private function validateTransaction() {
        $store_id = urldecode($this->config->get('ssl_commerce_merchant'));
        $store_password = urldecode($this->config->get('ssl_commerce_password'));
        $val_id = isset($_POST['val_id']) ? urldecode($_POST['val_id']) : '';

        if(!$val_id) {
            return null;
        }

        if ($this->config->get('ssl_commerce_mode') == 'live') {
            $requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=" . $val_id ."&store_id=" . $store_id . "&store_passwd=" . $store_password ."&v=1&format=json");
        } else {
            $requested_url = ("https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php?val_id=" . $val_id ."&store_id=" . $store_id . "&store_passwd=" . $store_password ."&v=1&format=json");
        }

        $post_data = array(
            'val_id' => $val_id,
            'store_id' => $store_id,
            'store_passwd' => $store_password,
            'v' => 1,
            'format' => 'json'
        );

        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $requested_url);
//        curl_setopt($handle, CURLOPT_TIMEOUT, 10);
//        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
//        curl_setopt($handle, CURLOPT_POST, 1);
//        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        if($this->config->get('ssl_commerce_mode') != "live") {
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC
        }

        $response = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $error_no = curl_errno($handle);
        curl_close($handle);

        if ($code == 200 && !$error_no) {

            $response = json_decode($response);
            $data = array();

            $status = $response->status;
            $data['tran_date'] = $response->tran_date;
            $data['tran_id'] = $response->tran_id;
            $data['val_id'] = $response->val_id;
            $data['amount'] = $response->amount;
            $data['store_amount'] = $response->store_amount;
            $data['bank_tran_id'] = $response->bank_tran_id;
            $data['card_type'] = $response->card_type;

            $data['card_no'] = $response->card_no;
            $data['card_issuer'] = $response->card_issuer;
            $data['card_brand'] = $response->card_brand;
            $data['card_issuer_country'] = $response->card_issuer_country;
            $data['card_issuer_country_code'] = $response->card_issuer_country_code;
            $data['risk_level'] = $response->risk_level;
            $data['risk_title'] = $response->risk_title;
            if ($status == 'VALID' || $status == 'VALIDATED') {
                if ($response->risk_level == 0) {
                    $status = 'success';
                }

                if ($response->risk_level == 1) {
                    $status = 'risk';
                }

            } else {
                $status = 'failed';
            }
            $data['status'] = $status;
            return $data;
        }
        return null;
    }


    public function index()
    {
        $data['text_loading'] = $this->language->get('text_loading');

        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['confirm'] = $this->url->link('payment/ssl_commerce/confirm', '', "SSL");

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ssl_commerce.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/ssl_commerce.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/ssl_commerce.tpl', $data);
        }
    }

    public function confirm() {
        $this->load->model('checkout/order');
        $this->load->model('checkout/payment');

        $order_id = $this->session->data['order_id'];

        if (!$order_id || $this->session->data['payment_method']['code'] != 'ssl_commerce') {
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
            'gateway_code' => "ssl_commerce",
            'gateway_title' => "SSL Commerce",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => ''
        ));

        $total_amount = $due;

        if( $this->config->get('ssl_commerce_merchant') && $order_info['emi']) {
            $emi_option = 1;
            $emi_allow_only = 1;
            $emi_selected_inst = $order_info['emi_tenure'];
            $emi_max_inst_option = $order_info['emi_tenure'];
        } else {
            $emi_allow_only = 0;
            $emi_option = 0;
            $emi_selected_inst = 0;
            $emi_max_inst_option = 0;
        }

        $post_data = array(
            'store_id' => $this->config->get('ssl_commerce_merchant'),
            'store_passwd' => $this->config->get('ssl_commerce_password'),
            'total_amount' => $total_amount,
            'tran_id' => $tran_id,
            'success_url' => $this->url->link('payment/ssl_commerce/callback', '', 'SSL'),
            'fail_url' => $this->url->link('payment/ssl_commerce/callback', '', 'SSL'),
            'cancel_url' => $this->url->link('checkout/cart', '', 'SSL'),
            'cus_name' => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
            'cus_add1' => trim($order_info['payment_address_1'], ','),
            'cus_country' => $order_info['payment_country'],
            'cus_state' => $order_info['payment_zone'],
            'cus_city' => $order_info['payment_city'],
            'cus_postcode' => $order_info['payment_postcode'],
            'cus_phone' => $order_info['telephone'],
            'cus_email' => $order_info['email'],
            'ship_name' => $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'],
            'ship_add1' => $order_info['shipping_address_1'],
            'ship_country' => $order_info['shipping_country'],
            'ship_state' => $order_info['shipping_zone'],
            'delivery_tel' => '',
            'ship_city' => $order_info['shipping_city'],
            'ship_postcode' => $order_info['shipping_postcode'],
            'emi_option' => $emi_option,
            'emi_allow_only' => $emi_allow_only,
            'emi_selected_inst' => $emi_selected_inst,
            'emi_max_inst_option' => $emi_max_inst_option,
            'currency' => $this->session->data['currency']
        );

        if ($this->config->get('ssl_commerce_mode') == 'live') {
            $gateway_url = 'https://securepay.sslcommerz.com/gwprocess/v3/api.php';
        } else {
            $gateway_url = 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php';
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $gateway_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 10);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $error_no = curl_errno($handle);
        curl_close($handle);

        $this->log->write($code . " " . $gateway_url . "\n" . json_encode($post_data) . "\n" . $content);
        if ($code == 200 && !$error_no) {
            $ssl_commerce_response = json_decode($content, true);
        } else {
            $this->session->data['error'] = "Can't connect to Payment Gateway";
            $this->response->redirect($this->url->link("checkout/success"), '', 'SSL');
        }
        $data['process_url'] = $ssl_commerce_response['GatewayPageURL'];
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ssl_commerce_confirm.tpl')) {
            return $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/ssl_commerce_confirm.tpl', $data));
        } else {
            return $this->response->setOutput($this->load->view('default/template/payment/ssl_commerce_confirm.tpl', $data));
        }
    }

    private function updateOrder($order_info, $payment_info, $data) {
        if (isset($data['status']) && $data['status'] == 'success') {
            $message = '';
            $message .= 'Payment Status = ' . $data['status'] . "\n";
            $message .= 'Bank txnid = ' . $data['bank_tran_id'] . "\n";
            $message .= 'Payment Date = ' . $data['tran_date'] . "\n";
            $message .= 'Card Number = ' . $data['card_no'] . "\n";
            $message .= 'Card Type = ' . $data['card_brand'] . '-' . $data['card_type'] . "\n";

            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Approved",
                'total' => $payment_info['total'],
                'transaction_id' => $data['bank_tran_id'],
                'tracking_no' => $data['val_id'],
                'payer_info' => $data['card_no'],
                'comment' => $message
            ));

            $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('ssl_commerce_order_status_id'), "Payer Info: " . $data['card_no'] . ", Gateway Status: "  . $data['status'], true);

        } else if (isset($data['status']) && $data['status'] == 'risk') {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_payment_failure'),
                'href' => '#'
            );

            $message = '';
            $message .= 'Payment Status = ' . $data['status'] . "\n";
            $message .= 'Bank txnid = ' . $data['bank_tran_id'] . "\n";
            $message .= 'Payment Date = ' . $data['tran_date'] . "\n";
            $message .= 'Card Number = ' . $data['card_no'] . "\n";
            $message .= 'Card Type = ' . $data['card_brand'] . '-' . $data['card_type'] . "\n";
            $message .= 'Transaction Risk Level = ' . $data['risk_level'] . "\n";
            $message .= 'Transaction Risk Description = ' . $data['risk_title'] . "\n";

            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Risk",
                'total' => $payment_info['total'],
                'transaction_id' => $data['bank_tran_id'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => $data['card_no'],
                'comment' => $message
            ));

            $this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('ssl_commerce_order_risk_id'), "Payer Info: " . $data['card_no'] . ", Gateway Status: "  . $data['order_status'], true);
        } else {
            $this->model_checkout_payment->editPayment(array(
                'payment_id' => $payment_info['payment_id'],
                'status' => "Failed",
                'total' => $payment_info['total'],
                'transaction_id' => $data['bank_tran_id'],
                'tracking_no' => $payment_info['tracking_no'],
                'payer_info' => $data['card_no'],
                'comment' => ''
            ));
        }
    }

    public function callback() {

        $data = $this->validateTransaction();

        if(!$data && $this->verifyHash()) {
            $data = $this->requst->post;
            $data['status'] = "failed";
        }

        $this->load->model('checkout/order');
        $this->load->model('checkout/payment');

        $payment_id = $data ? $data['tran_id'] : null;
        $payment_info = $payment_id ? $this->model_checkout_payment->getPayment($payment_id) : null;
        $order_info = $payment_info ? $this->model_checkout_order->getOrder($payment_info['order_id']) : null;

        if ($order_info) {
            if($payment_info['status'] == "Pending") {
                $this->updateOrder($order_info, $payment_info, $data);
            }
            $this->language->load('payment/ssl_commerce');
            if (isset($data['status']) && $data['status'] == 'success') {
                $this->response->redirect($this->url->link("checkout/success", '', 'SSL'));
            } else if (isset($data['status']) && $data['status'] == 'risk') {
                $this->response->redirect($this->url->link("checkout/success", '', 'SSL'));
            } else {
                $this->session->data['error'] = $this->language->get('text_failure');
                $this->response->redirect($this->url->link("checkout/success", '', 'SSL'));
            }
        } else {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }
    }

    public function ipn() {
        if($this->verifyHash()) {
            $data = $this->validateTransaction();

            if(!$data) {
                $data = $this->requst->post;
                $data['status'] = "failed";
            }

            $this->load->model('checkout/order');
            $this->load->model('checkout/payment');

            $payment_id = $data ? $data['tran_id'] : null;
            $payment_info = $payment_id ? $this->model_checkout_payment->getPayment($payment_id) : null;
            $order_info = $payment_info ? $this->model_checkout_order->getOrder($payment_info['order_id']) : null;

            if($order_info && $payment_info['status'] == "Pending") {
                $this->updateOrder($order_info, $payment_info, $data);
            }
            echo "Tanks for your info :)";
        }
    }
}
