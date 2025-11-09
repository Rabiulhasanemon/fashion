<?php
class ModelDesignAds extends Model {
	public function addAds($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "ads` SET title = '" . $this->db->escape($data['title']) . "', `image` = '" . $this->db->escape($data['image']) . "', `url` = '" . $this->db->escape($data['url']) . "', status = '" . (int)$data['status'] . "', ads_position_id = '" . (int) $data['ads_position_id'] . "', device_type = '" . (int) $data['device_type'] . "', date_added = NOW()");
	}

	public function editAds($ads_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "ads` SET title = '" . $this->db->escape($data['title']) . "', `image` = '" . $this->db->escape($data['image']) . "', `url` = '" . $this->db->escape($data['url']) . "', status = '" . (int)$data['status'] . "', ads_position_id = '" . (int) $data['ads_position_id'] . "', device_type = '" . (int) $data['device_type'] . "' WHERE ads_id = '" . (int)$ads_id . "'");
	}

	public function deleteAds($ads_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ads` WHERE ads_id = '" . (int)$ads_id . "'");
	}

	public function getAds($ads_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ads` WHERE ads_id = '" . (int)$ads_id . "'");

		return $query->row;
	}

	public function getAdsList($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "ads`";

		$sort_data = array(
			'title',
			'device_type',
			'status',
			'date_added',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY title";
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

	public function getTotalAds() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "ads`");

		return $query->row['total'];
	}
}