<?php
class ModelVendorOrder extends Model {
	public function getTotalOrders($vendor_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor_order WHERE vendor_id = '" . (int)$vendor_id . "'");
		return (int)$query->row['total'];
	}

	public function getRecentOrders($vendor_id, $limit = 5) {
		$query = $this->db->query("SELECT vo.*, o.firstname, o.lastname, o.email, o.telephone, os.name AS order_status FROM " . DB_PREFIX . "vendor_order vo LEFT JOIN " . DB_PREFIX . "order o ON (vo.order_id = o.order_id) LEFT JOIN " . DB_PREFIX . "order_status os ON (vo.order_status_id = os.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE vo.vendor_id = '" . (int)$vendor_id . "' ORDER BY vo.date_added DESC LIMIT " . (int)$limit);
		return $query->rows;
	}

	public function getOrders($vendor_id, $data = array()) {
		$sql = "SELECT vo.*, o.firstname, o.lastname, o.email, o.telephone, os.name AS order_status FROM " . DB_PREFIX . "vendor_order vo LEFT JOIN " . DB_PREFIX . "order o ON (vo.order_id = o.order_id) LEFT JOIN " . DB_PREFIX . "order_status os ON (vo.order_status_id = os.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE vo.vendor_id = '" . (int)$vendor_id . "'";

		$sort_data = array(
			'vo.date_added',
			'vo.total',
			'o.firstname'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY vo.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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

	public function getOrder($order_id, $vendor_id) {
		$query = $this->db->query("SELECT vo.*, o.*, os.name AS order_status FROM " . DB_PREFIX . "vendor_order vo LEFT JOIN " . DB_PREFIX . "order o ON (vo.order_id = o.order_id) LEFT JOIN " . DB_PREFIX . "order_status os ON (vo.order_status_id = os.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') WHERE vo.order_id = '" . (int)$order_id . "' AND vo.vendor_id = '" . (int)$vendor_id . "' LIMIT 1");
		return $query->row;
	}

	public function getOrderProducts($order_id, $vendor_id) {
		$query = $this->db->query("SELECT vo.*, op.name, op.model, op.quantity, op.price FROM " . DB_PREFIX . "vendor_order vo LEFT JOIN " . DB_PREFIX . "order_product op ON (vo.order_product_id = op.order_product_id) WHERE vo.order_id = '" . (int)$order_id . "' AND vo.vendor_id = '" . (int)$vendor_id . "'");
		return $query->rows;
	}
}

