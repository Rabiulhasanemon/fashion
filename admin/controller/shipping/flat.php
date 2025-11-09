<?php
class ControllerShippingFlat extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/flat');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
        $this->load->model('extension/shipping');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            if (!isset($this->request->get['shipping_id'])) {
                $this->model_extension_shipping->addShipping('flat', $this->request->post);
            } else {
                $this->model_extension_shipping->editShipping($this->request->get['shipping_id'], $this->request->post);
            }


            $this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_cost'] = $this->language->get('entry_cost');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/flat', 'token=' . $this->session->data['token'], 'SSL')
		);

        if (!isset($this->request->get['shipping_id'])) {
		    $data['action'] = $this->url->link('shipping/flat', 'token=' . $this->session->data['token'], 'SSL');
        } else {
		    $data['action'] = $this->url->link('shipping/flat', 'token=' . $this->session->data['token'] . '&shipping_id=' . $this->request->get['shipping_id'], 'SSL');
        }

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
        if (isset($this->request->get['shipping_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $shipping_info = $this->model_extension_shipping->getShipping($this->request->get['shipping_id']);
        }

		if (isset($this->request->post['cost'])) {
			$data['cost'] = $this->request->post['cost'];
		} elseif (!empty($shipping_info)) {
            $data['cost'] = $shipping_info['cost'];
        } else {
			$data['cost'] = $this->config->get('cost');
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($shipping_info)) {
            $data['name'] = $shipping_info['name'];
        } else {
			$data['name'] = $this->config->get('name');
		}

		if (isset($this->request->post['tax_class_id'])) {
			$data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($shipping_info)) {
            $data['tax_class_id'] = $shipping_info['tax_class_id'];
        } else {
			$data['tax_class_id'] = $this->config->get('tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['geo_zone_id'])) {
			$data['geo_zone_id'] = $this->request->post['geo_zone_id'];
		} elseif (!empty($shipping_info)) {
            $data['geo_zone_id'] = $shipping_info['geo_zone_id'];
        } else {
			$data['geo_zone_id'] = $this->config->get('geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($shipping_info)) {
            $data['status'] = $shipping_info['status'];
        } else {
			$data['status'] = $this->config->get('status');
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($shipping_info)) {
            $data['sort_order'] = $shipping_info['sort_order'];
        } else {
			$data['sort_order'] = $this->config->get('sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/flat.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/flat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}