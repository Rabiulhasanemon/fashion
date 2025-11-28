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

		$this->load->model('catalog/manufacturer');
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

		// DEBUG: Log settings
		$debug_log = DIR_LOGS . 'manufacturer_debug.log';
		file_put_contents($debug_log, date('Y-m-d H:i:s') . " === MANUFACTURER MODULE DEBUG ===\n", FILE_APPEND);
		file_put_contents($debug_log, date('Y-m-d H:i:s') . " Settings: " . print_r($setting, true) . "\n", FILE_APPEND);

		// If no manufacturers selected in settings, try to get all manufacturers
		if (empty($setting['manufacturer'])) {
			file_put_contents($debug_log, date('Y-m-d H:i:s') . " No manufacturers in settings, fetching all manufacturers\n", FILE_APPEND);
			$all_manufacturers = $this->model_catalog_manufacturer->getManufacturers();
			if ($all_manufacturers) {
				$manufacturer_ids = array();
				foreach ($all_manufacturers as $mfg) {
					$manufacturer_ids[] = $mfg['manufacturer_id'];
				}
				$setting['manufacturer'] = array_slice($manufacturer_ids, 0, (int)$setting['limit']);
				file_put_contents($debug_log, date('Y-m-d H:i:s') . " Found " . count($setting['manufacturer']) . " manufacturers\n", FILE_APPEND);
			}
		}

		if (!empty($setting['manufacturer'])) {
			$manufacturers = array_slice($setting['manufacturer'], 0, (int)$setting['limit']);
			file_put_contents($debug_log, date('Y-m-d H:i:s') . " Processing " . count($manufacturers) . " manufacturers\n", FILE_APPEND);

			foreach ($manufacturers as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				file_put_contents($debug_log, date('Y-m-d H:i:s') . " Manufacturer ID $manufacturer_id: " . print_r($manufacturer_info, true) . "\n", FILE_APPEND);

				if ($manufacturer_info) {
					$image = null;
					
					// Check for thumb first, then image
					if (!empty($manufacturer_info['thumb'])) {
						file_put_contents($debug_log, date('Y-m-d H:i:s') . " Found thumb: " . $manufacturer_info['thumb'] . "\n", FILE_APPEND);
						$image = $this->model_tool_image->resize($manufacturer_info['thumb'], $setting['width'], $setting['height']);
						file_put_contents($debug_log, date('Y-m-d H:i:s') . " Resized thumb result: " . ($image ?: 'NULL') . "\n", FILE_APPEND);
					} elseif (!empty($manufacturer_info['image'])) {
						file_put_contents($debug_log, date('Y-m-d H:i:s') . " Found image: " . $manufacturer_info['image'] . "\n", FILE_APPEND);
						$image = $this->model_tool_image->resize($manufacturer_info['image'], $setting['width'], $setting['height']);
						file_put_contents($debug_log, date('Y-m-d H:i:s') . " Resized image result: " . ($image ?: 'NULL') . "\n", FILE_APPEND);
					} else {
						file_put_contents($debug_log, date('Y-m-d H:i:s') . " No thumb or image found for manufacturer\n", FILE_APPEND);
					}
					
					// If resize returned null (file missing) or no image found, use SVG placeholder
					if (!$image) {
						file_put_contents($debug_log, date('Y-m-d H:i:s') . " Using SVG placeholder for " . $manufacturer_info['name'] . "\n", FILE_APPEND);
						// Generate SVG placeholder data URI
						$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$setting['width'].'" height="'.$setting['height'].'" viewBox="0 0 '.$setting['width'].' '.$setting['height'].'"><rect width="100%" height="100%" fill="#f8f9fa"/><text x="50%" y="50%" font-family="Arial, sans-serif" font-size="12" fill="#adb5bd" text-anchor="middle" dy=".3em">' . htmlspecialchars($manufacturer_info['name']) . '</text></svg>';
						$image = 'data:image/svg+xml;base64,' . base64_encode($svg);
					}

					$data['manufacturers'][] = array(
						'manufacturer_id'  => $manufacturer_info['manufacturer_id'],
						'thumb'       => $image,
						'name'        => $manufacturer_info['name'],
						'href'        => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_info['manufacturer_id'])
					);
					file_put_contents($debug_log, date('Y-m-d H:i:s') . " Added manufacturer: " . $manufacturer_info['name'] . " with image: " . substr($image, 0, 100) . "...\n", FILE_APPEND);
				} else {
					file_put_contents($debug_log, date('Y-m-d H:i:s') . " Manufacturer ID $manufacturer_id not found in database\n", FILE_APPEND);
				}
			}
		} else {
			file_put_contents($debug_log, date('Y-m-d H:i:s') . " No manufacturers to process\n", FILE_APPEND);
		}

		file_put_contents($debug_log, date('Y-m-d H:i:s') . " Final manufacturers count: " . count($data['manufacturers']) . "\n", FILE_APPEND);
		file_put_contents($debug_log, date('Y-m-d H:i:s') . " === END DEBUG ===\n\n", FILE_APPEND);

		if ($data['manufacturers']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/manufacturer.tpl', $data);
			} else {
				return $this->load->view('default/template/module/manufacturer.tpl', $data);
			}
		}
	}
}