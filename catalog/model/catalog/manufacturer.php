<?php
class ModelCatalogManufacturer extends Model {

    public function getManufacturer($manufacturer_id) {
        // First, get the manufacturer (must exist)
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
        
        if (!$query->num_rows) {
            return false;
        }
        
        $manufacturer = $query->row;
        
        // Get description if available for current language
        $query_desc = $this->db->query("SELECT description, meta_title, meta_description, meta_keyword FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
        if ($query_desc && $query_desc->num_rows) {
            // Merge description fields into manufacturer array (override base table fields if description exists)
            foreach ($query_desc->row as $key => $value) {
                if ($value !== null && $value !== '') {
                    $manufacturer[$key] = $value;
                }
            }
        }
        
        // Check store assignment (optional - we'll return manufacturer even if not assigned)
        // But we can add a flag to indicate store assignment status
        $query_store = $this->db->query("SELECT store_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");
        $manufacturer['store_assigned'] = $query_store->num_rows > 0;
        
        return $manufacturer;
    }

	public function getManufacturers($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

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
		} else {
			$manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));

			if (!$manufacturer_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");

				$manufacturer_data = $query->rows;

				$this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
			}

			return $manufacturer_data;
		}
	}

    public function getManufacturerLayoutId($manufacturer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }
}