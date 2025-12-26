<?php
class ModelVendorVendor extends Model {
	public function addVendor($customer_id, $data) {
		$store_name = isset($data['store_name']) ? $data['store_name'] : '';
		$store_slug = $this->generateSlug($store_name);
		$commission_rate = $this->config->get('config_vendor_commission') !== null
			? (float)$this->config->get('config_vendor_commission')
			: 0.0;

		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor SET
			customer_id = '" . (int)$customer_id . "',
			store_name = '" . $this->db->escape($store_name) . "',
			store_slug = '" . $this->db->escape($store_slug) . "',
			store_description = '" . $this->db->escape(isset($data['store_description']) ? $data['store_description'] : '') . "',
			store_address = '" . $this->db->escape(isset($data['store_address']) ? $data['store_address'] : '') . "',
			store_city = '" . $this->db->escape(isset($data['store_city']) ? $data['store_city'] : '') . "',
			store_country_id = '" . (int)(isset($data['store_country_id']) ? $data['store_country_id'] : 0) . "',
			store_zone_id = '" . (int)(isset($data['store_zone_id']) ? $data['store_zone_id'] : 0) . "',
			store_phone = '" . $this->db->escape(isset($data['store_phone']) ? $data['store_phone'] : '') . "',
			store_email = '" . $this->db->escape(isset($data['store_email']) ? $data['store_email'] : '') . "',
			commission_type = 'percentage',
			commission_rate = '" . $commission_rate . "',
			status = 'pending',
			verification_status = '0',
			date_added = NOW(),
			date_modified = NOW()");

		return $this->db->getLastId();
	}

	public function getVendor($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor WHERE vendor_id = '" . (int)$vendor_id . "'");
		return $query->row;
	}

	public function getVendorByCustomerId($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row;
	}

	public function getVendorBySlug($slug) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor WHERE store_slug = '" . $this->db->escape($slug) . "'");
		return $query->row;
	}

	public function getVendors($data = array()) {
		$sql = "SELECT v.*, c.firstname, c.lastname, c.email FROM " . DB_PREFIX . "vendor v LEFT JOIN " . DB_PREFIX . "customer c ON (v.customer_id = c.customer_id)";

		$conditions = array();
		if (isset($data['status'])) {
			$conditions[] = "v.status = '" . $this->db->escape($data['status']) . "'";
		}

		if ($conditions) {
			$sql .= " WHERE " . implode(' AND ', $conditions);
		}

		$sql .= " ORDER BY v.date_added DESC";

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

	public function approveVendor($vendor_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "vendor SET status = 'approved', date_modified = NOW() WHERE vendor_id = '" . (int)$vendor_id . "'");
	}

	public function suspendVendor($vendor_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "vendor SET status = 'suspended', date_modified = NOW() WHERE vendor_id = '" . (int)$vendor_id . "'");
	}

	public function getTotalEarnings($vendor_id) {
		$query = $this->db->query("SELECT pending_balance + total_earnings AS total FROM " . DB_PREFIX . "vendor WHERE vendor_id = '" . (int)$vendor_id . "'");
		return $query->num_rows ? (float)$query->row['total'] : 0.0;
	}

	public function updateVendorRating($vendor_id) {
		$query = $this->db->query("SELECT AVG(rating) AS rating, COUNT(*) AS total FROM " . DB_PREFIX . "vendor_review WHERE vendor_id = '" . (int)$vendor_id . "' AND status = '1'");
		
		if ($query->num_rows) {
			$this->db->query("UPDATE " . DB_PREFIX . "vendor SET 
				rating = '" . (float)$query->row['rating'] . "',
				review_count = '" . (int)$query->row['total'] . "',
				date_modified = NOW()
				WHERE vendor_id = '" . (int)$vendor_id . "'");
		}
	}

	private function generateSlug($name) {
		$base = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
		if ($base === '') {
			$base = 'store-' . time();
		}

		$slug = $base;
		$counter = 1;
		while (true) {
			$query = $this->db->query("SELECT vendor_id FROM " . DB_PREFIX . "vendor WHERE store_slug = '" . $this->db->escape($slug) . "'");
			if (!$query->num_rows) {
				break;
			}
			$slug = $base . '-' . $counter;
			$counter++;
		}

		return $slug;
	}
}

