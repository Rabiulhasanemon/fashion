<?php
class ModelVendorWithdrawal extends Model {
	public function addWithdrawal($vendor_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_withdrawal SET 
			vendor_id = '" . (int)$vendor_id . "',
			amount = '" . (float)$data['amount'] . "',
			payment_method = '" . $this->db->escape($data['payment_method']) . "',
			account_details = '" . $this->db->escape(isset($data['account_details']) ? $data['account_details'] : '') . "',
			status = 'pending',
			request_date = NOW()");

		// Deduct from pending balance
		$this->db->query("UPDATE " . DB_PREFIX . "vendor SET 
			pending_balance = pending_balance - '" . (float)$data['amount'] . "',
			date_modified = NOW()
			WHERE vendor_id = '" . (int)$vendor_id . "'");

		return $this->db->getLastId();
	}

	public function getWithdrawals($vendor_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "vendor_withdrawal WHERE vendor_id = '" . (int)$vendor_id . "' ORDER BY request_date DESC");
		return $query->rows;
	}
}


