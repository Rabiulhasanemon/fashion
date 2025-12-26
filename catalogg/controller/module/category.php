<?php
class ControllerModuleCategory extends Controller {
	public function index() {
		$this->load->language('module/category');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		if (isset($parts[2])) {
			$data['child_id_2'] = $parts[2];
		} else {
			$data['child_id_2'] = 0;
		}



		$categories = $this->cache->get("all.category.list.with.child");
		if($categories == false) {
            $this->load->model('catalog/category');
            $this->load->model('catalog/category_manufacturer');
            $this->load->model('catalog/product');
			$data['categories'] = array();
			$categories = $this->model_catalog_category->getCategories(0);
			foreach ($categories as $category) {
				if ($category['top']) {
					// Level 2
					$children_data_1 = array();

					$children_level_1 = $this->model_catalog_category->getCategories($category['category_id']);

					foreach ($children_level_1 as $child) {
                        if(!$child['top']) { continue; }
						$children_level_2 = $this->model_catalog_category->getCategories($child['category_id']);

						//Level 3
						$children_data_2 = array();
						foreach ($children_level_2 as $child_2) {
                            if(!$child_2['top']) { continue; }
							$filter_data = array(
								'filter_category_id'  => $child_2['category_id'],
								'filter_sub_category' => true
							);

							$children_data_2[] = array(
								'name'  => $child_2['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
								'category_id' => $child_2['category_id'],
								'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'].'_'.$child_2['category_id'])
							);

						}
                        if(!$children_level_2) {
                            $brands_level_2 = $this->model_catalog_category_manufacturer->getCategoryManufacturers(array('filter_category_id' => $child['category_id']));
                            foreach ($brands_level_2 as $child_2) {
                                $children_data_2[] = array(
                                    'name'  => $child_2['manufacturer_name'],
                                    'manufacturer_id' => $child_2['manufacturer_id'],
                                    'href'  => $this->url->link('product/category', 'path=' . $category['category_id']  . '_' . $child['category_id'] . '&manufacturer_id=' . $child_2['manufacturer_id']),
                                    'children' => array()
                                );
                            }
                        }
						$filter_data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

						$children_data_1[] = array(
							'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
							'category_id' => $child['category_id'],
							'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']),
							'children' => $children_data_2
						);
					}

                    if(!$children_level_1) {
                        $brands_level_1 = $this->model_catalog_category_manufacturer->getCategoryManufacturers(array('filter_category_id' => $category['category_id']));
                        foreach ($brands_level_1 as $child) {
                            $children_data_1[] = array(
                                'name'  => $child['manufacturer_name'],
                                'manufacturer_id' => $child['manufacturer_id'],
                                'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '&manufacturer_id=' . $child['manufacturer_id']),
                                'children' => array()
                            );
                        }
                    }
					// Level 1
					$data['categories'][] = array(
						'name'     => $category['name'],
						'category_id' => $category['category_id'],
						'children' => $children_data_1,
						'column'   => $category['column'] ? $category['column'] : 1,
						'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
					);
				}
			}
			$this->cache->set("all.category.list.with.child", $data["categories"]);
		} else {
			$data['categories'] = $categories;
		}



		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/category.tpl', $data);
		} else {
			return $this->load->view('default/template/module/category.tpl', $data);
		}

	}
}