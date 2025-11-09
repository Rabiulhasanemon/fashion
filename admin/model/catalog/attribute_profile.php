<?php
class ModelCatalogAttributeProfile extends Model {
	public function addAttributeProfile($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_profile SET name = '" . $this->db->escape($data['name']) . "', date_added = NOW()");
		$attribute_profile_id = $this->db->getLastId();
		return $attribute_profile_id;
	}

	public function editAttributeProfile($attribute_profile_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "attribute_profile SET name = '" . $this->db->escape($data['name']) . "' WHERE attribute_profile_id = '" . (int)$attribute_profile_id . "'");
	}


	public function deleteAttributeProfile($attribute_profile_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_profile WHERE attribute_profile_id = '" . (int)$attribute_profile_id . "'");
	}

	public function getAttributeProfile($attribute_profile_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "attribute_profile r WHERE r.attribute_profile_id = '" . (int)$attribute_profile_id . "'");
		return $query->row;
	}

    public function getAttributeProfileByName($name) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "attribute_profile r WHERE r.name = '" . $this->db->escape($name) . "'");
        return $query->row;
    }

	public function getAttributeProfiles($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_profile r";

        if (!empty($data['filter_name'])) {
            $sql .= " WHERE r.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

		$sort_data = array(
			'r.name',
			'r.date_added'
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
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

	public function getTotalAttributeProfiles($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_profile r";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}