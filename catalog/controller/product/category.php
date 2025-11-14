<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		// Handle both path and category_id parameters (SEO URLs may use category_id)
		if (isset($this->request->get['category_id'])) {
			$category_id = (int)$this->request->get['category_id'];
			$path = (string)$category_id;
		} elseif (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
			
			// Set path for breadcrumbs
			if (isset($this->request->get['path'])) {
				$path = $this->request->get['path'];
			}
		} else {
			$category_id = 0;
			$path = '';
		}

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->document->setTitle($category_info['meta_title']);
			$this->document->setDescription($category_info['meta_description']);
			$this->document->setKeywords($category_info['meta_keyword']);

			$data['heading_title'] = $category_info['name'];

			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

			// Set the last category breadcrumb
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			// Build path for breadcrumb
			$breadcrumb_path = '';
			if (isset($this->request->get['path'])) {
				$breadcrumb_path = 'path=' . $this->request->get['path'];
			} elseif (isset($this->request->get['category_id'])) {
				$breadcrumb_path = 'category_id=' . $category_id;
			}
			
			$data['breadcrumbs'][] = array(
				'text' => $category_info['name'],
				'href' => $this->url->link('product/category', $breadcrumb_path . $url)
			);

			if ($category_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['categories'] = array();

			$results = $this->model_catalog_category->getCategories($category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_category_id'  => $result['category_id'],
					'filter_sub_category' => true
				);

				// Build path for subcategory link
				$subcategory_path = '';
				if (isset($this->request->get['path'])) {
					$subcategory_path = 'path=' . $this->request->get['path'] . '_' . $result['category_id'];
				} elseif (isset($this->request->get['category_id'])) {
					$subcategory_path = 'path=' . $category_id . '_' . $result['category_id'];
				}
				
				$data['categories'][] = array(
					'name' => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
					'href' => $this->url->link('product/category', $subcategory_path . $url)
				);
			}

			$data['products'] = array();

			$filter_data = array(
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

				// Check if purchase should be disabled (out of stock)
				$disablePurchase = false;
				$stock_status = isset($result['stock_status']) ? $result['stock_status'] : '';
				$restock_request_btn = isset($result['restock_request_btn']) ? $result['restock_request_btn'] : '';
				
				if (isset($result['quantity']) && $result['quantity'] <= 0 && $stock_status != "In Stock") {
					$disablePurchase = true;
				}

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'disablePurchase' => $disablePurchase,
					'stock_status' => $stock_status,
					'restock_request_btn' => $restock_request_btn,
					'href'        => $this->url->link('product/product', (isset($this->request->get['path']) ? 'path=' . $this->request->get['path'] . '&' : (isset($this->request->get['category_id']) ? 'category_id=' . $category_id . '&' : '')) . 'product_id=' . $result['product_id'] . $url)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			// Build base path for sort/limit URLs
			$base_path = '';
			if (isset($this->request->get['path'])) {
				$base_path = 'path=' . $this->request->get['path'];
			} elseif (isset($this->request->get['category_id'])) {
				$base_path = 'category_id=' . $category_id;
			}
			
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', $base_path . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', $base_path . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', $base_path . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', $base_path . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', $base_path . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			// http://www.google.com/webmasters/tools/mobile-friendly/
			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			// Language variables for template
			$data['text_limit'] = $this->language->get('text_limit');
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_pagination'] = $this->language->get('text_pagination');
			$data['text_default'] = $this->language->get('text_default');
			$data['text_name_asc'] = $this->language->get('text_name_asc');
			$data['text_name_desc'] = $this->language->get('text_name_desc');
			$data['text_price_asc'] = $this->language->get('text_price_asc');
			$data['text_price_desc'] = $this->language->get('text_price_desc');
			$data['text_rating_asc'] = $this->language->get('text_rating_asc');
			$data['text_rating_desc'] = $this->language->get('text_rating_desc');
			$data['text_model_asc'] = $this->language->get('text_model_asc');
			$data['text_model_desc'] = $this->language->get('text_model_desc');

			$data['continue'] = $this->url->link('common/home');

			// Load category modules
			$data['category_modules'] = array();
			$category_modules = $this->model_catalog_category->getCategoryModules($category_id);
			
			if (!empty($category_modules)) {
				// Load extension module model if needed
				$this->load->model('extension/module');
				
				foreach ($category_modules as $module) {
					$module_output = '';
					$module_description = isset($module['description']) ? $module['description'] : '';
					$module_code = isset($module['code']) ? $module['code'] : '';
					
					// Get module settings
					$module_setting = array();
					
					// If module_id is set and > 0, load full settings from module table
					if (isset($module['module_id']) && (int)$module['module_id'] > 0) {
						$module_setting = $this->model_extension_module->getModule($module['module_id']);
						// Merge with category_module settings (category_module settings take precedence)
						if (isset($module['setting']) && is_array($module['setting']) && !empty($module['setting'])) {
							$module_setting = array_merge($module_setting, $module['setting']);
						}
					} else {
						// Use settings directly from category_module
						$module_setting = isset($module['setting']) && is_array($module['setting']) ? $module['setting'] : array();
					}
					
					// Ensure setting is an array
					if (!is_array($module_setting)) {
						$module_setting = array();
					}
					
					// Try to load module from extension/module first, then module/
					$extension_path = DIR_APPLICATION . '../catalog/controller/extension/module/' . $module_code . '.php';
					$module_path = DIR_APPLICATION . '../catalog/controller/module/' . $module_code . '.php';
					
					try {
						if (file_exists($extension_path)) {
							$module_output = $this->load->controller('extension/module/' . $module_code, $module_setting);
						} elseif (file_exists($module_path)) {
							$module_output = $this->load->controller('module/' . $module_code, $module_setting);
						} else {
							// Log missing controller file
							error_log('Category module controller not found: ' . $module_code . ' (checked: ' . $extension_path . ' and ' . $module_path . ')');
						}
					} catch (Exception $e) {
						// Log error but don't break the page
						error_log('Error loading category module ' . $module_code . ': ' . $e->getMessage());
						$module_output = '';
					} catch (Error $e) {
						// Catch fatal errors too
						error_log('Fatal error loading category module ' . $module_code . ': ' . $e->getMessage());
						$module_output = '';
					}
					
					// Add module even if output is empty (for debugging)
					$data['category_modules'][] = array(
						'output' => $module_output,
						'description' => $module_description,
						'code' => $module_code
					);
				}
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/category.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/product/category.tpl', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view('error/not_found', $data));
			} else {
				// Fallback if error template doesn't exist
				$data['text_error'] = $this->language->get('text_error');
				$data['button_continue'] = $this->language->get('button_continue');
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
}

