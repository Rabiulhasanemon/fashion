<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['metas'] = $this->document->getMetas();
		$data['styles'] = $this->document->getStyles();
		$data['synScripts'] = $this->document->getSynScripts();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
        $data['date'] = $this->date->getDate(time());
        $data['en_date'] = $this->date->translate(time(), 'd F Y');

		if ($this->config->get('config_google_analytics_status')) {
			$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['google_analytics'] = '';
		}

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . '/image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$compare_count = (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0);

		$data['text_account'] = $this->language->get('text_account');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), $compare_count);
        $data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts());
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');

        $data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/onepagecheckout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
        $data['email'] = $this->config->get("config_email");
        $data['manufacturer'] = $this->url->link('brand');
        $data['pc_builder'] = $this->url->link('tool/pc_builder');
        $data['compare'] = $this->url->link('product/compare');
        $data['compare_count'] = $compare_count;
        $data['item_count'] = $this->cart->countProducts();
        $data['flash_sale_url'] = $this->url->link('product/special');

        // Big Offer Button (from module settings)
        $data['big_offer'] = array();
        if ($this->config->get('big_offer_status')) {
            $button_text = $this->config->get('big_offer_button_text') ? $this->config->get('big_offer_button_text') : 'Offer';
            $button_icon = $this->config->get('big_offer_button_icon') ? $this->config->get('big_offer_button_icon') : 'local_offer';
            // Build SEO-friendly link via url->link and seo_url rewrite
            $data['big_offer'] = array(
                'text' => $button_text,
                'icon' => $button_icon,
                'href' => $this->url->link('common/big_offer')
            );
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data["error"] = "";
        }

        $status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}



		// Menu - NEW NAVIGATION SYSTEM
		// Always clear cache to ensure fresh categories
		if (method_exists($this->cacheManger, 'deleteCache')) {
			$this->cacheManger->deleteCache("html", "main_nav");
		}
		
		// Initialize navigation data
		$navViewData = array();
		$navViewData['text_category'] = $this->language->get('text_category');
		$navViewData['text_all'] = $this->language->get('text_all');
		$navViewData['domain'] = $this->config->get('config_url');
		$navViewData['item_count'] = $data['item_count'];
		$navViewData['categories'] = array();
		$navViewData['logo'] = $data['logo'];
		$navViewData['name'] = $this->config->get('config_name');
		$navViewData['navigations'] = array();
		$navViewData['flash_sale_url'] = isset($data['flash_sale_url']) ? $data['flash_sale_url'] : '';

		if($this->config->get('config_navigation_type') === 'navigation') {
			$this->load->model('catalog/navigation');
			$navigations = $this->model_catalog_navigation->getNavigations(0);
			foreach ($navigations as $navigation) {
				if ($navigation['top']) {
					// Level 2
					$children_data_1 = array();

					$children_level_1 = $this->model_catalog_navigation->getNavigations($navigation['navigation_id']);

					foreach ($children_level_1 as $child) {
						if(!$child['top']) { continue; }
						$children_level_2 = $this->model_catalog_navigation->getNavigations($child['navigation_id']);
						//Level 3
						$children_data_2 = array();
						foreach ($children_level_2 as $child_2) {
							if(!$child_2['top']) { continue; }

							$children_level_3 = $this->model_catalog_navigation->getNavigations($child_2['navigation_id']);
							//Level 4
							$children_data_3 = array();

							foreach ($children_level_3 as $child_3) {
								if(!$child_3['top']) { continue; }

								$children_data_3[] = array(
									'name'  => $child_3['name'],
									'href'  => $child_3['url'],
								);

							}

							$children_data_2[] = array(
								'name'  => $child_2['name'],
								'href'  => $child_2['url'],
								'icon'  => $child_2['image'] ? $this->config->get('config_ssl') . '/image/' . $child_2['image'] : "",
								'children' => $children_data_3
							);

						}


						$children_data_1[] = array(
							'name'  => $child['name'],
							'href'  => $child['url'],
							'children' => $children_data_2
						);
					}


					// Level 1
					$navViewData['navigations'][] = array(
						'name'     => $navigation['name'],
						'icon'     => $navigation['image'] ? $this->config->get('config_ssl') . '/image/' . $navigation['image'] : "",
						'children' => $children_data_1,
						'column'   => $navigation['column'] ? $navigation['column'] : 1,
						'href'     => $navigation['url'],
					);
				}
			}

		} else {
			$this->load->model('catalog/category');
			$this->load->model('blog/category');
			$this->load->model('catalog/category_manufacturer');
			$this->load->model('catalog/product');

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

							$children_level_3 = $this->model_catalog_category->getCategories($child_2['category_id']);
							//Level 4
							$children_data_3 = array();

							foreach ($children_level_3 as $child_3) {
								if(!$child_3['top']) { continue; }
								$filter_data = array(
									'filter_category_id'  => $child_3['category_id'],
									'filter_sub_category' => true
								);

								$children_data_3[] = array(
									'name'  => $child_3['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
									'href'  => $this->url->link('product/category', 'category_id=' . $child_3['category_id'])
								);

							}
							$filter_data = array(
								'filter_category_id'  => $child_2['category_id'],
								'filter_sub_category' => true
							);

							$children_data_2[] = array(
								'name'  => $child_2['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
								'icon'  => $child_2['icon'] ? $this->config->get('config_ssl') . '/image/' . $child_2['icon'] : "",
								'href'  => $this->url->link('product/category', 'category_id=' . $child_2['category_id']),
								'children' => $children_data_3
							);

						}
						if(!$children_level_2) {
							$brands_level_2 = $this->model_catalog_category_manufacturer->getCategoryManufacturers(array('filter_category_id' => $child['category_id']));
							foreach ($brands_level_2 as $child_2) {
								if(!$child_2['top']) { continue; }
								$children_data_2[] = array(
									'name'  => $child_2['manufacturer_name'],
									'icon'  => $child_2['icon'] ? $this->config->get('config_ssl') . '/image/' . $child_2['icon'] : "",
									'href'  => $this->url->link('product/category', 'category_id=' . $child['category_id'] . '&manufacturer_id=' . $child_2['manufacturer_id']),
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
							'href'  => $this->url->link('product/category', 'category_id=' . $child['category_id']),
							'children' => $children_data_2
						);
					}

					if(!$children_level_1) {
						$brands_level_1 = $this->model_catalog_category_manufacturer->getCategoryManufacturers(array('filter_category_id' => $category['category_id']));
						foreach ($brands_level_1 as $child) {
							if(!$child['top']) { continue; }
							$children_data_1[] = array(
								'name'  => $child['manufacturer_name'],
								'href'  => $this->url->link('product/category', 'category_id=' . $category['category_id'] . '&manufacturer_id=' . $child['manufacturer_id']),
								'children' => array()
							);
						}
					}

					// Level 1
					$navViewData['categories'][] = array(
						'name'     => $category['name'],
						'icon'     => $category['icon'] ? $this->config->get('config_ssl') . '/image/' . $category['icon'] : "",
						'children' => $children_data_1,
						'column'   => $category['column'] ? $category['column'] : 1,
						'href'     => $this->url->link('product/category', 'category_id=' . $category['category_id'])
					);
				}
			}

			$navViewData['manufacturers'] = array();

			if ($this->config->get('config_include_brand_navigation')) {
				$this->load->model('catalog/manufacturer');
				$manufacturers = $this->model_catalog_manufacturer->getManufacturers();
				foreach ($manufacturers as $manufacturer) {
					$navViewData['manufacturers'][] = array(
						'name'     => $manufacturer['name'],
						'sort_order'     => $manufacturer['sort_order'],
						'href'     => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id'])
					);
				}
			}

			$categories = $this->model_blog_category->getCategories(0);
			foreach ($categories as $category) {
				if(!$category['top']) continue;

				$children_data_1 = array();

				$children_level_1 = $this->model_blog_category->getCategories($category['category_id']);

				foreach ($children_level_1 as $child) {
					if(!$child['top']) continue;

					$children_level_2 = $this->model_blog_category->getCategories($child['category_id']);
					//Level 3
					$children_data_2 = array();
					foreach ($children_level_2 as $child_2) {
						if(!$child_2['top']) continue;

						$children_data_2[] = array(
							'name'  => $child_2['name'],
							'href'  => $this->url->link('blog/category', 'blog_category_id=' .$child_2['category_id'])
						);

					}

					$children_data_1[] = array(
						'name'  => $child['name'],
						'href'  => $this->url->link('blog/category', 'blog_category_id=' . $child['category_id']),
						'children' => $children_data_2
					);
				}


				// Level 1
				$navViewData['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data_1,
					'href'     => $this->url->link('blog/category', 'blog_category_id=' . $category['category_id'])
				);
			}
		}

		$navigation = $this->load->view($this->config->get('config_template') . '/template/common/navigation.tpl', $navViewData);
		$this->cacheManger->setCache("html", "main_nav", $navigation);
		$data['navigation'] = $navigation;

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['category_id'])) {
				$class = '-' . $this->request->get['category_id'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header-dev.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header-dev.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}

	public function search() {
		$this->response->setOutput($this->load->controller('common/search'));
	}
}
