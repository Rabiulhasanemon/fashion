<?php
class ControllerMarketingSMS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/sms');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_single'] = $this->language->get('text_single');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_customer_all'] = $this->language->get('text_customer_all');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_to'] = $this->language->get('entry_to');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_message'] = $this->language->get('entry_message');

		$data['help_customer'] = $this->language->get('help_customer');
		$data['help_affiliate'] = $this->language->get('help_affiliate');
		$data['help_product'] = $this->language->get('help_product');

		$data['button_send'] = $this->language->get('button_send');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/sms', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['cancel'] = $this->url->link('marketing/sms', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('setting/store');


		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/sms.tpl', $data));
	}

	public function send() {
		$this->load->language('marketing/sms');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'marketing/sms')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if ($this->request->post['to'] == "single" && !preg_match('/^(011|013|014|015|016|017|018|019)[0-9]{8}$/i', $this->request->post['telephone'])) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {
				$this->load->model('setting/store');
				$this->load->model('sale/customer');
				$this->load->model('sale/customer_group');
				$this->load->model('marketing/affiliate');
				$this->load->model('sale/order');

				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}

				$sms_total = 0;

				$contact_list = array();

				switch ($this->request->post['to']) {
                    case "single":
                        $sms_total = 1;
                        $contact_list[] = $this->request->post['telephone'];
                        break;
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);

						$sms_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$contact_list[] = $result['sms'];
						}
						break;
					case 'customer_all':
						$customer_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$sms_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$contact_list[] = $result['sms'];
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);

						$sms_total = $this->model_sale_customer->getTotalCustomers($customer_data);

						$results = $this->model_sale_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$contact_list[$result['customer_id']] = $result['sms'];
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_sale_customer->getCustomer($customer_id);

								if ($customer_info) {
									$contact_list[] = $customer_info['sms'];
								}
							}
						}
						break;
					case 'affiliate_all':
						$affiliate_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$sms_total = $this->model_marketing_affiliate->getTotalAffiliates($affiliate_data);

						$results = $this->model_marketing_affiliate->getAffiliates($affiliate_data);

						foreach ($results as $result) {
							$contact_list[] = $result['sms'];
						}
						break;
					case 'affiliate':
						if (!empty($this->request->post['affiliate'])) {
							foreach ($this->request->post['affiliate'] as $affiliate_id) {
								$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

								if ($affiliate_info) {
									$contact_list[] = $affiliate_info['sms'];
								}
							}
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$sms_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

							foreach ($results as $result) {
								$contact_list[] = $result['sms'];
							}
						}
						break;
				}

				if ($contact_list) {
					$start = ($page - 1) * 10;
					$end = $start + 10;

					if ($end < $sms_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $sms_total);
					} else {
						$json['success'] = $this->language->get('text_success');
					}

					if ($end < $sms_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('marketing/sms/send', 'token=' . $this->session->data['token'] . '&page=' . ($page + 1), 'SSL'));
					} else {
						$json['next'] = '';
					}

					$message  = $this->request->post['message'];

					foreach ($contact_list as $contact) {
						if (preg_match('/^(011|013|014|015|016|017|018|019)[0-9]{8}$/i', $contact)) {
							$this->sms->send($contact, $message);
						}
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}