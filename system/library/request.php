<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

	public function __construct() {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}

	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}

	public function getJSONData() {
	    try {
            $content_type = isset($this->server["CONTENT_TYPE"]) ? strtolower(trim($this->server["CONTENT_TYPE"])) : '';
            if($content_type == "application/json") {
                return json_decode(file_get_contents('php://input'), true);
            }
        }catch (Exception $ignore){}
        return array();
    }
    public function getIP() {
        if (!empty($this->server['HTTP_X_FORWARDED_FOR'])) {
            $ip = $this->server['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($this->server['HTTP_CLIENT_IP'])) {
            $ip = $this->server['HTTP_CLIENT_IP'];
        } else {
            $ip = $this->server['REMOTE_ADDR'];
        }
        return $ip;
    }
}