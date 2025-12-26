<?php
class ControllerOperatorEdit extends Controller {
	private $error = array();

	public function __construct($registry) {
        parent::__construct($registry);
        $this->operator = new Operator($registry);
    }

    public function index() {
		if (!$this->operator->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('operator/edit', '', 'SSL');
			$this->response->redirect($this->url->link('operator/login', '', 'SSL'));
		}

		$this->load->language('operator/edit');
		$this->document->setTitle($this->language->get('heading_title'));


		$this->load->model('operator/operator');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_operator_operator->editOperator($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			// Add to activity log
			$this->load->model('operator/activity');
			$activity_data = array(
				'operator_id' => $this->operator->getId(),
				'name'        => $this->operator->getFirstName() . ' ' . $this->operator->getLastName()
			);

			$this->model_operator_activity->addActivity('edit', $activity_data);

			$this->response->redirect($this->url->link('operator/operator', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_operator'),
			'href'      => $this->url->link('operator/operator', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_edit'),
			'href'      => $this->url->link('operator/edit', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_additional'] = $this->language->get('text_additional');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_designation'] = $this->language->get('entry_designation');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_designation'] = $this->error['designation'];
		} else {
			$data['error_designation'] = '';
		}


		$data['action'] = $this->url->link('operator/edit', '', 'SSL');

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$operator_info = $this->model_operator_operator->getOperator($this->operator->getId());
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($operator_info)) {
			$data['firstname'] = $operator_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($operator_info)) {
			$data['lastname'] = $operator_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($operator_info)) {
			$data['email'] = $operator_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($operator_info)) {
			$data['telephone'] = $operator_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['designation'])) {
			$data['designation'] = $this->request->post['designation'];
		} elseif (!empty($operator_info)) {
			$data['designation'] = $operator_info['designation'];
		} else {
			$data['designation'] = '';
		}

		$data['back'] = $this->url->link('operator/operator', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/edit.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/edit.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/operator/edit.tpl', $data));
		}
	}

	protected function validate() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (($this->operator->getEmail() != $this->request->post['email']) && $this->model_operator_operator->getTotalOperatorsByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

        if ((utf8_strlen(trim($this->request->post['designation'])) < 1) || (utf8_strlen(trim($this->request->post['designation'])) > 100)) {
            $this->error['designation'] = $this->language->get('error_designation');
        }

		return !$this->error;
	}
}