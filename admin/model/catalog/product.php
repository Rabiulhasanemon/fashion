<?php
class ModelCatalogProduct extends Model {
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_category_id'])) {
			$sql .= " AND p.product_id IN (SELECT ptc.product_id FROM " . DB_PREFIX . "product_to_category ptc WHERE ptc.category_id = '" . (int)$data['filter_category_id'] . "')";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }

        if (isset($data['filter_category_id'])) {
			$sql .= " AND p.product_id IN (SELECT ptc.product_id FROM " . DB_PREFIX . "product_to_category ptc WHERE ptc.category_id = '" . (int)$data['filter_category_id'] . "')";
        }

        if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
        }

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority ASC, price ASC");

		return $query->rows;
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'sub_name'         => isset($result['sub_name']) ? $result['sub_name'] : '',
				'description'      => $result['description'],
				'short_description' => isset($result['short_description']) ? $result['short_description'] : '',
				'tag'              => $result['tag'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $product_description_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductFilterProfiles($product_id) {
		$product_filter_profile_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_filter_profile WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_profile_data[] = $result['filter_profile_id'];
		}

		return $product_filter_profile_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$attribute_id = $result['attribute_id'];
			$language_id = $result['language_id'];

			if (!isset($product_attribute_data[$attribute_id])) {
				$product_attribute_data[$attribute_id] = array(
					'product_attribute_description' => array()
				);
			}

			$product_attribute_data[$attribute_id]['product_attribute_description'][$language_id] = array(
				'text' => $result['text']
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'name'                    => isset($product_option_value['name']) ? $product_option_value['name'] : '',
					'show'                    => isset($product_option_value['show']) ? (int)$product_option_value['show'] : 1,
					'quantity'                => isset($product_option_value['quantity']) ? (int)$product_option_value['quantity'] : 0,
					'subtract'                => isset($product_option_value['subtract']) ? (int)$product_option_value['subtract'] : 0,
					'price'                   => isset($product_option_value['price']) ? (float)$product_option_value['price'] : 0,
					'price_prefix'            => isset($product_option_value['price_prefix']) ? $product_option_value['price_prefix'] : '+',
					'points'                   => isset($product_option_value['points']) ? (int)$product_option_value['points'] : 0,
					'points_prefix'            => isset($product_option_value['points_prefix']) ? $product_option_value['points_prefix'] : '+',
					'weight'                   => isset($product_option_value['weight']) ? (float)$product_option_value['weight'] : 0,
					'weight_prefix'            => isset($product_option_value['weight_prefix']) ? $product_option_value['weight_prefix'] : '+',
					'color'                   => isset($product_option_value['color']) ? $product_option_value['color'] : ''
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => isset($product_option['name']) ? $product_option['name'] : '',
				'type'                 => isset($product_option['type']) ? $product_option['type'] : '',
				'required'              => isset($product_option['required']) ? (int)$product_option['required'] : 0,
				'value'                => isset($product_option['value']) ? $product_option['value'] : ''
			);
		}

		return $product_option_data;
	}

	public function getProductVariations($product_id) {
		$product_variation_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_variation_data[] = array(
				'key'          => isset($result['key']) ? $result['key'] : '',
				'sku'          => isset($result['sku']) ? $result['sku'] : '',
				'price_prefix' => isset($result['price_prefix']) ? $result['price_prefix'] : '+',
				'price'        => isset($result['price']) ? (float)$result['price'] : 0,
				'quantity'     => isset($result['quantity']) ? (int)$result['quantity'] : 0,
				'image'        => isset($result['image']) ? $result['image'] : ''
			);
		}

		return $product_variation_data;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity ASC, priority ASC, price ASC");

		return $query->rows;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getProductCompatible($product_id) {
		$product_compatible_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_compatible_data[] = $result['compatible_id'];
		}

		return $product_compatible_data;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array(
				'points' => $result['points']
			);
		}

		return $product_reward_data;
	}

	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function addProduct($data) {
		// Clean up any orphaned product with product_id = 0 before inserting
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=0'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0");
		
		// Ensure auto-increment is properly set
		$max_check = $this->db->query("SELECT MAX(product_id) as max_id FROM " . DB_PREFIX . "product");
		$max_id = 0;
		if ($max_check && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
			$max_id = (int)$max_check->row['max_id'];
		}
		$next_id = max($max_id + 1, 1);
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . $next_id);
		
		// Insert main product record
		$sql = "INSERT INTO " . DB_PREFIX . "product SET ";
		$sql .= "model = '" . $this->db->escape(isset($data['model']) ? $data['model'] : '') . "', ";
		$sql .= "sku = '" . $this->db->escape(isset($data['sku']) ? $data['sku'] : '') . "', ";
		$sql .= "mpn = '" . $this->db->escape(isset($data['mpn']) ? $data['mpn'] : '') . "', ";
		$sql .= "short_note = '" . $this->db->escape(isset($data['short_note']) ? $data['short_note'] : '') . "', ";
		$sql .= "quantity = '" . (int)(isset($data['quantity']) ? $data['quantity'] : 0) . "', ";
		$sql .= "minimum = '" . (int)(isset($data['minimum']) ? $data['minimum'] : 1) . "', ";
		$sql .= "maximum = '" . (int)(isset($data['maximum']) ? $data['maximum'] : 0) . "', ";
		$sql .= "subtract = '" . (int)(isset($data['subtract']) ? $data['subtract'] : 1) . "', ";
		$sql .= "stock_status_id = '" . (int)(isset($data['stock_status_id']) ? $data['stock_status_id'] : 0) . "', ";
		$sql .= "date_available = '" . $this->db->escape(isset($data['date_available']) ? $data['date_available'] : date('Y-m-d')) . "', ";
		$sql .= "manufacturer_id = '" . (int)(isset($data['manufacturer_id']) ? $data['manufacturer_id'] : 0) . "', ";
		$sql .= "is_manufacturer_is_parent = '" . (int)(isset($data['is_manufacturer_is_parent']) ? $data['is_manufacturer_is_parent'] : 0) . "', ";
		$sql .= "parent_id = '" . (int)(isset($data['parent_id']) ? $data['parent_id'] : 0) . "', ";
		$sql .= "attribute_profile_id = '" . (int)(isset($data['attribute_profile_id']) ? $data['attribute_profile_id'] : 0) . "', ";
		$sql .= "shipping = '" . (int)(isset($data['shipping']) ? $data['shipping'] : 1) . "', ";
		$sql .= "emi = '" . (int)(isset($data['emi']) ? $data['emi'] : 0) . "', ";
		$sql .= "cost_price = '" . (float)(isset($data['cost_price']) ? $data['cost_price'] : 0) . "', ";
		$sql .= "price = '" . (float)(isset($data['price']) ? $data['price'] : 0) . "', ";
		$sql .= "regular_price = '" . (float)(isset($data['regular_price']) ? $data['regular_price'] : 0) . "', ";
		$sql .= "points = '" . (int)(isset($data['points']) ? $data['points'] : 0) . "', ";
		$sql .= "weight = '" . (float)(isset($data['weight']) ? $data['weight'] : 0) . "', ";
		$sql .= "weight_class_id = '" . (int)(isset($data['weight_class_id']) ? $data['weight_class_id'] : $this->config->get('config_weight_class_id')) . "', ";
		$sql .= "length = '" . (float)(isset($data['length']) ? $data['length'] : 0) . "', ";
		$sql .= "width = '" . (float)(isset($data['width']) ? $data['width'] : 0) . "', ";
		$sql .= "height = '" . (float)(isset($data['height']) ? $data['height'] : 0) . "', ";
		$sql .= "length_class_id = '" . (int)(isset($data['length_class_id']) ? $data['length_class_id'] : $this->config->get('config_length_class_id')) . "', ";
		$sql .= "status = '" . (int)(isset($data['status']) ? $data['status'] : 1) . "', ";
		$sql .= "tax_class_id = '" . (int)(isset($data['tax_class_id']) ? $data['tax_class_id'] : 0) . "', ";
		$sql .= "sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "', ";
		$sql .= "view = '" . $this->db->escape(isset($data['view']) ? $data['view'] : '') . "', ";
		$sql .= "image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', ";
		$sql .= "featured_image = '" . $this->db->escape(isset($data['featured_image']) ? $data['featured_image'] : '') . "', ";
		$sql .= "date_added = NOW(), ";
		$sql .= "date_modified = NOW()";

		// Get the next available product_id before inserting
		$max_query = $this->db->query("SELECT MAX(product_id) AS max_id FROM " . DB_PREFIX . "product");
		$next_product_id = 1;
		if ($max_query && $max_query->num_rows && isset($max_query->row['max_id']) && $max_query->row['max_id'] !== null) {
			$next_product_id = (int)$max_query->row['max_id'] + 1;
		}
		
		// Verify the product doesn't already exist by model or SKU
		$model = isset($data['model']) ? $data['model'] : '';
		$sku = isset($data['sku']) ? $data['sku'] : '';
		$existing_product_id = null;
		
		if ($model) {
			$check_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($model) . "' LIMIT 1");
			if ($check_query && $check_query->num_rows) {
				$existing_product_id = (int)$check_query->row['product_id'];
			}
		}
		
		if (!$existing_product_id && $sku) {
			$check_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($sku) . "' LIMIT 1");
			if ($check_query && $check_query->num_rows) {
				$existing_product_id = (int)$check_query->row['product_id'];
			}
		}
		
		// If product exists, return its ID (don't insert again)
		if ($existing_product_id) {
			return $existing_product_id;
		}
		
		// Insert the product - DO NOT suppress errors, we need to know if it fails
		// Log the SQL for debugging
		$log_file = DIR_LOGS . 'product_insert_debug.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Attempting to insert product. Next expected product_id: ' . $next_product_id . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - SQL: ' . substr($sql, 0, 500) . '...' . PHP_EOL, FILE_APPEND);
		
		$insert_result = $this->db->query($sql);
		$product_id = $this->db->getLastId();
		
		// Log result
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Insert result: ' . ($insert_result ? 'SUCCESS' : 'FAILED') . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - getLastId() returned: ' . $product_id . PHP_EOL, FILE_APPEND);
		
		// Get database error if insert failed
		if (!$insert_result) {
			$db_error = '';
			$db_errno = 0;
			// Try to get MySQL error
			if (is_object($this->db) && method_exists($this->db, 'getError')) {
				$db_error = $this->db->getError();
			} elseif (property_exists($this->db, 'link') && is_object($this->db->link)) {
				if (property_exists($this->db->link, 'error')) {
					$db_error = $this->db->link->error;
				}
				if (property_exists($this->db->link, 'errno')) {
					$db_errno = $this->db->link->errno;
				}
			}
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Database error: ' . $db_error . ' (Error No: ' . $db_errno . ')' . PHP_EOL, FILE_APPEND);
			
			// If it's a duplicate key error, throw exception with details
			if ($db_errno == 1062 || stripos($db_error, 'duplicate') !== false || stripos($db_error, 'primary') !== false) {
				$error_msg = "Duplicate entry error: " . $db_error . ". This usually means product_id = 0 exists or AUTO_INCREMENT is broken.";
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
				throw new Exception($error_msg);
			}
		}
		
		// If insert failed or product_id is 0, try to recover
		if (!$product_id || $product_id == 0) {
			// Method 1: Try to find by model (in case insert succeeded but getLastId failed)
			if ($model) {
				$find_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE model = '" . $this->db->escape($model) . "' ORDER BY product_id DESC LIMIT 1");
				if ($find_query && $find_query->num_rows) {
					$found_id = (int)$find_query->row['product_id'];
					// Only use if it's a new product (>= next_product_id)
					if ($found_id >= $next_product_id) {
						$product_id = $found_id;
					}
				}
			}
			
			// Method 2: Try to find by SKU
			if ((!$product_id || $product_id == 0) && $sku) {
				$find_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE sku = '" . $this->db->escape($sku) . "' ORDER BY product_id DESC LIMIT 1");
				if ($find_query && $find_query->num_rows) {
					$found_id = (int)$find_query->row['product_id'];
					// Only use if it's a new product (>= next_product_id)
					if ($found_id >= $next_product_id) {
						$product_id = $found_id;
					}
				}
			}
			
			// Method 3: Get the latest inserted product
			if (!$product_id || $product_id == 0) {
				$latest_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product ORDER BY product_id DESC LIMIT 1");
				if ($latest_query && $latest_query->num_rows) {
					$latest_id = (int)$latest_query->row['product_id'];
					// Only use this if it's >= our calculated next_product_id (meaning it's a new product)
					if ($latest_id >= $next_product_id) {
						$product_id = $latest_id;
					}
				}
			}
			
			// Method 4: Try inserting with explicit product_id (but check if it exists first)
			if (!$product_id || $product_id == 0) {
				// Check if next_product_id already exists
				$check_id = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$next_product_id . "' LIMIT 1");
				if (!$check_id || !$check_id->num_rows) {
					// ID doesn't exist, safe to insert
					$sql_with_id = str_replace("INSERT INTO " . DB_PREFIX . "product SET ", "INSERT INTO " . DB_PREFIX . "product SET product_id = '" . (int)$next_product_id . "', ", $sql);
					$insert_result = $this->db->query($sql_with_id);
					if ($insert_result) {
						$product_id = $this->db->getLastId();
						if (!$product_id || $product_id == 0) {
							$product_id = $next_product_id;
						}
					} else {
						// Insert failed, try to get the ID that was actually inserted
						$product_id = $this->db->getLastId();
					}
				} else {
					// ID exists, find next available
					$max_final = $this->db->query("SELECT MAX(product_id) AS max_id FROM " . DB_PREFIX . "product");
					if ($max_final && $max_final->num_rows && isset($max_final->row['max_id'])) {
						$next_product_id = (int)$max_final->row['max_id'] + 1;
					} else {
						$next_product_id = 1;
					}
					// Try again with new ID
					$sql_with_id = str_replace("INSERT INTO " . DB_PREFIX . "product SET ", "INSERT INTO " . DB_PREFIX . "product SET product_id = '" . (int)$next_product_id . "', ", $sql);
					$insert_result = $this->db->query($sql_with_id);
					if ($insert_result) {
						$product_id = $this->db->getLastId();
						if (!$product_id || $product_id == 0) {
							$product_id = $next_product_id;
						}
					}
				}
			}
		}
		
		// Final verification - ensure we have a valid product_id (must be > 0)
		if (!$product_id || $product_id <= 0) {
			// Absolute last resort: get max and add 1, then verify it doesn't exist
			$max_final = $this->db->query("SELECT MAX(product_id) AS max_id FROM " . DB_PREFIX . "product");
			$final_id = 1;
			if ($max_final && $max_final->num_rows && isset($max_final->row['max_id'])) {
				$final_id = (int)$max_final->row['max_id'] + 1;
			}
			// Verify this ID doesn't exist
			$verify_id = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$final_id . "' LIMIT 1");
			if ($verify_id && $verify_id->num_rows) {
				// ID exists, keep incrementing until we find one that doesn't
				while ($verify_id && $verify_id->num_rows) {
					$final_id++;
					$verify_id = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$final_id . "' LIMIT 1");
				}
			}
			// Now insert with this verified ID
			$sql_with_id = str_replace("INSERT INTO " . DB_PREFIX . "product SET ", "INSERT INTO " . DB_PREFIX . "product SET product_id = '" . (int)$final_id . "', ", $sql);
			$insert_result = $this->db->query($sql_with_id);
			if ($insert_result) {
				$product_id = $this->db->getLastId();
				if (!$product_id || $product_id <= 0) {
					$product_id = $final_id;
				}
			} else {
				// Get the actual database error
				$db_error = '';
				if (method_exists($this->db, 'getError')) {
					$db_error = $this->db->getError();
				} elseif (isset($this->db->error)) {
					$db_error = $this->db->error;
				}
				
				$error_msg = "Failed to insert product: Duplicate entry or database error.";
				if ($db_error) {
					$error_msg .= " Database error: " . $db_error;
				}
				$error_msg .= " Please check your database. Try running admin/test_product_insert.php for diagnostics.";
				
				// Log detailed error
				$error_log_file = DIR_LOGS . 'product_insert_error.log';
				file_put_contents($error_log_file, date('Y-m-d H:i:s') . ' - CRITICAL ERROR: ' . $error_msg . PHP_EOL, FILE_APPEND);
				file_put_contents($error_log_file, date('Y-m-d H:i:s') . ' - SQL: ' . substr($sql_with_id, 0, 500) . '...' . PHP_EOL, FILE_APPEND);
				file_put_contents($error_log_file, date('Y-m-d H:i:s') . ' - Attempted product_id: ' . $final_id . PHP_EOL, FILE_APPEND);
				if ($db_error) {
					file_put_contents($error_log_file, date('Y-m-d H:i:s') . ' - Database error: ' . $db_error . PHP_EOL, FILE_APPEND);
				}
				
				throw new Exception($error_msg);
			}
		}
		
		// Final check - product_id must be > 0
		if ($product_id <= 0) {
			$error_msg = "Invalid product_id after insertion: " . $product_id . ". Please check your database. Try running admin/test_product_insert.php for diagnostics.";
			
			// Log error
			$error_log_file = DIR_LOGS . 'product_insert_error.log';
			file_put_contents($error_log_file, date('Y-m-d H:i:s') . ' - CRITICAL ERROR: ' . $error_msg . PHP_EOL, FILE_APPEND);
			file_put_contents($error_log_file, date('Y-m-d H:i:s') . ' - Final product_id: ' . $product_id . PHP_EOL, FILE_APPEND);
			
			throw new Exception($error_msg);
		}
		
		// Log success
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - SUCCESS: Product inserted with product_id: ' . $product_id . PHP_EOL, FILE_APPEND);
		
		// CRITICAL: Verify product_id is valid before proceeding with related data
		if ($product_id <= 0) {
			$error_msg = "CRITICAL: Product was inserted but product_id is invalid (" . $product_id . "). Cannot proceed with related data insertion.";
			file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
			throw new Exception($error_msg);
		}

		// Insert product descriptions
		if (isset($data['product_description']) && is_array($data['product_description'])) {
			foreach ($data['product_description'] as $language_id => $value) {
				// Check if description already exists
				$check_desc = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$language_id . "' LIMIT 1");
				if (!$check_desc || !$check_desc->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
						product_id = '" . (int)$product_id . "', 
						language_id = '" . (int)$language_id . "', 
						name = '" . $this->db->escape(isset($value['name']) ? $value['name'] : '') . "', 
						sub_name = '" . $this->db->escape(isset($value['sub_name']) ? $value['sub_name'] : '') . "', 
						description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', 
						short_description = '" . $this->db->escape(isset($value['short_description']) ? $value['short_description'] : '') . "', 
						tag = '" . $this->db->escape(isset($value['tag']) ? $value['tag'] : '') . "', 
						meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', 
						meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', 
						meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
				}
			}
		} else {
			// Insert default description if none provided
			$default_language_id = $this->config->get('config_language_id');
			$name = isset($data['name']) ? $data['name'] : '';
			$check_desc = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$default_language_id . "' LIMIT 1");
			if (!$check_desc || !$check_desc->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
					product_id = '" . (int)$product_id . "', 
					language_id = '" . (int)$default_language_id . "', 
					name = '" . $this->db->escape($name) . "', 
					sub_name = '', 
					description = '', 
					short_description = '', 
					tag = '', 
					meta_title = '" . $this->db->escape($name) . "', 
					meta_description = '', 
					meta_keyword = ''");
			}
		}

		// Insert product to store
		if (isset($data['product_store']) && is_array($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$check_store = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . (int)$store_id . "' LIMIT 1");
				if (!$check_store || !$check_store->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
				}
			}
		} else {
			// Default to store 0 if none specified
			$check_store = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '0' LIMIT 1");
			if (!$check_store || !$check_store->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
			}
		}

		// Insert product categories
		if (isset($data['product_category']) && is_array($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$check_cat = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' AND category_id = '" . (int)$category_id . "' LIMIT 1");
				if (!$check_cat || !$check_cat->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
				}
			}
		}

		// Insert product images - delete existing first, then insert
		// IMPORTANT: Only insert images if we have a valid product_id > 0
		if ($product_id > 0 && isset($data['product_image']) && is_array($data['product_image'])) {
			// Log image insertion attempt
			$log_file = DIR_LOGS . 'product_insert_debug.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Attempting to insert ' . count($data['product_image']) . ' product images for product_id: ' . $product_id . PHP_EOL, FILE_APPEND);
			
			// Clean up any orphaned product_image records with product_id = 0
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0");
			
			// Also clean up any product_image records with product_image_id = 0
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
			
			// Verify product exists before inserting images
			$verify_product = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			if (!$verify_product || !$verify_product->num_rows) {
				$error_msg = "Cannot insert product images: Product with ID " . $product_id . " does not exist in database!";
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
				throw new Exception($error_msg);
			}
			
			foreach ($data['product_image'] as $index => $product_image) {
				$image_path = isset($product_image['image']) ? trim($product_image['image']) : '';
				$sort_order = isset($product_image['sort_order']) ? (int)$product_image['sort_order'] : 0;
				
				// Validate product_id again (double check)
				if ($product_id <= 0) {
					$error_msg = "Cannot insert product image #" . ($index + 1) . ": Invalid product_id (" . $product_id . ")";
					file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
					throw new Exception($error_msg);
				}
				
				if ($image_path && $image_path !== '') {
					// Delete ALL existing records for this product/image combination first
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "'");
					// Also delete any records with empty image for this product
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND (image = '' OR image IS NULL)");
					
					// Now insert - check if it already exists first
					$check_existing = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' LIMIT 1");
					if (!$check_existing || !$check_existing->num_rows) {
						$insert_sql = "INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . $sort_order . "'";
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Inserting image #' . ($index + 1) . ': product_id=' . $product_id . ', image=' . substr($image_path, 0, 50) . '...' . PHP_EOL, FILE_APPEND);
						
						$result = $this->db->query($insert_sql);
						
						if (!$result) {
							// Get database error
							$db_error = '';
							$db_errno = 0;
							if (property_exists($this->db, 'link') && is_object($this->db->link)) {
								if (property_exists($this->db->link, 'error')) {
									$db_error = $this->db->link->error;
								}
								if (property_exists($this->db->link, 'errno')) {
									$db_errno = $this->db->link->errno;
								}
							}
							
							$error_msg = "Failed to insert product image #" . ($index + 1) . ". Product ID: " . $product_id . ", Image: " . substr($image_path, 0, 50);
							if ($db_error) {
								$error_msg .= ", Database error: " . $db_error . " (Error No: " . $db_errno . ")";
							}
							
							file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
							file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - SQL: ' . $insert_sql . PHP_EOL, FILE_APPEND);
							
							// If it's a duplicate key error for PRIMARY key, throw exception
							if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'product_image_id') !== false)) {
								$error_msg = "Duplicate entry '0' for key 'PRIMARY' in product_image table. This means product_image_id = 0 exists or AUTO_INCREMENT is broken.";
								file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - CRITICAL: ' . $error_msg . PHP_EOL, FILE_APPEND);
								throw new Exception($error_msg);
							}
							// For other errors, just log and continue
						} else {
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image #' . ($index + 1) . ' inserted successfully' . PHP_EOL, FILE_APPEND);
						}
					}
				}
			}
		} elseif ($product_id <= 0) {
			// Log error if product_id is invalid
			$error_msg = "Cannot insert product images: Invalid product_id (" . $product_id . ")";
			file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
			throw new Exception($error_msg);
		}

		// Insert keyword if provided - delete existing first, then insert
		if (isset($data['keyword']) && $data['keyword']) {
			$keyword = trim($data['keyword']);
			if ($keyword && $keyword !== '') {
				// Delete any existing url_alias for this product first
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
				// Check if keyword already exists for a different product
				$check_keyword = $this->db->query("SELECT url_alias_id, query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' LIMIT 1");
				if ($check_keyword && $check_keyword->num_rows) {
					$existing_query = isset($check_keyword->row['query']) ? $check_keyword->row['query'] : '';
					if ($existing_query != 'product_id=' . (int)$product_id) {
						// Keyword exists for different product, make it unique
						$keyword = $keyword . '-' . $product_id;
					}
				}
				// Delete any existing record with this keyword
				$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");
				// Now insert - check if it already exists first
				$check_existing = $this->db->query("SELECT url_alias_id FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "' AND keyword = '" . $this->db->escape($keyword) . "' LIMIT 1");
				if (!$check_existing || !$check_existing->num_rows) {
					$result = $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					// If insert failed due to duplicate key, it's okay - record already exists
				}
			}
		}

		return $product_id;
	}

	public function editProduct($product_id, $data) {
		// Validate product_id
		$product_id = (int)$product_id;
		if ($product_id <= 0) {
			throw new Exception('Invalid product ID: ' . $product_id);
		}

		// Verify product exists before updating
		$check_query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "' LIMIT 1");
		if (!$check_query->num_rows) {
			throw new Exception('Product with ID ' . $product_id . ' does not exist');
		}

		// Update main product record
		$sql = "UPDATE " . DB_PREFIX . "product SET ";
		$sql .= "model = '" . $this->db->escape(isset($data['model']) ? $data['model'] : '') . "', ";
		$sql .= "sku = '" . $this->db->escape(isset($data['sku']) ? $data['sku'] : '') . "', ";
		$sql .= "mpn = '" . $this->db->escape(isset($data['mpn']) ? $data['mpn'] : '') . "', ";
		$sql .= "short_note = '" . $this->db->escape(isset($data['short_note']) ? $data['short_note'] : '') . "', ";
		$sql .= "quantity = '" . (int)(isset($data['quantity']) ? $data['quantity'] : 0) . "', ";
		$sql .= "minimum = '" . (int)(isset($data['minimum']) ? $data['minimum'] : 1) . "', ";
		$sql .= "maximum = '" . (int)(isset($data['maximum']) ? $data['maximum'] : 0) . "', ";
		$sql .= "subtract = '" . (int)(isset($data['subtract']) ? $data['subtract'] : 1) . "', ";
		$sql .= "stock_status_id = '" . (int)(isset($data['stock_status_id']) ? $data['stock_status_id'] : 0) . "', ";
		$sql .= "date_available = '" . $this->db->escape(isset($data['date_available']) ? $data['date_available'] : date('Y-m-d')) . "', ";
		$sql .= "manufacturer_id = '" . (int)(isset($data['manufacturer_id']) ? $data['manufacturer_id'] : 0) . "', ";
		$sql .= "is_manufacturer_is_parent = '" . (int)(isset($data['is_manufacturer_is_parent']) ? $data['is_manufacturer_is_parent'] : 0) . "', ";
		$sql .= "parent_id = '" . (int)(isset($data['parent_id']) ? $data['parent_id'] : 0) . "', ";
		$sql .= "attribute_profile_id = '" . (int)(isset($data['attribute_profile_id']) ? $data['attribute_profile_id'] : 0) . "', ";
		$sql .= "shipping = '" . (int)(isset($data['shipping']) ? $data['shipping'] : 1) . "', ";
		$sql .= "emi = '" . (int)(isset($data['emi']) ? $data['emi'] : 0) . "', ";
		$sql .= "cost_price = '" . (float)(isset($data['cost_price']) ? $data['cost_price'] : 0) . "', ";
		$sql .= "price = '" . (float)(isset($data['price']) ? $data['price'] : 0) . "', ";
		$sql .= "regular_price = '" . (float)(isset($data['regular_price']) ? $data['regular_price'] : 0) . "', ";
		$sql .= "points = '" . (int)(isset($data['points']) ? $data['points'] : 0) . "', ";
		$sql .= "weight = '" . (float)(isset($data['weight']) ? $data['weight'] : 0) . "', ";
		$sql .= "weight_class_id = '" . (int)(isset($data['weight_class_id']) ? $data['weight_class_id'] : $this->config->get('config_weight_class_id')) . "', ";
		$sql .= "length = '" . (float)(isset($data['length']) ? $data['length'] : 0) . "', ";
		$sql .= "width = '" . (float)(isset($data['width']) ? $data['width'] : 0) . "', ";
		$sql .= "height = '" . (float)(isset($data['height']) ? $data['height'] : 0) . "', ";
		$sql .= "length_class_id = '" . (int)(isset($data['length_class_id']) ? $data['length_class_id'] : $this->config->get('config_length_class_id')) . "', ";
		$sql .= "status = '" . (int)(isset($data['status']) ? $data['status'] : 1) . "', ";
		$sql .= "tax_class_id = '" . (int)(isset($data['tax_class_id']) ? $data['tax_class_id'] : 0) . "', ";
		$sql .= "sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "', ";
		$sql .= "view = '" . $this->db->escape(isset($data['view']) ? $data['view'] : '') . "', ";
		$sql .= "image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', ";
		$sql .= "featured_image = '" . $this->db->escape(isset($data['featured_image']) ? $data['featured_image'] : '') . "', ";
		$sql .= "date_modified = NOW() ";
		$sql .= "WHERE product_id = '" . (int)$product_id . "'";

		$this->db->query($sql);

		// Update product descriptions
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product_id . "'");
		if (isset($data['product_description']) && is_array($data['product_description'])) {
			foreach ($data['product_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
						product_id = '" . $product_id . "', 
						language_id = '" . $language_id . "', 
						name = '" . $this->db->escape(isset($value['name']) ? $value['name'] : '') . "', 
						sub_name = '" . $this->db->escape(isset($value['sub_name']) ? $value['sub_name'] : '') . "', 
						description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', 
						short_description = '" . $this->db->escape(isset($value['short_description']) ? $value['short_description'] : '') . "', 
						tag = '" . $this->db->escape(isset($value['tag']) ? $value['tag'] : '') . "', 
						meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', 
						meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', 
						meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
				}
			}
		}

		// Update product to store
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . $product_id . "'");
		if (isset($data['product_store']) && is_array($data['product_store']) && !empty($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . $product_id . "', store_id = '" . $store_id . "'");
				}
			}
		} else {
			// Default to store 0 if no stores specified
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . $product_id . "', store_id = '0'");
		}

		// Update product categories
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "'");
		if (isset($data['product_category']) && is_array($data['product_category']) && !empty($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$category_id = (int)$category_id;
				if ($category_id > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . $product_id . "', category_id = '" . $category_id . "'");
				}
			}
		}

		// Update product images
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . $product_id . "'");
		if (isset($data['product_image']) && is_array($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$image_path = isset($product_image['image']) ? trim($product_image['image']) : '';
				$sort_order = isset($product_image['sort_order']) ? (int)$product_image['sort_order'] : 0;
				// Only insert if image path is not empty
				if ($image_path && $image_path !== '') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . $product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . $sort_order . "'");
				}
			}
		}

		// Update keyword
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . $product_id . "'");
		if (isset($data['keyword']) && !empty(trim($data['keyword']))) {
			$keyword = trim($data['keyword']);
			// Check if keyword already exists for a different product
			$existing = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND query != 'product_id=" . $product_id . "' LIMIT 1");
			if (!$existing->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . $product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
			}
		}
	}

	public function deleteProduct($product_id) {
		// Delete from all related tables
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_filter_profile WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		
		// Delete product options and option values
		$product_option_query = $this->db->query("SELECT product_option_id FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		foreach ($product_option_query->rows as $product_option) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_compatible WHERE compatible_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
		
		// Delete reviews if they exist
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		
		// Finally delete the main product record
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
	}
}

