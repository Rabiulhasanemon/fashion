<?php
class ModelDesignAdsPosition extends Model {
	public function addAdsPosition($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ads_position` SET name = '" . $this->db->escape($data['name']) . "'");
	}

	public function editAdsPosition($ads_position_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ads_position` SET name = '" . $this->db->escape($data['name']) . "' WHERE ads_position_id = '" . (int)$ads_position_id . "'");
	}

	public function deleteAdsPosition($ads_position_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ads_position` WHERE ads_position_id = '" . (int)$ads_position_id . "'");
	}

	public function getAdsPosition($ads_position_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ads_position` WHERE ads_position_id = '" . (int)$ads_position_id . "'");

		return $query->row;
	}

	public function getAdsPositions($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "ads_position`";

		$sort_data = array(
			'name',
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

	public function getTotalAdsPositions() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "ads_position`");

		return $query->row['total'];
	}
}