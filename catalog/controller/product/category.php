<?php
class ControllerProductCategory extends Controller {
	public function index() {
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['search'])) {
			$filter_name = $this->request->get['search'];
		} else {
			$filter_name = '';
		}

        if (isset($this->request->get['filter'])) {
            $filter = $this->request->get['filter'];
        } else {
            $filter = '';
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = '';
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
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = (int)$this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = 0;
        }

        if (isset($this->request->get['category_id'])) {
            $category_id = (int)$this->request->get['category_id'];
        } else {
            $category_id = null;
        }

        if($category_id) {
            $path = $this->model_catalog_category->getCategoryPath($category_id);
        } else {
            $path = null;
        }

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

		if ($path) {
			$parts = explode('_', $path);
			foreach ($parts as $path_id) {
				$category_info = $this->model_catalog_category->getCategory($path_id);
				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'category_id=' . $path_id . $url)
					);
				}
			}
		}

        if ($manufacturer_id) {
            $this->load->model('catalog/category_manufacturer');
            $page_info = $this->model_catalog_category_manufacturer->getCategoryManufacturer($category_id, $manufacturer_id);
        } else {
            $page_info = $this->model_catalog_category->getCategory($category_id);
        }

		if ($page_info) {
            $url = 'category_id=' . $category_id;
            if($manufacturer_id) {
                $url .= "&manufacturer_id=" . $manufacturer_id;
                $data['breadcrumbs'][] = array(
                    'text' => $page_info['name'],
                    'href' => $this->url->link('product/category', $url)
                );
            }

            $this->request->get['filter_profile_id'] = $page_info['filter_profile_id'];

			$this->document->setTitle($page_info['meta_title']);
			$this->document->setDescription($page_info['meta_description']);
			$this->document->setKeywords($page_info['meta_keyword']);
            $canonical = $this->url->link('product/category', $url);


			$data['heading_title'] = $page_info['name'];
			$data['blurb'] = $page_info['blurb'];

			$data['text_refine'] = $this->language->get('text_refine');
			$data['text_empty'] = $this->language->get('text_empty');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$data['text_sort'] = $this->language->get('text_sort');
			$data['text_limit'] = $this->language->get('text_limit');

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_list'] = $this->language->get('button_list');
			$data['button_grid'] = $this->language->get('button_grid');

			if ($page_info['image']) {
				$data['image'] = $this->model_tool_image->resize($page_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$data['image'] = '';
			}
            $data['intro'] = html_entity_decode($page_info['intro'], ENT_QUOTES, 'UTF-8');

			$data['description'] = html_entity_decode($page_info['description'], ENT_QUOTES, 'UTF-8');
			$data['compare'] = $this->url->link('product/compare');

            $data['children'] = [];
			$children = $this->model_catalog_category->getChildren($category_id);

			foreach ($children as $child) {
			    if($child['thumb']) {
			        $thumb = $this->model_tool_image->resize($child['thumb'], 80, 80);
                } else {
			        $thumb = $this->model_tool_image->resize("placeholder.png", 80, 80);
                }
                $data['children'][] = array(
                    'name' => $child['name'],
                    'href' => $child['href'],
                    'thumb' => $thumb
                );
            }

			$data['products'] = array();

			$filter_data = array(
                'filter_manufacturer_id' => $manufacturer_id,
				'filter_category_id' => $category_id,
				'filter_filter'      => $filter,
				'filter_name'      => $filter_name,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);

            if($filter_price) {
                $filter_price = explode("-", $filter_price);
                $filter_data['filter_price_from'] = (float) $filter_price[0];
                $filter_data['filter_price_to'] = isset($filter_price[1]) ? (float) $filter_price[1] : null;
            }

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				if ($result['featured_image']) {
					$featured_image = $this->model_tool_image->resize($result['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));
				} else {
					$featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));
				}

			// Standard product image size for premium consistent display
			$image_width = 500;
			$image_height = 500;
			
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $image_width, $image_height);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
			}

				$disablePurchase = false;
				if ($result['quantity'] <= 0 && $result['stock_status'] != "In Stock") {
					$disablePurchase = true;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

                if ($result['regular_price'] > 0 && (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price'))) {
                    $regular_price = $this->currency->format($this->tax->calculate($result['regular_price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $regular_price = false;
                }

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                    $mark = "Save: " . $this->currency->format($this->tax->calculate($result['price'] - $result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
                    $mark = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

                if($result['manufacturer_thumb']) {
                    $manufacturer_thumb = $this->config->get('config_ssl') . '/image/' . $result['manufacturer_thumb'];
                } else {
                    $manufacturer_thumb = null;
                }

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'featured_image'    => $featured_image,
					'name'        => $result['name'],
					'sub_name'        => $result['sub_name'],
					'manufacturer'        => $result['manufacturer'],
					'manufacturer_thumb'        => $manufacturer_thumb,
					'short_description' => $result['short_description'],
					'price'       => $price,
                    'regular_price'       => $regular_price,
					'mark'       => $mark,
					'disablePurchase' => $disablePurchase,
                    'stock_status' => $result['stock_status'],
                    'restock_request_btn' => $result['restock_request_btn'],
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            if($manufacturer_id) {
                $url .= "&manufacturer_id=" . $manufacturer_id;
            }

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/category', 'category_id=' . $category_id . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

            if($manufacturer_id) {
                $url .= "&manufacturer_id=" . $manufacturer_id;
            }

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('config_product_limit'), 24, 48, 75, 90));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/category', 'category_id=' . $category_id . $url . '&limit=' . $value)
				);
			}

			$url = '';

            if (isset($this->request->get['search'])) {
                $url .= '&filter=' . $this->request->get['search'];
            }

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
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

            if($url) {
                $this->document->addLink($canonical, 'canonical');
            }

            if($manufacturer_id) {
                $url .= "&manufacturer_id=" . $manufacturer_id;
            }

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('product/category', 'category_id=' . $category_id . $url . '&page={page}');
            $data['pagination'] = $pagination->render();

            $prev = $pagination->prev();
            if($prev) {
                $this->document->addLink($prev, 'prev');
            }

            $next = $pagination->next();
            if($next) {
                $this->document->addLink($next, 'next');
            }



            $prev = $pagination->prev();
            if($prev) {
                $this->document->addLink($prev, 'prev');
            }

            $next = $pagination->next();
            if($next) {
                $this->document->addLink($next, 'next');
            }



			$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');

			// Load category modules
			$data['category_modules'] = array();
			if ($category_id) {
				$category_modules = $this->model_catalog_category->getCategoryModules($category_id);
				foreach ($category_modules as $module) {
					if ($module['status']) {
						// Try to load module controller
						$module_output = '';
						$module_code = $module['code'];
						$module_setting = $module['setting'];
						
						// Try extension/module path first
						if (file_exists(DIR_APPLICATION . 'controller/extension/module/' . $module_code . '.php')) {
							$module_output = $this->load->controller('extension/module/' . $module_code, $module_setting);
						} 
						// Try module path
						elseif (file_exists(DIR_APPLICATION . 'controller/module/' . $module_code . '.php')) {
							$module_output = $this->load->controller('module/' . $module_code, $module_setting);
						}
						
						if ($module_output) {
							$data['category_modules'][] = array(
								'output' => $module_output,
								'sort_order' => $module['sort_order']
							);
						}
					}
				}
				
				// Sort modules by sort_order
				usort($data['category_modules'], function($a, $b) {
					return $a['sort_order'] - $b['sort_order'];
				});
			}

			$data['after_header'] = $this->load->controller('common/after_header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

            if (isset($page_info['view']) && $page_info['view'] && file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category_' . $page_info['view'] . '.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/category_' . $page_info['view'] . '.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/category.tpl', $data));
            }
		} else {
			$url = '';

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
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
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
}