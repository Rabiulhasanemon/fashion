<?php
namespace DB;
final class MySQLi {
	private $link;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		$this->link = new \mysqli($hostname, $username, $password, $database, $port);

		if ($this->link->connect_error) {
			trigger_error('Error: Could not make a database link (' . $this->link->connect_errno . ') ' . $this->link->connect_error);
			exit();
		}

		$this->link->set_charset("utf8");
		$this->link->query("SET SQL_MODE = ''");
	}

	public function query($sql) {
		$query = $this->link->query($sql);

		if (!$this->link->errno) {
			if ($query instanceof \mysqli_result) {
				$data = array();

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : array();
				$result->rows = $data;

				$query->close();

				return $result;
			} else {
				return true;
			}
		} else {
			// Suppress duplicate key errors (1062) for INSERT statements to allow graceful handling
			if ($this->link->errno == 1062 && (stripos($sql, 'INSERT') === 0 || stripos($sql, 'INSERT IGNORE') === 0 || stripos($sql, 'REPLACE') === 0)) {
				// Return false to indicate the insert failed, but don't trigger error
				return false;
			}
			trigger_error('Error: ' . $this->link->error  . '<br />Error No: ' . $this->link->errno . '<br />' . $sql);
		}
	}

	public function escape($value) {
		return $this->link->real_escape_string($value);
	}

	public function countAffected() {
		return $this->link->affected_rows;
	}

	public function getLastId() {
		return $this->link->insert_id;
	}

	public function __destruct() {
		$this->link->close();
	}
}