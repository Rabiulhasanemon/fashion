<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/18/2020
 * Time: 7:43 PM
 */

class ControllerApiApi extends Controller {
    function index() {
        $this->load->model('user/api');

        if (isset($this->request->post['filter_username'])) {
            $filter_username = $this->request->post['filter_username'];
        } else {
            $filter_username = null;
        }

        if (isset($this->request->post['filter_status'])) {
            $filter_status = $this->request->post['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->post['sort'])) {
            $sort = $this->request->post['sort'];
        } else {
            $sort = 'username';
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


        $data['apis'] = array();

        $filter_data = array(
            'filter_username'  => $filter_username,
            'status'  => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $data['total'] = $this->model_user_api->getTotalApis();

        $results = $this->model_user_api->getApis($filter_data);

        foreach ($results as $result) {
            $data['apis'][] = array(
                'api_id'    => $result['api_id'],
                'username'   => $result['username'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


}