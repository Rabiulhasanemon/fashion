<?php
class ControllerModuleReviewView extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/review_view');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('review_view', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_reviews'] = $this->language->get('entry_reviews');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_show_rating'] = $this->language->get('entry_show_rating');
		$data['entry_show_date'] = $this->language->get('entry_show_date');
		$data['entry_show_product'] = $this->language->get('entry_show_product');
		$data['entry_layout'] = $this->language->get('entry_layout');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/review_view', 'token=' . $this->session->data['token'], 'SSL')
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('module/review_view', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL')
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/review_view', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/review_view', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($module_info) && isset($module_info['title'])) {
			$data['title'] = $module_info['title'];
		} else {
			$data['title'] = '';
		}

		if (isset($this->request->post['review_ids'])) {
			$data['review_ids'] = $this->request->post['review_ids'];
		} elseif (!empty($module_info) && isset($module_info['review_ids'])) {
			$data['review_ids'] = $module_info['review_ids'];
		} else {
			$data['review_ids'] = array();
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info) && isset($module_info['limit'])) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		if (isset($this->request->post['show_rating'])) {
			$data['show_rating'] = $this->request->post['show_rating'];
		} elseif (!empty($module_info) && isset($module_info['show_rating'])) {
			$data['show_rating'] = $module_info['show_rating'];
		} else {
			$data['show_rating'] = 1;
		}

		if (isset($this->request->post['show_date'])) {
			$data['show_date'] = $this->request->post['show_date'];
		} elseif (!empty($module_info) && isset($module_info['show_date'])) {
			$data['show_date'] = $module_info['show_date'];
		} else {
			$data['show_date'] = 1;
		}

		if (isset($this->request->post['show_product'])) {
			$data['show_product'] = $this->request->post['show_product'];
		} elseif (!empty($module_info) && isset($module_info['show_product'])) {
			$data['show_product'] = $module_info['show_product'];
		} else {
			$data['show_product'] = 1;
		}

		if (isset($this->request->post['layout'])) {
			$data['layout'] = $this->request->post['layout'];
		} elseif (!empty($module_info) && isset($module_info['layout'])) {
			$data['layout'] = $module_info['layout'];
		} else {
			$data['layout'] = 'grid';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		// Load all reviews
		$this->load->model('catalog/review');
		$all_reviews = $this->model_catalog_review->getReviews(array(
			'sort' => 'r.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 1000
		));

		$data['reviews'] = array();
		foreach ($all_reviews as $review) {
			$data['reviews'][] = array(
				'review_id' => $review['review_id'],
				'author' => $review['author'],
				'product' => $review['name'] ? $review['name'] : 'N/A',
				'rating' => $review['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($review['date_added']))
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/review_view.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/review_view')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}

