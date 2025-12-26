<?php
class ControllerVendorRegister extends Controller {
	private $error = array();
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/register', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('vendor/register');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/vendor');

		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());
		if ($vendor) {
			// Already applied
			$this->response->redirect($this->url->link('vendor/dashboard', '', true));
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->model_vendor_vendor->addVendor($this->customer->getId(), $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success_pending');
			// Manual approval required
			$this->response->redirect($this->url->link('vendor/dashboard', '', true));
		}

		$data = array();
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_description'] = $this->language->get('text_description');
		$data['action'] = $this->url->link('vendor/register', '', true);
		$data['button_continue'] = $this->language->get('button_continue');
		$data['entry_store_name'] = $this->language->get('entry_store_name');
		$data['entry_store_description'] = $this->language->get('entry_store_description');
		$data['entry_store_address'] = $this->language->get('entry_store_address');
		$data['entry_store_city'] = $this->language->get('entry_store_city');
		$data['entry_store_phone'] = $this->language->get('entry_store_phone');
		$data['entry_store_email'] = $this->language->get('entry_store_email');

		$data['store_name'] = isset($this->request->post['store_name']) ? $this->request->post['store_name'] : '';
		$data['store_description'] = isset($this->request->post['store_description']) ? $this->request->post['store_description'] : '';
		$data['store_address'] = isset($this->request->post['store_address']) ? $this->request->post['store_address'] : '';
		$data['store_city'] = isset($this->request->post['store_city']) ? $this->request->post['store_city'] : '';
		$data['store_phone'] = isset($this->request->post['store_phone']) ? $this->request->post['store_phone'] : '';
		$data['store_email'] = isset($this->request->post['store_email']) ? $this->request->post['store_email'] : $this->customer->getEmail();

		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
		$data['error_store_name'] = isset($this->error['store_name']) ? $this->error['store_name'] : '';

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/register')
		);

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = '';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/register.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/vendor/register.tpl', $data));
		}
	}

	protected function validate() {
		$this->error = array();

		if (!isset($this->request->post['store_name']) || utf8_strlen(trim($this->request->post['store_name'])) < 3) {
			$this->error['store_name'] = $this->language->get('error_store_name');
		}

		if ($this->error) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}

