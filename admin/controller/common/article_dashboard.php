<?php
class ControllerCommonArticleDashboard extends Controller {
	public function index() {
		$this->load->language('common/article_dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/article_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/article_dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');

		$data['article'] = $this->load->controller('article_dashboard/article');
		$data['published'] = $this->load->controller('article_dashboard/published');
		$data['comment'] = $this->load->controller('article_dashboard/comment');
		$data['customer'] = $this->load->controller('article_dashboard/customer');

		$data['chart'] = $this->load->controller('article_dashboard/chart');

		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/article_dashboard.tpl', $data));
	}
}