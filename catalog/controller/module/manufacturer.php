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
        $data['name'] = $setting['name'];

		$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');

		$data['manufacturers'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		// Set default width and height if not provided
		if (!isset($setting['width']) || !$setting['width']) {
			$setting['width'] = 160;
		}
		if (!isset($setting['height']) || !$setting['height']) {
			$setting['height'] = 90;
		}

		if (!empty($setting['manufacturer'])) {
			$manufacturers = array_slice($setting['manufacturer'], 0, (int)$setting['limit']);

			foreach ($manufacturers as $manufacturer_id) {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

				if ($manufacturer_info) {
					$image = null;
					
					// Check for thumb first, then image
					if (!empty($manufacturer_info['thumb'])) {
						$image = $this->model_tool_image->resize($manufacturer_info['thumb'], $setting['width'], $setting['height']);
					} elseif (!empty($manufacturer_info['image'])) {
						$image = $this->model_tool_image->resize($manufacturer_info['image'], $setting['width'], $setting['height']);
					}
					
					// If resize returned null (file missing) or no image found, use SVG placeholder
					if (!$image) {
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
				}
			}
		}

		if ($data['manufacturers']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/manufacturer.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/manufacturer.tpl', $data);
			} else {
				return $this->load->view('default/template/module/manufacturer.tpl', $data);
			}
		}
	}
}