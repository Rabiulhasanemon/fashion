<?php

class ControllerApiProduct extends Controller {
    private $error = array();

    public function info() {

        $product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : 0;
        $this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

        if(!$product_info) return;

        $data['product_id'] = $product_info['product_id'];
        $data['image'] = $product_info['image'];
        $data['model'] = $product_info['model'];
        $data['sku'] = $product_info['sku'];
        $data['mpn'] = $product_info['mpn'];
        $data['short_note'] = $product_info['short_note'];
        $data['keyword'] = $product_info['keyword'];
        $data['shipping'] = $product_info['shipping'];
        $data['emi'] = $product_info['emi'];
        $data['price'] = $product_info['price'];
        $data['cost_price'] = $product_info['cost_price'];
        $data['regular_price'] = $product_info['regular_price'];
        $data['tax_class_id'] = $product_info['tax_class_id'];
        $data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
        $data['quantity'] = $product_info['quantity'];
        $data['minimum'] = $product_info['minimum'];
        $data['subtract'] = $product_info['subtract'];
        $data['sort_order'] = $product_info['sort_order'];
        $data['stock_status_id'] = $product_info['stock_status_id'];
        $data['status'] = $product_info['status'];
        $data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);

        $product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
        $data['product_discounts'] = array();

        foreach ($product_discounts as $product_discount) {
            $data['product_discounts'][] = array(
                'product_discount_id' => $product_discount['product_discount_id'],
                'customer_group_id' => $product_discount['customer_group_id'],
                'quantity'          => $product_discount['quantity'],
                'priority'          => $product_discount['priority'],
                'price'             => $product_discount['price'],
                'date_start'        => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
                'date_end'          => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
            );
        }

        $product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
        $data['product_specials'] = array();

        foreach ($product_specials as $product_special) {
            $data['product_specials'][] = array(
                'product_special_id'=> $product_special['product_special_id'],
                'customer_group_id' => $product_special['customer_group_id'],
                'api_id'            => $product_special['api_id'],
                'priority'          => $product_special['priority'],
                'price'             => $product_special['price'],
                'date_start'        => $product_special['date_start'] ,
                'date_end'          => $product_special['date_end']
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));
    }

    public function edit_price() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;

        $data = $this->request->post;
        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET quantity = '" . (int)$data['quantity'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', regular_price = '" . (float)$data['regular_price']  . "', price = '" . (float)$data['price'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
            $json['type'] = 'success';
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit_sort_order() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;

        $data = $this->request->post;
        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET sort_order = '" . (float)$data['sort_order'] . "' WHERE product_id = '" . (int)$product_id . "'");
            $json['type'] = 'success';
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function update_tag() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;

        $json = array();
        if ($this->validate()) {
            $this->model_catalog_product->updateSearchKeywords($product_id);
            $json['type'] = 'success';
            $json['product_id'] = $product_id;
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function add_special() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;

        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->model_catalog_product->addProductSpecial($product_id, $this->request->post);
            $json['type'] = 'success';
            $json['product_specials'] = $this->model_catalog_product->getProductSpecials($product_id);
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function update_special() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $json = array();
        if ($this->validate() && $this->validateUpdateSpecial()) {
            $this->model_catalog_product->updateProductSpecialByPriority($this->request->post);
            $json['type'] = 'success';
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function add_commission() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;

        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->model_catalog_product->addProductCommission($product_id, $this->request->post);
            $json['type'] = 'success';
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete_special() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;
        $product_special_id = isset($this->request->post['product_special_id']) ? (int) $this->request->post['product_special_id'] : 0;

        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->model_catalog_product->deleteProductSpecial($product_id, $product_special_id);
            $json['type'] = 'success';
            $json['product_specials'] = $this->model_catalog_product->getProductSpecials($product_id);
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function add_discount() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;

        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->model_catalog_product->addProductDiscount($product_id, $this->request->post);
            $json['type'] = 'success';
            $json['product_discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function delete_discount() {
        $this->load->language('catalog/product');
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;
        $product_discount_id = isset($this->request->post['product_discount_id']) ? (int) $this->request->post['product_discount_id'] : 0;

        $json = array();
        if ($this->validate() && $this->validateForm()) {
            $this->model_catalog_product->deleteProductDiscount($product_id, $product_discount_id);
            $json['type'] = 'success';
            $json['product_discounts'] = $this->model_catalog_product->getProductDiscounts($product_id);
            $json['message'] =  $this->language->get('text_success');
        } else {
            $json['type'] = 'error';
            $json['message'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'api/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        return !$this->error;
    }

    protected function validateForm() {
        $this->load->model('catalog/product');

        $product_id = isset($this->request->post['product_id']) ? (int) $this->request->post['product_id'] : 0;
        $product_info = $this->model_catalog_product->getProduct($product_id);
        if(!$product_info) {
            $this->error['warning'] = $this->language->get('error_product_id');
        }
        return !$this->error;
    }

    protected function validateUpdateSpecial() {
        if(!isset($this->request->post['priority']) || !is_numeric($this->request->post['priority'])) {
            $this->error['warning'] = $this->language->get('error_priority');
        }
        return !$this->error;
    }
}