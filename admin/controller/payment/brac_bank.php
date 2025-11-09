<?php
class ControllerPaymentBracBank extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/brac_bank');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('brac_bank', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_resource_path'] = $this->language->get('entry_resource_path');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_online'] = $this->language->get('entry_online');
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


        if (isset($this->error['brac_bank_merchant_id'])) {
            $data['error_brac_bank_merchant_id'] = $this->error['brac_bank_merchant_id'];
        } else {
            $data['error_brac_bank_merchant_id'] = '';
        }


        if (isset($this->error['brac_bank_resource_path'])) {
            $data['error_brac_bank_resource_path'] = $this->error['brac_bank_resource_path'];
        } else {
            $data['error_brac_bank_resource_path'] = '';
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
			'href' => $this->url->link('payment/brac_bank', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/brac_bank', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['brac_bank_total'])) {
			$data['brac_bank_total'] = $this->request->post['brac_bank_total'];
		} else {
			$data['brac_bank_total'] = $this->config->get('brac_bank_total');
		}

		if (isset($this->request->post['brac_bank_order_status_id'])) {
			$data['brac_bank_order_status_id'] = $this->request->post['brac_bank_order_status_id'];
		} else {
			$data['brac_bank_order_status_id'] = $this->config->get('brac_bank_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['brac_bank_geo_zone_id'])) {
			$data['brac_bank_geo_zone_id'] = $this->request->post['brac_bank_geo_zone_id'];
		} else {
			$data['brac_bank_geo_zone_id'] = $this->config->get('brac_bank_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['brac_bank_merchant_id'])) {
			$data['brac_bank_merchant_id'] = $this->request->post['brac_bank_merchant_id'];
		} else {
			$data['brac_bank_merchant_id'] = $this->config->get('brac_bank_merchant_id');
		}


		if (isset($this->request->post['brac_bank_resource_path'])) {
			$data['brac_bank_resource_path'] = $this->request->post['brac_bank_resource_path'];
		} else {
			$data['brac_bank_resource_path'] = $this->config->get('brac_bank_resource_path');
		}

		if (isset($this->request->post['brac_bank_online'])) {
			$data['brac_bank_online'] = $this->request->post['brac_bank_online'];
		} else {
			$data['brac_bank_online'] = $this->config->get('brac_bank_online');
		}

		if (isset($this->request->post['brac_bank_status'])) {
			$data['brac_bank_status'] = $this->request->post['brac_bank_status'];
		} else {
			$data['brac_bank_status'] = $this->config->get('brac_bank_status');
		}

		if (isset($this->request->post['brac_bank_sort_order'])) {
			$data['brac_bank_sort_order'] = $this->request->post['brac_bank_sort_order'];
		} else {
			$data['brac_bank_sort_order'] = $this->config->get('brac_bank_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/brac_bank.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/brac_bank')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (empty($this->request->post['brac_bank_resource_path'])) {
            $this->error['brac_bank_resource_path'] = $this->language->get('error_brac_bank_resource_path');
        }

        if (empty($this->request->post['brac_bank_merchant_id'])) {
            $this->error['brac_bank_merchant_id'] = $this->language->get('error_merchant_id');
        }

		return !$this->error;
	}
}