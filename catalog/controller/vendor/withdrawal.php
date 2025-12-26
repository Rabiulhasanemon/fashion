<?php
class ControllerVendorWithdrawal extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('vendor/withdrawal', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if (!$vendor || $vendor['status'] != 'approved') {
			$this->response->redirect($this->url->link('vendor/dashboard'));
		}

		$this->load->language('vendor/withdrawal');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('vendor/withdrawal');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_vendor_withdrawal->addWithdrawal($vendor['vendor_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('vendor/withdrawal', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_vendor'),
			'href' => $this->url->link('vendor/dashboard')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/withdrawal')
		);

		$data['pending_balance'] = $this->currency->format($vendor['pending_balance'], $this->config->get('config_currency'));
		$data['action'] = $this->url->link('vendor/withdrawal', '', 'SSL');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['withdrawals'] = array();
		$results = $this->model_vendor_withdrawal->getWithdrawals($vendor['vendor_id']);

		foreach ($results as $result) {
			$data['withdrawals'][] = array(
				'withdrawal_id' => $result['withdrawal_id'],
				'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'status' => $result['status'],
				'payment_method' => $result['payment_method'],
				'request_date' => date($this->language->get('date_format_short'), strtotime($result['request_date'])),
				'processed_date' => $result['processed_date'] ? date($this->language->get('date_format_short'), strtotime($result['processed_date'])) : '-'
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/vendor/withdrawal.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/vendor/withdrawal.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/vendor/withdrawal.tpl', $data));
		}
	}

	protected function validate() {
		if (!isset($this->request->post['amount']) || (float)$this->request->post['amount'] <= 0) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		$this->load->model('vendor/vendor');
		$vendor = $this->model_vendor_vendor->getVendorByCustomerId($this->customer->getId());

		if ((float)$this->request->post['amount'] > $vendor['pending_balance']) {
			$this->error['amount'] = $this->language->get('error_amount_exceed');
		}

		return !$this->error;
	}
}


