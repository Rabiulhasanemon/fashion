<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/18/2020
 * Time: 7:43 PM
 */

class ControllerApiUser extends Controller {
    function index() {
        $this->load->model('user/user');

        if (isset($this->request->post['filter_name'])) {
            $filter_name = $this->request->post['filter_name'];
        } else {
            $filter_name = null;
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


        $data['users'] = array();

        $filter_data = array(
            'filter_name'  => $filter_name,
            'status'  => $filter_status,
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $data['total'] = $this->model_user_user->getTotalUsers();

        $results = $this->model_user_user->getUsers($filter_data);

        foreach ($results as $result) {
            $data['users'][] = array(
                'user_id'    => $result['user_id'],
                'firstname'   => $result['firstname'],
                'lastname'   => $result['lastname'],
                'username'   => $result['username'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }


}