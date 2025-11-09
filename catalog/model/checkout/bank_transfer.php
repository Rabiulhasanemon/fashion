<?php
class ControllerPaymentBankTransfer extends Controller {
	public function confirm() {
		$this->load->language('payment/bank_transfer');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('payment/bank_transfer'),
            'text' => $this->language->get('heading_title')
        );

        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_description'] = $this->language->get('text_description');
        $data['text_payment'] = $this->language->get('text_payment');
        $data['text_loading'] = $this->language->get('text_loading');

		$data['entry_comment'] = $this->language->get('entry_comment');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));

		$data['action'] = $this->url->link('payment/bank_transfer/process');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bank_transfer.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/bank_transfer.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/payment/bank_transfer.tpl', $data));
        }
	}

	public function process() {
        $order_id = isset($this->session->data["order_id"]) ? $this->session->data["order_id"] : false;
        if(!$order_id || $this->session->data['payment_method']['code'] != 'bank_transfer') {
            $this->response->redirect($this->url->link("checkout/cart", '', 'SSL'));
        }

        $this->load->language('payment/bank_transfer');

        $this->load->model("checkout/order");
        $this->load->model("checkout/payment");

        $order_info = $this->model_checkout_order->getOrder($order_id);
        $due = $order_info['total'] - $this->model_checkout_payment->getAmountPaid($order_info['order_id']);

        $this->model_checkout_payment->addPayment(array(
            'order_id' => $order_id,
            'total' => $due,
            'status' => "Pending",
            'gateway_code' => "bank_transfer",
            'gateway_title' => "Bank Transfer",
            'transaction_id' => '',
            'tracking_no' => '',
            'payer_info' => '',
            'comment' => $this->request->post['comment']
        ));

        $comment  = $this->language->get('text_instruction') . "\n\n";
        $comment .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "\n\n";
        $comment .= $this->language->get('text_payment');

        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);

        $this->response->redirect($this->url->link("checkout/success"));
	}
}