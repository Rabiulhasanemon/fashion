<?php
class ModelExtensionShipping extends Model {
	public function addShipping($code, $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "shipping` SET `name` = '" . $this->db->escape($data['name']) . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(serialize($data)) . "'");
	}
	
	public function editShipping($shipping_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "shipping` SET `name` = '" . $this->db->escape($data['name']) . "', `setting` = '" . $this->db->escape(serialize($data)) . "' WHERE `shipping_id` = '" . (int)$shipping_id . "'");
	}

	public function deleteShipping($shipping_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "shipping` WHERE `shipping_id` = '" . (int)$shipping_id . "'");
	}
		
	public function getShipping($shipping_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "shipping` WHERE `shipping_id` = '" . $this->db->escape($shipping_id) . "'");

		if ($query->row) {
			return unserialize($query->row['setting']);
		} else {
			return array();	
		}
	}
	
	public function getShippings() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "shipping` ORDER BY `code`");

		return $query->rows;
	}	
		
	public function getShippingsByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "shipping` WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `name`");

		return $query->rows;
	}	
	
	public function deleteShippingsByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "shipping` WHERE `code` = '" . $this->db->escape($code) . "'");
	}
}