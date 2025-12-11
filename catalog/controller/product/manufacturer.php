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

			// Debug: Check if products exist for this manufacturer (regardless of other filters)
			if (isset($this->request->get['debug'])) {
				$debug_query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product p WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "'");
				$debug_query_all = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				if (defined('DIR_LOGS')) {
					$log = new Log('manufacturer_debug.log');
					$log->write('Debug - Manufacturer ID: ' . $manufacturer_id . ' | All Products: ' . $debug_query->row['total'] . ' | Active Products in Store: ' . $debug_query_all->row['total']);
				}
			}

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
			$data['debug_info'] = array();
			
			// Enable debug mode if debug parameter is in URL
			$debug_mode = isset($this->request->get['debug']) && $this->request->get['debug'] == '1';
			
			if ($debug_mode) {
				// Collect debug information
				$data['debug_info']['manufacturer_id'] = $manufacturer_id;
				$data['debug_info']['store_id'] = $this->config->get('config_store_id');
				$data['debug_info']['language_id'] = $this->config->get('config_language_id');
				$data['debug_info']['customer_group_id'] = $this->config->get('config_customer_group_id');
				$data['debug_info']['filter_data'] = $filter_data;
				
				// Check raw product counts
				$raw_check = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
				$data['debug_info']['raw_products_count'] = $raw_check->row['total'];
				
				$active_check = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product p WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' AND p.status = '1' AND p.date_available <= NOW()");
				$data['debug_info']['active_products_count'] = $active_check->row['total'];
				
				$store_check = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
				$data['debug_info']['store_products_count'] = $store_check->row['total'];
				
				$lang_check = $this->db->query("SELECT COUNT(DISTINCT p.product_id) as total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "') LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.manufacturer_id = '" . (int)$manufacturer_id . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$data['debug_info']['lang_products_count'] = $lang_check->row['total'];
			}
			
			try {
				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
				$product_total = $product_total ? (int)$product_total : 0;

				$results = $this->model_catalog_product->getProducts($filter_data);
				if (!is_array($results)) {
					$results = array();
				}
				
				// Debug: Filter out false values from getProducts (products that failed getProduct() check)
				$results_before_filter = count($results);
				$results = array_filter($results, function($product) {
					return $product !== false && is_array($product);
				});
				$results_after_filter = count($results);
				
				if ($debug_mode) {
					$data['debug_info']['query_total'] = $product_total;
					$data['debug_info']['results_before_filter'] = $results_before_filter;
					$data['debug_info']['results_after_filter'] = $results_after_filter;
					$data['debug_info']['product_ids'] = array();
					if (!empty($results)) {
						$data['debug_info']['product_ids'] = array_slice(array_keys($results), 0, 10);
						$data['debug_info']['first_product_sample'] = reset($results);
					}
				}
				
			} catch (Exception $e) {
				$product_total = 0;
				$results = array();
				if ($debug_mode) {
					$data['debug_info']['error'] = $e->getMessage();
					$data['debug_info']['error_trace'] = $e->getTraceAsString();
				}
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
            
            // Generate debug HTML output if debug mode is enabled
            $data['debug_html'] = '';
            if ($debug_mode && !empty($data['debug_info'])) {
                $debug_html = '<div style="background: #f0f0f0; border: 2px solid #333; padding: 20px; margin: 20px; font-family: monospace; font-size: 12px;">';
                $debug_html .= '<h2 style="color: #d00; margin-top: 0;">🔍 MANUFACTURER DEBUG INFORMATION</h2>';
                $debug_html .= '<div style="background: white; padding: 15px; border: 1px solid #ccc; margin-bottom: 15px;">';
                $debug_html .= '<h3 style="margin-top: 0; color: #0066cc;">Basic Information</h3>';
                $debug_html .= '<table style="width: 100%; border-collapse: collapse;">';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold; width: 200px;">Manufacturer ID:</td><td style="padding: 5px; border: 1px solid #ddd;">' . htmlspecialchars($data['debug_info']['manufacturer_id']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Store ID:</td><td style="padding: 5px; border: 1px solid #ddd;">' . htmlspecialchars($data['debug_info']['store_id']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Language ID:</td><td style="padding: 5px; border: 1px solid #ddd;">' . htmlspecialchars($data['debug_info']['language_id']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Customer Group ID:</td><td style="padding: 5px; border: 1px solid #ddd;">' . htmlspecialchars($data['debug_info']['customer_group_id']) . '</td></tr>';
                $debug_html .= '</table></div>';
                
                $debug_html .= '<div style="background: white; padding: 15px; border: 1px solid #ccc; margin-bottom: 15px;">';
                $debug_html .= '<h3 style="margin-top: 0; color: #0066cc;">Product Counts</h3>';
                $debug_html .= '<table style="width: 100%; border-collapse: collapse;">';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold; width: 200px;">Raw Products (All):</td><td style="padding: 5px; border: 1px solid #ddd; color: ' . ($data['debug_info']['raw_products_count'] > 0 ? '#006600' : '#d00') . ';">' . htmlspecialchars($data['debug_info']['raw_products_count']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Active Products:</td><td style="padding: 5px; border: 1px solid #ddd; color: ' . ($data['debug_info']['active_products_count'] > 0 ? '#006600' : '#d00') . ';">' . htmlspecialchars($data['debug_info']['active_products_count']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Store Assigned:</td><td style="padding: 5px; border: 1px solid #ddd; color: ' . ($data['debug_info']['store_products_count'] > 0 ? '#006600' : '#d00') . ';">' . htmlspecialchars($data['debug_info']['store_products_count']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">With Language:</td><td style="padding: 5px; border: 1px solid #ddd; color: ' . ($data['debug_info']['lang_products_count'] > 0 ? '#006600' : '#d00') . ';">' . htmlspecialchars($data['debug_info']['lang_products_count']) . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Query Total:</td><td style="padding: 5px; border: 1px solid #ddd; color: ' . (isset($data['debug_info']['query_total']) && $data['debug_info']['query_total'] > 0 ? '#006600' : '#d00') . ';">' . (isset($data['debug_info']['query_total']) ? htmlspecialchars($data['debug_info']['query_total']) : 'N/A') . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Results (Before Filter):</td><td style="padding: 5px; border: 1px solid #ddd;">' . (isset($data['debug_info']['results_before_filter']) ? htmlspecialchars($data['debug_info']['results_before_filter']) : 'N/A') . '</td></tr>';
                $debug_html .= '<tr><td style="padding: 5px; border: 1px solid #ddd; font-weight: bold;">Results (After Filter):</td><td style="padding: 5px; border: 1px solid #ddd; color: ' . (isset($data['debug_info']['results_after_filter']) && $data['debug_info']['results_after_filter'] > 0 ? '#006600' : '#d00') . ';">' . (isset($data['debug_info']['results_after_filter']) ? htmlspecialchars($data['debug_info']['results_after_filter']) : 'N/A') . '</td></tr>';
                $debug_html .= '</table></div>';
                
                if (isset($data['debug_info']['product_ids']) && !empty($data['debug_info']['product_ids'])) {
                    $debug_html .= '<div style="background: white; padding: 15px; border: 1px solid #ccc; margin-bottom: 15px;">';
                    $debug_html .= '<h3 style="margin-top: 0; color: #0066cc;">Product IDs Found (First 10)</h3>';
                    $debug_html .= '<p>' . implode(', ', $data['debug_info']['product_ids']) . '</p>';
                    $debug_html .= '</div>';
                }
                
                if (isset($data['debug_info']['filter_data'])) {
                    $debug_html .= '<div style="background: white; padding: 15px; border: 1px solid #ccc; margin-bottom: 15px;">';
                    $debug_html .= '<h3 style="margin-top: 0; color: #0066cc;">Filter Data</h3>';
                    $debug_html .= '<pre style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; overflow-x: auto;">' . htmlspecialchars(print_r($data['debug_info']['filter_data'], true)) . '</pre>';
                    $debug_html .= '</div>';
                }
                
                if (isset($data['debug_info']['error'])) {
                    $debug_html .= '<div style="background: #ffe6e6; padding: 15px; border: 2px solid #d00; margin-bottom: 15px;">';
                    $debug_html .= '<h3 style="margin-top: 0; color: #d00;">❌ ERROR</h3>';
                    $debug_html .= '<p style="color: #d00; font-weight: bold;">' . htmlspecialchars($data['debug_info']['error']) . '</p>';
                    if (isset($data['debug_info']['error_trace'])) {
                        $debug_html .= '<pre style="background: #fff; padding: 10px; border: 1px solid #ddd; overflow-x: auto; font-size: 10px;">' . htmlspecialchars($data['debug_info']['error_trace']) . '</pre>';
                    }
                    $debug_html .= '</div>';
                }
                
                if (isset($data['debug_info']['first_product_sample'])) {
                    $debug_html .= '<div style="background: white; padding: 15px; border: 1px solid #ccc; margin-bottom: 15px;">';
                    $debug_html .= '<h3 style="margin-top: 0; color: #0066cc;">First Product Sample</h3>';
                    $debug_html .= '<pre style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; overflow-x: auto; max-height: 300px; overflow-y: auto;">' . htmlspecialchars(print_r($data['debug_info']['first_product_sample'], true)) . '</pre>';
                    $debug_html .= '</div>';
                }
                
                $debug_html .= '<p style="color: #666; font-size: 11px; margin-top: 15px;">To disable debug mode, remove <code>?debug=1</code> from the URL</p>';
                $debug_html .= '</div>';
                $data['debug_html'] = $debug_html;
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
}