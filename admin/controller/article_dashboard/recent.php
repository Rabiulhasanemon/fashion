<?php
class ControllerArticleDashboardRecent extends Controller {
	public function index() {
		$this->load->language('article_dashboard/recent');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_article_id'] = $this->language->get('column_article_id');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Articles
		$data['articles'] = array();

		$filter_data = array(
			'sort'  => 'o.date_added',
			'article' => 'DESC',
			'start' => 0,
			'limit' => 5
		);

		$results = $this->model_blog_article->getArticles($filter_data);

		foreach ($results as $result) {
			$data['articles'][] = array(
				'article_id'   => $result['article_id'],
				'name'   => $result['name'],
                'status' => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('blog/article/edit', 'token=' . $this->session->data['token'] . '&article_id=' . $result['article_id'], 'SSL'),
			);
		}

		return $this->load->view('article_dashboard/recent.tpl', $data);
	}
}