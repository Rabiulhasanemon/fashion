<?php
class ControllerApiZone extends Controller {

	public function index() {
        $this->load->model('localisation/zone');

        if (isset($this->request->post['filter_country_id'])) {
            $filter_country_id = $this->request->post['filter_country_id'];
        } else {
            $filter_country_id = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'c.name';
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


        $data['zones'] = array();

        $filter_data = array(
            'filter_country_id' => $filter_country_id,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $limit
        );

        $data['total'] = $this->model_localisation_zone->getTotalZones();

        $results = $this->model_localisation_zone->getZones($filter_data);

        foreach ($results as $result) {
            $data['zones'][] = array(
                'zone_id' => $result['zone_id'],
                'country' => $result['country'],
                'name' => $result['name'] . (($result['zone_id'] == $this->config->get('config_zone_id')) ? $this->language->get('text_default') : null),
                'code' => $result['code'],
            );
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}