<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {
		// Validate required data
		if (!isset($data['name']) || empty(trim($data['name']))) {
			throw new Exception('Manufacturer name is required');
		}

		// CRITICAL: Clean up any manufacturer with manufacturer_id = 0 before inserting
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=0'");
		
		// Calculate next manufacturer_id explicitly
		$max_check = $this->db->query("SELECT MAX(manufacturer_id) as max_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id > 0");
		$max_id = 0;
		if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
			$max_id = (int)$max_check->row['max_id'];
		}
		$next_id = max($max_id + 1, 1);
		
		// Set AUTO_INCREMENT for consistency
		$alter_result = $this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . $next_id);
		if (!$alter_result) {
			$error = '';
			if (property_exists($this->db, 'link') && is_object($this->db->link)) {
				if (property_exists($this->db->link, 'error')) {
					$error = $this->db->link->error;
				}
			}
			throw new Exception('Failed to set AUTO_INCREMENT: ' . $error);
		}

		// Try to insert with explicit manufacturer_id first
		$insert_result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = '" . (int)$next_id . "', name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "'");
		
		if (!$insert_result) {
			// Get error details
			$error = '';
			$errno = 0;
			if (property_exists($this->db, 'link') && is_object($this->db->link)) {
				if (property_exists($this->db->link, 'error')) {
					$error = $this->db->link->error;
				}
				if (property_exists($this->db->link, 'errno')) {
					$errno = $this->db->link->errno;
				}
			}
			
			// If explicit ID failed, try without it (let AUTO_INCREMENT handle it)
			// This handles cases where explicit ID insertion is not allowed
			if ($errno == 1062 || stripos($error, 'Duplicate') !== false || stripos($error, 'PRIMARY') !== false) {
				// Duplicate key - try without explicit ID
				$insert_result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "'");
				
				if ($insert_result) {
					// Get the actual inserted ID
					$manufacturer_id = $this->db->getLastId();
					if (!$manufacturer_id || $manufacturer_id <= 0) {
						// Query database directly to get the ID
						$find = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($data['name']) . "' ORDER BY manufacturer_id DESC LIMIT 1");
						if ($find && $find->num_rows) {
							$manufacturer_id = (int)$find->row['manufacturer_id'];
						} else {
							throw new Exception('Failed to insert manufacturer - could not retrieve manufacturer_id after insert');
						}
					}
				} else {
					// Get new error
					$error2 = '';
					$errno2 = 0;
					if (property_exists($this->db, 'link') && is_object($this->db->link)) {
						if (property_exists($this->db->link, 'error')) {
							$error2 = $this->db->link->error;
						}
						if (property_exists($this->db->link, 'errno')) {
							$errno2 = $this->db->link->errno;
						}
					}
					throw new Exception('Failed to insert manufacturer (both explicit and auto methods): ' . $error2 . ' (Error Code: ' . $errno2 . ')');
				}
			} else {
				throw new Exception('Failed to insert manufacturer: ' . $error . ' (Error Code: ' . $errno . ')');
			}
		} else {
			// Explicit ID insert succeeded - verify it
			$verify = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$next_id . "' LIMIT 1");
			if (!$verify || !$verify->num_rows) {
				throw new Exception('Failed to insert manufacturer - record not found after insert (manufacturer_id: ' . $next_id . ')');
			}
			$manufacturer_id = (int)$next_id;
		}

		// CRITICAL: Delete any existing records for this manufacturer_id first (in case of retry or orphaned data)
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0) {
					// Check if exists first to avoid duplicate key errors
					$check_desc = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "' AND language_id = '" . $language_id . "' LIMIT 1");
					if (!$check_desc || !$check_desc->num_rows) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . $language_id . "', description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
					}
				}
			}
		} else {
			// Insert default description if none provided
			$default_language_id = 1; // Default to 1 if config not available
			if (isset($this->config) && method_exists($this->config, 'get')) {
				$config_lang_id = $this->config->get('config_language_id');
				if ($config_lang_id) {
					$default_language_id = (int)$config_lang_id;
				}
			}
			// Check if exists first
			$check_default = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "' AND language_id = '" . (int)$default_language_id . "' LIMIT 1");
			if (!$check_default || !$check_default->num_rows) {
				$desc_result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . (int)$default_language_id . "', description = '', meta_title = '" . $this->db->escape($data['name']) . "', meta_description = '', meta_keyword = ''");
				if (!$desc_result) {
					// Log but don't fail - description is optional
					$error = '';
					if (property_exists($this->db, 'link') && is_object($this->db->link) && property_exists($this->db->link, 'error')) {
						$error = $this->db->link->error;
					}
					error_log('Warning: Failed to insert manufacturer description: ' . $error);
				}
			}
		}

		if (isset($data['manufacturer_store']) && is_array($data['manufacturer_store']) && !empty($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					// Check if exists first
					$check_store = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "' AND store_id = '" . $store_id . "' LIMIT 1");
					if (!$check_store || !$check_store->num_rows) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "'");
					}
				}
			}
		} else {
			// Default to store 0 if none specified
			$check_store_default = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "' AND store_id = '0' LIMIT 1");
			if (!$check_store_default || !$check_store_default->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '0'");
			}
		}

		if (isset($data['keyword']) && !empty(trim($data['keyword']))) {
			$keyword = trim($data['keyword']);
			// Check if keyword already exists
			$existing = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' LIMIT 1");
			if (!$existing->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . $manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
			}
		}

		if (isset($data['manufacturer_layout']) && is_array($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$layout_id = (int)$layout_id;
				if ($layout_id > 0) {
					$store_id = (int)$store_id;
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "', layout_id = '" . $layout_id . "'");
				}
			}
		}

		$this->cache->delete('manufacturer');

		return $manufacturer_id;
	}

	public function editManufacturer($manufacturer_id, $data) {
		// Validate manufacturer_id
		$manufacturer_id = (int)$manufacturer_id;
		if ($manufacturer_id <= 0) {
			throw new Exception('Invalid manufacturer ID: ' . $manufacturer_id);
		}

		// Verify manufacturer exists before updating
		$check_query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . $manufacturer_id . "' LIMIT 1");
		if (!$check_query->num_rows) {
			throw new Exception('Manufacturer with ID ' . $manufacturer_id . ' does not exist');
		}

		// Validate required data
		if (!isset($data['name']) || empty(trim($data['name']))) {
			throw new Exception('Manufacturer name is required');
		}

		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "' WHERE manufacturer_id = '" . $manufacturer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . $language_id . "', description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_store']) && is_array($data['manufacturer_store']) && !empty($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "'");
				}
			}
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '0'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . $manufacturer_id . "'");

		if (isset($data['keyword']) && !empty(trim($data['keyword']))) {
			$keyword = trim($data['keyword']);
			// Check if keyword already exists for a different manufacturer
			$existing = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND query != 'manufacturer_id=" . $manufacturer_id . "' LIMIT 1");
			if (!$existing->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . $manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_layout']) && is_array($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$layout_id = (int)$layout_id;
				if ($layout_id > 0) {
					$store_id = (int)$store_id;
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "', layout_id = '" . $layout_id . "'");
				}
			}
		}

		$this->cache->delete('manufacturer');
	}

	public function deleteManufacturer($manufacturer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function getTotalManufacturers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getManufacturerDescriptions($manufacturer_id) {
		$manufacturer_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_description_data[$result['language_id']] = array(
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $manufacturer_description_data;
	}

	public function getManufacturerStores($manufacturer_id) {
		$manufacturer_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getManufacturerLayouts($manufacturer_id) {
		$manufacturer_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $manufacturer_layout_data;
	}
}


