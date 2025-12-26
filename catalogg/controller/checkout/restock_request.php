<?php
class ControllerCheckoutRestockRequest extends Controller {

    private function _add($product_id) {
        $this->load->model('catalog/product');
        $product_info = $this->model_catalog_product->getProduct($product_id);

        $this->load->model('checkout/restock_request');
        $this->model_checkout_restock_request->addRestockRequest($product_id, $this->customer->getId());

        $this->load->model('localisation/stock_status');
        $stock_status = $this->model_localisation_stock_status->getStockStatus($product_info["stock_status_id"]);
        return $stock_status["request_success"];
    }

	public function add() {
        if (isset($this->request->post['product_id'])) {
            $product_id = (int)$this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = isset($this->request->post["referrer"]) ? $this->request->post["referrer"] : $this->url->link('account/account', '', 'SSL');
            $this->session->data['restock_request'] = array(
                'product_id' => $product_id
            );
            $this->response->setOutput(json_encode(array('redirect' => $this->url->link('account/login', '', 'SSL'))));
            return;
        }

		$json = array();

        $json["success"] = $this->_add($product_id);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function after_login() {
	    if(isset($this->session->data['restock_request'])) {
            $this->session->data["success"] = $this->_add($this->session->data['restock_request']["product_id"]);
            unset($this->session->data['restock_request']);
        }
    }
}
