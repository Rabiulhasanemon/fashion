<?php
class ControllerModuleProductShowcaseTabs extends Controller {
	public function index($setting) {
		$this->load->language('module/product_showcase_tabs');

		// Use module name from admin if provided; fallback to language heading
		$data['heading_title'] = !empty($setting['name']) ? $setting['name'] : $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('extension/module/product_showcase_tabs');

		// Get module settings
		$data['module_id'] = isset($setting['module_id']) ? $setting['module_id'] : 0;
		$limit = isset($setting['limit']) ? (int)$setting['limit'] : 10;

		// Get tabs for this module
		$module_id = isset($setting['module_id']) ? $setting['module_id'] : 0;
		$tabs_data = $this->model_extension_module_product_showcase_tabs->getTabs($module_id);

		$data['tabs'] = array();

		foreach ($tabs_data as $tab) {
			if ($tab['status']) {
				$data['tabs'][] = array(
					'id' => $tab['id'],
					'title' => $tab['tab_title'],
					'sort_order' => $tab['sort_order']
				);
			}
		}

		// If no tabs available, return empty
		if (empty($data['tabs'])) {
			return '';
		}

		// Generate unique ID for this module instance
		$data['module_uid'] = 'pst-module-' . $module_id;

		// AJAX URL for loading tab products
		$data['ajax_url'] = $this->url->link('extension/module/product_showcase_tabs/getTabProducts', '', true);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/product_showcase_tabs.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/extension/module/product_showcase_tabs.tpl', $data);
		} else {
			return $this->load->view('extension/module/product_showcase_tabs', $data);
		}
	}

	public function getTabProducts() {
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('extension/module/product_showcase_tabs');
		$this->load->model('catalog/category');

		$json = array();

		if (isset($this->request->get['tab_id']) && isset($this->request->get['module_id'])) {
			$tab_id = (int)$this->request->get['tab_id'];
			$module_id = (int)$this->request->get['module_id'];

			// Get module settings to get the limit
			$this->load->model('extension/module');
			$module_info = $this->model_extension_module->getModule($module_id);
			$limit = isset($module_info['limit']) ? (int)$module_info['limit'] : 10;

			// Get tab data
			$tab = $this->model_extension_module_product_showcase_tabs->getTabById($tab_id);

			if ($tab && $tab['status']) {
				$product_ids = array();

				// Get products based on selection type
				if ($tab['selection_type'] == 'category' && !empty($tab['category_ids'])) {
					// Get products from categories
					foreach ($tab['category_ids'] as $category_id) {
						$filter_data = array(
							'filter_category_id' => $category_id,
							'limit' => $limit
						);

						$category_products = $this->model_catalog_product->getProducts($filter_data);

						foreach ($category_products as $product) {
							if (!in_array($product['product_id'], $product_ids)) {
								$product_ids[] = $product['product_id'];
							}
						}

						if (count($product_ids) >= $limit) {
							break;
						}
					}

					$product_ids = array_slice($product_ids, 0, $limit);
				} elseif ($tab['selection_type'] == 'product' && !empty($tab['product_ids'])) {
					// Use manually selected products
					$product_ids = array_slice($tab['product_ids'], 0, $limit);
				}

				// Build product data
				$json['products'] = array();

				foreach ($product_ids as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);

					if ($product_info) {
						if ($product_info['image']) {
							$image = $this->model_tool_image->resize($product_info['image'], 200, 200);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', 200, 200);
						}

						if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
							$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$price = false;
						}

						if ((float)$product_info['special']) {
							$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
						} else {
							$special = false;
						}

						// Calculate discount percentage
						$discount_percentage = false;
						if ((float)$product_info['special'] && (float)$product_info['price'] > 0) {
							$discount_percentage = round((((float)$product_info['price'] - (float)$product_info['special']) / (float)$product_info['price']) * 100);
						}

						// Get category name
						$category_name = '';
						$category_query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE p2c.product_id = '" . (int)$product_info['product_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name) ASC LIMIT 1");
						if ($category_query->num_rows) {
							$category_name = $category_query->row['name'];
						}

						// Get rating
						$rating = (int)$product_info['rating'];

						// Get review count
						$review_count = 0;
						$review_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_info['product_id'] . "' AND status = '1'");
						if ($review_query->num_rows) {
							$review_count = (int)$review_query->row['total'];
						}

						$json['products'][] = array(
							'product_id' => $product_info['product_id'],
							'thumb' => $image,
							'name' => $product_info['name'],
							'category_name' => $category_name,
							'manufacturer' => $product_info['manufacturer'],
							'price' => $price,
							'special' => $special,
							'discount' => $discount_percentage,
							'rating' => $rating,
							'reviews' => $review_count,
							'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
						);
					}
				}

				$json['success'] = true;
			} else {
				$json['error'] = 'Tab not found or disabled';
			}
		} else {
			$json['error'] = 'Invalid request';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}





