<?php
class ControllerApiVoucher extends Controller {
	private $error = array();

	public function add() {
		$this->load->language('sale/voucher');


		$this->load->model('sale/voucher');

        $json = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_voucher->addVoucher($this->request->post);
            $json['type'] = 'success';
            $json['message'] = $this->language->get('text_success');
		} else {
		    $json = $this->error['warning'];;
            $json['type'] = 'success';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/voucher') || !$this->user->hasPermission('modify', 'api/voucher')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 20)) {
			$this->error['warning'] = $this->language->get('error_code');
		}

		$voucher_info = $this->model_sale_voucher->getVoucherByCode($this->request->post['code']);

		if ($voucher_info) {
			if (!isset($this->request->get['voucher_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			} elseif ($voucher_info['voucher_id'] != $this->request->get['voucher_id'])  {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		if ((utf8_strlen($this->request->post['to_name']) > 64)) {
			$this->error['warning'] = $this->language->get('error_to_name');
		}

		if ((utf8_strlen($this->request->post['to_email']) > 96) || (strlen($this->request->post['to_email']) > 1 && !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['to_email']))) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
			$this->error['warning'] = $this->language->get('error_from_name');
		}

		if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['from_email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		if ($this->request->post['amount'] < 1) {
			$this->error['warning'] = $this->language->get('error_amount');
		}

		return !$this->error;
	}

}