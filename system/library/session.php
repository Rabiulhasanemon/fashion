<?php
class Session {
	public $data = array();

	public function __construct() {
		if (!session_id()) {
			// Only set session ini settings if headers haven't been sent
			if (!headers_sent()) {
				@ini_set('session.use_only_cookies', 'On');
				@ini_set('session.use_trans_sid', 'Off');
				@ini_set('session.cookie_httponly', 'On');
				@session_set_cookie_params(0, '/');
			}
			
			// Only start session if headers haven't been sent
			if (!headers_sent()) {
				@session_start();
			}
		}

		// Initialize data array if $_SESSION doesn't exist
		if (!isset($_SESSION)) {
			$_SESSION = array();
		}
		
		$this->data =& $_SESSION;
	}

	public function getId() {
		return session_id();
	}

	public function destroy() {
		return session_destroy();
	}

    public function setApiId($api_id) {
        $this->api_id = $api_id;
    }

    public function getApiId() {
        return $this->api_id;
    }
}