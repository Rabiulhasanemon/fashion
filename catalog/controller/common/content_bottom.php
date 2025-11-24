<?php
class ControllerCommonContentBottom extends Controller {
	public function index() {
		$this->load->model('design/layout');
        $this->load->language('product/category');

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
        }elseif ($route == 'blog/category' && isset($this->request->get['blog_category_id'])) {
            $this->load->model('blog/category');
            $layout_id = $this->model_blog_category->getCategoryLayoutId($this->request->get['blog_category_id']);
        } elseif ($route == 'blog/article' && isset($this->request->get['article_id'])) {
            $this->load->model('blog/article');
            $layout_id = $this->model_blog_article->getArticleLayoutId($this->request->get['article_id']);
        }

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$this->load->model('extension/module');

		$data['modules'] = array();

		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_bottom');

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);

			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				$data['modules'][] = $this->load->controller('module/' . $part[0]);
			}

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
			}
		}

        $data['home_view_all_enabled'] = ($route === 'common/home');
        $data['home_view_all_link'] = $data['home_view_all_enabled'] ? $this->url->link('product/category', 'view_all=1') : '';
        $data['home_view_all_label'] = $this->language->get('text_all_products');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/content_bottom.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/content_bottom.tpl', $data);
		} else {
			return $this->load->view('default/template/common/content_bottom.tpl', $data);
		}
	}
}