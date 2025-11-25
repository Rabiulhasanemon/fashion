<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}

		// Log login page access
		$log_file = DIR_LOGS . 'admin_login_debug.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== LOGIN PAGE ACCESSED ==========' . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Request Method: ' . $this->request->server['REQUEST_METHOD'] . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data: ' . (isset($this->request->post) ? 'YES (' . count($this->request->post) . ' items)' : 'NO') . PHP_EOL, FILE_APPEND);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation passed, generating token...' . PHP_EOL, FILE_APPEND);
			
			$this->session->data['token'] = md5(mt_rand());
			$token = $this->session->data['token'];
			
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Token generated: ' . $token . PHP_EOL, FILE_APPEND);

            // Add to activity log
            try {
                $this->load->model('user/user');
                $activity_data = array(
                    '%user_id' => $this->user->getId(),
                    '%name'   => $this->user->getFirstName() . ' ' . $this->user->getLastName()
                );
                $this->model_user_user->addActivity($this->user->getId(), 'login', $activity_data);
                file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Activity logged' . PHP_EOL, FILE_APPEND);
            } catch (Exception $e) {
                // Log error but don't block login
                $error_msg = "Activity log error: " . $e->getMessage();
                error_log($error_msg);
                file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ' . $error_msg . PHP_EOL, FILE_APPEND);
            }

			// Handle redirect - use JavaScript fallback if header redirect fails (cPGuard workaround)
			$redirect_url = '';
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
				$redirect_url = $this->request->post['redirect'] . '&token=' . $token;
			} else {
				$redirect_url = $this->url->link('common/dashboard', 'token=' . $token, 'SSL');
			}
			
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Redirect URL: ' . $redirect_url . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Attempting redirect...' . PHP_EOL, FILE_APPEND);
			
			// Output redirect page with multiple fallback methods
			?>
			<!DOCTYPE html>
			<html>
			<head>
				<meta charset="UTF-8">
				<meta http-equiv="refresh" content="0;url=<?php echo htmlspecialchars($redirect_url); ?>">
				<title>Redirecting...</title>
				<script type="text/javascript">
					// Immediate JavaScript redirect
					window.location.href = <?php echo json_encode($redirect_url); ?>;
					
					// Fallback after 1 second
					setTimeout(function() {
						window.location.href = <?php echo json_encode($redirect_url); ?>;
					}, 1000);
				</script>
			</head>
			<body>
				<p>Login successful! Redirecting to dashboard...</p>
				<p>If you are not redirected automatically, <a href="<?php echo htmlspecialchars($redirect_url); ?>">click here</a>.</p>
				<p>Or copy this URL: <br><input type="text" value="<?php echo htmlspecialchars($redirect_url); ?>" style="width:100%;max-width:600px;" readonly onclick="this.select();"></p>
			</body>
			</html>
			<?php
			exit;
		} else {
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Validation failed' . PHP_EOL, FILE_APPEND);
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_login'] = $this->language->get('text_login');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('common/login', '', 'SSL');

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url, 'SSL');
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/login.tpl', $data));
	}

	protected function validate() {
		// Log login attempt for debugging
		$log_file = DIR_LOGS . 'admin_login_debug.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== LOGIN ATTEMPT ==========' . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - POST data received: ' . (isset($this->request->post['username']) ? 'YES' : 'NO') . PHP_EOL, FILE_APPEND);
		
		if (!isset($this->request->post['username'])) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Username not provided in POST' . PHP_EOL, FILE_APPEND);
			$this->error['warning'] = $this->language->get('error_login');
			return !$this->error;
		}
		
		if (!isset($this->request->post['password'])) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Password not provided in POST' . PHP_EOL, FILE_APPEND);
			$this->error['warning'] = $this->language->get('error_login');
			return !$this->error;
		}
		
		$username = $this->request->post['username'];
		$password = $this->request->post['password'];
		
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Username: ' . $username . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Attempting login...' . PHP_EOL, FILE_APPEND);
		
		// Try to login
		$login_result = $this->user->login($username, $password);
		
		if (!$login_result) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - LOGIN FAILED: Invalid credentials or user not active' . PHP_EOL, FILE_APPEND);
			
			// Check if user exists
			$user_check = $this->db->query("SELECT user_id, username, status FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "'");
			if ($user_check->num_rows) {
				$user_data = $user_check->row;
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - User exists: ID=' . $user_data['user_id'] . ', Status=' . $user_data['status'] . PHP_EOL, FILE_APPEND);
				if ($user_data['status'] != '1') {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: User account is disabled (status=' . $user_data['status'] . ')' . PHP_EOL, FILE_APPEND);
				}
			} else {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: User not found in database' . PHP_EOL, FILE_APPEND);
			}
			
			$this->error['warning'] = $this->language->get('error_login');
		} else {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - LOGIN SUCCESS: User ID=' . $this->user->getId() . PHP_EOL, FILE_APPEND);
		}

		return !$this->error;
	}

	public function check() {
		$route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

        if($this->isAPIRequest($route)) {
            return $this->apiCheck($route);
        }

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);

        if(!$this->user->isLogged() &&  isset($this->request->server['PHP_AUTH_USER']) &&  isset($this->request->server['PHP_AUTH_PW']) && $this->user->login($this->request->server['PHP_AUTH_USER'], $this->request->server['PHP_AUTH_PW'])) {
            $this->request->get['token'] = $this->session->data['token'] = md5(mt_rand());
        }

		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
			return new Action('common/login');
		}

		if (isset($this->request->get['route'])) {
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return new Action('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return new Action('common/login');
			}
		}
	}

    private function isAPIRequest($route) {
        if (strpos($route, "api/") === 0) {
            return true;
        }
        return false;
    }


    private function apiCheck($route) {
        $username = isset($this->request->server['PHP_AUTH_USER']) ? $this->request->server['PHP_AUTH_USER'] : '';
        $token = isset($this->request->server['PHP_AUTH_PW']) ? $this->request->server['PHP_AUTH_PW'] : '';
        $json = array();

        $ignore = array(
            'api/auth',
            'api/auth/token',
        );


        if(!in_array($route, $ignore) && !$this->user->loginByAccessToken($username, $token)) {
            $json['type'] = "error";
            $json['error'] = "invalid_access_token";
            $json['message'] = "Invalid Access Token";
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
            $this->response->output();
            exit();
        }
    }
}