<?php
class ControllerModuleFlashDeal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/flash_deal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
		$this->load->model('extension/module/flash_deal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$module_id = 0;
			if (!isset($this->request->get['module_id'])) {
				$this->request->post['module_id'] = 0;
				$this->model_extension_module->addModule('flash_deal', $this->request->post);
				$module_id = $this->db->getLastId();
				
				$updated_settings = $this->request->post;
				$updated_settings['module_id'] = $module_id;
				$this->model_extension_module->editModule($module_id, $updated_settings);
			} else {
				$module_id = $this->request->get['module_id'];
				$this->request->post['module_id'] = $module_id;
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			if (isset($this->request->post['products']) && is_array($this->request->post['products'])) {
				$products = array();
				foreach ($this->request->post['products'] as $product) {
					if (!empty($product['product_id'])) {
						// Validate and format the end date
						$end_date = '';
						if (isset($product['end_date']) && !empty($product['end_date'])) {
							$date_input = trim($product['end_date']);
							
							// Parse the date - OpenCart datetimepicker sends in YYYY-MM-DD HH:mm:ss format
							// Check if date is already in correct format
							if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $date_input)) {
								$end_date = $date_input;
							} else {
								// Try to parse it
								$timestamp = strtotime($date_input);
								if ($timestamp !== false) {
									$end_date = date('Y-m-d H:i:s', $timestamp);
								} else {
									// If invalid, use default (7 days from now)
									$end_date = date('Y-m-d H:i:s', strtotime('+7 days'));
								}
							}
						} else {
							$end_date = date('Y-m-d H:i:s', strtotime('+7 days'));
						}
						
						$products[] = array(
							'product_id' => $product['product_id'],
							'discount' => isset($product['discount']) ? (float)$product['discount'] : 0,
							'end_date' => $end_date,
							'sort_order' => isset($product['sort_order']) ? (int)$product['sort_order'] : 0,
							'status' => isset($product['status']) ? (int)$product['status'] : 1
						);
					}
				}
				if (!empty($products)) {
					$this->model_extension_module_flash_deal->saveProducts($module_id, $products);
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_end_date'] = $this->language->get('entry_end_date');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_product'] = $this->language->get('button_add_product');
		$data['button_remove'] = $this->language->get('button_remove');

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
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/flash_deal', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/flash_deal', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($module_info) && isset($module_info['title'])) {
			$data['title'] = $module_info['title'];
		} else {
			$data['title'] = 'Flash Deal';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = 1;
		}

		if (isset($this->request->get['module_id'])) {
			$data['module_id'] = $this->request->get['module_id'];
		} elseif (!empty($module_info) && isset($module_info['module_id'])) {
			$data['module_id'] = $module_info['module_id'];
		} else {
			$data['module_id'] = 0;
		}

		if (isset($this->request->post['products'])) {
			$data['products'] = $this->request->post['products'];
		} elseif (!empty($module_info) && isset($data['module_id'])) {
			$products_from_db = $this->model_extension_module_flash_deal->getProducts($data['module_id']);
			$data['products'] = $products_from_db;
		} else {
			$data['products'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/flash_deal.tpl', $data));
	}

	protected function validate() {
		return true;
	}

	public function install() {
		$this->load->model('extension/module/flash_deal');
		$this->model_extension_module_flash_deal->install();
	}

	public function uninstall() {
		$this->load->model('extension/module/flash_deal');
		$this->model_extension_module_flash_deal->uninstall();
	}
}

