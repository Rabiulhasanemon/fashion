<?php
class ControllerPaymentBkashV2 extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/bkash_v2');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bkash_v2', $this->request->post);

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

		$data['entry_user'] = $this->language->get('entry_user');
		$data['entry_pass'] = $this->language->get('entry_pass');
		$data['entry_app_key'] = $this->language->get('entry_app_key');
		$data['entry_app_secret'] = $this->language->get('entry_app_secret');
		$data['entry_base_url'] = $this->language->get('entry_base_url');
		$data['entry_coupon'] = $this->language->get('entry_coupon');
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


        if (isset($this->error['bkash_v2_user'])) {
            $data['error_bkash_v2_user'] = $this->error['bkash_v2_user'];
        } else {
            $data['error_bkash_v2_user'] = '';
        } 
        
        if (isset($this->error['bkash_v2_pass'])) {
            $data['error_bkash_v2_pass'] = $this->error['bkash_v2_pass'];
        } else {
            $data['error_bkash_v2_pass'] = '';
        }

        if (isset($this->error['bkash_v2_app_key'])) {
            $data['error_bkash_v2_app_key'] = $this->error['bkash_v2_app_key'];
        } else {
            $data['error_bkash_v2_app_key'] = '';
        }
        
        if (isset($this->error['bkash_v2_app_secret'])) {
            $data['error_bkash_v2_app_secret'] = $this->error['bkash_v2_app_secret'];
        } else {
            $data['error_bkash_v2_app_secret'] = '';
        }
        
        if (isset($this->error['bkash_v2_base_url'])) {
            $data['error_bkash_v2_base_url'] = $this->error['bkash_v2_base_url'];
        } else {
            $data['error_bkash_v2_base_url'] = '';
        }

        if (isset($this->error['bkash_v2_coupon'])) {
            $data['error_bkash_v2_coupon'] = $this->error['bkash_v2_coupon'];
        } else {
            $data['error_bkash_v2_coupon'] = '';
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
			'href' => $this->url->link('payment/bkash_v2', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/bkash_v2', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['bkash_v2_total'])) {
			$data['bkash_v2_total'] = $this->request->post['bkash_v2_total'];
		} else {
			$data['bkash_v2_total'] = $this->config->get('bkash_v2_total');
		}

		if (isset($this->request->post['bkash_v2_order_status_id'])) {
			$data['bkash_v2_order_status_id'] = $this->request->post['bkash_v2_order_status_id'];
		} else {
			$data['bkash_v2_order_status_id'] = $this->config->get('bkash_v2_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['bkash_v2_geo_zone_id'])) {
			$data['bkash_v2_geo_zone_id'] = $this->request->post['bkash_v2_geo_zone_id'];
		} else {
			$data['bkash_v2_geo_zone_id'] = $this->config->get('bkash_v2_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['bkash_v2_user'])) {
			$data['bkash_v2_user'] = $this->request->post['bkash_v2_user'];
		} else {
			$data['bkash_v2_user'] = $this->config->get('bkash_v2_user');
		}
		
		if (isset($this->request->post['bkash_v2_pass'])) {
			$data['bkash_v2_pass'] = $this->request->post['bkash_v2_pass'];
		} else {
			$data['bkash_v2_pass'] = $this->config->get('bkash_v2_pass');
		}
		
		if (isset($this->request->post['bkash_v2_app_key'])) {
			$data['bkash_v2_app_key'] = $this->request->post['bkash_v2_app_key'];
		} else {
			$data['bkash_v2_app_key'] = $this->config->get('bkash_v2_app_key');
		}
		
		if (isset($this->request->post['bkash_v2_app_secret'])) {
			$data['bkash_v2_app_secret'] = $this->request->post['bkash_v2_app_secret'];
		} else {
			$data['bkash_v2_app_secret'] = $this->config->get('bkash_v2_app_secret');
		}
		
		if (isset($this->request->post['bkash_v2_base_url'])) {
			$data['bkash_v2_base_url'] = $this->request->post['bkash_v2_base_url'];
		} else {
			$data['bkash_v2_base_url'] = $this->config->get('bkash_v2_base_url');
		}

		if (isset($this->request->post['bkash_v2_coupon'])) {
			$data['bkash_v2_coupon'] = $this->request->post['bkash_v2_coupon'];
		} else {
			$data['bkash_v2_coupon'] = $this->config->get('bkash_v2_coupon');
		}
		
		if (isset($this->request->post['bkash_v2_online'])) {
			$data['bkash_v2_online'] = $this->request->post['bkash_v2_online'];
		} else {
			$data['bkash_v2_online'] = $this->config->get('bkash_v2_online');
		}
		
		if (isset($this->request->post['bkash_v2_status'])) {
			$data['bkash_v2_status'] = $this->request->post['bkash_v2_status'];
		} else {
			$data['bkash_v2_status'] = $this->config->get('bkash_v2_status');
		}

		if (isset($this->request->post['bkash_v2_sort_order'])) {
			$data['bkash_v2_sort_order'] = $this->request->post['bkash_v2_sort_order'];
		} else {
			$data['bkash_v2_sort_order'] = $this->config->get('bkash_v2_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/bkash_v2.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bkash_v2')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

        if (empty($this->request->post['bkash_v2_user'])) {
            $this->error['bkash_v2_user'] = $this->language->get('error_user');
        }
        
        if (empty($this->request->post['bkash_v2_pass'])) {
            $this->error['bkash_v2_pass'] = $this->language->get('error_pass');
        }
        
        if (empty($this->request->post['bkash_v2_app_key'])) {
            $this->error['bkash_v2_app_key'] = $this->language->get('error_app_key');
        }
        
        if (empty($this->request->post['bkash_v2_app_secret'])) {
            $this->error['bkash_v2_app_secret'] = $this->language->get('error_app_secret');
        }
        
        if (empty($this->request->post['bkash_v2_base_url'])) {
            $this->error['bkash_v2_base_url'] = $this->language->get('error_base_url');
        }
        
		return !$this->error;
	}
}