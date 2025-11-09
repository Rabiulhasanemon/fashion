<?php
class ControllerSettingPermanentRedirect extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('setting/permanent_redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/permanent_redirect');

		$this->getList();
	}

	public function add() {
		$this->load->language('setting/permanent_redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/permanent_redirect');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_permanent_redirect->addPermanentRedirect($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('setting/permanent_redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/permanent_redirect');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_permanent_redirect->editPermanentRedirect($this->request->get['permanent_redirect_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('setting/permanent_redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/permanent_redirect');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $permanent_redirect_id) {
				$this->model_setting_permanent_redirect->deletePermanentRedirect($permanent_redirect_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
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
			'href' => $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('setting/permanent_redirect/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('setting/permanent_redirect/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['permanent_redirects'] = array();

		$filter_data = array(
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$permanent_redirect_total = $this->model_setting_permanent_redirect->getTotalPermanentRedirects($filter_data);

		$results = $this->model_setting_permanent_redirect->getPermanentRedirects($filter_data);

		foreach ($results as $result) {
			$data['permanent_redirects'][] = array(
				'permanent_redirect_id'  => $result['permanent_redirect_id'],
				'old_url'       => $result['old_url'],
				'new_url'     => $result['new_url'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('setting/permanent_redirect/edit', 'token=' . $this->session->data['token'] . '&permanent_redirect_id=' . $result['permanent_redirect_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_old_url'] = $this->language->get('column_old_url');
		$data['column_new_url'] = $this->language->get('column_new_url');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		$data['sort_old_url'] = $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . '&sort=r.old_url' . $url, 'SSL');
		$data['sort_new_url'] = $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . '&sort=r.new_url' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $permanent_redirect_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($permanent_redirect_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($permanent_redirect_total - $this->config->get('config_limit_admin'))) ? $permanent_redirect_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $permanent_redirect_total, ceil($permanent_redirect_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/permanent_redirect_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['permanent_redirect_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_old_url'] = $this->language->get('entry_old_url');
		$data['entry_new_url'] = $this->language->get('entry_new_url');


		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['old_url'])) {
			$data['error_old_url'] = $this->error['old_url'];
		} else {
			$data['error_old_url'] = '';
		}

        if (isset($this->error['new_url'])) {
            $data['error_new_url'] = $this->error['new_url'];
        } else {
            $data['error_new_url'] = '';
        }

		$url = '';

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
			'href' => $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['permanent_redirect_id'])) {
			$data['action'] = $this->url->link('setting/permanent_redirect/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('setting/permanent_redirect/edit', 'token=' . $this->session->data['token'] . '&permanent_redirect_id=' . $this->request->get['permanent_redirect_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('setting/permanent_redirect', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['permanent_redirect_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$permanent_redirect_info = $this->model_setting_permanent_redirect->getPermanentRedirect($this->request->get['permanent_redirect_id']);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['new_url'])) {
			$data['new_url'] = $this->request->post['new_url'];
		} elseif (!empty($permanent_redirect_info)) {
			$data['new_url'] = $permanent_redirect_info['new_url'];
		} else {
			$data['new_url'] = '';
		}

		if (isset($this->request->post['old_url'])) {
			$data['old_url'] = $this->request->post['old_url'];
		} elseif (!empty($permanent_redirect_info)) {
			$data['old_url'] = $permanent_redirect_info['old_url'];
		} else {
			$data['old_url'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/permanent_redirect_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'setting/permanent_redirect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (utf8_strlen($this->request->post['old_url']) < 1) {
			$this->error['old_url'] = $this->language->get('error_url');
		}

		if($this->request->post['old_url'] && ($permanent_redirect = $this->model_setting_permanent_redirect->getPermanentRedirectByOldUrl($this->request->post['old_url'])) && (!isset($this->request->get['permanent_redirect_id']) || $permanent_redirect['permanent_redirect_id'] != $this->request->get['permanent_redirect_id'])) {
            $this->error['old_url'] = $this->language->get('error_old_url');
        }

        if (utf8_strlen($this->request->post['new_url']) < 1) {
            $this->error['new_url'] = $this->language->get('error_url');
        }


		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'setting/permanent_redirect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}