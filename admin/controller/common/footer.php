<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['text_footer'] = $this->language->get('text_footer');

		// Check if user is logged in safely
		$isLogged = false;
		if ($this->user && method_exists($this->user, 'isLogged')) {
			$isLogged = $this->user->isLogged();
		}

		if ($isLogged && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
		} else {
			$data['text_version'] = '';
		}


		return $this->load->view('common/footer.tpl', $data);
	}
}