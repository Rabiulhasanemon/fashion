<?php
class ControllerApiStockStatus extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/stock_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/stock_status');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

        if (isset($this->request->post['limit'])) {
            $limit = $this->request->post['limit'];
        } else {
            $limit = $this->config->get('config_limit_admin');
        }

		$data['stock_statuses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$data['total'] = $this->model_localisation_stock_status->getTotalStockStatuses();

		$results = $this->model_localisation_stock_status->getStockStatuses($filter_data);

		foreach ($results as $result) {
			$data['stock_statuses'][] = array(
				'stock_status_id' => $result['stock_status_id'],
				'name'            => $result['name'],
			);
		}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
	}
}