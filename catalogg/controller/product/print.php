<?php

/**
 * Created by PhpStorm.
 * User: Sajid
 * Date: 31-10-15
 * Time: 12.25
 */
class ControllerProductPrint extends Controller {

    public function index() {
        $product_id = $this->request->get['product_id'];
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $result = $this->model_catalog_product->getProduct($product_id);
        if ($result['image']) {
            $image = $this->model_tool_image->resize($result['image'], 300, 300);
        } else {
            $image = $this->model_tool_image->resize('placeholder.png', 300, 300);
        }
        $attributes = $this->model_catalog_product->getProductAttributes($product_id);
        $attr = $attributes[0]["attribute"];
        $data["attributes"] = $attr;
        $data["product"] = $result;
        $data['thumb'] = $image;
        $data['description'] = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
        $this->response->setOutput($this->load->view('startech/template/product/printprod.tpl', $data));
    }
}