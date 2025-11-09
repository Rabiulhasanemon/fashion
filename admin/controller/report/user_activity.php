<?php
class ControllerReportUserActivity extends Controller {
	public function index() {
		$this->load->language('report/user_activity');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}

		if (isset($this->request->get['filter_reference'])) {
			$filter_reference = $this->request->get['filter_reference'];
		} else {
			$filter_reference = null;
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . urlencode($this->request->get['filter_user']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . $this->request->get['filter_reference'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/user_activity', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text' => $this->language->get('heading_title')
		);

		$this->load->model('report/user');

		$data['activities'] = array();

		$filter_data = array(
			'filter_user'       => $filter_user,
			'filter_ip'         => $filter_ip,
			'filter_reference'  => $filter_reference,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$activity_total = $this->model_report_user->getTotalUserActivities($filter_data);

		$results = $this->model_report_user->getUserActivities($filter_data);

		foreach ($results as $result) {
            $args = unserialize($result['data']);
			$comment = str_replace(array_keys($args), array_values($args), $this->language->get('text_' . $result['key']));

			$find = array(
				'user_id=',
				'product_id=',
				'category_id=',
				'coupon_id=',
				'information_id=',
				'offer_id=',
				'module_id=',
				'module_name=',
				'setting_code=',
			);

			$replace = array(
				$this->url->link('sale/user/edit', 'token=' . $this->session->data['token'] . '&user_id=', 'SSL'),
				$this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=', 'SSL'),
				$this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=', 'SSL'),
				$this->url->link('marketing/coupon/edit', 'token=' . $this->session->data['token'] . '&coupon_id=', 'SSL'),
				$this->url->link('catalog/information/edit', 'token=' . $this->session->data['token'] . '&information_id=', 'SSL'),
				$this->url->link('catalog/offer/edit', 'token=' . $this->session->data['token'] . '&offer_id=', 'SSL'),
			);

            $comment = str_replace($find, $replace, $comment);

			$data['activities'][] = array(
				'comment'    => $comment,
				'ip'         => $result['ip'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_comment'] = $this->language->get('column_comment');
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['entry_user'] = $this->language->get('entry_user');
		$data['entry_reference'] = $this->language->get('entry_reference');
		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . urlencode($this->request->get['filter_user']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

        if (isset($this->request->get['filter_reference'])) {
            $url .= '&filter_reference=' . $this->request->get['filter_reference'];
        }

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $activity_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/user_activity', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

		$data['filter_user'] = $filter_user;
		$data['filter_ip'] = $filter_ip;
		$data['filter_reference'] = $filter_reference;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/user_activity.tpl', $data));
	}
}