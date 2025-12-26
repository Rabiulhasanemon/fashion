<?php
class ControllerVendorDashboard extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/dashboard', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('vendor/dashboard');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/vendor');
		$this->load->model('vendor/order');
		$this->load->model('vendor/product');

		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor) {
			$this->response->redirect($this->url->link('vendor/register', '', true));
		}

		$data = array();
		$data['vendor'] = $vendor;
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_pending_review'] = $this->language->get('text_pending_review');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_orders'] = $this->language->get('text_orders');
		$data['text_balance'] = $this->language->get('text_balance');

		$data['total_products'] = $this->model_vendor_product->getTotalProducts($vendor['vendor_id']);
		$data['total_orders'] = $this->model_vendor_order->getTotalOrders($vendor['vendor_id']);
		$data['pending_balance'] = $this->currency->format($vendor['pending_balance'], $this->config->get('config_currency'));
		$data['total_earnings'] = $this->currency->format($this->model_vendor_vendor->getTotalEarnings($vendor['vendor_id']), $this->config->get('config_currency'));

		$data['home'] = $this->url->link('common/home');
		$data['text_home'] = $this->language->get('text_home');
		
		$data['link_products'] = $this->url->link('vendor/product', '', 'SSL');
		$data['link_orders'] = $this->url->link('vendor/order', '', 'SSL');
		$data['link_withdrawal'] = $this->url->link('vendor/withdrawal', '', 'SSL');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/dashboard')
		);

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/dashboard.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/dashboard.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/vendor/dashboard.tpl', $data));
		}
	}
}

