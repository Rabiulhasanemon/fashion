<?php
class ControllerVendorReview extends Controller {
	public function add() {
		$this->load->language('vendor/review');
		$this->load->model('vendor/review');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->customer->isLogged()) {
				$json['error'] = $this->language->get('error_login');
			}

			if (!isset($this->request->post['vendor_id']) || !isset($this->request->post['rating']) || $this->request->post['rating'] < 1 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			if (empty($this->request->post['comment']) || utf8_strlen($this->request->post['comment']) < 25) {
				$json['error'] = $this->language->get('error_comment');
			}

			if (!isset($json['error'])) {
				$this->model_vendor_review->addReview($this->request->post['vendor_id'], $this->customer->getId(), $this->request->post);
				
				// Update vendor rating
				$this->load->model('vendor/vendor');
				$this->model_vendor_vendor->updateVendorRating($this->request->post['vendor_id']);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}


