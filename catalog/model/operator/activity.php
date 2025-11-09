<?php
class ModelOperatorActivity extends Model {

	public function addActivity($key, $data) {
		if (isset($data['operator_id'])) {
			$operator_id = $data['operator_id'];
		} else {
			$operator_id = 0;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "operator_activity` SET `operator_id` = '" . (int)$operator_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}