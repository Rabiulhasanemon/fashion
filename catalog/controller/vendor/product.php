<?php
class ControllerVendorProduct extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/product', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor) {
			$this->response->redirect($this->url->link('vendor/register'));
		}

		if ($vendor['status'] != 'approved') {
			$this->session->data['error'] = 'Your vendor account is pending approval';
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/product');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/product');

		$this->getList($vendor['vendor_id']);
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/product/add', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/product');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/product');
		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// Set vendor_id and status to 0 (pending approval)
			$this->request->post['vendor_id'] = $vendor['vendor_id'];
			$this->request->post['status'] = 0; // Pending approval

			$product_id = $this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_pending');

			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('vendor/product', $url, 'SSL'));
		}

		$this->getForm($vendor['vendor_id']);
	}

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/product/edit', 'product_id=' . $this->request->get['product_id'], 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/product');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/product');
		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// Verify product belongs to vendor
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			if ($product_info && isset($product_info['vendor_id']) && $product_info['vendor_id'] == $vendor['vendor_id']) {
				// Set status back to pending if admin changed it
				$this->request->post['vendor_id'] = $vendor['vendor_id'];
				if ($product_info['status'] == 1) {
					$this->request->post['status'] = 0; // Back to pending after edit
				}

				$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);
				$this->session->data['success'] = $this->language->get('text_success_pending');
			} else {
				$this->error['warning'] = $this->language->get('error_product_not_found');
			}

			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('vendor/product', $url, 'SSL'));
		}

		$this->getForm($vendor['vendor_id']);
	}

	public function delete() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/product');
		$this->load->model('vendor/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				if ($product_info && isset($product_info['vendor_id']) && $product_info['vendor_id'] == $vendor['vendor_id']) {
					$this->model_catalog_product->deleteProduct($product_id);
				}
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('vendor/product', $url, 'SSL'));
		}

		$this->getList($vendor['vendor_id']);
	}

	protected function getList($vendor_id) {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_vendor'),
			'href' => $this->url->link('vendor/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/product', $url)
		);

		$data['add'] = $this->url->link('vendor/product/add', '', 'SSL');
		$data['delete'] = $this->url->link('vendor/product/delete', '', 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_vendor_id' => $vendor_id,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$product_total = $this->model_vendor_product->getTotalProducts($vendor_id);
		$results = $this->model_vendor_product->getProducts($vendor_id, $filter_data);

		foreach ($results as $result) {
			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'name' => $result['name'],
				'model' => $result['model'],
				'price' => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'quantity' => $result['quantity'],
				'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'status_class' => $result['status'] ? 'success' : 'warning',
				'edit' => $this->url->link('vendor/product/edit', 'product_id=' . $result['product_id'], 'SSL')
			);
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('vendor/product', $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/product_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/product_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/vendor/product_list.tpl', $data));
		}
	}

	protected function getForm($vendor_id) {
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_vendor'),
			'href' => $this->url->link('vendor/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/product', $url)
		);

		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('vendor/product/add', $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('vendor/product/edit', 'product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('vendor/product', $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
			if ($product_info && isset($product_info['vendor_id']) && $product_info['vendor_id'] != $vendor_id) {
				$this->response->redirect($this->url->link('vendor/product'));
			}
		}

		// Load product form data (simplified - you'll need to load all product fields)
		// For now, redirect to admin product form or create simplified form
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		// Note: You'll need to create product_form.tpl or redirect to admin product form
		$this->response->setOutput($this->load->view('default/template/vendor/product_form.tpl', $data));
	}

	protected function validateForm() {
		if (utf8_strlen($this->request->post['product_description'][$this->config->get('config_language_id')]['name']) < 3 || utf8_strlen($this->request->post['product_description'][$this->config->get('config_language_id')]['name']) > 255) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		return true;
	}
}


