<?php
class ModelUserUser extends Model {
	public function addUser($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function editUser($user_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET username = '" . $this->db->escape($data['username']) . "', user_group_id = '" . (int)$data['user_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE user_id = '" . (int)$user_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE user_id = '" . (int)$user_id . "'");
		}
	}

	public function editPassword($user_id, $password) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE user_id = '" . (int)$user_id . "'");
	}

	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "user` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function deleteUser($user_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "user` WHERE user_id = '" . (int)$user_id . "'");
	}

	public function getUser($user_id) {
		$query = $this->db->query("SELECT *, (SELECT ug.name FROM `" . DB_PREFIX . "user_group` ug WHERE ug.user_group_id = u.user_group_id) AS user_group FROM `" . DB_PREFIX . "user` u WHERE u.user_id = '" . (int)$user_id . "'");

		return $query->row;
	}

	public function getUserByUsername($username) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE username = '" . $this->db->escape($username) . "'");

		return $query->row;
	}

	public function getUserByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getUsers($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "user`";
        $implodes = array();

		if(isset($data['status'])) {
		    $implodes[] = "status = '" . (int) $data['status'] . "'";
        }

        if(isset($data['filter_name'])) {
            $implodes[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }
        if($implodes) {
		    $sql .= " WHERE " . implode(' AND ', $implodes);
        }
		$sort_data = array(
			'username',
			'status',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY username";
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

	public function getTotalUsers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user`");

		return $query->row['total'];
	}

	public function getTotalUsersByGroupId($user_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE user_group_id = '" . (int)$user_group_id . "'");

		return $query->row['total'];
	}

	public function getTotalUsersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "user` WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}
    public function addActivity($user_id, $key, $data, $reference_id = 0) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "user_activity` SET `user_id` = '" . (int)$user_id . "', `key` = '" . $this->db->escape($key) . "', `reference_id` = '" . (int) $reference_id . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->getIP()) . "', `date_added` = NOW()");
    }


    public function getTempAuthToken($user_id) {
        $access_token = md5(mt_rand());
        $refresh_token = md5(mt_rand());
        $expiry = date('Y-m-d H:i:s', time() + 10 * 60);
        $user = $this->getUser($user_id);
        $salt = $user['salt'];
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET access_token = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($access_token)))) . "', refresh_token = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($refresh_token)))) . "', access_token_expiry = '" . $this->db->escape($expiry)  . "', refresh_token_expiry = '" . $this->db->escape($expiry) . "' WHERE user_id = '" . (int)$user_id . "'");
        return array(
            'access_token' => $access_token,
            'refresh_token' => $refresh_token
        );
    }

    public function getAuthToken($user_id) {
        $access_token = md5(mt_rand());
        $refresh_token = md5(mt_rand());
        $access_token_expiry = date('Y-m-d H:i:s', time() + 60 * 60);
        $refresh_token_expiry = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 5);
        $user = $this->getUser($user_id);
        $salt = $user['salt'];
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET access_token = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($access_token)))) . "', refresh_token = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($refresh_token)))) . "', access_token_expiry = '" . $this->db->escape($access_token_expiry)  . "', refresh_token_expiry = '" . $this->db->escape($refresh_token_expiry) . "' WHERE user_id = '" . (int)$user_id . "'");
        return array(
            'access_token' => $access_token,
            'refresh_token' => $refresh_token
        );
    }

}