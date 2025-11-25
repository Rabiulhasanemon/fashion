<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['token'] = md5(mt_rand());

            // Add to activity log
            try {
                $this->load->model('user/user');
                $activity_data = array(
                    '%user_id' => $this->user->getId(),
                    '%name'   => $this->user->getFirstName() . ' ' . $this->user->getLastName()
                );
                $this->model_user_user->addActivity($this->user->getId(), 'login', $activity_data);
            } catch (Exception $e) {
                // Log error but don't block login
                error_log("Activity log error: " . $e->getMessage());
            }

			// Handle redirect - use JavaScript fallback if header redirect fails (cPGuard workaround)
			$redirect_url = '';
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
				$redirect_url = $this->request->post['redirect'] . '&token=' . $this->session->data['token'];
			} else {
				$redirect_url = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
			}
			
			// Try header redirect first
			$this->response->redirect($redirect_url);
			
			// If redirect fails (cPGuard blocking), output JavaScript redirect as fallback
			echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($redirect_url) . '"></head>';
			echo '<body><script>window.location.href = "' . htmlspecialchars($redirect_url) . '";</script>';
			echo '<p>Redirecting... If you are not redirected, <a href="' . htmlspecialchars($redirect_url) . '">click here</a>.</p></body></html>';
			exit;
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
		if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
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