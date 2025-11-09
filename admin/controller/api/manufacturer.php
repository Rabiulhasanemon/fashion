<?php
/**
 * Created by PhpStorm.
 * Manufacturer: user
 * Date: 7/18/2020
 * Time: 7:43 PM
 */

class ControllerApiManufacturer extends Controller {
    function index() {
        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['filter_name'])) {
            $filter_name = $this->request->post['filter_name'];
        } else {
            $filter_name = null;
        }

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
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $data['total'] = $this->model_catalog_manufacturer->getTotalManufacturers();

        $results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

        foreach ($results as $result) {
            $data['manufacturers'][] = array(
                'manufacturer_id'    => $result['manufacturer_id'],
                'name'            => $result['name'],
                'sort_order'      => $result['sort_order'],
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

}