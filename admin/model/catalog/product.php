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
				'video_url'        => isset($result['video_url']) ? $result['video_url'] : '',
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
		// Ensure video_url column exists
		$this->ensureVideoUrlColumn();
		
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
			
			// If it's a duplicate key error, clean up and retry
			if ($db_errno == 1062 || stripos($db_error, 'duplicate') !== false || stripos($db_error, 'primary') !== false) {
				// Clean up any orphaned records with product_id = 0
				$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = 0");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = 0");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = 0");
				
				// Fix AUTO_INCREMENT
				$max_retry = $this->db->query("SELECT MAX(product_id) as max_id FROM " . DB_PREFIX . "product WHERE product_id > 0");
				$next_retry = 1;
				if ($max_retry && $max_retry->num_rows && isset($max_retry->row['max_id']) && $max_retry->row['max_id'] !== null) {
					$next_retry = (int)$max_retry->row['max_id'] + 1;
				}
				$this->db->query("ALTER TABLE " . DB_PREFIX . "product AUTO_INCREMENT = " . $next_retry);
				
				// Retry the insert
				$insert_result = $this->db->query($sql);
				$product_id = $this->db->getLastId();
				
				if (!$insert_result || $product_id <= 0) {
					$error_msg = "Duplicate entry error: " . $db_error . ". Failed to insert product even after cleanup and retry.";
					file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
					throw new Exception($error_msg);
				}
			} else {
				$error_msg = "Database error: " . $db_error;
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

		// CRITICAL: Verify product_id is valid before proceeding with related data
		if ($product_id <= 0) {
			$error_msg = "CRITICAL: Product was inserted but product_id is invalid (" . $product_id . "). Cannot proceed with related data insertion.";
			file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
			throw new Exception($error_msg);
		}
		
		// Insert product descriptions
		if (isset($data['product_description']) && is_array($data['product_description'])) {
			foreach ($data['product_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				// CRITICAL: Ensure both product_id and language_id are valid before inserting
				if ($language_id > 0 && $product_id > 0) {
					// Delete any existing record first (safety)
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . $language_id . "'");
					
					$result = $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
						product_id = '" . (int)$product_id . "', 
						language_id = '" . $language_id . "', 
						name = '" . $this->db->escape(isset($value['name']) ? $value['name'] : '') . "', 
						sub_name = '" . $this->db->escape(isset($value['sub_name']) ? $value['sub_name'] : '') . "', 
						description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', 
						short_description = '" . $this->db->escape(isset($value['short_description']) ? $value['short_description'] : '') . "', 
						video_url = '" . $this->db->escape(isset($value['video_url']) ? $value['video_url'] : '') . "', 
						tag = '" . $this->db->escape(isset($value['tag']) ? $value['tag'] : '') . "', 
						meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', 
						meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', 
						meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
					
					// Check for errors
					if (!$result) {
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
						
						// If duplicate key error, delete and retry once
						if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'duplicate') !== false)) {
							// Delete any conflicting record and retry
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . $language_id . "'");
							
							// Retry the insert
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
								product_id = '" . (int)$product_id . "', 
								language_id = '" . $language_id . "', 
								name = '" . $this->db->escape(isset($value['name']) ? $value['name'] : '') . "', 
								sub_name = '" . $this->db->escape(isset($value['sub_name']) ? $value['sub_name'] : '') . "', 
								description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', 
								short_description = '" . $this->db->escape(isset($value['short_description']) ? $value['short_description'] : '') . "', 
								video_url = '" . $this->db->escape(isset($value['video_url']) ? $value['video_url'] : '') . "', 
								tag = '" . $this->db->escape(isset($value['tag']) ? $value['tag'] : '') . "', 
								meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', 
								meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', 
								meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
						}
					}
				}
			}
		} else {
			// Insert default description if none provided
			$default_language_id = $this->config->get('config_language_id');
			$name = isset($data['name']) ? $data['name'] : '';
			// CRITICAL: Ensure product_id is valid before inserting
			if ($product_id > 0 && $default_language_id > 0) {
				// Delete any existing record first (safety)
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$default_language_id . "'");
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
					product_id = '" . (int)$product_id . "', 
					language_id = '" . (int)$default_language_id . "', 
					name = '" . $this->db->escape($name) . "', 
					sub_name = '', 
					description = '', 
					short_description = '', 
					video_url = '', 
					tag = '', 
					meta_title = '" . $this->db->escape($name) . "', 
					meta_description = '', 
					meta_keyword = ''");
			}
		}

		// Insert product to store
		// CRITICAL: Ensure product_id is valid before inserting
		if ($product_id > 0) {
			if (isset($data['product_store']) && is_array($data['product_store'])) {
				foreach ($data['product_store'] as $store_id) {
					$store_id = (int)$store_id;
					if ($store_id >= 0) {
						// Delete any existing record first (safety)
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '" . $store_id . "'");
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . $store_id . "'");
					}
				}
			} else {
				// Default to store 0 if none specified
				// Delete any existing record first (safety)
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "' AND store_id = '0'");
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
			
			// CRITICAL: ALWAYS fix product_image_id = 0 and AUTO_INCREMENT BEFORE any operations
			$zero_check = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
			$zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;
			if ($zero_count > 0) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Found ' . $zero_count . ' record(s) with product_image_id = 0. Deleting...' . PHP_EOL, FILE_APPEND);
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
			}
			
			// Fix AUTO_INCREMENT to ensure it's correct
			$max_check_initial = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
			$max_id_initial = 0;
			if ($max_check_initial && $max_check_initial->num_rows && isset($max_check_initial->row['max_id']) && $max_check_initial->row['max_id'] !== null) {
				$max_id_initial = (int)$max_check_initial->row['max_id'];
			}
			$next_id_initial = max($max_id_initial + 1, 1);
			$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id_initial);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Set AUTO_INCREMENT to ' . $next_id_initial . ' before starting' . PHP_EOL, FILE_APPEND);
			
			// CRITICAL: Clean up any orphaned product_image records with product_id = 0
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0");
			
			// CRITICAL: Clean up any product_image records with product_image_id = 0 (double-check)
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
			
			// CRITICAL: Fix AUTO_INCREMENT before starting image insertion
			$max_check = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
			$max_id = 0;
			if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
				$max_id = (int)$max_check->row['max_id'];
			}
			$next_id = max($max_id + 1, 1);
			$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Fixed AUTO_INCREMENT to ' . $next_id . ' before starting image insertion' . PHP_EOL, FILE_APPEND);
			
			// Verify product exists before inserting images
			$verify_product = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "' LIMIT 1");
			if (!$verify_product || !$verify_product->num_rows) {
				$error_msg = "Cannot insert product images: Product with ID " . $product_id . " does not exist in database!";
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
				throw new Exception($error_msg);
			}
			
			// Process all images in a loop
			$total_images = count($data['product_image']);
			$successful_images = 0;
			$failed_images = 0;
			
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Starting to process ' . $total_images . ' images' . PHP_EOL, FILE_APPEND);
			
			foreach ($data['product_image'] as $index => $product_image) {
				$image_path = isset($product_image['image']) ? trim($product_image['image']) : '';
				$sort_order = isset($product_image['sort_order']) ? (int)$product_image['sort_order'] : 0;
				
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Processing image #' . ($index + 1) . '/' . $total_images . ': path=' . substr($image_path, 0, 50) . '...' . PHP_EOL, FILE_APPEND);
				
				// Validate product_id again (double check)
				if ($product_id <= 0) {
					$error_msg = "Cannot insert product image #" . ($index + 1) . ": Invalid product_id (" . $product_id . ")";
					file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
					$failed_images++;
					continue; // Skip this image and continue with next
				}
				
				if ($image_path && $image_path !== '') {
					// Delete ALL existing records for this product/image combination first
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "'");
					// Also delete any records with empty image for this product
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND (image = '' OR image IS NULL)");
					
					// Now insert - check if it already exists first
					$check_existing = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' LIMIT 1");
					if (!$check_existing || !$check_existing->num_rows) {
						// CRITICAL: Delete any product_image_id = 0 before each insert
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
						
						// Before inserting, ensure AUTO_INCREMENT is correct
						// Get current max product_image_id (excluding 0)
						$max_pi_check = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
						$next_pi_id = 1;
						if ($max_pi_check && $max_pi_check->num_rows && isset($max_pi_check->row['max_id']) && $max_pi_check->row['max_id'] !== null) {
							$next_pi_id = (int)$max_pi_check->row['max_id'] + 1;
						}
						
						// Ensure AUTO_INCREMENT is at least next_pi_id
						$safe_next_id = max($next_pi_id, 1);
						$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $safe_next_id);
						
						// Verify AUTO_INCREMENT was set correctly
						$verify_ai = $this->db->query("SHOW CREATE TABLE " . DB_PREFIX . "product_image");
						if ($verify_ai && $verify_ai->num_rows) {
							$create_table = isset($verify_ai->row['Create Table']) ? $verify_ai->row['Create Table'] : (isset($verify_ai->row[1]) ? $verify_ai->row[1] : '');
							if ($create_table && preg_match('/AUTO_INCREMENT=(\d+)/i', $create_table, $ai_matches)) {
								$actual_ai = $ai_matches[1];
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - AUTO_INCREMENT verified: ' . $actual_ai . ' (expected: ' . $safe_next_id . ')' . PHP_EOL, FILE_APPEND);
							}
						}
						
						// EXPLICITLY set product_image_id to ensure it's not 0
						$insert_sql = "INSERT INTO " . DB_PREFIX . "product_image SET product_image_id = '" . (int)$safe_next_id . "', product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . (int)$sort_order . "'";
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Inserting image #' . ($index + 1) . ' with explicit product_image_id=' . $safe_next_id . ': product_id=' . $product_id . ', image=' . substr($image_path, 0, 50) . '...' . PHP_EOL, FILE_APPEND);
						
						$result = $this->db->query($insert_sql);
						
						if ($result) {
							// CRITICAL: ALWAYS query the database to get the actual inserted ID
							// Don't rely on getLastId() as it may be unreliable
							$find = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' ORDER BY product_image_id DESC LIMIT 1");
							
							if ($find && $find->num_rows) {
								$inserted_id = (int)$find->row['product_image_id'];
								$getLastId_value = $this->db->getLastId();
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - getLastId() returned: ' . $getLastId_value . ', actual ID from database: ' . $inserted_id . PHP_EOL, FILE_APPEND);
								
								// If the actual ID is 0, this is a critical problem - delete it and retry with explicit ID
								if ($inserted_id == 0) {
									file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - CRITICAL: Record was inserted with product_image_id = 0! Deleting and retrying...' . PHP_EOL, FILE_APPEND);
									$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0 AND product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "'");
									
									// Fix AUTO_INCREMENT - ensure it's higher than any existing ID
									$max_retry = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
									$next_retry = 1;
									if ($max_retry && $max_retry->num_rows && isset($max_retry->row['max_id']) && $max_retry->row['max_id'] !== null) {
										$next_retry = (int)$max_retry->row['max_id'] + 1;
									}
									// Ensure AUTO_INCREMENT is at least 1, never 0
									$next_retry = max($next_retry, 1);
									$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_retry);
									file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Fixed AUTO_INCREMENT to ' . $next_retry . ' and retrying insert with explicit ID...' . PHP_EOL, FILE_APPEND);
									
									// Retry the insert with explicit ID
									$insert_sql_retry = "INSERT INTO " . DB_PREFIX . "product_image SET product_image_id = '" . (int)$next_retry . "', product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . (int)$sort_order . "'";
									$result_retry = $this->db->query($insert_sql_retry);
									if ($result_retry) {
										$find_retry = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' ORDER BY product_image_id DESC LIMIT 1");
										if ($find_retry && $find_retry->num_rows) {
											$inserted_id = (int)$find_retry->row['product_image_id'];
											file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retry successful, actual ID: ' . $inserted_id . PHP_EOL, FILE_APPEND);
										} else {
											$inserted_id = 0;
										}
									} else {
										$inserted_id = 0;
									}
								}
								
								if ($inserted_id > 0) {
									$successful_images++;
									file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image #' . ($index + 1) . ' inserted successfully with product_image_id: ' . $inserted_id . PHP_EOL, FILE_APPEND);
								} else {
									$failed_images++;
									file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - FAILED: Image #' . ($index + 1) . ' insert resulted in product_image_id = 0' . PHP_EOL, FILE_APPEND);
									continue; // Skip to next image
								}
							} else {
								file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - CRITICAL: Insert reported success but record not found in database! Image: ' . substr($image_path, 0, 50) . PHP_EOL, FILE_APPEND);
								$failed_images++;
								continue; // Skip to next image
							}
						}
						
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
							
							// If it's a duplicate key error for PRIMARY key, try to fix and retry with new explicit ID
							if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'product_image_id') !== false)) {
								// Clean up product_image_id = 0 again (in case it was created)
								$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
								
								// Get new max and fix AUTO_INCREMENT (excluding 0)
								$max_pi_retry = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
								$next_pi_id_retry = 1;
								if ($max_pi_retry && $max_pi_retry->num_rows && isset($max_pi_retry->row['max_id']) && $max_pi_retry->row['max_id'] !== null) {
									$next_pi_id_retry = (int)$max_pi_retry->row['max_id'] + 1;
								}
								$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_pi_id_retry);
								
								// Retry the insert with explicit ID
								$insert_sql_retry = "INSERT INTO " . DB_PREFIX . "product_image SET product_image_id = '" . (int)$next_pi_id_retry . "', product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . (int)$sort_order . "'";
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retrying image #' . ($index + 1) . ' insert with explicit product_image_id=' . $next_pi_id_retry . ' after duplicate key error' . PHP_EOL, FILE_APPEND);
								$result = $this->db->query($insert_sql_retry);
								
								if (!$result) {
									// Get error again
									$db_error_retry = '';
									$db_errno_retry = 0;
									if (property_exists($this->db, 'link') && is_object($this->db->link)) {
										if (property_exists($this->db->link, 'error')) {
											$db_error_retry = $this->db->link->error;
										}
										if (property_exists($this->db->link, 'errno')) {
											$db_errno_retry = $this->db->link->errno;
										}
									}
									
									$error_msg = "Failed to insert image #" . ($index + 1) . " even after retry. Error: " . $db_error_retry . " (" . $db_errno_retry . ")";
									file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
									// Don't throw exception - continue with next image
									file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Skipping image #' . ($index + 1) . ' due to insert failure, continuing with next image' . PHP_EOL, FILE_APPEND);
									continue; // Skip to next image instead of throwing exception
								} else {
									file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image #' . ($index + 1) . ' inserted successfully after retry' . PHP_EOL, FILE_APPEND);
									// CRITICAL: ALWAYS query the database to get the actual inserted ID
									$find_retry = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' ORDER BY product_image_id DESC LIMIT 1");
									if ($find_retry && $find_retry->num_rows) {
										$inserted_id = (int)$find_retry->row['product_image_id'];
										$getLastId_value = $this->db->getLastId();
										file_put_contents($log_file, date('Y-m-d H:i:s') . ' - After retry: getLastId() returned: ' . $getLastId_value . ', actual ID from database: ' . $inserted_id . PHP_EOL, FILE_APPEND);
										
										if ($inserted_id > 0) {
											$successful_images++;
										} else {
											$failed_images++;
											file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - FAILED: Image #' . ($index + 1) . ' insert resulted in product_image_id = 0 even after retry' . PHP_EOL, FILE_APPEND);
											continue; // Skip to next image
										}
									} else {
										$failed_images++;
										file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - FAILED: Image #' . ($index + 1) . ' insert reported success but record not found after retry' . PHP_EOL, FILE_APPEND);
										continue; // Skip to next image
									}
								}
							} else {
								// For other errors, just log and continue to next image
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Skipping image #' . ($index + 1) . ' due to error, continuing with next image' . PHP_EOL, FILE_APPEND);
								$failed_images++;
								continue; // Skip to next image
							}
						}
						
						// Handle successful insert (either first try or after retry from error handler)
						if (isset($inserted_id) && $inserted_id > 0) {
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Processing successful insert for image #' . ($index + 1) . ' with product_image_id: ' . $inserted_id . PHP_EOL, FILE_APPEND);
							
							// Verify the inserted record exists
							$verify_insert = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_image_id = '" . (int)$inserted_id . "' LIMIT 1");
							if ($verify_insert && $verify_insert->num_rows) {
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Verified: product_image_id ' . $inserted_id . ' exists in database' . PHP_EOL, FILE_APPEND);
							} else {
								file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - WARNING: product_image_id ' . $inserted_id . ' not found after insert!' . PHP_EOL, FILE_APPEND);
							}
							
							// CRITICAL: Update AUTO_INCREMENT for next image (important for multiple images)
							// Delete any product_image_id = 0 that might have been created
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
							
							// Get max excluding 0
							$max_after_insert = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
							$next_after = 1;
							if ($max_after_insert && $max_after_insert->num_rows && isset($max_after_insert->row['max_id']) && $max_after_insert->row['max_id'] !== null) {
								$next_after = (int)$max_after_insert->row['max_id'] + 1;
							}
							
							// Set AUTO_INCREMENT to next value
							$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_after);
							
							// Verify AUTO_INCREMENT was set
							$verify_ai_after = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product_image'");
							$verified_ai_after = 'N/A';
							if ($verify_ai_after && $verify_ai_after->num_rows) {
								if (isset($verify_ai_after->row['Auto_increment'])) {
									$verified_ai_after = $verify_ai_after->row['Auto_increment'];
								} elseif (isset($verify_ai_after->row['AUTO_INCREMENT'])) {
									$verified_ai_after = $verify_ai_after->row['AUTO_INCREMENT'];
								}
							}
							
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Updated AUTO_INCREMENT to ' . $next_after . ' for next image (verified: ' . $verified_ai_after . ')' . PHP_EOL, FILE_APPEND);
							
							if ($verified_ai_after != 'N/A' && $verified_ai_after != $next_after) {
								file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - WARNING: AUTO_INCREMENT mismatch after update! Expected: ' . $next_after . ', Got: ' . $verified_ai_after . PHP_EOL, FILE_APPEND);
							}
						} else {
							// Image path was empty, skip
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image #' . ($index + 1) . ' skipped: empty image path' . PHP_EOL, FILE_APPEND);
						}
					} else {
						// Image already exists, skip
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image #' . ($index + 1) . ' skipped: already exists in database' . PHP_EOL, FILE_APPEND);
					}
				} else {
					// Empty image path
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image #' . ($index + 1) . ' skipped: empty image path' . PHP_EOL, FILE_APPEND);
				}
			}
			
			// Log final summary
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image processing complete: ' . $successful_images . ' successful, ' . $failed_images . ' failed out of ' . $total_images . ' total' . PHP_EOL, FILE_APPEND);
			
			if ($failed_images > 0) {
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - WARNING: ' . $failed_images . ' image(s) failed to insert for product_id: ' . $product_id . PHP_EOL, FILE_APPEND);
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

		// Insert product related
		if (isset($data['product_related']) && is_array($data['product_related'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
			foreach ($data['product_related'] as $related_id) {
				$related_id = (int)$related_id;
				if ($related_id > 0 && $related_id != $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . $related_id . "'");
				}
			}
		}

		// Insert product compatible
		if (isset($data['product_compatible']) && is_array($data['product_compatible'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
			foreach ($data['product_compatible'] as $compatible_id) {
				$compatible_id = (int)$compatible_id;
				if ($compatible_id > 0 && $compatible_id != $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_compatible SET product_id = '" . (int)$product_id . "', compatible_id = '" . $compatible_id . "'");
				}
			}
		}

		// Insert product options
		if (isset($data['product_option']) && is_array($data['product_option']) && !empty($data['product_option'])) {
			$this->persistProductOptions($product_id, $data['product_option']);
		}

		// Insert product variations
		if (isset($data['product_variation']) && is_array($data['product_variation']) && !empty($data['product_variation'])) {
			$this->persistProductVariations($product_id, $data['product_variation']);
		}

		return $product_id;
	}

	public function editProduct($product_id, $data) {
		// Ensure video_url column exists
		$this->ensureVideoUrlColumn();
		
		// Validate product_id
		$product_id = (int)$product_id;
		if ($product_id <= 0) {
			throw new Exception('Invalid product ID: ' . $product_id);
		}

		// CRITICAL: Clean up any orphaned records with product_id = 0 in all related tables
		// This prevents "Duplicate entry '0' for key 'PRIMARY'" errors
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = 0");

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
		// CRITICAL: Clean up any orphaned records with product_id = 0 first
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = 0");
		
		// Delete existing descriptions for this product
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . $product_id . "'");
		
		if (isset($data['product_description']) && is_array($data['product_description'])) {
			foreach ($data['product_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				// CRITICAL: Ensure both product_id and language_id are valid before inserting
				if ($language_id > 0 && $product_id > 0) {
					// Delete any existing record for this product_id + language_id combination first (safety)
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . $language_id . "'");
					
					$result = $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
						product_id = '" . (int)$product_id . "', 
						language_id = '" . $language_id . "', 
						name = '" . $this->db->escape(isset($value['name']) ? $value['name'] : '') . "', 
						sub_name = '" . $this->db->escape(isset($value['sub_name']) ? $value['sub_name'] : '') . "', 
						description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', 
						short_description = '" . $this->db->escape(isset($value['short_description']) ? $value['short_description'] : '') . "', 
						video_url = '" . $this->db->escape(isset($value['video_url']) ? $value['video_url'] : '') . "', 
						tag = '" . $this->db->escape(isset($value['tag']) ? $value['tag'] : '') . "', 
						meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', 
						meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', 
						meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
					
					// Check for errors
					if (!$result) {
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
						
						// If duplicate key error, delete and retry once
						if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'duplicate') !== false)) {
							// Delete any conflicting record and retry
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . $language_id . "'");
							
							// Retry the insert
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
								product_id = '" . (int)$product_id . "', 
								language_id = '" . $language_id . "', 
								name = '" . $this->db->escape(isset($value['name']) ? $value['name'] : '') . "', 
								sub_name = '" . $this->db->escape(isset($value['sub_name']) ? $value['sub_name'] : '') . "', 
								description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', 
								short_description = '" . $this->db->escape(isset($value['short_description']) ? $value['short_description'] : '') . "', 
								video_url = '" . $this->db->escape(isset($value['video_url']) ? $value['video_url'] : '') . "', 
								tag = '" . $this->db->escape(isset($value['tag']) ? $value['tag'] : '') . "', 
								meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', 
								meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', 
								meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
						}
					}
				}
			}
		}

		// Update product to store
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . $product_id . "'");
		if (isset($data['product_store']) && is_array($data['product_store']) && !empty($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$store_id = (int)$store_id;
				// CRITICAL: Ensure product_id is valid before inserting
				if ($store_id >= 0 && $product_id > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . $store_id . "'");
				}
			}
		} else {
			// Default to store 0 if no stores specified
			// CRITICAL: Ensure product_id is valid before inserting
			if ($product_id > 0) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '0'");
			}
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
		// Log image update attempt
		$log_file = DIR_LOGS . 'product_insert_debug.log';
		if (isset($data['product_image']) && is_array($data['product_image'])) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Attempting to update ' . count($data['product_image']) . ' product images for product_id: ' . $product_id . PHP_EOL, FILE_APPEND);
		}
		
		// CRITICAL: ALWAYS fix product_image_id = 0 and AUTO_INCREMENT BEFORE any operations
		$zero_check = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
		$zero_count = $zero_check && $zero_check->num_rows ? (int)$zero_check->row['count'] : 0;
		if ($zero_count > 0) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] WARNING: Found ' . $zero_count . ' record(s) with product_image_id = 0. Deleting...' . PHP_EOL, FILE_APPEND);
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
		}
		
		// Fix AUTO_INCREMENT to ensure it's correct
		$max_check_initial = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
		$max_id_initial = 0;
		if ($max_check_initial && $max_check_initial->num_rows && isset($max_check_initial->row['max_id']) && $max_check_initial->row['max_id'] !== null) {
			$max_id_initial = (int)$max_check_initial->row['max_id'];
		}
		$next_id_initial = max($max_id_initial + 1, 1);
		$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id_initial);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Set AUTO_INCREMENT to ' . $next_id_initial . ' before starting' . PHP_EOL, FILE_APPEND);
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . $product_id . "'");
		
		if (isset($data['product_image']) && is_array($data['product_image']) && count($data['product_image']) > 0) {
			// CRITICAL: Clean up any product_image records with product_image_id = 0 (double-check)
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
			
			// CRITICAL: Fix AUTO_INCREMENT before starting image insertion
			// IMPORTANT: Get MAX from ALL records, not just this product, to avoid conflicts
			$max_check = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
			$max_id = 0;
			if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
				$max_id = (int)$max_check->row['max_id'];
			}
			$next_id = max($max_id + 1, 1);
			
			// Verify AUTO_INCREMENT is actually being set
			$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id);
			
			// Verify it was set correctly
			$verify_ai = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product_image'");
			$actual_ai = 'N/A';
			if ($verify_ai && $verify_ai->num_rows) {
				$actual_ai = isset($verify_ai->row['Auto_increment']) ? $verify_ai->row['Auto_increment'] : (isset($verify_ai->row['AUTO_INCREMENT']) ? $verify_ai->row['AUTO_INCREMENT'] : 'N/A');
			}
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Fixed AUTO_INCREMENT to ' . $next_id . ' (verified: ' . $actual_ai . ') before starting image insertion. Max existing ID: ' . $max_id . PHP_EOL, FILE_APPEND);
			
			$total_images = count($data['product_image']);
			$successful_images = 0;
			$failed_images = 0;
			
			foreach ($data['product_image'] as $index => $product_image) {
				$image_path = isset($product_image['image']) ? trim($product_image['image']) : '';
				$sort_order = isset($product_image['sort_order']) ? (int)$product_image['sort_order'] : 0;
				
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Processing image #' . ($index + 1) . '/' . $total_images . ': path=' . substr($image_path, 0, 50) . '...' . PHP_EOL, FILE_APPEND);
				
				// Only insert if image path is not empty
				if ($image_path && $image_path !== '') {
					// Delete product_image_id = 0 before each insert (safety)
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
					
					// CRITICAL: Calculate the next ID explicitly instead of relying on AUTO_INCREMENT
					// Get MAX from ALL records to ensure no conflicts
					$max_before = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
					$max_before_id = 0;
					if ($max_before && $max_before->num_rows && isset($max_before->row['max_id']) && $max_before->row['max_id'] !== null) {
						$max_before_id = (int)$max_before->row['max_id'];
					}
					$next_id = max($max_before_id + 1, 1);
					
					// Also set AUTO_INCREMENT for consistency
					$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_id);
					
					// Verify AUTO_INCREMENT was set
					$verify_ai_before = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product_image'");
					if ($verify_ai_before && $verify_ai_before->num_rows) {
						$ai_before_value = isset($verify_ai_before->row['Auto_increment']) ? $verify_ai_before->row['Auto_increment'] : (isset($verify_ai_before->row['AUTO_INCREMENT']) ? $verify_ai_before->row['AUTO_INCREMENT'] : 'N/A');
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Before insert #' . ($index + 1) . ': Calculated next ID: ' . $next_id . ', AUTO_INCREMENT set to ' . $next_id . ' (verified: ' . $ai_before_value . '), Max ID: ' . $max_before_id . PHP_EOL, FILE_APPEND);
					}
					
					// EXPLICITLY set product_image_id to ensure it's not 0
					$insert_sql = "INSERT INTO " . DB_PREFIX . "product_image SET product_image_id = '" . (int)$next_id . "', product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . (int)$sort_order . "'";
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Inserting image #' . ($index + 1) . ' with explicit product_image_id=' . $next_id . ': ' . substr($image_path, 0, 50) . '...' . PHP_EOL, FILE_APPEND);
					
					$result = $this->db->query($insert_sql);
					
					if ($result) {
						// CRITICAL: ALWAYS query the database to get the actual inserted ID
						// Don't rely on getLastId() as it may be unreliable
						$find = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' ORDER BY product_image_id DESC LIMIT 1");
						
						if ($find && $find->num_rows) {
							$inserted_id = (int)$find->row['product_image_id'];
							$getLastId_value = $this->db->getLastId();
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] getLastId() returned: ' . $getLastId_value . ', actual ID from database: ' . $inserted_id . PHP_EOL, FILE_APPEND);
							
							// If the actual ID is 0, this is a critical problem - delete it and retry
							if ($inserted_id == 0) {
								file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] CRITICAL: Record was inserted with product_image_id = 0! Deleting and retrying...' . PHP_EOL, FILE_APPEND);
								$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0 AND product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "'");
								
								// Fix AUTO_INCREMENT - ensure it's higher than any existing ID
								$max_retry = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
								$next_retry = 1;
								if ($max_retry && $max_retry->num_rows && isset($max_retry->row['max_id']) && $max_retry->row['max_id'] !== null) {
									$next_retry = (int)$max_retry->row['max_id'] + 1;
								}
								// Ensure AUTO_INCREMENT is at least 1, never 0
								$next_retry = max($next_retry, 1);
								$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_retry);
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Fixed AUTO_INCREMENT to ' . $next_retry . ' and retrying insert...' . PHP_EOL, FILE_APPEND);
								
								// Verify AUTO_INCREMENT was set correctly
								$verify_ai_retry = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product_image'");
								if ($verify_ai_retry && $verify_ai_retry->num_rows) {
									$ai_value = isset($verify_ai_retry->row['Auto_increment']) ? $verify_ai_retry->row['Auto_increment'] : (isset($verify_ai_retry->row['AUTO_INCREMENT']) ? $verify_ai_retry->row['AUTO_INCREMENT'] : 'N/A');
									file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Verified AUTO_INCREMENT after fix: ' . $ai_value . PHP_EOL, FILE_APPEND);
								}
								
								// Retry the insert
								$result_retry = $this->db->query($insert_sql);
								if ($result_retry) {
									$find_retry = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' ORDER BY product_image_id DESC LIMIT 1");
									if ($find_retry && $find_retry->num_rows) {
										$inserted_id = (int)$find_retry->row['product_image_id'];
										file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Retry successful, actual ID: ' . $inserted_id . PHP_EOL, FILE_APPEND);
									} else {
										$inserted_id = 0;
									}
								} else {
									$inserted_id = 0;
								}
							}
						} else {
							file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] CRITICAL: Insert reported success but record not found in database! Image: ' . substr($image_path, 0, 50) . PHP_EOL, FILE_APPEND);
							$inserted_id = 0;
						}
						
						// Verify the insert actually worked
						if ($inserted_id > 0) {
							$verify = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_image_id = '" . (int)$inserted_id . "' AND product_id = '" . (int)$product_id . "' LIMIT 1");
							if (!$verify || !$verify->num_rows) {
								file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] WARNING: product_image_id ' . $inserted_id . ' not found after insert!' . PHP_EOL, FILE_APPEND);
							} else {
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] ✓ Verified: product_image_id ' . $inserted_id . ' exists in database' . PHP_EOL, FILE_APPEND);
							}
						}
						
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Image #' . ($index + 1) . ' inserted successfully with product_image_id: ' . $inserted_id . PHP_EOL, FILE_APPEND);
						
						if ($inserted_id > 0) {
							$successful_images++;
							
							// CRITICAL: Update AUTO_INCREMENT after each successful insert for next image
							// Delete any ID 0 records first
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
							
							// Get new MAX and set AUTO_INCREMENT
							$max_after = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
							$next_after = 1;
							if ($max_after && $max_after->num_rows && isset($max_after->row['max_id']) && $max_after->row['max_id'] !== null) {
								$next_after = (int)$max_after->row['max_id'] + 1;
							}
							$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_after);
							
							// Verify AUTO_INCREMENT was updated
							$verify_ai_after = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product_image'");
							if ($verify_ai_after && $verify_ai_after->num_rows) {
								$ai_after_value = isset($verify_ai_after->row['Auto_increment']) ? $verify_ai_after->row['Auto_increment'] : (isset($verify_ai_after->row['AUTO_INCREMENT']) ? $verify_ai_after->row['AUTO_INCREMENT'] : 'N/A');
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Updated AUTO_INCREMENT to ' . $next_after . ' (verified: ' . $ai_after_value . ') for next image. Current max ID: ' . ($max_after && $max_after->num_rows && isset($max_after->row['max_id']) ? $max_after->row['max_id'] : 0) . PHP_EOL, FILE_APPEND);
							}
						} else {
							$failed_images++;
							file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] FAILED: Image #' . ($index + 1) . ' insert resulted in product_image_id = 0' . PHP_EOL, FILE_APPEND);
						}
					} else {
						// Get error
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
						
						$error_msg = "Failed to insert image #" . ($index + 1) . " for product_id: " . $product_id . ". Error: " . $db_error . " (" . $db_errno . ")";
						file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] ' . $error_msg . PHP_EOL, FILE_APPEND);
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] WARNING: Failed to insert image #' . ($index + 1) . ', continuing with next image' . PHP_EOL, FILE_APPEND);
						$failed_images++;
						
						// If duplicate key error, try to fix and retry with new explicit ID
						if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'product_image_id') !== false)) {
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_image_id = 0");
							$max_retry = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
							$next_retry = 1;
							if ($max_retry && $max_retry->num_rows && isset($max_retry->row['max_id']) && $max_retry->row['max_id'] !== null) {
								$next_retry = (int)$max_retry->row['max_id'] + 1;
							}
							$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_retry);
							
							// Retry with explicit ID
							$insert_sql_retry = "INSERT INTO " . DB_PREFIX . "product_image SET product_image_id = '" . (int)$next_retry . "', product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($image_path) . "', sort_order = '" . (int)$sort_order . "'";
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Retrying image #' . ($index + 1) . ' with explicit product_image_id=' . $next_retry . ' after duplicate key error' . PHP_EOL, FILE_APPEND);
							$result_retry = $this->db->query($insert_sql_retry);
							
							if ($result_retry) {
								// Verify the insert worked
								$find_retry = $this->db->query("SELECT product_image_id FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' AND image = '" . $this->db->escape($image_path) . "' ORDER BY product_image_id DESC LIMIT 1");
								$inserted_id_retry = 0;
								if ($find_retry && $find_retry->num_rows) {
									$inserted_id_retry = (int)$find_retry->row['product_image_id'];
								}
								file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Image #' . ($index + 1) . ' inserted successfully after retry with product_image_id: ' . $inserted_id_retry . PHP_EOL, FILE_APPEND);
								if ($inserted_id_retry > 0) {
									$successful_images++;
									$failed_images--; // Adjust count
									
									// Update AUTO_INCREMENT after retry
									$max_after_retry = $this->db->query("SELECT MAX(product_image_id) as max_id FROM " . DB_PREFIX . "product_image WHERE product_image_id > 0");
									if ($max_after_retry && $max_after_retry->num_rows && isset($max_after_retry->row['max_id']) && $max_after_retry->row['max_id'] !== null) {
										$next_after_retry = (int)$max_after_retry->row['max_id'] + 1;
										$this->db->query("ALTER TABLE " . DB_PREFIX . "product_image AUTO_INCREMENT = " . $next_after_retry);
									}
								}
							}
						}
					}
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Image #' . ($index + 1) . ' skipped: empty image path' . PHP_EOL, FILE_APPEND);
				}
			}
			
			// Log final summary
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] Image update complete: ' . $successful_images . ' successful, ' . $failed_images . ' failed out of ' . $total_images . ' total' . PHP_EOL, FILE_APPEND);
			
			if ($failed_images > 0) {
				file_put_contents(DIR_LOGS . 'product_insert_error.log', date('Y-m-d H:i:s') . ' - [EDIT] WARNING: ' . $failed_images . ' image(s) failed to insert for product_id: ' . $product_id . PHP_EOL, FILE_APPEND);
			}
		} else {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - [EDIT] No product images to insert (empty array or not set)' . PHP_EOL, FILE_APPEND);
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

		// Update product related
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_related']) && is_array($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$related_id = (int)$related_id;
				if ($related_id > 0 && $related_id != $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . $related_id . "'");
				}
			}
		}

		// Update product compatible
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_compatible WHERE product_id = '" . (int)$product_id . "'");
		if (isset($data['product_compatible']) && is_array($data['product_compatible'])) {
			foreach ($data['product_compatible'] as $compatible_id) {
				$compatible_id = (int)$compatible_id;
				if ($compatible_id > 0 && $compatible_id != $product_id) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_compatible SET product_id = '" . (int)$product_id . "', compatible_id = '" . $compatible_id . "'");
				}
			}
		}

		// Update product options
		if (isset($data['product_option']) && is_array($data['product_option']) && !empty($data['product_option'])) {
			$this->persistProductOptions($product_id, $data['product_option']);
		}

		// Update product variations
		if (isset($data['product_variation']) && is_array($data['product_variation'])) {
			$this->persistProductVariations($product_id, $data['product_variation']);
		}
	}

	protected function persistProductOptions($product_id, $product_options = array()) {
		if (empty($product_options) || !is_array($product_options)) {
			return;
		}

		// CRITICAL: Validate product_id before proceeding
		$product_id = (int)$product_id;
		if ($product_id <= 0) {
			error_log("persistProductOptions: Invalid product_id: " . $product_id);
			return;
		}

		// CRITICAL: Clean up any orphaned records with product_id = 0 or product_option_id = 0
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = 0");
		
		// Delete existing product options for this product
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . $product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . $product_id . "'");

		// Ensure required and value columns exist in product_option table
		$this->ensureRequiredColumn();
		$this->ensureValueColumn();
		
		// Ensure all required columns exist in product_option_value table
		$this->ensureProductOptionValueColumns();

		foreach ($product_options as $product_option) {
			if (!isset($product_option['option_id'])) {
				continue;
			}

			$option_id = (int)$product_option['option_id'];
			if ($option_id <= 0) {
				continue;
			}

			$required = isset($product_option['required']) ? (int)$product_option['required'] : 0;
			$value    = isset($product_option['value']) ? $product_option['value'] : '';

			$result = $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . $option_id . "', required = '" . $required . "', value = '" . $this->db->escape($value) . "'");
			$product_option_id = $this->db->getLastId();

			// CRITICAL: Validate product_option_id before proceeding
			if ($product_option_id <= 0) {
				// Try to get the actual ID from database
				$find_query = $this->db->query("SELECT product_option_id FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . $option_id . "' ORDER BY product_option_id DESC LIMIT 1");
				if ($find_query && $find_query->num_rows) {
					$product_option_id = (int)$find_query->row['product_option_id'];
				} else {
					error_log("persistProductOptions: Failed to get valid product_option_id for product_id={$product_id}, option_id={$option_id}");
					continue;
				}
			}

			if (isset($product_option['product_option_value']) && is_array($product_option['product_option_value'])) {
				foreach ($product_option['product_option_value'] as $product_option_value) {
					if (empty($product_option_value['option_value_id'])) {
						continue;
					}

					$option_value_id = (int)$product_option_value['option_value_id'];
					if ($option_value_id <= 0) {
						continue;
					}

					$show            = !empty($product_option_value['show']) ? 1 : 0;
					$quantity        = isset($product_option_value['quantity']) ? (int)$product_option_value['quantity'] : 0;
					$subtract        = isset($product_option_value['subtract']) ? (int)$product_option_value['subtract'] : 0;
					$price           = isset($product_option_value['price']) ? (float)$product_option_value['price'] : 0;
					$price_prefix    = isset($product_option_value['price_prefix']) ? $product_option_value['price_prefix'] : '+';
					$points          = isset($product_option_value['points']) ? (int)$product_option_value['points'] : 0;
					$points_prefix   = isset($product_option_value['points_prefix']) ? $product_option_value['points_prefix'] : '+';
					$weight          = isset($product_option_value['weight']) ? (float)$product_option_value['weight'] : 0;
					$weight_prefix   = isset($product_option_value['weight_prefix']) ? $product_option_value['weight_prefix'] : '+';
					$color           = isset($product_option_value['color']) ? $product_option_value['color'] : '';

					// Delete any existing record first (safety)
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND product_option_id = '" . (int)$product_option_id . "' AND option_value_id = '" . $option_value_id . "'");

					$result = $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET 
						product_option_id = '" . (int)$product_option_id . "', 
						product_id = '" . (int)$product_id . "', 
						option_id = '" . $option_id . "', 
						option_value_id = '" . $option_value_id . "', 
						quantity = '" . $quantity . "', 
						subtract = '" . $subtract . "', 
						price = '" . $price . "', 
						price_prefix = '" . $this->db->escape($price_prefix) . "', 
						points = '" . $points . "', 
						points_prefix = '" . $this->db->escape($points_prefix) . "', 
						weight = '" . $weight . "', 
						weight_prefix = '" . $this->db->escape($weight_prefix) . "', 
						color = '" . $this->db->escape($color) . "', 
						`show` = '" . (int)$show . "'");

					// Check for errors
					if (!$result) {
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

						// If duplicate key error, delete and retry once
						if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'duplicate') !== false)) {
							// Delete any conflicting record and retry
							$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND product_option_id = '" . (int)$product_option_id . "' AND option_value_id = '" . $option_value_id . "'");
							
							// Retry the insert
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET 
								product_option_id = '" . (int)$product_option_id . "', 
								product_id = '" . (int)$product_id . "', 
								option_id = '" . $option_id . "', 
								option_value_id = '" . $option_value_id . "', 
								quantity = '" . $quantity . "', 
								subtract = '" . $subtract . "', 
								price = '" . $price . "', 
								price_prefix = '" . $this->db->escape($price_prefix) . "', 
								points = '" . $points . "', 
								points_prefix = '" . $this->db->escape($points_prefix) . "', 
								weight = '" . $weight . "', 
								weight_prefix = '" . $this->db->escape($weight_prefix) . "', 
								color = '" . $this->db->escape($color) . "', 
								`show` = '" . (int)$show . "'");
						}
					}
				}
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

	/**
	 * Ensure video_url column exists in product_description table
	 * This method checks if the column exists and adds it if missing
	 */
	private function ensureVideoUrlColumn() {
		$table_name = DB_PREFIX . "product_description";
		
		// Check if column exists
		$check_query = $this->db->query("SHOW COLUMNS FROM `" . $table_name . "` LIKE 'video_url'");
		
		if (!$check_query->num_rows) {
			// Column doesn't exist, add it
			try {
				$this->db->query("ALTER TABLE `" . $table_name . "` ADD COLUMN `video_url` VARCHAR(255) DEFAULT NULL AFTER `short_description`");
			} catch (Exception $e) {
				// Log error but don't throw - column might already exist from concurrent request
				error_log("Error adding video_url column: " . $e->getMessage());
			}
		}
	}

	/**
	 * Ensure required column exists in product_option table
	 * This method checks if the column exists and adds it if missing
	 */
	private function ensureRequiredColumn() {
		$table_name = DB_PREFIX . "product_option";
		
		// Check if column exists
		$check_query = $this->db->query("SHOW COLUMNS FROM `" . $table_name . "` LIKE 'required'");
		
		if (!$check_query->num_rows) {
			// Column doesn't exist, add it
			try {
				$this->db->query("ALTER TABLE `" . $table_name . "` ADD COLUMN `required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `option_id`");
			} catch (Exception $e) {
				// Log error but don't throw - column might already exist from concurrent request
				error_log("Error adding required column: " . $e->getMessage());
			}
		}
	}

	/**
	 * Ensure value column exists in product_option table
	 * This method checks if the column exists and adds it if missing
	 */
	private function ensureValueColumn() {
		$table_name = DB_PREFIX . "product_option";
		
		// Check if column exists
		$check_query = $this->db->query("SHOW COLUMNS FROM `" . $table_name . "` LIKE 'value'");
		
		if (!$check_query->num_rows) {
			// Column doesn't exist, add it
			try {
				$this->db->query("ALTER TABLE `" . $table_name . "` ADD COLUMN `value` TEXT DEFAULT NULL AFTER `required`");
			} catch (Exception $e) {
				// Log error but don't throw - column might already exist from concurrent request
				error_log("Error adding value column: " . $e->getMessage());
			}
		}
	}

	/**
	 * Persist product variations to database
	 * This method saves product variations (combinations of option values)
	 */
	protected function persistProductVariations($product_id, $product_variations = array()) {
		if (empty($product_variations) || !is_array($product_variations)) {
			// If no variations provided, delete existing ones
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "'");
			return;
		}

		// CRITICAL: Validate product_id before proceeding
		$product_id = (int)$product_id;
		if ($product_id <= 0) {
			error_log("persistProductVariations: Invalid product_id: " . $product_id);
			return;
		}

		// CRITICAL: Clean up any orphaned records with product_id = 0
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_variation WHERE product_id = 0");
		
		// Delete existing variations for this product
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "'");

		// Insert new variations
		foreach ($product_variations as $variation) {
			if (!isset($variation['key']) || empty($variation['key'])) {
				continue;
			}

			$key = $this->db->escape($variation['key']);
			$sku = isset($variation['sku']) ? $this->db->escape($variation['sku']) : '';
			$price_prefix = isset($variation['price_prefix']) ? $this->db->escape($variation['price_prefix']) : '+';
			$price = isset($variation['price']) ? (float)$variation['price'] : 0;
			$quantity = isset($variation['quantity']) ? (int)$variation['quantity'] : 0;
			$image = isset($variation['image']) ? $this->db->escape($variation['image']) : '';

			// Delete any existing record first (safety)
			$this->db->query("DELETE FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "' AND `key` = '" . $key . "'");

			$result = $this->db->query("INSERT INTO " . DB_PREFIX . "product_variation SET 
				product_id = '" . (int)$product_id . "', 
				`key` = '" . $key . "', 
				sku = '" . $sku . "', 
				price_prefix = '" . $price_prefix . "', 
				price = '" . $price . "', 
				quantity = '" . $quantity . "', 
				image = '" . $image . "'");

			// Check for errors
			if (!$result) {
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

				// If duplicate key error, delete and retry once
				if ($db_errno == 1062 && (stripos($db_error, 'PRIMARY') !== false || stripos($db_error, 'duplicate') !== false)) {
					// Delete any conflicting record and retry
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "' AND `key` = '" . $key . "'");
					
					// Retry the insert
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_variation SET 
						product_id = '" . (int)$product_id . "', 
						`key` = '" . $key . "', 
						sku = '" . $sku . "', 
						price_prefix = '" . $price_prefix . "', 
						price = '" . $price . "', 
						quantity = '" . $quantity . "', 
						image = '" . $image . "'");
				} else {
					error_log("Error inserting product variation: " . $db_error . " (Error No: " . $db_errno . ")");
				}
			}
		}
	}

	/**
	 * Ensure all required columns exist in product_option_value table
	 * This method checks if columns exist and adds them if missing
	 */
	private function ensureProductOptionValueColumns() {
		$table_name = DB_PREFIX . "product_option_value";
		
		// List of columns that need to exist with their definitions
		$columns = array(
			'quantity' => "INT(11) NOT NULL DEFAULT '0'",
			'subtract' => "TINYINT(1) NOT NULL DEFAULT '0'",
			'price' => "DECIMAL(15,4) NOT NULL DEFAULT '0.0000'",
			'price_prefix' => "VARCHAR(1) NOT NULL DEFAULT '+'",
			'points' => "INT(8) NOT NULL DEFAULT '0'",
			'points_prefix' => "VARCHAR(1) NOT NULL DEFAULT '+'",
			'weight' => "DECIMAL(15,8) NOT NULL DEFAULT '0.00000000'",
			'weight_prefix' => "VARCHAR(1) NOT NULL DEFAULT '+'",
			'color' => "VARCHAR(255) DEFAULT NULL",
			'show' => "TINYINT(1) NOT NULL DEFAULT '1'"
		);
		
		// Get existing columns
		$existing_columns = array();
		$columns_query = $this->db->query("SHOW COLUMNS FROM `" . $table_name . "`");
		if ($columns_query && $columns_query->num_rows) {
			foreach ($columns_query->rows as $row) {
				$existing_columns[] = $row['Field'];
			}
		}
		
		// Add missing columns
		foreach ($columns as $column_name => $column_definition) {
			if (!in_array($column_name, $existing_columns)) {
				try {
					// Determine position - add after option_value_id if it exists, otherwise at the end
					$after_column = 'option_value_id';
					if (in_array($after_column, $existing_columns)) {
						$this->db->query("ALTER TABLE `" . $table_name . "` ADD COLUMN `" . $column_name . "` " . $column_definition . " AFTER `" . $after_column . "`");
					} else {
						$this->db->query("ALTER TABLE `" . $table_name . "` ADD COLUMN `" . $column_name . "` " . $column_definition);
					}
					// Add to existing columns list to avoid duplicate attempts
					$existing_columns[] = $column_name;
				} catch (Exception $e) {
					// Log error but don't throw - column might already exist from concurrent request
					error_log("Error adding " . $column_name . " column to product_option_value: " . $e->getMessage());
				}
			}
		}
	}
}

