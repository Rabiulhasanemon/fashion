<?php
class ControllerPaymentNagad extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/nagad');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nagad', $this->request->post);

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
		$data['entry_private_key'] = $this->language->get('entry_private_key');
		$data['entry_public_key'] = $this->language->get('entry_public_key');
		$data['entry_base_url'] = $this->language->get('entry_base_url');
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


        if (isset($this->error['nagad_merchant_id'])) {
            $data['error_nagad_merchant_id'] = $this->error['nagad_merchant_id'];
        } else {
            $data['error_nagad_merchant_id'] = '';
        } 
        
        if (isset($this->error['nagad_private_key'])) {
            $data['error_nagad_private_key'] = $this->error['nagad_private_key'];
        } else {
            $data['error_nagad_private_key'] = '';
        }
        
        if (isset($this->error['nagad_public_key'])) {
            $data['error_nagad_public_key'] = $this->error['nagad_public_key'];
        } else {
            $data['error_nagad_public_key'] = '';
        }
        
        if (isset($this->error['nagad_base_url'])) {
            $data['error_nagad_base_url'] = $this->error['nagad_base_url'];
        } else {
            $data['error_nagad_base_url'] = '';
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
			'href' => $this->url->link('payment/nagad', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/nagad', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['nagad_total'])) {
			$data['nagad_total'] = $this->request->post['nagad_total'];
		} else {
			$data['nagad_total'] = $this->config->get('nagad_total');
		}

		if (isset($this->request->post['nagad_order_status_id'])) {
			$data['nagad_order_status_id'] = $this->request->post['nagad_order_status_id'];
		} else {
			$data['nagad_order_status_id'] = $this->config->get('nagad_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['nagad_geo_zone_id'])) {
			$data['nagad_geo_zone_id'] = $this->request->post['nagad_geo_zone_id'];
		} else {
			$data['nagad_geo_zone_id'] = $this->config->get('nagad_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['nagad_merchant_id'])) {
			$data['nagad_merchant_id'] = $this->request->post['nagad_merchant_id'];
		} else {
			$data['nagad_merchant_id'] = $this->config->get('nagad_merchant_id');
		}
	
		
		if (isset($this->request->post['nagad_private_key'])) {
			$data['nagad_private_key'] = $this->request->post['nagad_private_key'];
		} else {
			$data['nagad_private_key'] = $this->config->get('nagad_private_key');
		}
		
		if (isset($this->request->post['nagad_public_key'])) {
			$data['nagad_public_key'] = $this->request->post['nagad_public_key'];
		} else {
			$data['nagad_public_key'] = $this->config->get('nagad_public_key');
		}

		if (isset($this->request->post['nagad_base_url'])) {
			$data['nagad_base_url'] = $this->request->post['nagad_base_url'];
		} else {
			$data['nagad_base_url'] = $this->config->get('nagad_base_url');
		}
		
		if (isset($this->request->post['nagad_online'])) {
			$data['nagad_online'] = $this->request->post['nagad_online'];
		} else {
			$data['nagad_online'] = $this->config->get('nagad_online');
		}
		
		if (isset($this->request->post['nagad_status'])) {
			$data['nagad_status'] = $this->request->post['nagad_status'];
		} else {
			$data['nagad_status'] = $this->config->get('nagad_status');
		}

		if (isset($this->request->post['nagad_sort_order'])) {
			$data['nagad_sort_order'] = $this->request->post['nagad_sort_order'];
		} else {
			$data['nagad_sort_order'] = $this->config->get('nagad_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/nagad.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/nagad')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (empty($this->request->post['nagad_merchant_id'])) {
            $this->error['nagad_merchant_id'] = $this->language->get('error_merchant_id');
        }
        
        if (empty($this->request->post['nagad_private_key'])) {
            $this->error['nagad_private_key'] = $this->language->get('error_private_key');
        }
        
        if (empty($this->request->post['nagad_public_key'])) {
            $this->error['nagad_public_key'] = $this->language->get('error_public_key');
        }
        
        if (empty($this->request->post['nagad_base_url'])) {
            $this->error['nagad_base_url'] = $this->language->get('error_base_url');
        }

        
		return !$this->error;
	}
}