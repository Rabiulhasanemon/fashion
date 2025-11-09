<?php
class ControllerModuleQuickCategory extends Controller {
	public function index($setting) {
		
        $data['name'] = $setting['name'];
        $data['class'] = $setting['class'];

		$this->load->model('catalog/category');
		$this->load->model('tool/image');

		$data['categories'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 5;
		}

		if (!empty($setting['category'])) {
			$categories = array_slice($setting['category'], 0, (int)$setting['limit']);

			foreach ($categories as $category_id) {
				$category_info = $this->model_catalog_category->getCategory($category_id);

				if ($category_info) {
                    $data['categories'][] = array(
                        'category_id'  => $category_info['category_id'],
                        'name'        => $category_info['name'],
                        'href'        => $this->url->link('product/category', 'category_id=' . $category_info['category_id'])
                    );
				}
			}
		}

		if ($data['categories']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/quick_category.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/quick_category.tpl', $data);
			} else {
				return $this->load->view('default/template/module/quick_category.tpl', $data);
			}
		}
	}
}