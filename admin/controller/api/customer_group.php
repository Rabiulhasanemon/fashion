<?php 

class ControllerApiCustomerGroup extends Controller {
    function index() {
        $this->load->model('sale/customer_group');

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


        $data['customer_groups'] = array();

        $filter_data = array(
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $data['total'] = $this->model_sale_customer_group->getTotalCustomerGroups();

        $results = $this->model_sale_customer_group->getCustomerGroups($filter_data);

        foreach ($results as $result) {
            $data['customer_groups'][] = array(
                'customer_group_id'    => $result['customer_group_id'],
                'name'   => $result['name'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}