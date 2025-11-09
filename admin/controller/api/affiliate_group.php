<?php 

class ControllerApiAffiliateGroup extends Controller {
    function index() {
        $this->load->model('marketing/affiliate_group');

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


        $data['affiliate_groups'] = array();

        $filter_data = array(
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $data['total'] = $this->model_marketing_affiliate_group->getTotalAffiliateGroups();

        $results = $this->model_marketing_affiliate_group->getAffiliateGroups($filter_data);

        foreach ($results as $result) {
            $data['affiliate_groups'][] = array(
                'affiliate_group_id'    => $result['affiliate_group_id'],
                'name'   => $result['name'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }
}