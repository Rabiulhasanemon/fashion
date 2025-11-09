<?php
class ModelMarketingContact extends Model {

	public function getSubscribers($data = array()) {
        $sql = "SELECT * FROM " . DB_PREFIX . "newsletter";

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

	public function getTotalSubscribers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "newsletter";

		$query = $this->db->query($sql);
		return $query->row['total'];
	}

}