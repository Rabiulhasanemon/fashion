<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$this->load->model('design/layout');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

        if ($route == 'product/category' && isset($this->request->get['category_id'])) {
            $this->load->model('catalog/category');

            $path = explode('_', (string)$this->request->get['category_id']);

            $layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
        } else if ($route == 'product/product' && isset($this->request->get['product_id'])) {
            $this->load->model('catalog/product');

            $layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
        } else if ($route == 'product/manufacturer/info' && isset($this->request->get['manufacturer_id'])) {
            $this->load->model('catalog/manufacturer');
            $layout_id = $this->model_catalog_manufacturer->getManufacturerLayoutId($this->request->get['manufacturer_id']);
        } else if ($route == 'information/information' && isset($this->request->get['information_id'])) {
            $this->load->model('catalog/information');
            $layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
        }

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$this->load->model('extension/module');

		$data['modules'] = array();

		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'column_left');

	foreach ($modules as $module) {
		$part = explode('.', $module['code']);

		// Extension modules (like HTML Content) have module_id in part[1]
		if (isset($part[1])) {
			$setting_info = $this->model_extension_module->getModule($part[1]);

			if ($setting_info && $setting_info['status']) {
				// Try extension/module path first, then fallback to module/
				$extension_path = DIR_APPLICATION . '../catalog/controller/extension/module/' . $part[0] . '.php';
				$module_path = DIR_APPLICATION . '../catalog/controller/module/' . $part[0] . '.php';
				
				if (file_exists($extension_path)) {
					$data['modules'][] = $this->load->controller('extension/module/' . $part[0], $setting_info);
				} elseif (file_exists($module_path)) {
					$data['modules'][] = $this->load->controller('module/' . $part[0], $setting_info);
				}
			}
		} elseif (isset($part[0]) && $this->config->get($part[0] . '_status')) {
			// Built-in modules without module_id (legacy modules)
			$data['modules'][] = $this->load->controller('module/' . $part[0]);
		}
	}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_left.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/column_left.tpl', $data);
		} else {
			return $this->load->view('default/template/common/column_left.tpl', $data);
		}
	}
}