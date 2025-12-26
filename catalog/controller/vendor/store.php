<?php
class ControllerVendorStore extends Controller {
	public function index() {
		$store_slug = isset($this->request->get['store']) ? $this->request->get['store'] : '';

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorBySlug($store_slug);

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('common/home'));
		}

		$this->load->language('vendor/store');
		$this->document->setTitle($vendor['store_name']);

		$this->load->model('vendor/product');
		$this->load->model('catalog/product');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['vendor'] = array(
			'vendor_id' => $vendor['vendor_id'],
			'store_name' => $vendor['store_name'],
			'store_description' => $vendor['store_description'],
			'logo' => $vendor['logo'] ? $this->config->get('config_ssl') . 'image/' . $vendor['logo'] : '',
			'banner' => $vendor['banner'] ? $this->config->get('config_ssl') . 'image/' . $vendor['banner'] : '',
			'rating' => $vendor['rating'],
			'review_count' => $vendor['review_count'],
			'total_products' => $vendor['total_products']
		);

		$data['products'] = array();

		$filter_data = array(
			'filter_vendor_id' => $vendor['vendor_id'],
			'start' => ($page - 1) * 12,
			'limit' => 12
		);

		$product_total = $this->model_vendor_product->getTotalProducts($vendor['vendor_id']);
		$results = $this->model_vendor_product->getProducts($vendor['vendor_id'], $filter_data);

		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], 300, 300);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', 300, 300);
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
			} else {
				$special = false;
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'thumb' => $image,
				'name' => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
				'price' => $price,
				'special' => $special,
				'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'])
			);
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 12;
		$pagination->url = $this->url->link('vendor/store', 'store=' . $store_slug . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/store.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/store.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/vendor/store.tpl', $data));
		}
	}
}


