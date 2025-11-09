<?php
class ControllerModuleProductShowcaseTabs extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/product_showcase_tabs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
		$this->load->model('extension/module/product_showcase_tabs');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$module_id = 0;
			if (!isset($this->request->get['module_id'])) {
				// Include module_id = 0 in the setting, it will be updated below
				$this->request->post['module_id'] = 0;
				$this->model_extension_module->addModule('product_showcase_tabs', $this->request->post);
				// Get the module_id that was just inserted
				$module_id = $this->db->getLastId();
				
				// Update the module with the correct module_id in its settings
				$updated_settings = $this->request->post;
				$updated_settings['module_id'] = $module_id;
				$this->model_extension_module->editModule($module_id, $updated_settings);
			} else {
				$module_id = $this->request->get['module_id'];
				// Include module_id in the setting
				$this->request->post['module_id'] = $module_id;
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			// Save tabs to database
			if (isset($this->request->post['tabs']) && is_array($this->request->post['tabs'])) {
				$this->model_extension_module_product_showcase_tabs->saveTabs($module_id, $this->request->post['tabs']);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_by_category'] = $this->language->get('text_by_category');
		$data['text_by_product'] = $this->language->get('text_by_product');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_tab_title'] = $this->language->get('entry_tab_title');
		$data['entry_selection_type'] = $this->language->get('entry_selection_type');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_tab'] = $this->language->get('button_add_tab');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['text_home'] = $this->language->get('text_home');
		$data['text_extension'] = $this->language->get('text_extension');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/product_showcase_tabs', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/product_showcase_tabs', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/product_showcase_tabs', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/product_showcase_tabs', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = isset($module_info['limit']) ? $module_info['limit'] : 10;
		} else {
			$data['limit'] = 10;
		}
		
		// Get module_id from GET parameter or module_info
		if (isset($this->request->get['module_id'])) {
			$data['module_id'] = $this->request->get['module_id'];
		} elseif (!empty($module_info) && isset($module_info['module_id'])) {
			$data['module_id'] = $module_info['module_id'];
		} else {
			$data['module_id'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		// Load tabs data
		if (isset($this->request->post['tabs'])) {
			$data['tabs'] = $this->request->post['tabs'];
		} elseif (!empty($module_info) && isset($data['module_id'])) {
			// Load tabs from database via model
			$tabs_from_db = $this->model_extension_module_product_showcase_tabs->getTabs($data['module_id']);
			$data['tabs'] = $tabs_from_db;
		} else {
			$data['tabs'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/product_showcase_tabs.tpl', $data));
	}

	protected function validate() {
		// No permission checks - anyone can access
		// No validation - allow access
		return true;
	}

	public function install() {
		// No permissions needed - anyone can access
	}

	public function uninstall() {
		// No permissions needed - anyone can access
	}
}
