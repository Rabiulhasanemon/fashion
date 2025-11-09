<?php
class ControllerModuleOperator extends Controller {

    public function __construct($registry) {
        parent::__construct($registry);
        $this->operator = new Operator($registry);
    }

    public function index() {
		$this->load->language('module/operator');
		$this->load->language('operator/operator');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_operator'] = $this->language->get('text_operator');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_quotation'] = $this->language->get('text_quotation');

        $data['logged'] = $this->operator->isLogged();
		$data['register'] = $this->url->link('operator/register', '', 'SSL');
		$data['quotation'] = $this->url->link('operator/quote', '', 'SSL');
		$data['login'] = $this->url->link('operator/login', '', 'SSL');
		$data['logout'] = $this->url->link('operator/logout', '', 'SSL');
		$data['forgotten'] = $this->url->link('operator/forgotten', '', 'SSL');
		$data['operator'] = $this->url->link('operator/operator', '', 'SSL');
		$data['edit'] = $this->url->link('operator/edit', '', 'SSL');
		$data['password'] = $this->url->link('operator/password', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/operator.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/operator.tpl', $data);
		} else {
			return $this->load->view('default/template/module/operator.tpl', $data);
		}
	}
}