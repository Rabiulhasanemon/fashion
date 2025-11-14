<?php
#[AllowDynamicProperties]
class User {
	private $user_id;
	private $username;
    private $firstname;
    private $lastname;
	private $permission = array();

    public function __construct($registry) {
        $this->db = $registry->get('db');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');

        if (isset($this->session->data['user_id'])) {
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data['user_id'] . "' AND status = '1'");

            if ($user_query->num_rows) {
                $this->user_id = $user_query->row['user_id'];
                $this->username = $user_query->row['username'];
                $this->firstname = $user_query->row['firstname'];
                $this->lastname = $user_query->row['lastname'];
                $this->user_group_id = $user_query->row['user_group_id'];

                $this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->getIP()) . "' WHERE user_id = '" . (int)$this->session->data['user_id'] . "'");

                $user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_query->row['user_group_id'] . "'");

                $permissions = unserialize($user_group_query->row['permission']);

                if (is_array($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $this->permission[$key] = $value;
                    }
                }
            } else {
                $this->logout();
            }
        }
    }

    private function _login($user_info) {
        $this->session->data['user_id'] = $user_info['user_id'];

        $this->user_id = $user_info['user_id'];
        $this->username = $user_info['username'];
        $this->firstname = $user_info['firstname'];
        $this->lastname = $user_info['lastname'];
        $this->user_group_id = $user_info['user_group_id'];

        $user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$user_info['user_group_id'] . "'");

        $permissions = unserialize($user_group_query->row['permission']);

        if (is_array($permissions)) {
            foreach ($permissions as $key => $value) {
                $this->permission[$key] = $value;
            }
        }

    }

    public function login($username, $password, $check_ip = false) {

        $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) AND status = '1'");
        if ($user_query->num_rows) {
            $user_info = $user_query->row;
        } else {
            return false;
        }

        if($user_info["check_ip"] && $check_ip && !$this->isAllowedIp()) {
            return false;
        }
        $this->_login($user_info);
        return true;
    }

    public function isAllowedIp() {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_allowed_ip` WHERE ip = '" . $this->db->escape($this->request->getIP()) . "'");

        return $query->num_rows;
    }
    public function loginByAccessToken($username, $token) {
        $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND access_token = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($token) . "'))))) AND access_token_expiry > now() AND status = '1'");

        if ($user_query->num_rows) {
            $this->_login($user_query->row);
            return true;
        } else {
            return false;
        }
    }


    public function loginByRefreshToken($username, $token) {
        $sql = "SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND refresh_token = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($token) . "'))))) AND refresh_token_expiry > now() AND status = '1'";
        $user_query = $this->db->query($sql);

        if ($user_query->num_rows) {
            $this->_login($user_query->row);
            return true;
        } else {
            return false;
        }
    }

	public function logout() {
		unset($this->session->data['user_id']);

		$this->user_id = '';
		$this->username = '';
	}

	public function hasPermission($key, $value) {
		if (isset($this->permission[$key])) {
			return in_array($value, $this->permission[$key]);
		} else {
			return false;
		}
	}

    public function isLogged() {
        return $this->user_id;
    }

    public function getId() {
        return $this->user_id;
    }

    public function getUserName() {
        return $this->username;
    }

    public function getFirstName() {
        return $this->firstname;
    }

    public function getLastName() {
        return $this->lastname;
    }

    public function getGroupId() {
        return $this->user_group_id;
    }
}