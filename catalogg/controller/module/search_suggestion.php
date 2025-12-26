<?php
/**
 * This file is part of FreeCart.
 *
 * @copyright  sv2109 <sv2109@gmail.com>
 * @link http://freecart.pro
*/

class ControllerModuleSearchSuggestion extends Controller {

	public function index() {
		return null;
	}
	
	public function ajax() {
		$data['products'] = array();
		if (isset($this->request->get['keyword'])) {
			$this->load->model('catalog/product');
			$filter_data['filter_name'] = $this->request->get['keyword'];
			$filter_data['start'] = 0;
			$filter_data['limit'] = 7;
			$filter_data['filter_category_id'] = isset($this->request->get['filter_category_id']) ? $this->request->get['filter_category_id'] : null;
			$results = $this->model_catalog_product->getProducts($filter_data);
			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			if ($product_total) {
				$this->load->model('tool/image');
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], 80, 80);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', 80, 80);
					}
                    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                    } else {
                        $price = false;
                    }
					$data['products'][] = array(
						'product_id' => $result['product_id'],
						'label' => $result['name'],
						'thumb' => $image,
						'price' => $price ?: "",
						'href' => str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $result['product_id']))
					);
				}
			}
			if ($product_total > count($data['products']) && ($remainder_cnt = $product_total - count($data['products'])) > 0) {
				$data['products'][] = array(
					'type' => 'remainder_cnt',
					'label' => $remainder_cnt . " more results",
					'href' => str_replace('&amp;', '&', $this->url->link('product/search', 'search=' . $this->request->get['keyword']))
				);
			}
		}
		$this->response->setOutput(json_encode($data['products']));
	}

	public function products() {
	    $this->load->model('catalog/product');

        if(isset($this->request->get["filter_category_id"])) {
            $filter_category_id = $this->request->get["filter_category_id"];
        } else {
            $filter_category_id = null;
        }

        $data = array();
        $data['products'] = array();

        $results = $this->model_catalog_product->getProducts(array("filter_category_id" => $filter_category_id));
        foreach ($results as $result) {
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'label' => $result['name'],
            );
        }

        $this->response->setOutput(json_encode($data));
	}
}