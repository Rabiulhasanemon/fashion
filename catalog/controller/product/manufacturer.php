<?php
class ControllerProductManufacturer extends Controller {
	public function index() {
		$this->load->language('product/manufacturer');

		$this->load->model('catalog/manufacturer');

		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_index'] = $this->language->get('text_index');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_brand'),
			'href' => $this->url->link('product/manufacturer')
		);

		$data['categories'] = array();

		$results = $this->model_catalog_manufacturer->getManufacturers();

		if ($results) {
			foreach ($results as $result) {
				if (!isset($result['name']) || empty($result['name'])) {
					continue;
				}
				
				if (is_numeric(utf8_substr($result['name'], 0, 1))) {
					$key = '0 - 9';
				} else {
					$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
				}

				if (!isset($data['categories'][$key])) {
					$data['categories'][$key]['name'] = $key;
					$data['categories'][$key]['manufacturer'] = array();
				}

				if (isset($result['image']) && $result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 170, 170);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 170, 170);
				}

				$manufacturer_id = isset($result['manufacturer_id']) ? $result['manufacturer_id'] : 0;
				
				$data['categories'][$key]['manufacturer'][] = array(
					'name' => $result['name'],
					'image' => $image,
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id)
				);
			}
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/manufacturer_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/product/manufacturer_list.tpl', $data));
		}
	}

	public function info() {
        $this->load->language('product/manufacturer');

        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('tool/image');
        
        // Load category_manufacturer model only if it exists (optional)
        if (file_exists(DIR_APPLICATION . 'model/catalog/category_manufacturer.php')) {
            $this->load->model('catalog/category_manufacturer');
        }

        if (isset($this->request->get['manufacturer_id'])) {
            $manufacturer_id = (int)$this->request->get['manufacturer_id'];
        } else {
            $manufacturer_id = 0;
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
            $limit = (int)$this->config->get('config_product_limit');
            if ($limit <= 0) {
                $limit = 20; // Default fallback
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_brand'),
            'href' => $this->url->link('product/manufacturer')
        );

        $manufacturer_info = false;
        if ($manufacturer_id > 0) {
            try {
                $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
                // Check if manufacturer exists
                if ($manufacturer_info && !empty($manufacturer_info['manufacturer_id'])) {
                    // Optional: Check if manufacturer is assigned to this store
                    // Comment out the next 3 lines if you want to show manufacturers even without store assignment
                    if (isset($manufacturer_info['store_assigned']) && !$manufacturer_info['store_assigned']) {
                        // Manufacturer exists but not assigned to this store - you can choose to show it or not
                        // For now, we'll allow it to show for debugging
                        // $manufacturer_info = false;
                    }
                } else {
                    $manufacturer_info = false;
                }
            } catch (Exception $e) {
                $manufacturer_info = false;
            } catch (Error $e) {
                $manufacturer_info = false;
            }
        }

		if ($manufacturer_info && !empty($manufacturer_info['manufacturer_id'])) {
			$this->document->setTitle(isset($manufacturer_info['meta_title']) ? $manufacturer_info['meta_title'] : '');
            $this->document->setDescription(isset($manufacturer_info['meta_description']) ? $manufacturer_info['meta_description'] : '');
            $this->document->setKeywords(isset($manufacturer_info['meta_keyword']) ? $manufacturer_info['meta_keyword'] : '');

			$url = '';

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
				'text' => isset($manufacturer_info['name']) ? $manufacturer_info['name'] : '',
				'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id . $url)
			);

			$data['heading_title'] = isset($manufacturer_info['name']) ? $manufacturer_info['name'] : '';

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

			$data['compare'] = $this->url->link('product/compare');
            $data['description'] = isset($manufacturer_info['description']) ? html_entity_decode($manufacturer_info['description'], ENT_QUOTES, 'UTF-8') : '';
            
            // Manufacturer image
            if (isset($manufacturer_info['image']) && $manufacturer_info['image']) {
                $data['manufacturer_info'] = array(
                    'image' => $this->model_tool_image->resize($manufacturer_info['image'], 200, 200),
                    'name' => isset($manufacturer_info['name']) ? $manufacturer_info['name'] : ''
                );
            } else {
                $data['manufacturer_info'] = null;
            }
            
			$data['products'] = array();
			$data['sorts'] = array();
			$data['limits'] = array();

			$filter_data = array(
				'filter_manufacturer_id' => $manufacturer_id,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
			);

			$product_total = 0;
			$results = array();
			
			try {
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				$product_total = $product_total ? (int)$product_total : 0;

				$results = $this->model_catalog_product->getProducts($filter_data);
				if (!is_array($results)) {
					$results = array();
				}
			} catch (Exception $e) {
				$product_total = 0;
				$results = array();
			}

            foreach ($results as $result) {
				// Standard product image size for premium consistent display
				$image_width = 500;
				$image_height = 500;
				
                if (isset($result['image']) && $result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $image_width, $image_height);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
                }
                if (isset($result['featured_image']) && $result['featured_image']) {
                    $featured_width = (int)$this->config->get('config_featured_image_width');
                    $featured_height = (int)$this->config->get('config_featured_image_height');
                    if ($featured_width <= 0) $featured_width = 500;
                    if ($featured_height <= 0) $featured_height = 500;
                    $featured_image = $this->model_tool_image->resize($result['featured_image'], $featured_width, $featured_height);
                } else {
                    $featured_width = (int)$this->config->get('config_featured_image_width');
                    $featured_height = (int)$this->config->get('config_featured_image_height');
                    if ($featured_width <= 0) $featured_width = 500;
                    if ($featured_height <= 0) $featured_height = 500;
                    $featured_image = $this->model_tool_image->resize('placeholder.png', $featured_width, $featured_height);
                }

                $disablePurchase = false;
                if (isset($result['quantity']) && isset($result['stock_status']) && $result['quantity'] <= 0 && $result['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $tax_class_id = isset($result['tax_class_id']) ? $result['tax_class_id'] : 0;
                    $product_price = isset($result['price']) ? $result['price'] : 0;
                    $price = $this->currency->format($this->tax->calculate($product_price, $tax_class_id, $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if (isset($result['special']) && (float)$result['special']) {
                    $tax_class_id = isset($result['tax_class_id']) ? $result['tax_class_id'] : 0;
                    $special = $this->currency->format($this->tax->calculate($result['special'], $tax_class_id, $this->config->get('config_tax')));
                } else {
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $special_price = isset($result['special']) ? $result['special'] : 0;
                    $product_price = isset($result['price']) ? $result['price'] : 0;
                    $tax = $this->currency->format((float)$special_price ? $special_price : $product_price);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = isset($result['rating']) ? (int)$result['rating'] : 0;
                    $reviews = isset($result['reviews']) ? (int)$result['reviews'] : 0;
                } else {
                    $rating = false;
                    $reviews = 0;
                }

                if(isset($result['manufacturer_thumb']) && $result['manufacturer_thumb']) {
                    $manufacturer_thumb = $this->config->get('config_ssl') . '/image/' . $result['manufacturer_thumb'];
                } else {
                    $manufacturer_thumb = null;
                }
                $data['products'][] = array(
                    'product_id'  => isset($result['product_id']) ? $result['product_id'] : 0,
                    'thumb'       => $image,
                    'featured_image'   => $featured_image,
                    'manufacturer' => isset($result['manufacturer']) ? $result['manufacturer'] : '',
                    'manufacturer_thumb'       => $manufacturer_thumb,
                    'name'        => isset($result['name']) ? $result['name'] : '',
                    'short_description' => isset($result['short_description']) ? $result['short_description'] : '',
                    'price'       => $price,
                    'disablePurchase' => $disablePurchase,
                    'stock_status' => isset($result['stock_status']) ? $result['stock_status'] : '',
                    'special'     => $special,
                    'tax'         => $tax,
                    'minimum'     => (isset($result['minimum']) && $result['minimum'] > 0) ? $result['minimum'] : 1,
                    'rating'      => $rating,
                    'reviews'     => $reviews,
                    'href'        => $this->url->link('product/product', 'product_id=' . (isset($result['product_id']) ? $result['product_id'] : 0))
                );
            }

			$url = '';

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_id_param = isset($this->request->get['manufacturer_id']) ? $this->request->get['manufacturer_id'] : $manufacturer_id;

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=p.sort_order&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=pd.name&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=pd.name&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=p.price&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=p.price&order=DESC' . $url)
			);

			if ($this->config->get('config_review_status')) {
				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=rating&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=rating&order=ASC' . $url)
				);
			}

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=p.model&order=ASC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . '&sort=p.model&order=DESC' . $url)
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$config_limit = (int)$this->config->get('config_product_limit');
			if ($config_limit <= 0) {
				$config_limit = 20; // Default fallback
			}
			$limits = array_unique(array($config_limit, 25, 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param . $url . '&limit=' . $value)
				);
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

            if($url) {
                $this->document->addLink($this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id), 'canonical');
            }

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = max(1, (int)$page);
			$pagination->limit = max(1, (int)$limit);
			$pagination->url = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer_id_param .  $url . '&page={page}');

			$data['pagination'] = $pagination->render();

            $prev = $pagination->prev();
            if($prev) {
                $this->document->addLink($prev, 'prev');
            }

            $next = $pagination->next();
            if($next) {
                $this->document->addLink($next, 'next');
            }

			$start = ($product_total > 0) ? (($page - 1) * $limit) + 1 : 0;
			$end = (($page - 1) * $limit) > ($product_total - $limit) ? $product_total : ((($page - 1) * $limit) + $limit);
			$total_pages = $product_total > 0 ? ceil($product_total / $limit) : 0;
			$data['results'] = sprintf($this->language->get('text_pagination'), $start, $end, $product_total, $total_pages);

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

			$data['continue'] = $this->url->link('common/home');
            
            // Initialize all required variables
            if (!isset($data['pagination'])) {
                $data['pagination'] = '';
            }
            if (!isset($data['results'])) {
                $data['results'] = '';
            }
            if (!isset($data['products'])) {
                $data['products'] = array();
            }
            if (!isset($data['sorts'])) {
                $data['sorts'] = array();
            }
            if (!isset($data['limits'])) {
                $data['limits'] = array();
            }
            
            try {
                $data['after_header'] = $this->load->controller('common/after_header');
            } catch (Exception $e) {
                $data['after_header'] = '';
            }
            
			try {
				$data['column_left'] = $this->load->controller('common/column_left');
			} catch (Exception $e) {
				$data['column_left'] = '';
			}
			
			try {
				$data['column_right'] = $this->load->controller('common/column_right');
			} catch (Exception $e) {
				$data['column_right'] = '';
			}
			
			try {
				$data['content_top'] = $this->load->controller('common/content_top');
			} catch (Exception $e) {
				$data['content_top'] = '';
			}
			
			try {
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
			} catch (Exception $e) {
				$data['content_bottom'] = '';
			}
			
			try {
				$data['footer'] = $this->load->controller('common/footer');
			} catch (Exception $e) {
				$data['footer'] = '';
			}
			
			try {
				$data['header'] = $this->load->controller('common/header');
			} catch (Exception $e) {
				$data['header'] = '';
			}

			$template_file = DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/manufacturer_info.tpl';
			if (file_exists($template_file)) {
				try {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/manufacturer_info.tpl', $data));
				} catch (Exception $e) {
					// Fallback to default template
					$this->response->setOutput($this->load->view('default/template/product/manufacturer_info.tpl', $data));
				}
			} else {
				$this->response->setOutput($this->load->view('default/template/product/manufacturer_info.tpl', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
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
				'href' => $this->url->link('product/manufacturer/info', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}

	public function debug() {
		// Set content type to HTML for debugging
		header('Content-Type: text/html; charset=utf-8');
		
		// Start output buffering
		ob_start();
		
		echo "<!DOCTYPE html><html><head><title>Manufacturer Debug Page</title>";
		echo "<style>
			body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
			.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
			h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
			h2 { color: #007bff; margin-top: 30px; border-left: 4px solid #007bff; padding-left: 10px; }
			.debug-section { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; border: 1px solid #dee2e6; }
			.debug-item { margin: 10px 0; padding: 10px; background: white; border-left: 3px solid #28a745; }
			.error { border-left-color: #dc3545; background: #fff5f5; }
			.warning { border-left-color: #ffc107; background: #fffbf0; }
			.success { border-left-color: #28a745; background: #f0fff4; }
			pre { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 12px; }
			table { width: 100%; border-collapse: collapse; margin: 10px 0; }
			table th, table td { padding: 10px; text-align: left; border: 1px solid #dee2e6; }
			table th { background: #007bff; color: white; }
			table tr:nth-child(even) { background: #f8f9fa; }
			.badge { display: inline-block; padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
			.badge-success { background: #28a745; color: white; }
			.badge-danger { background: #dc3545; color: white; }
			.badge-warning { background: #ffc107; color: #333; }
			.badge-info { background: #17a2b8; color: white; }
		</style></head><body>";
		echo "<div class='container'>";
		echo "<h1>🔍 Manufacturer Debug Page</h1>";
		
		// Get manufacturer_id from request
		$manufacturer_id = isset($this->request->get['manufacturer_id']) ? (int)$this->request->get['manufacturer_id'] : 0;
		
		echo "<div class='debug-section'>";
		echo "<h2>Request Parameters</h2>";
		echo "<div class='debug-item'>";
		echo "<strong>manufacturer_id:</strong> " . ($manufacturer_id > 0 ? $manufacturer_id : "Not provided") . "<br>";
		echo "<strong>GET Parameters:</strong><br>";
		echo "<pre>" . print_r($this->request->get, true) . "</pre>";
		echo "</div></div>";
		
		// Load required models
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');
		
		// Check manufacturer
		echo "<div class='debug-section'>";
		echo "<h2>Manufacturer Information</h2>";
		
		if ($manufacturer_id > 0) {
			try {
				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
				
				if ($manufacturer_info && !empty($manufacturer_info['manufacturer_id'])) {
					echo "<div class='debug-item success'>";
					echo "<span class='badge badge-success'>Manufacturer Found</span><br><br>";
					echo "<table>";
					foreach ($manufacturer_info as $key => $value) {
						echo "<tr><th>" . htmlspecialchars($key) . "</th><td>" . htmlspecialchars(print_r($value, true)) . "</td></tr>";
					}
					echo "</table>";
					echo "</div>";
				} else {
					echo "<div class='debug-item error'>";
					echo "<span class='badge badge-danger'>Manufacturer NOT Found</span><br>";
					echo "Manufacturer ID: " . $manufacturer_id . " does not exist in database.";
					echo "</div>";
				}
			} catch (Exception $e) {
				echo "<div class='debug-item error'>";
				echo "<span class='badge badge-danger'>Error Loading Manufacturer</span><br>";
				echo "Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
				echo "Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
				echo "</div>";
			}
		} else {
			echo "<div class='debug-item warning'>";
			echo "<span class='badge badge-warning'>No Manufacturer ID Provided</span>";
			echo "</div>";
		}
		echo "</div>";
		
		// Check products
		if ($manufacturer_id > 0) {
			echo "<div class='debug-section'>";
			echo "<h2>Product Query Debug</h2>";
			
			// Get filter data (same as in info method)
			$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'p.sort_order';
			$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'ASC';
			$page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
			$limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : (int)$this->config->get('config_product_limit');
			if ($limit <= 0) {
				$limit = 20;
			}
			
			$filter_data = array(
				'filter_manufacturer_id' => $manufacturer_id,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
			);
			
			echo "<div class='debug-item'>";
			echo "<strong>Filter Data:</strong><br>";
			echo "<pre>" . print_r($filter_data, true) . "</pre>";
			echo "</div>";
			
			// Check config values
			echo "<div class='debug-item'>";
			echo "<strong>Configuration Values:</strong><br>";
			echo "config_store_id: " . (int)$this->config->get('config_store_id') . "<br>";
			echo "config_language_id: " . (int)$this->config->get('config_language_id') . "<br>";
			echo "config_customer_group_id: " . (int)$this->config->get('config_customer_group_id') . "<br>";
			echo "config_product_limit: " . (int)$this->config->get('config_product_limit') . "<br>";
			echo "</div>";
			
			// Try to get total products
			echo "<div class='debug-item'>";
			echo "<strong>Total Products Query:</strong><br>";
			try {
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				$product_total = $product_total ? (int)$product_total : 0;
				
				if ($product_total > 0) {
					echo "<span class='badge badge-success'>Total Products Found: " . $product_total . "</span><br>";
				} else {
					echo "<span class='badge badge-warning'>Total Products: 0</span><br>";
					echo "No products found for manufacturer_id = " . $manufacturer_id . "<br>";
				}
			} catch (Exception $e) {
				echo "<span class='badge badge-danger'>Error in getTotalProducts</span><br>";
				echo "Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
				echo "Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
			}
			echo "</div>";
			
			// Try to get products
			echo "<div class='debug-item'>";
			echo "<strong>Products Query:</strong><br>";
			try {
				$results = $this->model_catalog_product->getProducts($filter_data);
				
				if (!is_array($results)) {
					$results = array();
				}
				
				$product_count = count($results);
				
				if ($product_count > 0) {
					echo "<span class='badge badge-success'>Products Retrieved: " . $product_count . "</span><br><br>";
					echo "<table>";
					echo "<tr><th>Product ID</th><th>Name</th><th>Status</th><th>Date Available</th><th>Manufacturer ID</th></tr>";
					$count = 0;
					foreach ($results as $product_id => $product) {
						if ($count >= 10) {
							echo "<tr><td colspan='5'><em>... showing first 10 products</em></td></tr>";
							break;
						}
						echo "<tr>";
						echo "<td>" . (isset($product['product_id']) ? $product['product_id'] : 'N/A') . "</td>";
						echo "<td>" . (isset($product['name']) ? htmlspecialchars($product['name']) : 'N/A') . "</td>";
						echo "<td>" . (isset($product['status']) ? $product['status'] : 'N/A') . "</td>";
						echo "<td>" . (isset($product['date_available']) ? $product['date_available'] : 'N/A') . "</td>";
						echo "<td>" . (isset($product['manufacturer_id']) ? $product['manufacturer_id'] : 'N/A') . "</td>";
						echo "</tr>";
						$count++;
					}
					echo "</table>";
				} else {
					echo "<span class='badge badge-warning'>No Products Retrieved</span><br>";
					echo "The getProducts() method returned an empty array.<br>";
				}
			} catch (Exception $e) {
				echo "<span class='badge badge-danger'>Error in getProducts</span><br>";
				echo "Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
				echo "Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
			}
			echo "</div>";
			
			// Direct database query check
			echo "<div class='debug-item'>";
			echo "<strong>Direct Database Query Check:</strong><br>";
			try {
				$store_id = (int)$this->config->get('config_store_id');
				$language_id = (int)$this->config->get('config_language_id');
				
				// Check products directly
				$sql = "SELECT COUNT(DISTINCT p.product_id) AS total 
						FROM " . DB_PREFIX . "product p 
						LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
						LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
						WHERE pd.language_id = '" . $language_id . "' 
						AND p.status = '1' 
						AND p.date_available <= NOW() 
						AND p2s.store_id = '" . $store_id . "'
						AND p.manufacturer_id = '" . $manufacturer_id . "'";
				
				echo "<strong>SQL Query:</strong><br>";
				echo "<pre>" . htmlspecialchars($sql) . "</pre>";
				
				$query = $this->db->query($sql);
				$direct_count = isset($query->row['total']) ? (int)$query->row['total'] : 0;
				
				if ($direct_count > 0) {
					echo "<span class='badge badge-success'>Direct Query Result: " . $direct_count . " products</span><br>";
					
					// Get sample products
					$sql_samples = "SELECT p.product_id, pd.name, p.status, p.date_available, p.manufacturer_id 
									FROM " . DB_PREFIX . "product p 
									LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
									LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
									WHERE pd.language_id = '" . $language_id . "' 
									AND p.status = '1' 
									AND p.date_available <= NOW() 
									AND p2s.store_id = '" . $store_id . "'
									AND p.manufacturer_id = '" . $manufacturer_id . "'
									LIMIT 10";
					
					$query_samples = $this->db->query($sql_samples);
					
					if ($query_samples->num_rows > 0) {
						echo "<br><strong>Sample Products from Database:</strong><br>";
						echo "<table>";
						echo "<tr><th>Product ID</th><th>Name</th><th>Status</th><th>Date Available</th><th>Manufacturer ID</th></tr>";
						foreach ($query_samples->rows as $row) {
							echo "<tr>";
							echo "<td>" . $row['product_id'] . "</td>";
							echo "<td>" . htmlspecialchars($row['name']) . "</td>";
							echo "<td>" . $row['status'] . "</td>";
							echo "<td>" . $row['date_available'] . "</td>";
							echo "<td>" . $row['manufacturer_id'] . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
				} else {
					echo "<span class='badge badge-warning'>Direct Query Result: 0 products</span><br>";
					echo "No products found in database with manufacturer_id = " . $manufacturer_id . "<br>";
					
					// Check if manufacturer_id exists in product table at all
					$sql_check = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . $manufacturer_id . "'";
					$query_check = $this->db->query($sql_check);
					$total_with_manufacturer = isset($query_check->row['total']) ? (int)$query_check->row['total'] : 0;
					
					echo "<br><strong>Products with this manufacturer_id (any status):</strong> " . $total_with_manufacturer . "<br>";
					
					if ($total_with_manufacturer > 0) {
						echo "<span class='badge badge-warning'>Products exist but may be inactive or not assigned to store</span><br>";
					}
				}
			} catch (Exception $e) {
				echo "<span class='badge badge-danger'>Error in Direct Query</span><br>";
				echo "Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
				echo "Trace: <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
			}
			echo "</div>";
			
			echo "</div>";
		}
		
		// System Information
		echo "<div class='debug-section'>";
		echo "<h2>System Information</h2>";
		echo "<div class='debug-item'>";
		echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
		echo "<strong>OpenCart Version:</strong> " . (defined('VERSION') ? VERSION : 'Unknown') . "<br>";
		echo "<strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "<br>";
		echo "<strong>DB_PREFIX:</strong> " . DB_PREFIX . "<br>";
		echo "</div></div>";
		
		echo "</div></body></html>";
		
		$output = ob_get_clean();
		$this->response->setOutput($output);
	}
}