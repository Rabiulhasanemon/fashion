<?php
class ControllerArticleDashboardChart extends Controller {
	public function index() {
		$this->load->language('article_dashboard/chart');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['token'] = $this->session->data['token'];

		return $this->load->view('article_dashboard/chart.tpl', $data);
	}

	public function chart() {
		$this->load->language('article_dashboard/chart');

		$json = array();

		$this->load->model('report/article');

        $results = $this->model_report_article->getArticleTotalByParent();
        foreach ($results as $result) {
             $json[] = array('label' => $result['parent_name'], 'y' => (int) $result['total']);
        }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}