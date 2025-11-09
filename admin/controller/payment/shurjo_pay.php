<?php
class ControllerPaymentShurjoPay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/shurjo_pay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shurjo_pay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_merchant_username'] = $this->language->get('entry_merchant_username');
		$data['entry_server_url'] = $this->language->get('entry_server_url');
		$data['entry_api_order_prefix'] = $this->language->get('entry_api_order_prefix');
		$data['entry_api_password'] = $this->language->get('entry_api_password');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


        if (isset($this->error['shurjo_pay_merchant_username'])) {
            $data['error_shurjo_pay_merchant_username'] = $this->error['shurjo_pay_merchant_username'];
        } else {
            $data['error_shurjo_pay_merchant_username'] = '';
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/shurjo_pay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/shurjo_pay', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['shurjo_pay_total'])) {
			$data['shurjo_pay_total'] = $this->request->post['shurjo_pay_total'];
		} else {
			$data['shurjo_pay_total'] = $this->config->get('shurjo_pay_total');
		}

		if (isset($this->request->post['shurjo_pay_order_status_id'])) {
			$data['shurjo_pay_order_status_id'] = $this->request->post['shurjo_pay_order_status_id'];
		} else {
			$data['shurjo_pay_order_status_id'] = $this->config->get('shurjo_pay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['shurjo_pay_geo_zone_id'])) {
			$data['shurjo_pay_geo_zone_id'] = $this->request->post['shurjo_pay_geo_zone_id'];
		} else {
			$data['shurjo_pay_geo_zone_id'] = $this->config->get('shurjo_pay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shurjo_pay_merchant_username'])) {
			$data['shurjo_pay_merchant_username'] = $this->request->post['shurjo_pay_merchant_username'];
		} else {
			$data['shurjo_pay_merchant_username'] = $this->config->get('shurjo_pay_merchant_username');
		}

		if (isset($this->request->post['shurjo_pay_server_url'])) {
			$data['shurjo_pay_server_url'] = $this->request->post['shurjo_pay_server_url'];
		} else {
			$data['shurjo_pay_server_url'] = $this->config->get('shurjo_pay_server_url');
		}

        if (isset($this->request->post['shurjo_pay_api_password'])) {
            $data['shurjo_pay_api_password'] = $this->request->post['shurjo_pay_api_password'];
        } else {
            $data['shurjo_pay_api_password'] = $this->config->get('shurjo_pay_api_password');
        }

        if (isset($this->request->post['shurjo_pay_api_order_prefix'])) {
            $data['shurjo_pay_api_order_prefix'] = $this->request->post['shurjo_pay_api_order_prefix'];
        } else {
            $data['shurjo_pay_api_order_prefix'] = $this->config->get('shurjo_pay_api_order_prefix');
        }

		if (isset($this->request->post['shurjo_pay_status'])) {
			$data['shurjo_pay_status'] = $this->request->post['shurjo_pay_status'];
		} else {
			$data['shurjo_pay_status'] = $this->config->get('shurjo_pay_status');
		}

		if (isset($this->request->post['shurjo_pay_sort_order'])) {
			$data['shurjo_pay_sort_order'] = $this->request->post['shurjo_pay_sort_order'];
		} else {
			$data['shurjo_pay_sort_order'] = $this->config->get('shurjo_pay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/shurjo_pay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/shurjo_pay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (empty($this->request->post['shurjo_pay_server_url'])) {
            $this->error['shurjo_pay_server_url'] = $this->language->get('entry_server_url');
        }

        if (empty($this->request->post['shurjo_pay_merchant_username'])) {
            $this->error['shurjo_pay_merchant_username'] = $this->language->get('error_merchant_username');
        }
        if (empty($this->request->post['shurjo_pay_api_password'])) {
            $this->error['shurjo_pay_api_password'] = $this->language->get('error_api_password');
        }

        if (empty($this->request->post['shurjo_pay_api_order_prefix'])) {
            $this->error['shurjo_pay_api_order_prefix'] = $this->language->get('error_api_order_prefix');
        }

		return !$this->error;
	}
}