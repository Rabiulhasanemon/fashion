<?php
class ControllerModuleFlashDeal extends Controller {
	public function index($setting) {
		$this->load->language('module/flash_deal');

		$data['heading_title'] = isset($setting['title']) ? $setting['title'] : $this->language->get('heading_title');
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('extension/module/flash_deal');

		// Define standard image sizes for all modules
        $image_width = 500;
        $image_height = 500;

		$data['module_id'] = isset($setting['module_id']) ? $setting['module_id'] : 0;
		$module_id = $data['module_id'];

		$flash_products = $this->model_extension_module_flash_deal->getProducts($module_id);
		
		$data['products'] = array();

		foreach ($flash_products as $fp) {
			if ($fp['status']) {
				$product_info = $this->model_catalog_product->getProduct($fp['product_id']);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $image_width, $image_height);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
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

				// Calculate discount percentage and special price
				$discount_percentage = false;
				$final_special = false;
				$final_price = $price;
				
				// If custom discount is set, calculate the special price
				if ((float)$fp['discount'] > 0 && (float)$product_info['price'] > 0) {
					$discount_percentage = (float)$fp['discount'];
					$discounted_price = (float)$product_info['price'] * (1 - $discount_percentage / 100);
					
					// Apply tax to the discounted price
					$taxed_discounted_price = $this->tax->calculate($discounted_price, $product_info['tax_class_id'], $this->config->get('config_tax'));
					$final_special = $this->currency->format($taxed_discounted_price, $this->session->data['currency']);
					
					// Keep formatted original price
					$final_price = $price;
				} elseif ((float)$product_info['special'] && (float)$product_info['price'] > 0) {
					// Use OpenCart's built-in special price
					$discount_percentage = round((((float)$product_info['price'] - (float)$product_info['special']) / (float)$product_info['price']) * 100);
					$final_special = $special;
					$final_price = $price;
				}

				// Get category name
				$category_name = '';
				$category_query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "product_to_category p2c LEFT JOIN " . DB_PREFIX . "category c ON (p2c.category_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE p2c.product_id = '" . (int)$product_info['product_id'] . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name) ASC LIMIT 1");
				if ($category_query->num_rows) {
					$category_name = $category_query->row['name'];
				}

				// Get rating
				$rating = (int)$product_info['rating'];

				// Format end date for JavaScript - ensure proper format
				$end_date_js = '';
				if (!empty($fp['end_date'])) {
					// Parse the date and convert to ISO format
					$timestamp = strtotime($fp['end_date']);
					if ($timestamp !== false) {
						$end_date_js = date('Y-m-d H:i:s', $timestamp);
					}
				}
				
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb' => $image,
					'name' => $product_info['name'],
					'category_name' => $category_name,
					'price' => $final_price,
					'special' => $final_special,
					'discount' => $discount_percentage,
					'rating' => $rating,
					'end_date' => $end_date_js,
					'href' => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
				}
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/flash_deal.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/flash_deal.tpl', $data);
		} else {
			return $this->load->view('module/flash_deal.tpl', $data);
		}
	}
}

