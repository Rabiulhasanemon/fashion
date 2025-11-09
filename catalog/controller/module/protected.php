<?php
class ControllerModuleProtected extends Controller {
    private $error = array();
	public function index() {
        if (!isset($this->session->data['pin']) || $this->session->data['pin'] !== $this->config->get('protected_pin')) {
            $this->response->redirect($this->url->link('module/protected/login'));
        }
    }

    public function login() {

        $this->load->language('module/protected');

        $this->document->setTitle($this->language->get('heading_title'));

        if (isset($this->session->data['pin']) && $this->session->data['pin'] === $this->config->get('protected_pin') || !$this->config->get('protected_status')) {
            $this->response->redirect($this->url->link('common/home'));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->session->data['pin'] = $this->config->get('protected_pin');
            $this->response->redirect($this->url->link('common/home'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_protected'),
            'href' => $this->url->link('module/protected', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_pin'] = $this->language->get('text_pin');

        $data['entry_pin'] = $this->language->get('entry_pin');

        $data['button_pin'] = $this->language->get('button_pin');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('module/protected/login', '', 'SSL');

        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/protected.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/module/protected.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/module/protected.tpl', $data));
        }
    }

    protected function validate() {
        $pin = $this->config->get('protected_pin');

        if ((utf8_strlen(trim($this->request->post['pin'])) < 1) || (utf8_strlen(trim($this->request->post['pin'])) > 32) || $this->request->post['pin'] !== $pin) {
            $this->error['warning'] = $this->language->get('error_pin');
        }
        return !$this->error;
    }

}