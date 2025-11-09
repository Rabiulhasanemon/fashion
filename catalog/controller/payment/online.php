<?php
class ControllerPaymentOnline extends Controller {
    public function confirm() {
        $this->load->language('payment/online');
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
        if(!$order_id || $this->session->data['payment_method']['code'] != 'online') {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }
        $this->response->redirect($this->url->link('checkout/payment', 'order_id=' . $order_id, 'SSL'));
    }
}