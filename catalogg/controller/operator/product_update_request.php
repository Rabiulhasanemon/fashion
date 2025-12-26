<?php
class ControllerOperatorProductUpdateRequest extends Controller {
	private $error = array();

	public function __construct($registry) {
        parent::__construct($registry);
        $operator = new Operator($registry);
        if(!$operator->getId() &&  isset($this->request->server['PHP_AUTH_USER']) &&  isset($this->request->server['PHP_AUTH_PW'])) {
            $operator->login($this->request->server['PHP_AUTH_USER'], $this->request->server['PHP_AUTH_PW']);
        }
        $this->operator = new Operator($registry);
    }

    public function index() {
        $this->load->language('operator/product_update_request');

        $data = array();
        $data['entry_new_price'] = $this->language->get("entry_new_price");
        $data['entry_new_regular_price'] = $this->language->get("entry_new_regular_price");
        $data['entry_new_status'] = $this->language->get("entry_new_status");
        $data['entry_new_sort_order'] = $this->language->get("entry_new_sort_order");

        $this->load->model("catalog/product");

        $data['product_id'] = $this->request->get["product_id"];

        $data['stock_statuses'] =  array(array('id' => null, 'name' => "None"), array('id' => 'delete', 'name' => "Delete"));

        $results  = $this->model_catalog_product->getProductStockStatuses();
        $product_info = $this->model_catalog_product->getProduct($data['product_id']);
        foreach ($results as $result) {
            $data['stock_statuses'][] = array(
                'id'     =>  $result['stock_status_id'],
                'name' => $result['name'],
            );
        }

        $data['price'] = $product_info['price'];
        $data['regular_price'] = $product_info['regular_price'];
        $data['sort_order'] = $product_info['sort_order'];

        if(!$this->operator->isLogged()) {
            $this->response->setOutput(sprintf($this->language->get("error_login"), $this->url->link("operator/login")));
        } else {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/operator/product_update_request.tpl', $data));
        }

	}

	public function add() {
        $this->load->language('operator/product_update_request');
        $json = array();
        if ($this->operator->isLogged() && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $new_price = $this->request->post['new_price'];
            $new_regular_price = $this->request->post['new_regular_price'];
            $new_stock_status = $this->request->post['new_status'];
            $new_sort_order = trim($this->request->post['new_sort_order']);
            $product_id = $this->request->post['product_id'];
            if($new_stock_status == "delete") {
                $new_status = 0;
                $new_stock_status = null;
            } else {
                $new_status = null;
            }
            if($new_stock_status) {
                $this->load->model("catalog/product");
                $new_stock_status = $this->model_catalog_product->getProductStockStatus($new_stock_status);
            }
            if((!$new_price && !$new_regular_price && $new_status === null && !$new_stock_status && empty($new_sort_order))) {
                $json['error'] = $this->language->get('error_entry');
                $json['product_id'] = $product_id;
            }

            if (!isset($json['error'])) {
                $this->load->model('operator/product_update_request');
                $this->model_operator_product_update_request->addProductUpdateRequest($this->operator->getId(),$product_id, $new_status, $new_stock_status, $new_price, $new_regular_price, $new_sort_order);
                $json['success'] = $this->language->get('text_success');
                $json['reload'] = $this->operator->getIsSafe();
            }
        } else {
            $json['error'] = "Unauthorized";
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}