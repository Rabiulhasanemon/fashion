<?php
class ControllerSalePayment extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/payment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/payment');

		$this->getList();
	}
	

	protected function getList() {
		if (isset($this->request->get['filter_payment_id'])) {
			$filter_payment_id = $this->request->get['filter_payment_id'];
		} else {
			$filter_payment_id = null;
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

        if (isset($this->request->get['filter_transaction_id'])) {
            $filter_transaction_id = $this->request->get['filter_transaction_id'];
        } else {
            $filter_transaction_id = null;
        }

        if (isset($this->request->get['filter_total'])) {
            $filter_total = $this->request->get['filter_total'];
        } else {
            $filter_total = null;
        }

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.date_added';
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_payment_id'])) {
			$url .= '&filter_payment_id=' . urlencode(html_entity_decode($this->request->get['filter_payment_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_transaction_id'])) {
            $url .= '&filter_transaction_id=' . $this->request->get['filter_transaction_id'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sale/payment/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('sale/payment/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['payments'] = array();

		$filter_data = array(
			'filter_payment_id'    => $filter_payment_id,
			'filter_order_id'     => $filter_order_id,
			'filter_status'     => $filter_status,
			'filter_transaction_id'=> $filter_transaction_id,
			'filter_total'      => $filter_total,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$payment_total = $this->model_sale_payment->getTotalPayments($filter_data);

		$results = $this->model_sale_payment->getPayments($filter_data);

		foreach ($results as $result) {
			$data['payments'][] = array(
				'payment_id'  => $result['payment_id'],
				'order_id'  => $result['order_id'],
				'status'  => $result['status'],
				'gateway_title' => $result['gateway_title'],
				'transaction_id'     => $result['transaction_id'],
				'tracking_no'     => $result['tracking_no'],
				'payer_info'     => $result['payer_info'],
				'comment'     => $result['comment'],
				'total'     => $result['total'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_approved'] = $this->language->get('text_approved');
        $data['text_failed'] = $this->language->get('text_failed');
        $data['text_pending'] = $this->language->get('text_pending');
        $data['text_refunded'] = $this->language->get('text_refunded');;

		$data['column_payment_id'] = $this->language->get('column_payment_id');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_transaction_id'] = $this->language->get('column_transaction_id');
		$data['column_tracking'] = $this->language->get('column_tracking');
		$data['column_gateway'] = $this->language->get('column_gateway');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_payment_id'] = $this->language->get('entry_payment_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_transaction_id'] = $this->language->get('entry_transaction_id');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_payment_id'] = $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . '&sort=p.payment_id' . $url, 'SSL');
		$data['sort_order_id'] = $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . '&sort=p.order_id' . $url, 'SSL');
		$data['sort_total'] = $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . '&sort=p.total' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . '&sort=p.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_payment_id'])) {
			$url .= '&filter_payment_id=' . urlencode(html_entity_decode($this->request->get['filter_payment_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . urlencode(html_entity_decode($this->request->get['filter_order_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

        if (isset($this->request->get['filter_transaction_id'])) {
            $url .= '&filter_transaction_id=' . $this->request->get['filter_transaction_id'];
        }

        if (isset($this->request->get['filter_total'])) {
            $url .= '&filter_total=' . $this->request->get['filter_total'];
        }

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $payment_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/payment', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payment_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payment_total - $this->config->get('config_limit_admin'))) ? $payment_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payment_total, ceil($payment_total / $this->config->get('config_limit_admin')));

		$data['filter_payment_id'] = $filter_payment_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_status'] = $filter_status;
		$data['filter_total'] = $filter_total;
		$data['filter_transaction_id'] = $filter_transaction_id;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/payment_list.tpl', $data));
	}
}