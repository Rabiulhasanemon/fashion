<?php
class ModelVendorReview extends Model {
	public function addReview($vendor_id, $customer_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_review SET 
			vendor_id = '" . (int)$vendor_id . "',
			customer_id = '" . (int)$customer_id . "',
			order_id = '" . (isset($data['order_id']) ? (int)$data['order_id'] : 0) . "',
			rating = '" . (int)$data['rating'] . "',
			comment = '" . $this->db->escape($data['comment']) . "',
			status = '1',
			date_added = NOW()");
	}

	public function getReviews($vendor_id, $start = 0, $limit = 20) {
		$query = $this->db->query("SELECT vr.*, CONCAT(c.firstname, ' ', c.lastname) AS customer_name FROM " . DB_PREFIX . "vendor_review vr LEFT JOIN " . DB_PREFIX . "customer c ON (vr.customer_id = c.customer_id) WHERE vr.vendor_id = '" . (int)$vendor_id . "' AND vr.status = '1' ORDER BY vr.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
		return $query->rows;
	}

	public function getTotalReviews($vendor_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor_review WHERE vendor_id = '" . (int)$vendor_id . "' AND status = '1'");
		return $query->row['total'];
	}
}


