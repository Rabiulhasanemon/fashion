<?php
class ControllerVendorOrder extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/order', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/order');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/order');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
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
			'href' => $this->url->link('vendor/order')
		);

		$data['orders'] = array();

		$filter_data = array(
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$order_total = $this->model_vendor_order->getTotalOrders($vendor['vendor_id']);
		$results = $this->model_vendor_order->getOrders($vendor['vendor_id'], $filter_data);

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id' => $result['order_id'],
				'customer_name' => $result['firstname'] . ' ' . $result['lastname'],
				'status' => $result['order_status'],
				'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'vendor_earning' => $this->currency->format($result['vendor_earning'], $result['currency_code'], $result['currency_value']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view' => $this->url->link('vendor/order/info', 'order_id=' . $result['order_id'], 'SSL')
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('vendor/order', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/order_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/vendor/order_list.tpl', $data));
		}
	}

	public function info() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/order');
		$this->load->model('vendor/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_vendor_order->getOrder($order_id, $vendor['vendor_id']);

		if ($order_info) {
			$data['order_id'] = $order_id;
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$data['payment_method'] = $order_info['payment_method'];
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			$data['email'] = $order_info['email'];
			$data['telephone'] = $order_info['telephone'];
			$data['home'] = $this->url->link('common/home');
			$data['vendor_dashboard'] = $this->url->link('vendor/dashboard');
			$data['order_list'] = $this->url->link('vendor/order');

			$data['products'] = array();
			$products = $this->model_vendor_order->getOrderProducts($order_id, $vendor['vendor_id']);

			foreach ($products as $product) {
				$data['products'][] = array(
					'name' => $product['name'],
					'model' => $product['model'],
					'quantity' => $product['quantity'],
					'price' => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value']),
					'total' => $this->currency->format($product['total'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$data['totals'] = array();
			$data['totals'][] = array(
				'title' => 'Sub-Total',
				'text' => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'])
			);
			$data['totals'][] = array(
				'title' => 'Your Earning',
				'text' => $this->currency->format($order_info['vendor_earning'], $order_info['currency_code'], $order_info['currency_value'])
			);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/vendor/order_info.tpl', $data));
			}
		} else {
			$this->response->redirect($this->url->link('vendor/order'));
		}
	}
}

