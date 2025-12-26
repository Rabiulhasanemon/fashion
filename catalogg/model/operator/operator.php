<?php
class ModelOperatorOperator extends Model {

	public function addOperator($data) {
		$this->event->trigger('pre.operator.add', $data);
		$this->db->query("INSERT INTO " . DB_PREFIX . "operator SET  store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', designation = '" . $this->db->escape($data['designation']). "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9))  . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '0', safe = '0', date_added = NOW()");
		$operator_id = $this->db->getLastId();

		$this->event->trigger('post.operator.add', $operator_id);

		return $operator_id;
	}

	public function editOperator($data) {
		$this->event->trigger('pre.operator.edit', $data);
		$operator_id = $this->operator->getId();
		$this->db->query("UPDATE " . DB_PREFIX . "operator SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', designation = '" . $this->db->escape($data['designation']) . "' WHERE operator_id = '" . (int)$operator_id . "'");
		$this->event->trigger('post.operator.edit', $operator_id);
	}

	public function editPassword($email, $password) {
		$this->event->trigger('pre.operator.edit.password');
		$this->db->query("UPDATE " . DB_PREFIX . "operator SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		$this->event->trigger('post.operator.edit.password');
	}

	public function getOperator($operator_id) {
		$query = $this->db->query("SELECT *, CONCAT(o.firstname, ' ', o.lastname) AS name  FROM " . DB_PREFIX . "operator o WHERE o.operator_id = '" . (int)$operator_id . "'");
		return $query->row;
	}

	public function getOperatorByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}

	public function getOperatorByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
		$this->db->query("UPDATE " . DB_PREFIX . "operator SET token = ''");
		return $query->row;
	}

	public function getTotalOperatorsByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "operator WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row['total'];
	}

	public function getIps($operator_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "operator_ip` WHERE operator_id = '" . (int)$operator_id . "'");
		return $query->rows;
	}

	public function isBanIp($operator_id, $ip) {
        $allowed_ip_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator_allowed_ip WHERE operator_id ='". (int) $operator_id . "'");
        if($allowed_ip_query->num_rows && array_search($ip, array_column($allowed_ip_query->rows, 'ip')) === false) {
            return true;
        }
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "operator_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");
		return $query->num_rows;
	}
	
	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		
		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "operator_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "operator_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE operator_login_id = '" . (int)$query->row['operator_login_id'] . "'");
		}			
	}	
	
	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "operator_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}
	
	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "operator_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}
}
