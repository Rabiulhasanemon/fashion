<?php
class ControllerPaymentIpdc extends Controller {
	public function index() {
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipdc.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/ipdc.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/ipdc.tpl', $data);
		}
	}

	public function confirm() {
        $order_id = $this->session->data['order_id'];

        if (!$order_id || $this->session->data['payment_method']['code'] != 'ipdc') {
            $this->response->redirect($this->url->link("checkout/success", '', 'SSL'));
        }

        $this->load->model('checkout/order');
        $this->load->model('checkout/payment');

        $order_info = $this->model_checkout_order->getOrder($order_id);
        if (!$order_info) {
            $this->response->redirect($this->url->link("cart/cart", '', 'SSL'));
        }

        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

        $data = array(
            'order_id' => $order_id,
            'shipping_address_1' => $order_info['shipping_address_1'],
            'name' => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
            'total' => $due,
        );
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ipdc_order_status_id'));

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/ipdc.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/ipdc.tpl', $data));
        } else {
            $this->response->setOutput( $this->load->view('default/template/payment/ipdc.tpl', $data));
        }
	}
}