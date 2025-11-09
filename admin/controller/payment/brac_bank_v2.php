<?php
class ControllerPaymentBracBankV2 extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/brac_bank_v2');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('brac_bank_v2', $this->request->post);

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

		$data['entry_gateway_url'] = $this->language->get('entry_gateway_url');
		$data['entry_access_key'] = $this->language->get('entry_access_key');
		$data['entry_profile_id'] = $this->language->get('entry_profile_id');
		$data['entry_secret_key'] = $this->language->get('entry_secret_key');
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


        if (isset($this->error['brac_bank_v2_gateway_url'])) {
            $data['error_brac_bank_v2_gateway_url'] = $this->error['brac_bank_v2_gateway_url'];
        } else {
            $data['error_brac_bank_v2_gateway_url'] = '';
        }

        if (isset($this->error['brac_bank_v2_access_key'])) {
            $data['error_brac_bank_v2_access_key'] = $this->error['brac_bank_v2_access_key'];
        } else {
            $data['error_brac_bank_v2_access_key'] = '';
        }
        
        if (isset($this->error['brac_bank_v2_profile_id'])) {
            $data['error_brac_bank_v2_profile_id'] = $this->error['brac_bank_v2_profile_id'];
        } else {
            $data['error_brac_bank_v2_profile_id'] = '';
        }

        if (isset($this->error['brac_bank_v2_secret_key'])) {
            $data['error_brac_bank_v2_secret_key'] = $this->error['brac_bank_v2_secret_key'];
        } else {
            $data['error_brac_bank_v2_secret_key'] = '';
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
			'href' => $this->url->link('payment/brac_bank_v2', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/brac_bank_v2', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['brac_bank_v2_total'])) {
			$data['brac_bank_v2_total'] = $this->request->post['brac_bank_v2_total'];
		} else {
			$data['brac_bank_v2_total'] = $this->config->get('brac_bank_v2_total');
		}

		if (isset($this->request->post['brac_bank_v2_order_status_id'])) {
			$data['brac_bank_v2_order_status_id'] = $this->request->post['brac_bank_v2_order_status_id'];
		} else {
			$data['brac_bank_v2_order_status_id'] = $this->config->get('brac_bank_v2_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['brac_bank_v2_geo_zone_id'])) {
			$data['brac_bank_v2_geo_zone_id'] = $this->request->post['brac_bank_v2_geo_zone_id'];
		} else {
			$data['brac_bank_v2_geo_zone_id'] = $this->config->get('brac_bank_v2_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['brac_bank_v2_gateway_url'])) {
			$data['brac_bank_v2_gateway_url'] = $this->request->post['brac_bank_v2_gateway_url'];
		} else {
			$data['brac_bank_v2_gateway_url'] = $this->config->get('brac_bank_v2_gateway_url');
		}

        if (isset($this->request->post['brac_bank_v2_access_key'])) {
			$data['brac_bank_v2_access_key'] = $this->request->post['brac_bank_v2_access_key'];
		} else {
			$data['brac_bank_v2_access_key'] = $this->config->get('brac_bank_v2_access_key');
		}
		
		if (isset($this->request->post['brac_bank_v2_profile_id'])) {
			$data['brac_bank_v2_profile_id'] = $this->request->post['brac_bank_v2_profile_id'];
		} else {
			$data['brac_bank_v2_profile_id'] = $this->config->get('brac_bank_v2_profile_id');
		}
		
		if (isset($this->request->post['brac_bank_v2_secret_key'])) {
			$data['brac_bank_v2_secret_key'] = $this->request->post['brac_bank_v2_secret_key'];
		} else {
			$data['brac_bank_v2_secret_key'] = $this->config->get('brac_bank_v2_secret_key');
		}

		
		if (isset($this->request->post['brac_bank_v2_online'])) {
			$data['brac_bank_v2_online'] = $this->request->post['brac_bank_v2_online'];
		} else {
			$data['brac_bank_v2_online'] = $this->config->get('brac_bank_v2_online');
		}
		
		if (isset($this->request->post['brac_bank_v2_status'])) {
			$data['brac_bank_v2_status'] = $this->request->post['brac_bank_v2_status'];
		} else {
			$data['brac_bank_v2_status'] = $this->config->get('brac_bank_v2_status');
		}

		if (isset($this->request->post['brac_bank_v2_sort_order'])) {
			$data['brac_bank_v2_sort_order'] = $this->request->post['brac_bank_v2_sort_order'];
		} else {
			$data['brac_bank_v2_sort_order'] = $this->config->get('brac_bank_v2_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/brac_bank_v2.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/brac_bank_v2')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (empty($this->request->post['brac_bank_v2_gateway_url'])) {
            $this->error['brac_bank_v2_gateway_url'] = $this->language->get('error_gateway_url');
        }
        if (empty($this->request->post['brac_bank_v2_access_key'])) {
            $this->error['brac_bank_v2_access_key'] = $this->language->get('error_access_key');
        }
        
        if (empty($this->request->post['brac_bank_v2_profile_id'])) {
            $this->error['brac_bank_v2_profile_id'] = $this->language->get('error_profile_id');
        }
        
        if (empty($this->request->post['brac_bank_v2_secret_key'])) {
            $this->error['brac_bank_v2_secret_key'] = $this->language->get('error_secret_key');
        }

        
		return !$this->error;
	}
}