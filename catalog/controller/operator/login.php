<?php
class ControllerOperatorLogin extends Controller {
	private $error = array();

	public function __construct($registry) {
        parent::__construct($registry);
        $this->operator = new Operator($registry);
    }

    public function index() {
		$this->load->model('operator/operator');

		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->event->trigger('pre.operator.login');

			$this->operator->logout();
			$operator_info = $this->model_operator_operator->getOperatorByToken($this->request->get['token']);
			if ($operator_info && $this->operator->login($operator_info['email'], '', true)) {
				$this->event->trigger('post.operator.login');
				$this->response->redirect($this->url->link('operator/operator', '', 'SSL'));
			}
		}

		if ($this->operator->isLogged()) {
			$this->response->redirect($this->url->link('operator/operator', '', 'SSL'));
		}

		$this->load->language('operator/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('operator/activity');

			$activity_data = array(
				'operator_id' => $this->operator->getId(),
				'name'        => $this->operator->getFirstName() . ' ' . $this->operator->getLastName()
			);

			$this->model_operator_activity->addActivity('login', $activity_data);

			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->response->redirect($this->url->link('operator/operator', '', 'SSL'));
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_operator'),
			'href' => $this->url->link('operator/operator', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('operator/login', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_new_operator'] = $this->language->get('text_new_operator');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_registered_operator'] = $this->language->get('text_registered_operator');
		$data['text_i_am_registered_operator'] = $this->language->get('text_i_am_registered_operator');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('operator/login', '', 'SSL');
		$data['register'] = $this->url->link('operator/register', '', 'SSL');
		$data['forgotten'] = $this->url->link('operator/forgotten', '', 'SSL');

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/login.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/operator/login.tpl', $data));
		}
	}

	protected function validate() {
		$this->event->trigger('pre.operator.login');
		// Check how many login attempts have been made.
		$login_info = $this->model_operator_operator->getLoginAttempts($this->request->post['email']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if operator has been approved.
		$operator_info = $this->model_operator_operator->getOperatorByEmail($this->request->post['email']);

		if ($operator_info && !$operator_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}
		if (!$this->error) {
		    if($operator_info && $this->model_operator_operator->isBanIp($operator_info['operator_id'], $this->request->server['REMOTE_ADDR'])) {
                $this->error['warning'] = $this->language->get('error_ban_ip');
                $this->model_operator_operator->addLoginAttempt($this->request->post['email']);
            } elseif (!$this->operator->login($this->request->post['email'], $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');
				$this->model_operator_operator->addLoginAttempt($this->request->post['email']);
			} else {
				$this->model_operator_operator->deleteLoginAttempts($this->request->post['email']);
				$this->event->trigger('post.operator.login');
			}
		}
		return !$this->error;
	}

	public function isLoggedIn() {
        $json = array();
        if ($this->operator->isLogged()) {
            $json['is_logged_in'] = true;
        } else {
            $json['is_logged_in'] = false;
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}