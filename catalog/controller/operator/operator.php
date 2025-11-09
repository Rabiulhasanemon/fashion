<?php
class ControllerOperatorOperator extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->operator = new Operator($registry);
    }

    public function index() {
		if (!$this->operator->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('operator/operator', '', 'SSL');

			$this->response->redirect($this->url->link('operator/login', '', 'SSL'));
		}

		$this->load->language('operator/operator');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_operator'),
			'href' => $this->url->link('operator/operator', '', 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_my_account'] = $this->language->get('text_my_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_quotation'] = $this->language->get('text_quotation');

		$data['quote'] = $this->url->link('operator/quote', '', 'SSL');
		$data['edit'] = $this->url->link('operator/edit', '', 'SSL');
		$data['password'] = $this->url->link('operator/password', '', 'SSL');
		$data['logout'] = $this->url->link('operator/logout', '', 'SSL');

		if ($this->config->get('reward_status')) {
			$data['reward'] = $this->url->link('operator/reward', '', 'SSL');
		} else {
			$data['reward'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/operator/operator.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/operator.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/operator/operator.tpl', $data));
		}
	}
}