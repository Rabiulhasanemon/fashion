<?php
class ControllerModuleManufacturer extends Controller {
	public function index($setting) {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}
		$data["route"] = $route;
		$this->load->language('module/manufacturer');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['name'] = isset($setting['name']) ? $setting['name'] : '';
        $data['manufacturer_list_url'] = $this->url->link('brand');

		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['manufacturers'] = array();

		if (!isset($setting['limit']) || !$setting['limit']) {
			$setting['limit'] = 4;
		}

		// Set default width and height if not provided
		if (!isset($setting['width']) || !$setting['width']) {
			$setting['width'] = 160;
		}
		if (!isset($setting['height']) || !$setting['height']) {
			$setting['height'] = 90;
		}

		// DEBUG: Prepare debug data for JavaScript console
		$data['debug_info'] = array();
		$data['debug_info']['settings'] = $setting;
		$data['debug_info']['manufacturers_found'] = array();

		// If no manufacturers selected in settings, try to get all manufacturers
		if (empty($setting['manufacturer'])) {
			$data['debug_info']['action'] = 'No manufacturers in settings, fetching all';
			$all_manufacturers = $this->model_catalog_manufacturer->getManufacturers();
			if ($all_manufacturers) {
				$manufacturer_ids = array();
				foreach ($all_manufacturers as $mfg) {
					$manufacturer_ids[] = $mfg['manufacturer_id'];
				}
				$setting['manufacturer'] = array_slice($manufacturer_ids, 0, (int)$setting['limit']);
				$data['debug_info']['action'] = 'Found ' . count($setting['manufacturer']) . ' manufacturers from database';
			}
		}

		if (!empty($setting['manufacturer'])) {
			$manufacturers = array_slice($setting['manufacturer'], 0, (int)$setting['limit']);

			foreach ($manufacturers as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				$debug_entry = array(
					'id' => $manufacturer_id,
					'found' => !empty($manufacturer_info),
					'name' => isset($manufacturer_info['name']) ? $manufacturer_info['name'] : 'N/A',
					'has_thumb' => !empty($manufacturer_info['thumb']),
					'has_image' => !empty($manufacturer_info['image']),
					'thumb_path' => isset($manufacturer_info['thumb']) ? $manufacturer_info['thumb'] : null,
					'image_path' => isset($manufacturer_info['image']) ? $manufacturer_info['image'] : null,
				);

				if ($manufacturer_info) {
					$image = null;
					$original_image_path = null;
					
					// Check for thumb first, then image
					if (!empty($manufacturer_info['thumb'])) {
						$original_image_path = $manufacturer_info['thumb'];
						$image = $this->model_tool_image->resize($manufacturer_info['thumb'], $setting['width'], $setting['height']);
						$debug_entry['resize_result'] = $image ? 'SUCCESS' : 'FAILED (file missing or cache issue)';
						$debug_entry['resized_url'] = $image;
					} elseif (!empty($manufacturer_info['image'])) {
						$original_image_path = $manufacturer_info['image'];
						$image = $this->model_tool_image->resize($manufacturer_info['image'], $setting['width'], $setting['height']);
						$debug_entry['resize_result'] = $image ? 'SUCCESS' : 'FAILED (file missing or cache issue)';
						$debug_entry['resized_url'] = $image;
					} else {
						$debug_entry['resize_result'] = 'NO IMAGE FOUND';
					}
					
					// If resize failed but original file exists, use original image directly
					if (!$image && $original_image_path) {
						// Check if original file actually exists
						if (is_file(DIR_IMAGE . $original_image_path)) {
							// Use original image directly with proper URL encoding
							// Split the path and encode each segment properly
							$path_parts = explode('/', $original_image_path);
							$encoded_parts = array_map('rawurlencode', $path_parts);
							$encoded_path = implode('/', $encoded_parts);
							
							if ($this->request->server['HTTPS']) {
								$base_url = rtrim($this->config->get('config_ssl'), '/');
								$image = $base_url . '/image/' . $encoded_path;
							} else {
								$base_url = rtrim($this->config->get('config_url'), '/');
								$image = $base_url . '/image/' . $encoded_path;
							}
							$debug_entry['resize_result'] = 'USING ORIGINAL IMAGE (cache failed)';
							$debug_entry['resized_url'] = $image;
						} else {
							$debug_entry['resize_result'] = 'ORIGINAL FILE NOT FOUND';
						}
					}
					
					// If still no image, use SVG placeholder
					if (!$image) {
						// Generate SVG placeholder data URI
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$setting['width'].'" height="'.$setting['height'].'" viewBox="0 0 '.$setting['width'].' '.$setting['height'].'"><rect width="100%" height="100%" fill="#f8f9fa"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="12" fill="#adb5bd" text-anchor="middle" dy=".3em">' . htmlspecialchars($manufacturer_info['name']) . '</text></svg>';
						$image = 'data:image/svg+xml;base64,' . base64_encode($svg);
						$debug_entry['resize_result'] = 'USING SVG PLACEHOLDER';
						$debug_entry['resized_url'] = 'data:image/svg+xml;base64,...';
					}

					// Get product count for this manufacturer
					$product_count = $this->model_catalog_product->getTotalProducts(array('filter_manufacturer_id' => $manufacturer_info['manufacturer_id']));
					
					$data['manufacturers'][] = array(
						'manufacturer_id'  => $manufacturer_info['manufacturer_id'],
						'thumb'       => $image,
						'name'        => $manufacturer_info['name'],
						'product_count' => $product_count,
						'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id'])
					);
					$debug_entry['final_image'] = substr($image, 0, 100) . (strlen($image) > 100 ? '...' : '');
				}
				
				$data['debug_info']['manufacturers_found'][] = $debug_entry;
			}
		} else {
			$data['debug_info']['action'] = 'No manufacturers to process';
		}

		$data['debug_info']['final_count'] = count($data['manufacturers']);

		if ($data['manufacturers']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/manufacturer.tpl', $data);
			} else {
				return $this->load->view('default/template/module/manufacturer.tpl', $data);
			}
		}
	}
}