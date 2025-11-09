<?php
class ModelCatalogFilterProfile extends Model {
	public function addFilterProfile($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "filter_profile SET name = '" . $this->db->escape($data['name']) . "', date_added = NOW()");
		$filter_profile_id = $this->db->getLastId();
		return $filter_profile_id;
	}

	public function editFilterProfile($filter_profile_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "filter_profile SET name = '" . $this->db->escape($data['name']) . "' WHERE filter_profile_id = '" . (int)$filter_profile_id . "'");
	}


	public function deleteFilterProfile($filter_profile_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_profile WHERE filter_profile_id = '" . (int)$filter_profile_id . "'");
	}

	public function getFilterProfile($filter_profile_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "filter_profile r WHERE r.filter_profile_id = '" . (int)$filter_profile_id . "'");
		return $query->row;
	}

    public function getFilterProfileByName($name) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "filter_profile r WHERE r.name = '" . $this->db->escape($name) . "'");
        return $query->row;
    }

	public function getFilterProfiles($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "filter_profile r";
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

	public function getTotalFilterProfiles($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "filter_profile r";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}