<?php
class ControllerApiOrderStatus extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/order_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/order_status');

		$this->getList();
	}


	protected function getList() {
		if (isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

        if (isset($this->request->post['limit'])) {
            $limit = $this->request->post['limit'];
        } else {
            $limit = $this->config->get('config_limit_admin');
        }

		$data['order_statuses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

        $data['total'] = $this->model_localisation_order_status->getTotalOrderStatuses();

		$results = $this->model_localisation_order_status->getOrderStatuses($filter_data);

		foreach ($results as $result) {
			$data['order_statuses'][] = array(
				'order_status_id' => $result['order_status_id'],
				'name'            => $result['name'] . (($result['order_status_id'] == $this->config->get('config_order_status_id')) ? $this->language->get('text_default') : null),
			);
		}

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
	}

}