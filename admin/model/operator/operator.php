<?php
class ModelOperatorOperator extends Model {
	public function addOperator($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "operator SET  store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', designation = '" . $this->db->escape($data['designation']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) .  "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', safe = '" . (int)$data['safe'] . "', date_added = NOW()");
	}

	public function editOperator($operator_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "operator SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', designation = '" . $this->db->escape($data['designation'])  . "', status = '" . (int)$data['status'] . "', approved = '" . (int)$data['approved'] . "', safe = '" . (int)$data['safe'] . "' WHERE operator_id = '" . (int)$operator_id . "'");
		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "operator SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE operator_id = '" . (int)$operator_id . "'");
		}
	}

	public function editToken($operator_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "operator SET token = '" . $this->db->escape($token) . "' WHERE operator_id = '" . (int)$operator_id . "'");
	}

	public function deleteOperator($operator_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "operator WHERE operator_id = '" . (int)$operator_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "operator_ip WHERE operator_id = '" . (int)$operator_id . "'");
	}

	public function getOperator($operator_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "operator WHERE operator_id = '" . (int)$operator_id . "'");
		return $query->row;
	}

	public function getOperatorByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "operator WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		return $query->row;
	}

	public function getOperators($data = array()) {
		$sql = "SELECT *, CONCAT(o.firstname, ' ', o.lastname) AS name FROM " . DB_PREFIX . "operator o";
		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "o.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}


		if (!empty($data['filter_ip'])) {
			$implode[] = "o.operator_id IN (SELECT operator_id FROM " . DB_PREFIX . "operator_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "o.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "o.approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'o.email',
			'o.status',
			'o.approved',
			'o.ip',
			'o.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
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

	public function approve($operator_id) {
		$operator_info = $this->getOperator($operator_id);

		if ($operator_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "operator SET approved = '1' WHERE operator_id = '" . (int)$operator_id . "'");

			$this->load->language('mail/operator');

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($operator_info['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=operator/login';
			} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG . 'index.php?route=operator/login';
			}

			$message  = sprintf($this->language->get('text_approve_welcome'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= html_entity_decode($store_name, ENT_QUOTES, 'UTF-8');

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($operator_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(sprintf($this->language->get('text_approve_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')));
			$mail->setText($message);
			$mail->send();
		}
	}

	public function getTotalOperators($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "operator";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_operator_group_id'])) {
			$implode[] = "operator_group_id = '" . (int)$data['filter_operator_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "operator_id IN (SELECT operator_id FROM " . DB_PREFIX . "operator_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalOperatorsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "operator WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}

	public function addAllowedIp($operator_id, $ip) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "operator_allowed_ip SET operator_id = '" . (int)$operator_id . "', ip = '" . $this->db->escape($ip) . "', date_added = NOW()");
	}

	public function deleteAllowedIp($operator_id, $operator_allowed_ip_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "operator_allowed_ip WHERE operator_id = '" . (int)$operator_id . "' AND operator_allowed_ip_id = '" . (int)$operator_allowed_ip_id . "'");
	}


	public function getAllowedIps($operator_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator_allowed_ip WHERE operator_id = '" . (int)$operator_id . "'");

        return $query->rows;
	}

    public function getAllowedTotalIps($operator_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "operator_allowed_ip WHERE operator_id = '" . (int)$operator_id . "'");

        return $query->row['total'];
    }

	public function getIps($operator_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator_ip WHERE operator_id = '" . (int)$operator_id . "'");

		return $query->rows;
	}

	public function getTotalIps($operator_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "operator_ip WHERE operator_id = '" . (int)$operator_id . "'");

		return $query->row['total'];
	}

	public function getTotalOperatorsByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "operator_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

	public function addBanIp($ip) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "operator_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function removeBanIp($ip) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "operator_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function getTotalBanIpsByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "operator_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

	public function getTotalLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "operator_login` WHERE `email` = '" . $this->db->escape($email) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "operator_login` WHERE `email` = '" . $this->db->escape($email) . "'");
	}
}