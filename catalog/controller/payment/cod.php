<?php
class ControllerPaymentCod extends Controller {
	public function index() {
		$data['text_loading'] = $this->language->get('text_loading');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/cod.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/cod.tpl', $data);
		}
	}

	public function confirm() {
        $this->load->language('payment/cod');

		if ($this->session->data['payment_method']['code'] != 'cod') {
            $this->response->redirect($this->url->link("checkout/cart"));
		}

        $this->load->model('checkout/order');
        if($this->config->get('cod_otp')) {
            $order_id = $this->session->data['order_id'];
            $order_info = $this->model_checkout_order->getOrder($order_id);
            if (!$order_info) {
                $this->response->redirect($this->url->link("cart/cart", '', 'SSL'));
            }
            $this->sms->sendPin($order_info['telephone']);

            $this->document->setTitle($this->language->get('heading_title'));

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_checkout'),
                'href' => $this->url->link('checkout/onepagecheckout')
            );

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('payment/cod/confirm', '', 'SSL')
            );

            if (isset($this->session->data['error'])) {
                $data['error_warning'] = $this->session->data['error'];
                unset($this->session->data['error']);
            } else {
                $data['error_warning'] = '';
            }

            $data['button_confirm'] = $this->language->get('button_confirm');
            $data['text_loading'] = $this->language->get('text_loading');
            $data['text_otp_verification'] = $this->language->get('text_otp_verification');
            $data['text_otp_info'] = $this->language->get('text_otp_info');

            $data['entry_otp'] = $this->language->get('entry_otp');

            $data['action'] = $this->url->link('payment/cod/verify_opt');

            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');


            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cod_otp.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/cod_otp.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/payment/cod_otp.tpl', $data));
            }
        } else {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
            $this->response->redirect($this->url->link("checkout/success"));
        }
	}

    public function verify_opt() {
        $this->load->model('checkout/order');

        if(($this->session->data['pin'] == $this->request->post['pin'])) {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
            $this->response->redirect($this->url->link("checkout/success"));
        } else {
            $this->session->data['error'] = $this->language->get('error_otp');
            $this->response->redirect($this->url->link("payment/cod/confirm", '', 'SSL'));
        }
    }
}