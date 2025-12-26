<?php
class ModelVendorProduct extends Model {
	public function getProducts($vendor_id, $data = array()) {
		$sql = "SELECT p.*, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.vendor_id = '" . (int)$vendor_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sql .= " ORDER BY p.date_added DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			$start = isset($data['start']) ? (int)$data['start'] : 0;
			$limit = isset($data['limit']) ? (int)$data['limit'] : 20;

			if ($start < 0) {
				$start = 0;
			}

			if ($limit < 1) {
				$limit = 20;
			}

			$sql .= " LIMIT " . $start . "," . $limit;
		}

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getTotalProducts($vendor_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE vendor_id = '" . (int)$vendor_id . "'");
		return (int)$query->row['total'];
	}

	public function getVendorProducts($vendor_id, $data = array()) {
		return $this->getProducts($vendor_id, $data);
	}
}


