<?php
class ModelSettingPermanentRedirect extends Model {
	public function addPermanentRedirect($data) {

		$this->db->query("INSERT INTO " . DB_PREFIX . "permanent_redirect SET old_url = '" . $this->db->escape($data['old_url']) . "', new_url = '" . $this->db->escape($data['new_url']) . "', date_added = NOW()");
		$permanent_redirect_id = $this->db->getLastId();
		return $permanent_redirect_id;
	}

	public function editPermanentRedirect($permanent_redirect_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "permanent_redirect SET old_url = '" . $this->db->escape($data['old_url']) . "', new_url = '" . $this->db->escape($data['new_url']) . "' WHERE permanent_redirect_id = '" . (int)$permanent_redirect_id . "'");
	}


	public function deletePermanentRedirect($permanent_redirect_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "permanent_redirect WHERE permanent_redirect_id = '" . (int)$permanent_redirect_id . "'");
	}

	public function getPermanentRedirect($permanent_redirect_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "permanent_redirect r WHERE r.permanent_redirect_id = '" . (int)$permanent_redirect_id . "'");
		return $query->row;
	}

    public function getPermanentRedirectByOldUrl($old_url) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "permanent_redirect r WHERE r.old_url = '" . $this->db->escape($old_url) . "'");
        return $query->row;
    }

	public function getPermanentRedirects($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "permanent_redirect r";

		$sort_data = array(
			'r.old_url',
			'r.new_url',
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

	public function getTotalPermanentRedirects($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "permanent_redirect r";
		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}