<?php
class Operator {
	private $operator_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $safe;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['operator_id'])) {
			$operator_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator WHERE operator_id = '" . (int)$this->session->data['operator_id'] . "' AND status = '1'");

			if ($operator_query->num_rows) {
				$this->operator_id = $operator_query->row['operator_id'];
				$this->firstname = $operator_query->row['firstname'];
				$this->lastname = $operator_query->row['lastname'];
				$this->email = $operator_query->row['email'];
				$this->telephone = $operator_query->row['telephone'];
                $this->safe = $operator_query->row['safe'];
				$this->db->query("UPDATE " . DB_PREFIX . "operator SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE operator_id = '" . (int)$this->operator_id . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator_ip WHERE operator_id = '" . (int)$this->session->data['operator_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "operator_ip SET operator_id = '" . (int)$this->session->data['operator_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {
		if ($override) {
			$operator_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$operator_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "operator WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		}

		if (!$operator_query->num_rows) {
		    return false;
		}
		$operator_id = $operator_query->row['operator_id'];
        $this->session->data['operator_id'] = $operator_id;
        $this->operator_id = $operator_id;
        $this->firstname = $operator_query->row['firstname'];
        $this->lastname = $operator_query->row['lastname'];
        $this->email = $operator_query->row['email'];
        $this->telephone = $operator_query->row['telephone'];
        $this->safe = $operator_query->row['safe'];

        $this->db->query("UPDATE " . DB_PREFIX . "operator SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE operator_id = '" . (int)$this->operator_id . "'");

        return true;
	}

	public function logout() {
		unset($this->session->data['operator_id']);
		$this->operator_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->safe = '';
	}

	public function isLogged() {
		return $this->operator_id;
	}

	public function getId() {
		return $this->operator_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getIsSafe() {
	    return $this->safe;
    }
}