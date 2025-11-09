<?php
class ControllerModuleFeaturedCategory extends Controller
{
	public function index($setting)
	{

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
					if ($category_info['image']) {
						$image = $this->model_tool_image->resize($category_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('default/category.png', $setting['width'], $setting['height']);
					}

					if ($setting['child']) {
						$child_list = array();
						$results = $this->model_catalog_category->getCategories($category_id, (int)$setting['child_limit']);
						foreach ($results as $child) {
							$child_list[] = array(
								'category_id'  => $child['category_id'],
								'icon'       => $child['icon'] ? "image/" . $child['icon'] : "image/default/icon.png",
								'name'        => $child['name'],
                                'blurb'        => $category_info['blurb'],
								'description'        => $child['description'],
								'href'        => $this->url->link('product/category', 'category_id=' . $child['category_id'])
							);
						}
					} else {
						$child_list = array();
					}


					$data['categories'][] = array(
						'category_id'  => $category_info['category_id'],
						'icon'       => $image,
						'children'    => $child_list,
						'blurb'        => $category_info['blurb'],
						'name'        => $category_info['name'],
                        'description'        => $category_info['description'],
						'href'        => $this->url->link('product/category', 'category_id=' . $category_info['category_id'])
					);
				}
			}
		}

        // TODO: Need Change
		if ($data['categories']) {
            if ($setting['child']) {
                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/child_with_featured_category.tpl')) {
                    return $this->load->view($this->config->get('config_template') . '/template/module/child_with_featured_category.tpl', $data);
                }
            }
			else if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured_category.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/featured_category.tpl', $data);
			} else {
				return $this->load->view('default/template/module/featured_category.tpl', $data);
			}
		}
	}
}
