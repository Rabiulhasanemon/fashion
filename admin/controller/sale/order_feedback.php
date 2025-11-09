<?php
class ControllerSaleOrderFeedback extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/order_feedback');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order_feedback');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/order_feedback');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order_feedback');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_order_feedback->addOrderFeedback($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['order_id'])) {
				$url .= '&order_id=' . (int) $this->request->get['order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_reorder'])) {
				$url .= '&filter_reorder=' . $this->request->get['filter_reorder'];
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

			$this->response->redirect($this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

        if (!isset($this->request->get['order_id'])) {
            $this->response->redirect($this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL'));
        }
		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/order_feedback');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order_feedback');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_order_feedback->editOrderFeedback($this->request->get['order_feedback_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['order_id'])) {
				$url .= '&order_id=' . urlencode(html_entity_decode($this->request->get['order_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_reorder'])) {
				$url .= '&filter_reorder=' . $this->request->get['filter_reorder'];
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

			$this->response->redirect($this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/order_feedback');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order_feedback');
        $selected = isset($this->request->post['selected']) ? $this->request->post['selected'] : array();
        if(isset($this->request->get['order_feedback_id'])) {
            $selected[] = $this->request->get['order_feedback_id'];
        }
		if (count($selected) > 0 && $this->validateDelete()) {
			foreach ($selected as $order_feedback_id) {
				$this->model_sale_order_feedback->deleteOrderFeedback($order_feedback_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['order_id'])) {
				$url .= '&order_id=' . urlencode(html_entity_decode($this->request->get['order_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_reorder'])) {
				$url .= '&filter_reorder=' . $this->request->get['filter_reorder'];
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

			$this->response->redirect($this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_reorder'])) {
			$filter_reorder = $this->request->get['filter_reorder'];
		} else {
			$filter_reorder = null;
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
			$sort = 'f.date_added';
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['order_id'])) {
			$url .= '&order_id=' . (int) $this->request->get['order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_reorder'])) {
			$url .= '&filter_reorder=' . $this->request->get['filter_reorder'];
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
			'href' => $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sale/order_feedback/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('sale/order_feedback/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['order_feedbacks'] = array();

		$filter_data = array(
			'order_id'    => $order_id,
			'filter_customer'     => $filter_customer,
			'filter_reorder'     => $filter_reorder,
			'filter_date_added' => $filter_date_added,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$order_feedback_total = $this->model_sale_order_feedback->getTotalOrderFeedbacks($filter_data);

		$results = $this->model_sale_order_feedback->getOrderFeedbacks($filter_data);

		foreach ($results as $result) {
			$data['order_feedbacks'][] = array(
				'order_feedback_id'  => $result['order_feedback_id'],
				'order_id'  => $result['order_id'],
				'customer_name'       => $result['customer_name'],
				'telephone'     => $result['telephone'],
				'support_agent'     => $result['support_agent'],
				'response'     => $result['response'],
				'delivery_service'     => $result['delivery_service'],
				'reorder'     => $result['response'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('sale/order_feedback/edit', 'token=' . $this->session->data['token'] . '&order_feedback_id=' . $result['order_feedback_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_yes'] = $this->language->get('text_enabled');
		$data['text_no'] = $this->language->get('text_disabled');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_response'] = $this->language->get('column_response');
		$data['column_support_agent'] = $this->language->get('column_support_agent');
		$data['column_delivery_service'] = $this->language->get('column_delivery_service');
		$data['column_reorder'] = $this->language->get('column_reorder');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_reorder'] = $this->language->get('entry_reorder');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

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
		$data['sort_response'] = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . '&sort=f.response' . $url, 'SSL');
		$data['sort_support_agent'] = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . '&sort=f.support_agent' . $url, 'SSL');
		$data['sort_delivery_service'] = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . '&sort=f.delivery_service' . $url, 'SSL');
		$data['sort_reorder'] = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . '&sort=f.reorder' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . '&sort=f.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['order_id'])) {
			$url .= '&order_id=' . (int) $this->request->get['order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_reorder'])) {
			$url .= '&filter_reorder=' . $this->request->get['filter_reorder'];
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
		$pagination->total = $order_feedback_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_feedback_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_feedback_total - $this->config->get('config_limit_admin'))) ? $order_feedback_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_feedback_total, ceil($order_feedback_total / $this->config->get('config_limit_admin')));

		$data['order_id'] = $order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_reorder'] = $filter_reorder;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_feedback_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
        $this->load->model('sale/order');
        
		$data['text_form'] = !isset($this->request->get['order_feedback_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
        $data['text_confirm'] = $this->language->get('text_confirm');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_response'] = $this->language->get('entry_response');
		$data['entry_support_agent'] = $this->language->get('entry_support_agent');
		$data['entry_delivery_service'] = $this->language->get('entry_delivery_service');
		$data['entry_reorder'] = $this->language->get('entry_reorder');
		$data['entry_comment'] = $this->language->get('entry_comment');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['response'])) {
			$data['error_response'] = $this->error['response'];
		} else {
			$data['error_response'] = '';
		}

		if (isset($this->error['comment'])) {
			$data['error_comment'] = $this->error['comment'];
		} else {
			$data['error_comment'] = '';
		}

		if (isset($this->error['support_agent'])) {
			$data['error_support_agent'] = $this->error['support_agent'];
		} else {
			$data['error_support_agent'] = '';
		}

		if (isset($this->error['delivery_service'])) {
			$data['error_delivery_service'] = $this->error['delivery_service'];
		} else {
			$data['error_delivery_service'] = '';
		}

		$url = '';

		if (isset($this->request->get['order_id'])) {
			$url .= '&order_id=' . (int) $this->request->get['order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_reorder'])) {
			$url .= '&filter_reorder=' . $this->request->get['filter_reorder'];
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
			'href' => $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['order_feedback_id'])) {
			$data['action'] = $this->url->link('sale/order_feedback/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('sale/order_feedback/edit', 'token=' . $this->session->data['token'] . '&order_feedback_id=' . $this->request->get['order_feedback_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('sale/order_feedback', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['order_feedback_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$order_feedback_info = $this->model_sale_order_feedback->getOrderFeedback($this->request->get['order_feedback_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('sale/order');

		if (isset($this->request->post['order_id'])) {
			$data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($order_feedback_info)) {
            $data['order_id'] = $order_feedback_info['order_id'];
		} else {
			$data['order_id'] = $this->request->get['order_id'];
		}

        $order_info = $this->model_sale_order->getOrder($data['order_id']);

		$data['customer'] = $order_info['firstname'] . " " . $order_info['lastname'];
		$data['telephone'] = $order_info['telephone'];

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($order_feedback_info)) {
			$data['comment'] = $order_feedback_info['comment'];
		} else {
			$data['comment'] = '';
		}

		if (isset($this->request->post['response'])) {
			$data['response'] = $this->request->post['response'];
		} elseif (!empty($order_feedback_info)) {
			$data['response'] = $order_feedback_info['response'];
		} else {
			$data['response'] = '';
		}

		if (isset($this->request->post['support_agent'])) {
			$data['support_agent'] = $this->request->post['support_agent'];
		} elseif (!empty($order_feedback_info)) {
			$data['support_agent'] = $order_feedback_info['support_agent'];
		} else {
			$data['support_agent'] = '';
		}

		if (isset($this->request->post['delivery_service'])) {
			$data['delivery_service'] = $this->request->post['delivery_service'];
		} elseif (!empty($order_feedback_info)) {
			$data['delivery_service'] = $order_feedback_info['delivery_service'];
		} else {
			$data['delivery_service'] = '';
		}

		if (isset($this->request->post['reorder'])) {
			$data['reorder'] = $this->request->post['reorder'];
		} elseif (!empty($order_feedback_info)) {
			$data['reorder'] = $order_feedback_info['reorder'];
		} else {
			$data['reorder'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_feedback_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/order_feedback')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['order_id']) {
			$this->error['order_id'] = $this->language->get('error_order_order_id');
		}


		if (utf8_strlen($this->request->post['comment']) > 500) {
			$this->error['comment'] = $this->language->get('error_comment');
		}

		if (!isset($this->request->post['response']) || $this->request->post['response'] < 0 || $this->request->post['response'] > 5) {
			$this->error['response'] = $this->language->get('error_response');
		}

		if (!isset($this->request->post['support_agent']) || $this->request->post['support_agent'] < 0 || $this->request->post['support_agent'] > 5) {
			$this->error['support_agent'] = $this->language->get('error_support_agent');
		}

		if (!isset($this->request->post['delivery_service']) || $this->request->post['delivery_service'] < 0 || $this->request->post['delivery_service'] > 5) {
			$this->error['delivery_service'] = $this->language->get('error_delivery_service');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/order_feedback')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}