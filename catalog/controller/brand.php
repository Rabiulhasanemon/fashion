<?php
// Brand controller - Root level route (brand instead of product/brand)
class ControllerBrand extends Controller {
	public function index() {
		$this->load->language('product/manufacturer');

		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_index'] = $this->language->get('text_index');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['button_continue'] = $this->language->get('button_continue');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('brand')
		);

		// Get total counts for animation
		$total_brands = 0;
		$total_products = 0;
		
		// Count total brands
		$all_manufacturers = $this->model_catalog_manufacturer->getManufacturers();
		if ($all_manufacturers) {
			$total_brands = count($all_manufacturers);
		}
		
		// Count total products
		try {
			$total_products = $this->model_catalog_product->getTotalProducts(array());
			$total_products = $total_products ? (int)$total_products : 0;
		} catch (Exception $e) {
			$total_products = 0;
		}
		
		$data['total_brands'] = $total_brands;
		$data['total_products'] = $total_products;

		$data['categories'] = array();
		$results = $this->model_catalog_manufacturer->getManufacturers();

		if ($results) {
			foreach ($results as $result) {
				if (!isset($result['name']) || empty($result['name'])) {
					continue;
				}
				
				if (is_numeric(utf8_substr($result['name'], 0, 1))) {
					$key = '0 - 9';
				} else {
					$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
				}

				if (!isset($data['categories'][$key])) {
					$data['categories'][$key]['name'] = $key;
					$data['categories'][$key]['manufacturer'] = array();
				}

				if (isset($result['image']) && $result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 170, 170);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 170, 170);
				}

				$manufacturer_id = isset($result['manufacturer_id']) ? $result['manufacturer_id'] : 0;
				
				$product_count = 0;
				if ($manufacturer_id > 0) {
					$filter_data = array('filter_manufacturer_id' => $manufacturer_id);
					try {
						$product_count = $this->model_catalog_product->getTotalProducts($filter_data);
						$product_count = $product_count ? (int)$product_count : 0;
					} catch (Exception $e) {
						$product_count = 0;
					}
				}
				
				$data['categories'][$key]['manufacturer'][] = array(
					'name' => $result['name'],
					'image' => $image,
					'href' => $this->url->link('brand/info', 'manufacturer_id=' . $manufacturer_id),
					'product_count' => $product_count
				);
			}
		}

		$data['continue'] = $this->url->link('common/home');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/manufacturer_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/manufacturer_list.tpl', $data));
		}
	}

	public function info() {
		// Use manufacturer controller but replace URLs in output
		require_once(DIR_APPLICATION . 'controller/product/manufacturer.php');
		$manufacturer_controller = new ControllerProductManufacturer($this->registry);
		
		// Start output buffering
		ob_start();
		
		// Call the manufacturer info method
		$manufacturer_controller->info();
		
		// Get the output
		$output = ob_get_clean();
		
		// Replace all manufacturer URLs with brand URLs (both product/brand and product/manufacturer)
		$output = str_replace('product/manufacturer', 'brand', $output);
		$output = str_replace('product/brand', 'brand', $output);
		$output = str_replace('route=product/manufacturer', 'route=brand', $output);
		$output = str_replace('route=product/brand', 'route=brand', $output);
		
		// Output the modified content
		$this->response->setOutput($output);
	}
}

