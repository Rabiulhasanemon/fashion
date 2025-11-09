<?php
class ControllerApiFeatured extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/featured');
		$this->load->model('extension/module');

        $json = array();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('featured', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

            $json['type'] = 'success';
			$json['message'] = $this->language->get('text_success');

		}

        if($this->error) {
            $json['type'] = 'error';
            $json['error'] = $this->error;
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'api/featured')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (utf8_strlen($this->request->post['name']) > 150) {
			$this->error['blurb'] = $this->language->get('error_blurb');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

		return !$this->error;
	}
}