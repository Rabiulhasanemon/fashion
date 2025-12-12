<?php
class ControllerCatalogProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			// Log the request for debugging
			$log_file = DIR_LOGS . 'product_insert_debug.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== NEW PRODUCT ADD REQUEST ==========' . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data keys: ' . implode(', ', array_keys($this->request->post)) . PHP_EOL, FILE_APPEND);
			
			// Ensure product_filter is always an array (even if empty)
			if (!isset($this->request->post['product_filter']) || !is_array($this->request->post['product_filter'])) {
				$this->request->post['product_filter'] = array();
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ADD] product_filter not set or not array, initializing as empty array' . PHP_EOL, FILE_APPEND);
			} else {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ADD] product_filter count: ' . count($this->request->post['product_filter']) . PHP_EOL, FILE_APPEND);
			}
			
			// Normalize product_attribute data structure if needed
			if (isset($this->request->post['product_attribute']) && is_array($this->request->post['product_attribute'])) {
				$normalized_attributes = array();
				foreach ($this->request->post['product_attribute'] as $key => $attribute) {
					// If key is numeric and attribute has attribute_id, use attribute_id as key
					if (is_numeric($key) && isset($attribute['attribute_id'])) {
						$attr_id = (int)$attribute['attribute_id'];
						if ($attr_id > 0) {
							$normalized_attributes[$attr_id] = $attribute;
						} else {
							$normalized_attributes[$key] = $attribute;
						}
					} else {
						$normalized_attributes[$key] = $attribute;
					}
				}
				$this->request->post['product_attribute'] = $normalized_attributes;
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ATTRIBUTE] Normalized attribute structure, count: ' . count($normalized_attributes) . PHP_EOL, FILE_APPEND);
			} else {
				// Ensure product_attribute is always an array (even if empty)
				if (!isset($this->request->post['product_attribute'])) {
					$this->request->post['product_attribute'] = array();
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ADD] product_attribute not set, initializing as empty array' . PHP_EOL, FILE_APPEND);
				}
			}
			
			// Log detailed information about each tab's data
			$tab_fields = array(
				'image' => 'Main Image',
				'featured_image' => 'Featured Image',
				'product_image' => 'Additional Images',
				'product_category' => 'Categories (Links)',
				'product_download' => 'Downloads (Links)',
				'product_related' => 'Related Products (Links)',
				'product_compatible' => 'Compatible Products (Links)',
				'product_attribute' => 'Attributes',
				'product_filter' => 'Filters',
				'product_option' => 'Options',
				'product_discount' => 'Discounts',
				'product_special' => 'Specials',
				'product_reward' => 'Reward Points',
				'product_layout' => 'Layouts (Design)',
				'manufacturer_id' => 'Manufacturer',
				'parent_id' => 'Parent Product'
			);
			
			foreach ($tab_fields as $key => $label) {
				if (isset($this->request->post[$key])) {
					if (is_array($this->request->post[$key])) {
						$count = count($this->request->post[$key]);
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [' . $label . '] ' . $key . ': YES (' . $count . ' items)' . PHP_EOL, FILE_APPEND);
						if ($count > 0 && $count <= 5) {
							// Log first few items for debugging
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [' . $label . '] Sample data: ' . print_r(array_slice($this->request->post[$key], 0, 2), true) . PHP_EOL, FILE_APPEND);
						}
					} else {
						$value = $this->request->post[$key];
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [' . $label . '] ' . $key . ': YES (value: ' . substr($value, 0, 100) . ')' . PHP_EOL, FILE_APPEND);
					}
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [' . $label . '] ' . $key . ': NOT SET' . PHP_EOL, FILE_APPEND);
				}
			}
			
			// Validate form and log results
			$validation_result = $this->validateForm();
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation result: ' . ($validation_result ? 'PASSED' : 'FAILED') . PHP_EOL, FILE_APPEND);
			if (!$validation_result) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation errors: ' . print_r($this->error, true) . PHP_EOL, FILE_APPEND);
				// Set error warning if not already set
				if (!isset($this->session->data['error_warning']) || empty($this->session->data['error_warning'])) {
					$error_messages = array();
					foreach ($this->error as $key => $value) {
						if (is_array($value)) {
							$error_messages[] = implode(', ', $value);
						} else {
							$error_messages[] = $value;
						}
					}
					if (!empty($error_messages)) {
						$this->session->data['error_warning'] = 'Validation failed: ' . implode('; ', $error_messages);
					} else {
						$this->session->data['error_warning'] = 'Please check the form for errors.';
					}
				}
			}
			
			if ($validation_result) {
				try {
					// Log attribute data before processing
					if (isset($this->request->post['product_attribute'])) {
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ATTRIBUTE] Raw POST data structure: ' . print_r($this->request->post['product_attribute'], true) . PHP_EOL, FILE_APPEND);
					}
					
					$product_id = $this->model_catalog_product->addProduct($this->request->post);
					
					// Verify product_id is valid
					if (!$product_id || $product_id <= 0) {
						$error_msg = "Error updating product: addProduct returned invalid product_id (" . $product_id . ")";
						file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
						$this->session->data['error_warning'] = $error_msg;
						$this->getForm();
						return;
					}
					
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Product added successfully with product_id: ' . $product_id . PHP_EOL, FILE_APPEND);

					// Add to activity log (non-blocking - don't fail if this fails)
					try {
						$this->load->model('user/user');
						$activity_data = array(
							'%user_id' => $this->user->getId(),
							'%product_id' => $product_id,
							'%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
						);
						$this->model_user_user->addActivity($this->user->getId(), 'add_product', $activity_data, $product_id);
					} catch (Exception $activity_error) {
						// Log but don't fail the save
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Activity log failed (non-critical): ' . $activity_error->getMessage() . PHP_EOL, FILE_APPEND);
					}

					$this->session->data['success'] = $this->language->get('text_success');
					
					// Build redirect URL
					$url = '';

					if (isset($this->request->get['filter_name'])) {
						$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['filter_model'])) {
						$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
					}

					if (isset($this->request->get['filter_price'])) {
						$url .= '&filter_price=' . $this->request->get['filter_price'];
					}

					if (isset($this->request->get['filter_quantity'])) {
						$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
					}

					if (isset($this->request->get['filter_status'])) {
						$url .= '&filter_status=' . $this->request->get['filter_status'];
					}

					if (isset($this->request->get['filter_category_id'])) {
						$url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
					}

					if (isset($this->request->get['filter_manufacturer_id'])) {
						$url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

					// CRITICAL: Always redirect after successful save
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Redirecting to product list' . PHP_EOL, FILE_APPEND);
					$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
					return; // Ensure we exit here
					
				} catch (Exception $e) {
					$error_message = $e->getMessage();
					file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - EXCEPTION: ' . $error_message . PHP_EOL, FILE_APPEND);
					file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - File: ' . $e->getFile() . ', Line: ' . $e->getLine() . PHP_EOL, FILE_APPEND);
					file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - Trace: ' . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
					
					// Check if it's a duplicate entry error - check if product was actually saved (retry might have succeeded)
					if (stripos($error_message, 'duplicate') !== false || stripos($error_message, 'primary') !== false) {
						// Check if product was actually saved despite the error (automatic retry might have succeeded)
						$check_saved = false;
						if (isset($this->request->post['model']) && !empty($this->request->post['model'])) {
							$check_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($this->request->post['model']) . "' ORDER BY product_id DESC LIMIT 1");
							if ($check_query && $check_query->num_rows) {
								$check_saved = true;
								$saved_product_id = (int)$check_query->row['product_id'];
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ADD] Product was actually saved despite error! product_id: ' . $saved_product_id . ' (automatic retry succeeded)' . PHP_EOL, FILE_APPEND);
								
								// Product was saved - redirect to success (don't show error)
								$this->session->data['success'] = $this->language->get('text_success');
								$url = '';
								if (isset($this->request->get['filter_name'])) {
									$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
								}
								if (isset($this->request->get['filter_model'])) {
									$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
								}
								if (isset($this->request->get['filter_price'])) {
									$url .= '&filter_price=' . $this->request->get['filter_price'];
								}
								if (isset($this->request->get['filter_quantity'])) {
									$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
								}
								if (isset($this->request->get['filter_status'])) {
									$url .= '&filter_status=' . $this->request->get['filter_status'];
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
								$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
								return;
							}
						}
						
						if (!$check_saved) {
							$error_message = "Database error: Duplicate entry detected. This usually means there's a product with product_id = 0 in the database. The system will attempt to clean this up. Please try saving again. If the problem persists, contact support.";
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Duplicate entry error detected, product was not saved' . PHP_EOL, FILE_APPEND);
							$this->session->data['error_warning'] = "Error saving product: " . $error_message;
						}
					} else {
						$this->session->data['error_warning'] = "Error saving product: " . $error_message;
					}
					
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Error occurred, showing form with error message' . PHP_EOL, FILE_APPEND);
					$this->getForm();
					return;
				}
			} else {
				// Validation failed - show form with errors
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation failed, showing form again' . PHP_EOL, FILE_APPEND);
			}
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// Log the request for debugging
			$log_file = DIR_LOGS . 'product_insert_debug.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== PRODUCT EDIT REQUEST ==========' . PHP_EOL, FILE_APPEND);
			
			// Validate product_id
			if (!isset($this->request->get['product_id']) || empty($this->request->get['product_id']) || (int)$this->request->get['product_id'] <= 0) {
				$this->session->data['error_warning'] = 'Invalid product ID. Cannot update product.';
				$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));
				return;
			}

			$product_id = (int)$this->request->get['product_id'];
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Product ID: ' . $product_id . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data keys: ' . implode(', ', array_keys($this->request->post)) . PHP_EOL, FILE_APPEND);
			
			// DEBUG: Check for product_id = 0 records before editing
			$check_zero = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE product_id = 0");
			if ($check_zero && $check_zero->num_rows && $check_zero->row['count'] > 0) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [WARNING] Found ' . $check_zero->row['count'] . ' product(s) with product_id = 0. Cleaning up...' . PHP_EOL, FILE_APPEND);
				// Clean up before proceeding
				$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0");
			}
			
			// DEBUG: Verify product exists
			$verify_product = $this->db->query("SELECT product_id, model, sku FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "' LIMIT 1");
			if (!$verify_product || !$verify_product->num_rows) {
				$error_msg = "Product with ID " . $product_id . " does not exist.";
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ERROR] ' . $error_msg . PHP_EOL, FILE_APPEND);
				$this->session->data['error_warning'] = $error_msg;
				$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));
				return;
			}
			
			// DEBUG: Check for duplicate model or SKU if they're being changed
			if (isset($this->request->post['model']) && !empty($this->request->post['model'])) {
				$check_model = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($this->request->post['model']) . "' AND product_id != '" . $product_id . "' LIMIT 1");
				if ($check_model && $check_model->num_rows > 0) {
					$error_msg = "Model '" . $this->request->post['model'] . "' already exists for another product (ID: " . $check_model->row['product_id'] . ")";
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ERROR] ' . $error_msg . PHP_EOL, FILE_APPEND);
					$this->session->data['error_warning'] = $error_msg;
					$this->getForm();
					return;
				}
			}
			
			if (isset($this->request->post['sku']) && !empty($this->request->post['sku'])) {
				$check_sku = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($this->request->post['sku']) . "' AND product_id != '" . $product_id . "' LIMIT 1");
				if ($check_sku && $check_sku->num_rows > 0) {
					$error_msg = "SKU '" . $this->request->post['sku'] . "' already exists for another product (ID: " . $check_sku->row['product_id'] . ")";
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [ERROR] ' . $error_msg . PHP_EOL, FILE_APPEND);
					$this->session->data['error_warning'] = $error_msg;
					$this->getForm();
					return;
				}
			}
			
			// Log detailed information about each tab's data
			$tab_fields = array(
				'image' => 'Main Image',
				'featured_image' => 'Featured Image',
				'product_image' => 'Additional Images',
				'product_category' => 'Categories (Links)',
				'product_download' => 'Downloads (Links)',
				'product_related' => 'Related Products (Links)',
				'product_compatible' => 'Compatible Products (Links)',
				'product_attribute' => 'Attributes',
				'product_filter' => 'Filters',
				'product_option' => 'Options',
				'product_discount' => 'Discounts',
				'product_special' => 'Specials',
				'product_reward' => 'Reward Points',
				'product_layout' => 'Layouts (Design)',
				'manufacturer_id' => 'Manufacturer',
				'parent_id' => 'Parent Product'
			);
			
			foreach ($tab_fields as $key => $label) {
				if (isset($this->request->post[$key])) {
					if (is_array($this->request->post[$key])) {
						$count = count($this->request->post[$key]);
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT][' . $label . '] ' . $key . ': YES (' . $count . ' items)' . PHP_EOL, FILE_APPEND);
						if ($count > 0 && $count <= 5) {
							// Log first few items for debugging
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT][' . $label . '] Sample data: ' . print_r(array_slice($this->request->post[$key], 0, 2), true) . PHP_EOL, FILE_APPEND);
						}
					} else {
						$value = $this->request->post[$key];
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT][' . $label . '] ' . $key . ': YES (value: ' . substr($value, 0, 100) . ')' . PHP_EOL, FILE_APPEND);
					}
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT][' . $label . '] ' . $key . ': NOT SET' . PHP_EOL, FILE_APPEND);
				}
			}
			
			// Ensure product_filter is always an array (even if empty)
			if (!isset($this->request->post['product_filter']) || !is_array($this->request->post['product_filter'])) {
				$this->request->post['product_filter'] = array();
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] product_filter not set or not array, initializing as empty array' . PHP_EOL, FILE_APPEND);
			} else {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] product_filter count: ' . count($this->request->post['product_filter']) . PHP_EOL, FILE_APPEND);
			}
			
			// Normalize product_attribute data structure if needed
			if (isset($this->request->post['product_attribute']) && is_array($this->request->post['product_attribute'])) {
				$normalized_attributes = array();
				foreach ($this->request->post['product_attribute'] as $key => $attribute) {
					// If key is numeric and attribute has attribute_id, use attribute_id as key
					if (is_numeric($key) && isset($attribute['attribute_id'])) {
						$attr_id = (int)$attribute['attribute_id'];
						if ($attr_id > 0) {
							$normalized_attributes[$attr_id] = $attribute;
						} else {
							$normalized_attributes[$key] = $attribute;
						}
					} else {
						$normalized_attributes[$key] = $attribute;
					}
				}
				$this->request->post['product_attribute'] = $normalized_attributes;
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT][ATTRIBUTE] Normalized attribute structure, count: ' . count($normalized_attributes) . PHP_EOL, FILE_APPEND);
			} else {
				// Ensure product_attribute is always an array (even if empty)
				if (!isset($this->request->post['product_attribute'])) {
					$this->request->post['product_attribute'] = array();
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] product_attribute not set, initializing as empty array' . PHP_EOL, FILE_APPEND);
				}
			}
			
			if (isset($this->request->post['product_image'])) {
				$img_count = is_array($this->request->post['product_image']) ? count($this->request->post['product_image']) : 'not array';
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_image count: ' . $img_count . PHP_EOL, FILE_APPEND);
				if (is_array($this->request->post['product_image'])) {
					foreach ($this->request->post['product_image'] as $idx => $img) {
						$img_path = isset($img['image']) ? $img['image'] : 'no image key';
						$img_sort = isset($img['sort_order']) ? $img['sort_order'] : 'no sort_order';
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - product_image[' . $idx . ']: path=' . substr($img_path, 0, 50) . '..., sort=' . $img_sort . PHP_EOL, FILE_APPEND);
					}
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: product_image is not an array! Type: ' . gettype($this->request->post['product_image']) . PHP_EOL, FILE_APPEND);
				}
			} else {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: product_image not set in POST data!' . PHP_EOL, FILE_APPEND);
			}

			// Verify product exists
			$product_info = $this->model_catalog_product->getProduct($product_id);
			if (!$product_info) {
				$this->session->data['error_warning'] = 'Product not found. Cannot update product.';
				$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'], 'SSL'));
				return;
			}

			try {
				// Log attribute data before processing
				if (isset($this->request->post['product_attribute'])) {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT][ATTRIBUTE] Raw POST data structure: ' . print_r($this->request->post['product_attribute'], true) . PHP_EOL, FILE_APPEND);
				}
				
				$this->model_catalog_product->editProduct($product_id, $this->request->post);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] editProduct completed successfully' . PHP_EOL, FILE_APPEND);

				$this->session->data['success'] = $this->language->get('text_success');

				// Add to activity log (non-blocking - if it fails, product is still saved)
				try {
					$this->load->model('user/user');
					if (isset($this->user) && method_exists($this->user, 'getId')) {
						$activity_data = array(
							'%user_id' => $this->user->getId(),
							'%product_id' => $product_id,
							'%name' => $this->user->getFirstName() . ' ' . $this->user->getLastName()
						);
						$this->model_user_user->addActivity($this->user->getId(), 'edit_product', $activity_data, $product_id);
					}
				} catch (Exception $e) {
					// Activity log failed, but product was saved - continue with redirect
					error_log('Activity log error: ' . $e->getMessage());
				}
			} catch (Exception $e) {
				// Product update failed
				$error_message = $e->getMessage();
				$db_error = '';
				$db_errno = 0;
				
				// Try to get detailed database error information
				if (property_exists($this->db, 'link') && is_object($this->db->link)) {
					if (property_exists($this->db->link, 'error')) {
						$db_error = $this->db->link->error;
					}
					if (property_exists($this->db->link, 'errno')) {
						$db_errno = $this->db->link->errno;
					}
				}
				
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] EXCEPTION: ' . $error_message . PHP_EOL, FILE_APPEND);
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] Database Error: ' . $db_error . ' (Error No: ' . $db_errno . ')' . PHP_EOL, FILE_APPEND);
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] File: ' . $e->getFile() . ', Line: ' . $e->getLine() . PHP_EOL, FILE_APPEND);
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] Trace: ' . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] EXCEPTION: ' . $error_message . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Database Error: ' . $db_error . ' (Error No: ' . $db_errno . ')' . PHP_EOL, FILE_APPEND);
				
				// Check if it's a duplicate entry error - attempt automatic cleanup
				if ($db_errno == 1062 || stripos($error_message, 'duplicate') !== false || stripos($error_message, 'primary') !== false) {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Duplicate entry error detected (Error #' . $db_errno . '), attempting automatic cleanup...' . PHP_EOL, FILE_APPEND);
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Full error message: ' . $db_error . PHP_EOL, FILE_APPEND);
					
					// Attempt aggressive cleanup
					$this->load->model('catalog/product');
					try {
						// Clean up product_id = 0 records from all related tables
						$cleanup_tables = array(
							'product_description', 'product_to_store', 'product_to_category',
							'product_image', 'product_option', 'product_option_value',
							'product_filter', 'product_attribute', 'product_discount',
							'product_special', 'product_reward', 'product_related',
							'product_compatible', 'product_to_layout', 'product_to_download'
						);
						
						$cleaned_count = 0;
						foreach ($cleanup_tables as $table) {
							try {
								$check_table = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
								if ($check_table && $check_table->num_rows) {
									$delete_result = $this->db->query("DELETE FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
									if ($delete_result) {
										$cleaned_count++;
									}
								}
							} catch (Exception $e) {
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Cleanup warning for ' . $table . ': ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
							}
						}
						
						// Clean up main product table
						try {
							$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0");
							$cleaned_count++;
						} catch (Exception $e) {
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Cleanup warning for product table: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
						}
						
						// Clean up url_alias
						try {
							$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=0'");
						} catch (Exception $e) {
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Cleanup warning for url_alias: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
						}
						
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Cleanup completed (' . $cleaned_count . ' tables cleaned). You can try saving again.' . PHP_EOL, FILE_APPEND);
						
						// Provide more detailed error message
						if ($db_errno == 1062) {
							$error_message = "Database error: Duplicate entry detected (MySQL Error #1062). The system has automatically cleaned up product_id = 0 records from " . $cleaned_count . " tables. Please try saving again. If the error persists, check the log file for details.";
						} else {
							$error_message = "Database error: Duplicate entry detected. The system has automatically cleaned up product_id = 0 records. Please try saving again.";
						}
					} catch (Exception $cleanup_error) {
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Cleanup failed: ' . $cleanup_error->getMessage() . PHP_EOL, FILE_APPEND);
						$error_message = "Database error: Duplicate entry detected (Error #" . $db_errno . "). The system attempted to clean this up but failed. Error details: " . $db_error . ". Please check the log file or contact support.";
					}
				} else {
					// Not a duplicate entry error - show original error
					$error_message = "Error updating product: " . $error_message;
					if ($db_error) {
						$error_message .= " (Database Error: " . $db_error . ")";
					}
				}
				
				$this->session->data['error_warning'] = $error_message;
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Error occurred, showing form with error message' . PHP_EOL, FILE_APPEND);
				error_log('Product update error: ' . $error_message);
			}

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

            if (isset($this->request->get['filter_category_id'])) {
                $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
            }

            if (isset($this->request->get['filter_manufacturer_id'])) {
                $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

			// Always redirect back to product list, even if there was an error
			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			return;
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);

                // Add to activity log
                $this->load->model('user/user');
                $activity_data = array(
                    '%user_id' => $this->user->getId(),
                    '%product_id' => $product_id,
                    '%name'        => $this->user->getFirstName() . ' ' . $this->user->getLastName()
                );

                $this->model_user_user->addActivity($this->user->getId(), 'delete_product', $activity_data, $product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

        if (isset($this->request->get['filter_category_id'])) {
            $filter_category_id = $this->request->get['filter_category_id'];
        } else {
            $filter_category_id = null;
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
        } else {
            $filter_manufacturer_id = null;
        }

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.product_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['import'] = $this->url->link('catalog/product/import', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['products'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'filter_category_id'   => $filter_category_id,
			'filter_manufacturer_id'   => $filter_manufacturer_id,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $product_special['price'];

					break;
				}
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'regular_price' => $result['regular_price'],
				'price'      => $result['price'],
				'special'    => $special,
                'date_added'    => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
                'date_modified'    => date($this->language->get('datetime_format'), strtotime($result['date_modified'])),
				'quantity'   => $result['quantity'],
				'sort_order'   => $result['sort_order'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_none'] = $this->language->get('text_none');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_regular_price'] = $this->language->get('column_regular_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
        $data['column_date_added'] = $this->language->get('column_date_added');
        $data['column_date_modified'] = $this->language->get('column_date_modified');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_download'] = $this->language->get('button_download');
		$data['button_import'] = $this->language->get('button_import');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=pd.name' . $url, 'SSL');
        $data['sort_date_added'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');
        $data['sort_date_modified'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.date_modified' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.model' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		$data['sort_regular_price'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.regular_price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
        }

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_model'] = $filter_model;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['filter_status'] = $filter_status;
		$data['filter_category_id'] = $filter_category_id;
		$data['filter_manufacturer_id'] = $filter_manufacturer_id;

		if($filter_category_id) {
		    $this->load->model("catalog/category");
		    $filter_category_info = $this->model_catalog_category->getCategory($filter_category_id);
		    $data["filter_category_name"] = $filter_category_info['name'];
        } else {
		    $data["filter_category_name"] = "";
        }

        $data['filter_manufacturer_id'] = $filter_manufacturer_id;
        if($filter_manufacturer_id) {
            $this->load->model("catalog/manufacturer");
            $filter_manufacturer_info = $this->model_catalog_manufacturer->getmanufacturer($filter_manufacturer_id);
            $data["filter_manufacturer_name"] = $filter_manufacturer_info['name'];
        } else {
            $data["filter_manufacturer_name"] = "";
        }

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_list.tpl', $data));
	}

	public function import() {
		// Increase memory limit for image processing during import
		ini_set('memory_limit', '1024M');
		ini_set('max_execution_time', '600'); // 10 minutes
		
		// Set flag to skip image processing during import (both constant and global)
		if (!defined('IMPORT_IN_PROGRESS')) {
			define('IMPORT_IN_PROGRESS', true);
		}
		$GLOBALS['IMPORT_IN_PROGRESS'] = true;
		
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title_import'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateImport()) {
			$file = $this->request->files['import_file']['tmp_name'];

			if (($handle = fopen($file, 'r')) !== false) {
				// Aggressive cleanup: Remove any orphaned product with product_id = 0 (which blocks all inserts)
				// Do this multiple times to ensure it's really gone
				for ($cleanup_attempt = 0; $cleanup_attempt < 3; $cleanup_attempt++) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=0'");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = 0");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = 0");
					$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0");
					
					// Also delete from any other related tables that might have product_id = 0
					$tables_to_clean = array(
						'product_attribute', 'product_discount', 'product_filter', 
						'product_image', 'product_option', 'product_option_value',
						'product_related', 'product_reward', 'product_special', 'product_to_category',
						'product_to_download', 'product_to_layout', 'product_variation', 'product_compatible'
					);
					foreach ($tables_to_clean as $table) {
						$check_table = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
						if ($check_table && $check_table->num_rows > 0) {
							$this->db->query("DELETE FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
						}
					}
				}
				
				// Fix auto-increment - ensure it's properly set
				$max_check = $this->db->query("SELECT MAX(product_id) as max_id FROM " . DB_PREFIX . "product");
				$max_id = 0;
				if ($max_check && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
					$max_id = (int)$max_check->row['max_id'];
				}
				$next_id = max($max_id + 1, 1);
				$this->db->query("ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . $next_id);
				
				// Verify cleanup worked
				$verify_zero = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = 0 LIMIT 1");
				if ($verify_zero && $verify_zero->num_rows > 0) {
					$this->session->data['error_warning'] = "Critical error: Cannot remove product with product_id = 0. Please manually delete it from the database using phpMyAdmin or MySQL command line: DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0;";
					$url = '';
					$redirect_url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
					$this->response->redirect($redirect_url);
					return;
				}
				
				$headers = fgetcsv($handle, 10000, ',');
				$header_map = array();
				foreach ($headers as $index => $header) {
					$header_map[trim(strtolower($header))] = $index;
				}

				$success_count = 0;
				$error_count = 0;
				$errors = array();
				$row_num = 1;
				$used_keywords = array();
				$total_rows = 0;

				$this->load->model('localisation/language');
				$languages = $this->model_localisation_language->getLanguages();
				$default_language_id = $this->config->get('config_language_id');
				
				// Debug: Log CSV info
				if (empty($headers) || count($headers) < 3) {
					$this->session->data['error_warning'] = "Invalid CSV file: Header row appears to be missing or malformed. Found " . count($headers) . " columns.";
					$url = '';
					$redirect_url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
					$this->response->redirect($redirect_url);
					return;
				}

				$nextRow = function($row, $name, $default = '') use ($header_map) {
					$key = trim(strtolower($name));
					if (isset($header_map[$key])) {
						return isset($row[$header_map[$key]]) ? $row[$header_map[$key]] : $default;
					}
					return $default;
				};

				while (($row = fgetcsv($handle, 20000, ',')) !== false) {
					$row_num++;
					$total_rows++;
					
					// Skip empty rows
					if (empty(array_filter($row))) {
						continue;
					}
					
					try {
						// Clear memory before processing each product
						if (function_exists('gc_collect_cycles')) {
							gc_collect_cycles();
						}
						
						// Build product data with defaults
						$product_data = array();
						$product_data['model'] = trim($nextRow($row, 'model'));
						$product_data['sku'] = trim($nextRow($row, 'sku'));
						
						// Skip if no model provided
						if (empty($product_data['model'])) {
							$error_count++;
							$errors[] = 'Row ' . $row_num . ': Missing model';
							continue;
						}
						$product_data['mpn'] = trim($nextRow($row, 'mpn'));
						$product_data['short_note'] = trim($nextRow($row, 'short_note'));
						$product_data['quantity'] = (int)$nextRow($row, 'quantity', 1);
						$product_data['minimum'] = (int)$nextRow($row, 'minimum', 1);
						$product_data['maximum'] = (int)$nextRow($row, 'maximum', 0);
						$product_data['subtract'] = (int)$nextRow($row, 'subtract', 1);
						$product_data['stock_status_id'] = (int)$nextRow($row, 'stock_status_id', 0);
						$product_data['date_available'] = $nextRow($row, 'date_available', date('Y-m-d'));
						$product_data['manufacturer_id'] = 0;
						$product_data['is_manufacturer_is_parent'] = (int)$nextRow($row, 'is_manufacturer_is_parent', 0);
						$product_data['parent_id'] = (int)$nextRow($row, 'parent_id', 0);
						$product_data['attribute_profile_id'] = (int)$nextRow($row, 'attribute_profile_id', 0);
						$product_data['shipping'] = (int)$nextRow($row, 'shipping', 1);
						$product_data['emi'] = (int)$nextRow($row, 'emi', 0);
						$product_data['cost_price'] = (float)$nextRow($row, 'cost_price', 0);
						$product_data['price'] = (float)$nextRow($row, 'price', 0);
						$product_data['regular_price'] = (float)$nextRow($row, 'regular_price', 0);
						$product_data['points'] = (int)$nextRow($row, 'points', 0);
						$product_data['weight'] = (float)$nextRow($row, 'weight', 0);
						$product_data['weight_class_id'] = (int)$nextRow($row, 'weight_class_id', $this->config->get('config_weight_class_id'));
						$product_data['length'] = (float)$nextRow($row, 'length', 0);
						$product_data['width'] = (float)$nextRow($row, 'width', 0);
						$product_data['height'] = (float)$nextRow($row, 'height', 0);
						$product_data['length_class_id'] = (int)$nextRow($row, 'length_class_id', $this->config->get('config_length_class_id'));
						$product_data['status'] = strtolower(trim($nextRow($row, 'status', 'enabled'))) == 'disabled' ? 0 : 1;
						$product_data['tax_class_id'] = (int)$nextRow($row, 'tax_class_id', 0);
						$product_data['sort_order'] = (int)$nextRow($row, 'sort_order', 1);
						$product_data['view'] = trim($nextRow($row, 'view', ''));

						// Stores
						$product_data['product_store'] = array();
						$stores_str = $nextRow($row, 'stores', '0');
						foreach (explode(',', $stores_str) as $store_id) {
							$store_id = trim($store_id);
							if ($store_id !== '' && is_numeric($store_id)) {
								$product_data['product_store'][] = (int)$store_id;
							}
						}
						if (empty($product_data['product_store'])) {
							$product_data['product_store'] = array(0);
						}

						// Manufacturer (id or name)
						$manufacturer = trim($nextRow($row, 'manufacturer'));
						if ($manufacturer !== '') {
							if (is_numeric($manufacturer)) {
								$product_data['manufacturer_id'] = (int)$manufacturer;
							} else {
								$q = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($manufacturer) . "' LIMIT 1");
								if ($q->num_rows) {
									$product_data['manufacturer_id'] = (int)$q->row['manufacturer_id'];
								}
							}
						}

						// Images - process without resizing to save memory
						$image_main = trim($nextRow($row, 'image'));
						if ($image_main !== '') {
							$product_data['image'] = $this->processImage($image_main, false); // false = skip resizing
						}

						$featured_image = trim($nextRow($row, 'featured_image'));
						if ($featured_image !== '') {
							$product_data['featured_image'] = $this->processImage($featured_image, false); // false = skip resizing
						}

						$product_data['product_image'] = array();
						$additional = trim($nextRow($row, 'additional_images'));
						if ($additional !== '') {
							// Format: path1[:sort]|path2[:sort]
							foreach (preg_split('/[|]/', $additional) as $imgSpec) {
								$imgSpec = trim($imgSpec);
								if ($imgSpec === '') continue;
								$parts = explode(':', $imgSpec);
								$imgPath = $this->processImage($parts[0], false); // false = skip resizing
								$sort = isset($parts[1]) ? (int)$parts[1] : 0;
								if ($imgPath !== '') {
									$product_data['product_image'][] = array('image' => $imgPath, 'sort_order' => $sort);
								}
							}
						}
						
						// Clear memory after processing images for this product
						if (function_exists('gc_collect_cycles')) {
							gc_collect_cycles();
						}

						// Categories (IDs or names separated by comma)
						$product_data['product_category'] = array();
						$categories_str = $nextRow($row, 'categories');
						if ($categories_str !== '') {
							$this->load->model('catalog/category');
							foreach (explode(',', $categories_str) as $cat) {
								$cat = trim($cat);
								if ($cat === '') continue;
								if (is_numeric($cat)) {
									$product_data['product_category'][] = (int)$cat;
								} else {
									$filter = array('filter_name' => $cat, 'start' => 0, 'limit' => 50);
									$found = $this->model_catalog_category->getCategories($filter);
									foreach ($found as $fc) {
										$name = $fc['name'];
										if (strpos($name, '&gt;') !== false) {
											$parts = explode('&gt;', $name);
											$name = trim(end($parts));
										}
										if (strcasecmp($name, $cat) == 0) {
											$product_data['product_category'][] = (int)$fc['category_id'];
											break;
										}
									}
								}
							}
						}

						// Descriptions per language
						$product_data['product_description'] = array();
						$default_name = '';
						foreach ($languages as $lang) {
							$code = isset($lang['code']) ? $lang['code'] : '';
							$language_id = (int)$lang['language_id'];
							$name = $nextRow($row, 'name_' . $code, $nextRow($row, 'name'));
							if ($language_id == $default_language_id) {
								$default_name = $name;
							}
							$product_data['product_description'][$language_id] = array(
								'name' => $name,
								'sub_name' => $nextRow($row, 'sub_name_' . $code, $nextRow($row, 'sub_name')),
								'description' => $nextRow($row, 'description_' . $code, $nextRow($row, 'description')),
								'short_description' => $nextRow($row, 'short_description_' . $code, $nextRow($row, 'short_description')),
								'video_url' => $nextRow($row, 'video_url_' . $code, $nextRow($row, 'video_url')),
								'tag' => $nextRow($row, 'tag_' . $code, $nextRow($row, 'tag')),
								'meta_title' => $nextRow($row, 'meta_title_' . $code, $nextRow($row, 'meta_title')),
								'meta_description' => $nextRow($row, 'meta_description_' . $code, $nextRow($row, 'meta_description')),
								'meta_keyword' => $nextRow($row, 'meta_keyword_' . $code, $nextRow($row, 'meta_keyword')),
							);
						}

						// SEO keyword - auto-generate from default name
						$keyword = '';
						if (!empty($default_name)) {
							$keyword = strtolower(trim($default_name));
							$keyword = preg_replace('/[^a-z0-9]+/', '-', $keyword);
							$keyword = preg_replace('/^-+|-+$/', '', $keyword);
						}
						if ($keyword === '') {
							$keyword = 'product-' . time() . '-' . rand(1000, 9999);
						}
						$this->load->model('catalog/url_alias');
						$original_keyword = $keyword;
						$counter = 1;
						while (isset($used_keywords[$keyword]) || $this->model_catalog_url_alias->getUrlAlias($keyword)) {
							$keyword = $original_keyword . '-' . $counter;
							$counter++;
						}
						$used_keywords[$keyword] = true;
						$product_data['keyword'] = $keyword;

						// Determine add or update
						$product_id = null;
						$csv_product_id = trim($nextRow($row, 'product_id'));
						if ($csv_product_id !== '' && is_numeric($csv_product_id)) {
							$product_id = (int)$csv_product_id;
							// ensure exists
							$q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
							if (!$q->num_rows) {
								$product_id = null;
							}
						}
						if ($product_id === null) {
							$lookupModel = trim($product_data['model']);
							if ($lookupModel !== '') {
								$q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($lookupModel) . "' LIMIT 1");
								if ($q->num_rows) {
									$product_id = (int)$q->row['product_id'];
								}
							}
						}
						if ($product_id === null) {
							$lookupSku = trim($product_data['sku']);
							if ($lookupSku !== '') {
								$q = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($lookupSku) . "' LIMIT 1");
								if ($q->num_rows) {
									$product_id = (int)$q->row['product_id'];
								}
							}
						}

						// Validate minimal fields
						if (empty($product_data['model'])) {
							throw new Exception($this->language->get('error_model'));
						}
						if (empty($product_data['product_description'][$default_language_id]['name'])) {
							throw new Exception($this->language->get('error_name'));
						}

						if ($product_id) {
							$this->model_catalog_product->editProduct($product_id, $product_data);
							if (!$product_id || $product_id == 0) {
								throw new Exception("Failed to update product");
							}
						} else {
							$product_id = $this->model_catalog_product->addProduct($product_data);
							if (!$product_id || $product_id == 0) {
								throw new Exception("Failed to add product - product_id was not returned");
							}
						}

						$success_count++;
					} catch (Exception $e) {
						$error_count++;
						$errors[] = 'Row ' . $row_num . ': ' . $e->getMessage();
					}
				}

				fclose($handle);

				// Always show results
				if ($success_count > 0 && $error_count > 0) {
					$this->session->data['error_warning'] = sprintf($this->language->get('text_import_partial'), $success_count, $error_count) . "<br><strong>Errors:</strong><br>" . implode("<br>", array_slice($errors, 0, 20));
					if (count($errors) > 20) {
						$this->session->data['error_warning'] .= "<br>... and " . (count($errors) - 20) . " more errors";
					}
				} elseif ($error_count > 0) {
					// All failed
					$this->session->data['error_warning'] = sprintf("Import failed: %d products could not be imported. Errors:<br>", $error_count) . implode("<br>", array_slice($errors, 0, 30));
					if (count($errors) > 30) {
						$this->session->data['error_warning'] .= "<br>... and " . (count($errors) - 30) . " more errors";
					}
				} elseif ($success_count > 0) {
					$this->session->data['success'] = sprintf($this->language->get('text_import_success'), $success_count);
				} else {
					if ($total_rows == 0) {
						$this->session->data['error_warning'] = "No data rows found in CSV file. Please ensure your CSV file contains product data rows after the header.";
					} else {
						$this->session->data['error_warning'] = sprintf("No products were imported. Processed %d rows but all failed. Please check your CSV file format and ensure products have required fields (model, name). Errors:<br>%s", $total_rows, implode("<br>", array_slice($errors, 0, 10)));
					}
				}
			} else {
				$this->error['warning'] = $this->language->get('error_file_read');
			}

			$url = '';
			$redirect_url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
			$this->response->redirect($redirect_url);
		}

		$this->getImportForm();
	}

	protected function processImage($image_path, $resize = true) {
		$image_path = trim($image_path, "\"' \t\n\r\0\x0B");
		
		// If it's a URL, download it
		if (filter_var($image_path, FILTER_VALIDATE_URL)) {
			return $this->downloadImageFromUrl($image_path);
		}
		
		// Check if path starts with full DIR_IMAGE path
		if (strpos($image_path, DIR_IMAGE) === 0) {
			$relative_path = str_replace(DIR_IMAGE, '', $image_path);
			if (is_file(DIR_IMAGE . $relative_path)) {
				return $relative_path;
			}
		}
		
		// Check if path starts with catalog/
		if (strpos($image_path, 'catalog/') === 0) {
			if (is_file(DIR_IMAGE . $image_path)) {
				// During import, skip resizing to save memory - just return the path
				if (!$resize) {
					return $image_path;
				}
				// If resize is needed (not during import), process the image
				return $image_path;
			}
		}
		
		// Try direct path
		if (is_file(DIR_IMAGE . $image_path)) {
			return $image_path;
		}
		
		return '';
	}

	protected function downloadImageFromUrl($url) {
		$path_info = pathinfo(parse_url($url, PHP_URL_PATH));
		$extension = isset($path_info['extension']) ? strtolower($path_info['extension']) : 'jpg';
		$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
		if (!in_array($extension, $allowed_extensions)) {
			$extension = 'jpg';
		}
		$filename = basename(isset($path_info['filename']) ? $path_info['filename'] : '');
		if (empty($filename)) {
			$filename = 'image_' . time();
		}
		$filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename) . '.' . $extension;
		$target_dir = DIR_IMAGE . 'catalog/import/' . date('Y/m/d') . '/';
		if (!is_dir($target_dir)) {
			mkdir($target_dir, 0755, true);
		}
		$target_file = $target_dir . $filename;
		$success = false;
		if (function_exists('curl_init')) {
			$ch = curl_init($url);
			$fp = fopen($target_file, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			fclose($fp);
			$success = ($http_code == 200 && is_file($target_file));
		} else {
			$context = stream_context_create(array('http' => array('timeout' => 30, 'follow_location' => true)));
			$image_data = @file_get_contents($url, false, $context);
			if ($image_data !== false) {
				$success = (file_put_contents($target_file, $image_data) !== false);
			}
		}
		if ($success && is_file($target_file)) {
			return 'catalog/import/' . date('Y/m/d') . '/' . $filename;
		}
		return '';
	}

	protected function validateImport() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!isset($this->request->files['import_file']) || !is_uploaded_file($this->request->files['import_file']['tmp_name'])) {
			$this->error['warning'] = $this->language->get('error_file');
		}
		return !$this->error;
	}

	protected function getImportForm() {
		$data['heading_title'] = $this->language->get('heading_title_import');
		$data['text_import'] = $this->language->get('text_import');
		$data['text_import_info_line1'] = $this->language->get('text_import_info_line1');
		$data['text_import_info_line2'] = $this->language->get('text_import_info_line2');
		$data['text_import_info_line3'] = $this->language->get('text_import_info_line3');
		$data['text_import_info_line4'] = $this->language->get('text_import_info_line4');
		$data['entry_file'] = $this->language->get('entry_file');
		$data['help_file'] = $this->language->get('help_file');
		$data['button_import'] = $this->language->get('button_import');
		$data['button_download_sample'] = $this->language->get('button_download_sample');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$url = '';
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['action'] = $this->url->link('catalog/product/import', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['download_sample'] = $this->url->link('catalog/product/downloadSample', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_import.tpl', $data));
	}

	public function downloadSample() {
		$this->load->language('catalog/product');
		$headers = array(
			'product_id','model','sku','mpn','name','description','short_description','tag','meta_title','meta_description','meta_keyword',
			'image','featured_image','additional_images','manufacturer','categories','stores','quantity','minimum','maximum','subtract','stock_status_id','date_available','price','regular_price','cost_price','tax_class_id','points','weight','weight_class_id','length','width','height','length_class_id','status','sort_order','view','keyword'
		);
		$sample = array(
			'', 'SKU-1001', 'SKU-1001', 'MPN-001', 'Sample Product', 'Long description here', 'Short desc', 'tag1|tag2', 'Sample Product', 'Meta desc', 'keyword1 keyword2',
			'https://via.placeholder.com/600x600.png', 'https://via.placeholder.com/800x800.png', 'https://via.placeholder.com/600x600.png:0|catalog/demo/iphone_1.jpg:1', 'Acme Inc', '1,2', '0', '10', '1', '0', '1', '0', date('Y-m-d'), '199.99', '249.99', '150.00', '0', '0', '0.5', (string)$this->config->get('config_weight_class_id'), '10', '5', '2', (string)$this->config->get('config_length_class_id'), 'enabled', '1', '' , ''
		);
		$fp = fopen('php://output', 'w');
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="product_import_sample.csv"');
		fputcsv($fp, $headers);
		fputcsv($fp, $sample);
		fclose($fp);
		exit;
	}

	public function download() {
        $this->load->model('catalog/product');

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_price'])) {
			$filter_price = $this->request->get['filter_price'];
		} else {
			$filter_price = null;
		}

		if (isset($this->request->get['filter_quantity'])) {
			$filter_quantity = $this->request->get['filter_quantity'];
		} else {
			$filter_quantity = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

        if (isset($this->request->get['filter_category_id'])) {
            $filter_category_id = $this->request->get['filter_category_id'];
        } else {
            $filter_category_id = null;
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $filter_manufacturer_id = $this->request->get['filter_manufacturer_id'];
        } else {
            $filter_manufacturer_id = null;
        }

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_model'	  => $filter_model,
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'filter_status'   => $filter_status,
			'filter_category_id'   => $filter_category_id,
			'filter_manufacturer_id'   => $filter_manufacturer_id,
		);


		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);
		$results = $this->model_catalog_product->getProducts($filter_data);

        $arrayToCsvLine = function(array $values) {
            $line = '';

            $values = array_map(function ($v) {
                return '"' . str_replace('"', '""', $v) . '"';
            }, $values);

            $line .= implode(',', $values);

            return $line;
        };

        $str = "ProductID,Name,Cost Price, Price,Quantity,Status".PHP_EOL;
        foreach ($results as $result) {
		    $special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $product_special['price'];

					break;
				}
			}

			$str = $str . $arrayToCsvLine(array(
				'product_id' => $result['product_id'],
				'name'       => html_entity_decode($result['name']),
				'price'      => $result['price'],
				'cost_price'      => $result['cost_price'],
				'quantity'   => $result['quantity'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
			)) . PHP_EOL;
		}


        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="products.csv"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        echo($str);
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_variation'] = $this->language->get('text_variation');
        $data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		$data['entry_name'] = $this->language->get('entry_name');
        $data['entry_sub_name'] = $this->language->get('entry_sub_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_short_description'] = $this->language->get('entry_short_description');
		$data['entry_video_url'] = $this->language->get('entry_video_url');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_mpn'] = $this->language->get('entry_mpn');
		$data['entry_short_note'] = $this->language->get('entry_short_note');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_maximum'] = $this->language->get('entry_maximum');
		$data['entry_emi'] = $this->language->get('entry_emi');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_attribute_profile'] = $this->language->get('entry_attribute_profile');
		$data['entry_filter_profile'] = $this->language->get('entry_filter_profile');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_cost_price'] = $this->language->get('entry_cost_price');
        $data['entry_regular_price'] = $this->language->get('entry_regular_price');
        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_option_points'] = $this->language->get('entry_option_points');
		$data['entry_subtract'] = $this->language->get('entry_subtract');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_featured_image'] = $this->language->get('entry_featured_image');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_compatible'] = $this->language->get('entry_compatible');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
        $data['entry_price_prefix'] = $this->language->get('entry_price_prefix');
		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_view'] = $this->language->get('entry_view');
        $data['entry_parent'] = $this->language->get('entry_parent');
        $data['entry_is_manufacturer_is_parent'] = $this->language->get('entry_is_manufacturer_is_parent');

		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_sku'] = $this->language->get('help_sku');
		$data['help_mpn'] = $this->language->get('help_mpn');
		$data['help_minimum'] = $this->language->get('help_minimum');
		$data['help_maximum'] = $this->language->get('help_maximum');
		$data['help_manufacturer'] = $this->language->get('help_manufacturer');
		$data['help_stock_status'] = $this->language->get('help_stock_status');
		$data['help_points'] = $this->language->get('help_points');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_download'] = $this->language->get('help_download');
		$data['help_related'] = $this->language->get('help_related');
		$data['help_tag'] = $this->language->get('help_tag');
		$data['help_video_url'] = $this->language->get('help_video_url');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_option_add'] = $this->language->get('button_option_add');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		$data['button_discount_add'] = $this->language->get('button_discount_add');
		$data['button_special_add'] = $this->language->get('button_special_add');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_filter'] = $this->language->get('tab_filter');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_discount'] = $this->language->get('tab_discount');
		$data['tab_special'] = $this->language->get('tab_special');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_openbay'] = $this->language->get('tab_openbay');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['sub_name'])) {
			$data['error_sub_name'] = $this->error['sub_name'];
		} else {
			$data['error_sub_name'] = array();
		}

        if (isset($this->error['short_description'])) {
            $data['error_short_description'] = $this->error['short_description'];
        } else {
            $data['error_short_description'] = array();
        }

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['date_available'])) {
			$data['error_date_available'] = $this->error['date_available'];
		} else {
			$data['error_date_available'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_category_id'])) {
            $url .= '&filter_category_id=' . $this->request->get['filter_category_id'];
        }

        if (isset($this->request->get['filter_manufacturer_id'])) {
            $url .= '&filter_manufacturer_id=' . $this->request->get['filter_manufacturer_id'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['product_id'])) {
			$data['action'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if(isset($this->request->get['product_id'])) {
		    $data['product_id'] = $this->request->get['product_id'];
        } else {
		    $data['product_id'] = '';
        }

		if (isset($this->request->post['product_description'])) {
			$data['product_description'] = $this->request->post['product_description'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
		} else {
			$data['product_description'] = array();
		}

        $this->load->model('tool/image');

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['featured_image'])) {
			$data['featured_image'] = $this->request->post['featured_image'];
		} elseif (!empty($product_info)) {
			$data['featured_image'] = $product_info['featured_image'];
		} else {
			$data['featured_image'] = '';
		}

		if (isset($this->request->post['featured_image']) && is_file(DIR_IMAGE . $this->request->post['featured_image'])) {
			$data['featured_thumb'] = $this->model_tool_image->resize($this->request->post['featured_image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['featured_image'])) {
			$data['featured_thumb'] = $this->model_tool_image->resize($product_info['featured_image'], 100, 100);
		} else {
			$data['featured_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['sku'])) {
			$data['sku'] = $this->request->post['sku'];
		} elseif (!empty($product_info)) {
			$data['sku'] = $product_info['sku'];
		} else {
			$data['sku'] = '';
		}


		if (isset($this->request->post['mpn'])) {
			$data['mpn'] = $this->request->post['mpn'];
		} elseif (!empty($product_info)) {
			$data['mpn'] = $product_info['mpn'];
		} else {
			$data['mpn'] = '';
		}

		if (isset($this->request->post['short_note'])) {
			$data['short_note'] = $this->request->post['short_note'];
		} elseif (!empty($product_info)) {
			$data['short_note'] = $product_info['short_note'];
		} else {
			$data['short_note'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['product_store'])) {
			$data['product_store'] = $this->request->post['product_store'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);
		} else {
			$data['product_store'] = array(0);
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$data['keyword'] = $product_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		if (isset($this->request->post['shipping'])) {
			$data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($product_info)) {
			$data['shipping'] = $product_info['shipping'];
		} else {
			$data['shipping'] = 1;
		}

        if (isset($this->request->post['emi'])) {
            $data['emi'] = $this->request->post['emi'];
        } elseif (!empty($product_info)) {
            $data['emi'] = $product_info['emi'];
        } else {
            $data['emi'] = 0;
        }

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
			$data['price'] = '';
		}

        if (isset($this->request->post['cost_price'])) {
            $data['cost_price'] = $this->request->post['cost_price'];
        } elseif (!empty($product_info)) {
            $data['cost_price'] = $product_info['cost_price'];
        } else {
            $data['cost_price'] = '';
        }

        if (isset($this->request->post['regular_price'])) {
            $data['regular_price'] = $this->request->post['regular_price'];
        } elseif (!empty($product_info)) {
            $data['regular_price'] = $product_info['regular_price'];
        } else {
            $data['regular_price'] = '';
        }

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
			$data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($product_info)) {
			$data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
			$data['tax_class_id'] = 0;
		}

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d');
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = 1;
		}

		if (isset($this->request->post['minimum'])) {
			$data['minimum'] = $this->request->post['minimum'];
		} elseif (!empty($product_info)) {
			$data['minimum'] = $product_info['minimum'];
		} else {
			$data['minimum'] = 1;
		}

		if (isset($this->request->post['maximum'])) {
			$data['maximum'] = $this->request->post['maximum'];
		} elseif (!empty($product_info)) {
			$data['maximum'] = $product_info['maximum'];
		} else {
			$data['maximum'] = 0;
		}

		if (isset($this->request->post['subtract'])) {
			$data['subtract'] = $this->request->post['subtract'];
		} elseif (!empty($product_info)) {
			$data['subtract'] = $product_info['subtract'];
		} else {
			$data['subtract'] = 1;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
			$data['stock_status_id'] = $this->request->post['stock_status_id'];
		} elseif (!empty($product_info)) {
			$data['stock_status_id'] = $product_info['stock_status_id'];
		} else {
			$data['stock_status_id'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['weight'])) {
			$data['weight'] = $this->request->post['weight'];
		} elseif (!empty($product_info)) {
			$data['weight'] = $product_info['weight'];
		} else {
			$data['weight'] = '';
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
			$data['weight_class_id'] = $this->request->post['weight_class_id'];
		} elseif (!empty($product_info)) {
			$data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
			$data['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['length'])) {
			$data['length'] = $this->request->post['length'];
		} elseif (!empty($product_info)) {
			$data['length'] = $product_info['length'];
		} else {
			$data['length'] = '';
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($product_info)) {
			$data['width'] = $product_info['width'];
		} else {
			$data['width'] = '';
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($product_info)) {
			$data['height'] = $product_info['height'];
		} else {
			$data['height'] = '';
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {
			$data['length_class_id'] = $this->request->post['length_class_id'];
		} elseif (!empty($product_info)) {
			$data['length_class_id'] = $product_info['length_class_id'];
		} else {
			$data['length_class_id'] = $this->config->get('config_length_class_id');
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['manufacturer_id'])) {
			$data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($product_info)) {
			$data['manufacturer_id'] = $product_info['manufacturer_id'];
		} else {
			$data['manufacturer_id'] = 0;
		}

		if (isset($this->request->post['manufacturer'])) {
			$data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

			if ($manufacturer_info) {
				$data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$data['manufacturer'] = '';
			}
		} else {
			$data['manufacturer'] = '';
		}

        if (isset($this->request->post['is_manufacturer_is_parent'])) {
            $data['is_manufacturer_is_parent'] = $this->request->post['is_manufacturer_is_parent'];
        } elseif (!empty($product_info)) {
            $data['is_manufacturer_is_parent'] = $product_info['is_manufacturer_is_parent'];
        } else {
            $data['is_manufacturer_is_parent'] = 0;
        }

        $this->load->model('catalog/category');

        if (isset($this->request->post['parent_id'])) {
            $data['parent_id'] = $this->request->post['parent_id'];
        } elseif (!empty($product_info)) {
            $data['parent_id'] = $product_info['parent_id'];
        } else {
            $data['parent_id'] = 0;
        }


        if($data['parent_id']) {
            $category_info = $this->model_catalog_category->getCategory($data['parent_id']);
            $data['path'] =  ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'];
        } else {
            $data['path'] = '';
        }

        // Categories
		if (isset($this->request->post['product_category'])) {
			$categories = $this->request->post['product_category'];
		} elseif (isset($this->request->get['product_id'])) {
			$categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
		} else {
			$categories = array();
		}

		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		// Filters
        $this->load->model('catalog/filter_profile');

        if (isset($this->request->post['product_filter_profile'])) {
            $product_filter_profiles = $this->request->post['product_filter_profile'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_filter_profiles = $this->model_catalog_product->getProductFilterProfiles($this->request->get['product_id']);
        } else {
            $product_filter_profiles = array();
        }

        $data['product_filter_profiles'] = array();

        foreach ($product_filter_profiles as $product_filter_profile_id) {
            $product_filter_profile_info = $this->model_catalog_filter_profile->getFilterProfile($product_filter_profile_id);

            if ($product_filter_profile_info) {
                $data['product_filter_profiles'][] = array(
                    'filter_profile_id' => $product_filter_profile_info['filter_profile_id'],
                    'name' => $product_filter_profile_info['name']
                );
            }
        }

        $data['filter'] = $this->load->controller("catalog/product/filter");
		
		
		// Attribute 
        if (isset($this->request->post['attribute_profile_id'])) {
            $data['attribute_profile_id'] = $this->request->post['attribute_profile_id'];
        } elseif (!empty($product_info)) {
            $data['attribute_profile_id'] = $product_info['attribute_profile_id'];
        } else {
            $data['attribute_profile_id'] = '';
        }

        if($data['attribute_profile_id']) {
            $this->load->model('catalog/attribute_profile');
            $result = $this->model_catalog_attribute_profile->getAttributeProfile($data['attribute_profile_id']);
            $data['attribute_profile'] =  $result['name'];
        } else {
            $data['attribute_profile'] = '';
        }

		$data['attribute'] = $this->load->controller("catalog/product/attribute");

        // Options
        $this->load->model('catalog/option');

        if (isset($this->request->post['product_option'])) {
            $product_options = $this->request->post['product_option'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
        } else {
            $product_options = array();
        }


        $data['product_options'] = array();

        foreach ($product_options as $product_option) {
            $product_option_value_data = array();

            if (!isset($product_option['product_option_value']) || !is_array($product_option['product_option_value']) || count($product_option['product_option_value']) < 1) {
                continue;
            }

            // Validate option_id exists
            if (!isset($product_option['option_id']) || empty($product_option['option_id'])) {
                continue;
            }

            $option_info = $this->model_catalog_option->getOption($product_option['option_id']);
            if (!$option_info) {
                continue;
            }
            
            $option_values = $this->model_catalog_option->getOptionValues($product_option['option_id']);
            if (!$option_values || !is_array($option_values)) {
                continue;
            }

            foreach ($option_values as $option_value) {
                $product_option_value = array_filter($product_option['product_option_value'], function ($item) use ($option_value) {
                    return isset($item['option_value_id']) && $item['option_value_id'] == $option_value['option_value_id'];
                });

                $product_option_value_data[] = array(
                    'option_value_id'         => $option_value['option_value_id'],
                    'name'         => $option_value['name'],
                    'selected' => count($product_option_value) > 0,
                    'show' => $product_option_value ? (isset(array_values($product_option_value)[0]['show']) ? array_values($product_option_value)[0]['show'] : false) : false
                );
            }

            $data['product_options'][] = array(
                'product_option_id'    => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id'            => $product_option['option_id'],
                'name'                 => $option_info['name'],
            );
        }

        if (isset($this->request->post['product_variation'])) {
            $product_variations = $this->request->post['product_variation'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_variations = $this->model_catalog_product->getProductVariations($this->request->get['product_id']);
        } else {
            $product_variations = array();
        }

        $data['product_variations'] = array();

        foreach ($product_variations as $product_variation) {
            if (is_file(DIR_IMAGE . $product_variation['image'])) {
                $image = $product_variation['image'];
                $thumb = $product_variation['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['product_variations'][] = array(
                'key'          => $product_variation['key'],
                'sku'          => $product_variation['sku'],
                'price_prefix' => $product_variation['price_prefix'],
                'price'        => $product_variation['price'],
                'quantity'     => $product_variation['quantity'],
                'image'        => $image,
                'thumb'        => $this->model_tool_image->resize($thumb, 100, 100),
            );
        }

		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['product_discount'])) {
			$product_discounts = $this->request->post['product_discount'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
		} else {
			$product_discounts = array();
		}

		$data['product_discounts'] = array();

		foreach ($product_discounts as $product_discount) {
			$data['product_discounts'][] = array(
				'customer_group_id' => $product_discount['customer_group_id'],
				'quantity'          => $product_discount['quantity'],
				'priority'          => $product_discount['priority'],
				'price'             => $product_discount['price'],
				'date_start'        => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
				'date_end'          => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
			);
		}

		if (isset($this->request->post['product_special'])) {
			$product_specials = $this->request->post['product_special'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
		} else {
			$product_specials = array();
		}

		$data['product_specials'] = array();

		foreach ($product_specials as $product_special) {
			$data['product_specials'][] = array(
				'customer_group_id' => $product_special['customer_group_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] :  ''
			);
		}

		// Images
		if (isset($this->request->post['product_image'])) {
			$product_images = $this->request->post['product_image'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		// Downloads
		$this->load->model('catalog/download');

		if (isset($this->request->post['product_download'])) {
			$product_downloads = $this->request->post['product_download'];
		} elseif (isset($this->request->get['product_id'])) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
		} else {
			$product_downloads = array();
		}

		$data['product_downloads'] = array();

		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($this->request->get['product_id'])) {
			$products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
		} else {
			$products = array();
		}

		$data['product_relateds'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$data['product_relateds'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

        if (isset($this->request->post['product_compatible'])) {
            $products = $this->request->post['product_compatible'];
        } elseif (isset($this->request->get['product_id'])) {
            $products = $this->model_catalog_product->getProductCompatible($this->request->get['product_id']);
        } else {
            $products = array();
        }

        $data['product_compatibles'] = array();

        foreach ($products as $product_id) {
            $compatible_info = $this->model_catalog_product->getProduct($product_id);

            if ($compatible_info) {
                $data['product_compatibles'][] = array(
                    'product_id' => $compatible_info['product_id'],
                    'name'       => $compatible_info['name']
                );
            }
        }
        
		if (isset($this->request->post['points'])) {
			$data['points'] = $this->request->post['points'];
		} elseif (!empty($product_info)) {
			$data['points'] = $product_info['points'];
		} else {
			$data['points'] = '';
		}

		if (isset($this->request->post['product_reward'])) {
			$data['product_reward'] = $this->request->post['product_reward'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
		} else {
			$data['product_reward'] = array();
		}

		if (isset($this->request->post['product_layout'])) {
			$data['product_layout'] = $this->request->post['product_layout'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);
		} else {
			$data['product_layout'] = array();
		}

        if (isset($this->request->post['view'])) {
            $data['view'] = $this->request->post['view'];
        } elseif (!empty($product_info)) {
            $data['view'] = $product_info['view'];
        } else {
            $data['view'] = '';
        }

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_form.tpl', $data));
	}

    public function filter() {
        if(isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            $product_id = null;
        }

        $this->load->model('catalog/product');
        $this->load->model('catalog/filter');

        if(isset($this->request->request['filter_profile_id'])) {
            $filter_profile_ids =  $this->request->request['filter_profile_id'];
        } elseif($product_id) {
            $filter_profile_ids = $this->model_catalog_product->getProductFilterProfiles($product_id);;
        } else {
            $filter_profile_ids = null;
        }

        $this->load->language('catalog/product');
        $data = array();

        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_filter'] = $this->language->get('entry_filter');

        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->request->post['product_filter'])) {
            $product_filters = $this->request->post['product_filter'];
        } elseif ($product_id) {
            $product_filters = $this->model_catalog_product->getProductFilters($product_id);
        } else {
            $product_filters = array();
        }
        
        // Log for debugging
        $log_file = DIR_LOGS . 'product_filter_debug.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER-CONTROLLER] product_id: $product_id" . PHP_EOL, FILE_APPEND);
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER-CONTROLLER] Raw product_filters: " . print_r($product_filters, true) . PHP_EOL, FILE_APPEND);
        
        // Normalize product_filters to integers for proper comparison
        $product_filters = array_map('intval', $product_filters);
        $product_filters = array_filter($product_filters, function($id) { return $id > 0; });
        $product_filters = array_values($product_filters); // Re-index array
        
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER-CONTROLLER] Normalized product_filters: " . print_r($product_filters, true) . PHP_EOL, FILE_APPEND);

        if($filter_profile_ids) {
            $filters = $this->model_catalog_filter->getFiltersByProfiles($filter_profile_ids);
        } else {
            $filters = array();
        }

        $filter_groups = array();

        foreach ($filters as $product_filter) {
            // Check if filter_id exists in product_filter array
            if (!isset($product_filter['filter_id'])) {
                continue;
            }
            
            $filter_info = $this->model_catalog_filter->getFilter($product_filter['filter_id']);
            if ($filter_info && isset($filter_info["filter_group_id"]) && !empty($filter_info["filter_group_id"])) {
                $filter_group_id = $filter_info["filter_group_id"];
                
                if (!isset($filter_groups[$filter_group_id])) {
                    $filter_group_info = $this->model_catalog_filter->getFilterGroup($filter_group_id);
                    
                    // Check if filter_group_info is valid and has required fields
                    if ($filter_group_info && is_array($filter_group_info) && !empty($filter_group_info)) {
                        $filter_groups[$filter_group_id] = array(
                            'filter_group_id' => isset($filter_group_info['filter_group_id']) ? $filter_group_info['filter_group_id'] : $filter_group_id,
                            'name' => isset($filter_group_info['name']) ? $filter_group_info['name'] : '',
                            'sort_order' => isset($filter_group_info['sort_order']) ? (int)$filter_group_info['sort_order'] : 0,
                            'product_filters' => array()
                        );
                    } else {
                        // If filter group doesn't exist, create a minimal entry
                        $filter_groups[$filter_group_id] = array(
                            'filter_group_id' => $filter_group_id,
                            'name' => '',
                            'sort_order' => 0,
                            'product_filters' => array()
                        );
                    }
                }
                
                // Add filter to the group
                if (isset($filter_groups[$filter_group_id])) {
                    $current_filter_id = (int)(isset($filter_info['filter_id']) ? $filter_info['filter_id'] : $product_filter['filter_id']);
                    // Check if this filter is in the product's saved filters (strict comparison with integers)
                    $is_checked = in_array($current_filter_id, $product_filters, true);
                    
                    // Log for debugging
                    $log_file = DIR_LOGS . 'product_filter_debug.log';
                    file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER-CONTROLLER] Checking filter_id: $current_filter_id, is_checked: " . ($is_checked ? 'YES' : 'NO') . PHP_EOL, FILE_APPEND);
                    
                    $filter_groups[$filter_group_id]['product_filters'][] = array(
                        'filter_id' => $current_filter_id,
                        'name' => isset($filter_info['name']) ? $filter_info['name'] : '',
                        'sort_order' => isset($filter_info['sort_order']) ? (int)$filter_info['sort_order'] : 0,
                        'checked' => $is_checked
                    );
                }
            }
        }

        function comparable($item1, $item2) {
            if($item1["sort_order"] == $item1["sort_order"]) {
                return 0;
            }
            return $item1['sort_order'] < $item2['sort_order'] ? -1 : 1;
        }

        usort($filter_groups, "comparable");
        foreach ($filter_groups as $group) {
            usort($group["product_filters"], "comparable");
        }
        $data['filter_groups'] = $filter_groups;

        if(isset($this->request->get['render'])) {
            $this->response->setOutput($this->load->view('catalog/product_filter.tpl', $data));
        } else {
            return $this->load->view('catalog/product_filter.tpl', $data);
        }
    }
    
	public function attribute() {
	    if(isset($this->request->get['product_id'])) {
	        $product_id = $this->request->get['product_id'];
        } else {
	        $product_id = null;
        }

        $this->load->model('catalog/product');

	    if($product_id) {
	        $product_info = $this->model_catalog_product->getProduct($product_id);
        } else {
            $product_info = null;
        }

        if(isset($this->request->request['attribute_profile_id'])) {
            $attribute_profile_id =  $this->request->request['attribute_profile_id'];
        } elseif($product_info) {
	        $attribute_profile_id = $product_info['attribute_profile_id'];
        } else {
	        $attribute_profile_id = null;
        }

        $this->load->language('catalog/product');
        $data = array();

        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_attribute'] = $this->language->get('entry_attribute');

        $data['button_remove'] = $this->language->get('button_remove');

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $this->load->model('catalog/attribute');

        if (isset($this->request->post['product_attribute'])) {
            $product_attributes = $this->request->post['product_attribute'];
        } elseif ($product_id) {
            $product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
        } else {
            $product_attributes = array();
        }

        if($attribute_profile_id) {
            $attributes = $this->model_catalog_attribute->getAttributesByProfile($attribute_profile_id);
        } else {
            $attributes = $product_attributes;
        }


        $attribute_groups = array();
        foreach ($attributes as $product_attribute) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);
            if ($attribute_info) {
                if (!isset($attribute_groups[$attribute_info["attribute_group_id"]])) {
                    $attribute_group_info = $this->model_catalog_attribute->getAttributeGroup($attribute_info["attribute_group_id"]);
                    $attribute_groups[$attribute_info["attribute_group_id"]] = array(
                        'attribute_group_id' => $attribute_group_info['attribute_group_id'],
                        'name' => $attribute_group_info['name'],
                        'sort_order' => $attribute_group_info['sort_order'],
                        'product_attributes' => array()
                    );
                }
                $attribute_groups[$attribute_info["attribute_group_id"]]['product_attributes'][] = array(
                    'attribute_id'                  => $attribute_info['attribute_id'],
                    'name'                          => $attribute_info['name'],
                    'sort_order'                          => $attribute_info['sort_order'],
                    'product_attribute_description' => isset($product_attributes[$attribute_info['attribute_id']]) ? $product_attributes[$attribute_info['attribute_id']]['product_attribute_description'] : array()
                );
            }
        }
        function compare_attr($item1, $item2) {
            if($item1["sort_order"] == $item2["sort_order"]) {
                return 0;
            }
            return $item1['sort_order'] < $item2['sort_order'] ? -1 : 1;
        }
        usort($attribute_groups, "compare_attr");
        foreach ($attribute_groups as $group) {
            usort($group["product_attributes"], "compare_attr");
        }
        $data['attribute_groups'] = $attribute_groups;

        if(isset($this->request->get['render'])) {
            $this->response->setOutput($this->load->view('catalog/product_attribute.tpl', $data));
        } else {
            return $this->load->view('catalog/product_attribute.tpl', $data);
        }
    }

    public function setParent() {
        $json = array();
        if($this->validateSetParent()) {
            $sql = "UPDATE " . DB_PREFIX ."product p SET p.parent_id ='" . $this->request->post['parent_id'] . "', is_manufacturer_is_parent= '" . (isset($this->request->post['is_manufacturer_is_parent']) ? (int) $this->request->post['is_manufacturer_is_parent'] : 0) . "' WHERE product_id='" .  (int) $this->request->post['product_id'] . "'";
            $this->db->query($sql);
            $json["success"] =  $this->language->get('text_success');
        } else {
            $json = $this->error;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validateSetParent() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        if(!isset($this->request->post['parent_id'])) {
            $this->error["parent"] = $this->language->get('error_parent');
        }

        if(!isset($this->request->post['product_id'])) {
            $this->error["product"] = $this->language->get('error_product');
        }
        return !$this->error;
    }

	protected function  validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['product_description']) || !is_array($this->request->post['product_description'])) {
			$this->error['product_description'] = $this->language->get('error_product_description');
			return false;
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

            if ($value['sub_name'] && (utf8_strlen($value['sub_name']) < 3 || utf8_strlen($value['sub_name']) > 255)) {
                $this->error['sub_name'][$language_id] = $this->language->get('error_sub_name');
            }

            if (utf8_strlen($value['short_description']) > 600) {
                $this->error['short_description'][$language_id] = $this->language->get('error_short_description');
            }

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['product_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

            foreach ($results as $result) {
                $option_data = array();

                $product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

                foreach ($product_options as $product_option) {
                    $option_info = $this->model_catalog_option->getOption($product_option['option_id']);

                    if ($option_info) {
                        $product_option_value_data = array();

                        foreach ($product_option['product_option_value'] as $product_option_value) {
                            $option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

                            if ($option_value_info) {
                                $product_option_value_data[] = array(
                                    'product_option_value_id' => $product_option_value['product_option_value_id'],
                                    'option_value_id'         => $product_option_value['option_value_id'],
                                    'name'                    => $option_value_info['name'],
                                );
                            }
                        }

                        $option_data[] = array(
                            'product_option_id'    => $product_option['product_option_id'],
                            'product_option_value' => $product_option_value_data,
                            'option_id'            => $product_option['option_id'],
                            'name'                 => $option_info['name'],
                            'type'                 => $option_info['type'],
                        );
                    }
                }

                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'option'     => $option_data,
                    'price'      => $result['price']
                );
            }
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
