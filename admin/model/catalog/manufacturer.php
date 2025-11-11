<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {
		// Validate required data - use 'name' from post or try to get from manufacturer_description
		$manufacturer_name = '';
		if (isset($data['name']) && !empty(trim($data['name']))) {
			$manufacturer_name = trim($data['name']);
		} elseif (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			// Try to get name from first language description
			foreach ($data['manufacturer_description'] as $lang_data) {
				if (isset($lang_data['meta_title']) && !empty(trim($lang_data['meta_title']))) {
					$manufacturer_name = trim($lang_data['meta_title']);
					break;
				}
			}
		}
		
		if (empty($manufacturer_name)) {
			throw new Exception('Manufacturer name is required');
		}
		
		// Ensure name is set in data array
		$data['name'] = $manufacturer_name;

		$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "'");

		$manufacturer_id = $this->db->getLastId();

		if (!$manufacturer_id || $manufacturer_id <= 0) {
			throw new Exception('Failed to insert manufacturer - manufacturer_id was not returned');
		}

		if (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . $language_id . "', description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
				}
			}
		} else {
			// Insert default description if none provided
			$default_language_id = $this->config->get('config_language_id');
			if ($default_language_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . (int)$default_language_id . "', description = '', meta_title = '" . $this->db->escape($data['name']) . "', meta_description = '', meta_keyword = ''");
			}
		}

		if (isset($data['manufacturer_store']) && is_array($data['manufacturer_store']) && !empty($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "'");
				}
			}
		} else {
			// Default to store 0 if none specified
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '0'");
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

		// Validate required data - use 'name' from post or try to get from manufacturer_description
		$manufacturer_name = '';
		if (isset($data['name']) && !empty(trim($data['name']))) {
			$manufacturer_name = trim($data['name']);
		} elseif (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			// Try to get name from first language description
			foreach ($data['manufacturer_description'] as $lang_data) {
				if (isset($lang_data['meta_title']) && !empty(trim($lang_data['meta_title']))) {
					$manufacturer_name = trim($lang_data['meta_title']);
					break;
				}
			}
		}
		
		if (empty($manufacturer_name)) {
			throw new Exception('Manufacturer name is required');
		}
		
		// Ensure name is set in data array
		$data['name'] = $manufacturer_name;

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
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

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


