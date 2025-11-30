<?php
class ControllerModuleLatest extends Controller {
	public function index($setting) {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

        $data['name'] = $setting["name"];
        $data['class'] = $setting['class'];

        if($setting['category_id']) {
            $data["see_all"] = $this->url->link('product/category', 'category_id=' . $setting['category_id']);
            $data['shop_all_url'] = $this->url->link('product/category', 'category_id=' . $setting['category_id']);
        } else {
            $data["see_all"] = "#";
            $data['shop_all_url'] = $this->url->link('product/category');
        }

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'filter_category_id' => isset($setting["category_id"]) ? $setting["category_id"] : 0,
			'limit' => $setting['limit']
		);

		$products = $this->model_catalog_product->getProducts($filter_data);

		if ($products) {
            foreach ($products as $product_info) {
                if ($product_info['image']) {
                    $image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                }

                if ($product_info['featured_image']) {
                    $featured_image = $this->model_tool_image->resize($product_info['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));
                } else {
                    $featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));;
                }

                $disablePurchase = false;
                if ($product_info['quantity'] <= 0 && $product_info['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$product_info['special']) {
                    $mark = "Save: " . $this->currency->format($this->tax->calculate($product_info['price'] - $product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                    $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $mark = false;
                    $special = false;
                }

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
                } else {
                    $tax = false;
                }

                if ($this->config->get('config_review_status')) {
                    $rating = $product_info['rating'];
                } else {
                    $rating = false;
                }

                if($product_info['manufacturer_thumb']) {
                    $manufacturer_thumb = $this->config->get('config_ssl') . '/image/' . $product_info['manufacturer_thumb'];
                } else {
                    $manufacturer_thumb = null;
                }

                $data['products'][] = array(
                    'product_id'            =>  $product_info['product_id'],
                    'featured_image'        =>  $featured_image,
                    'thumb'                 =>  $image,
                    'manufacturer'          =>  $product_info['manufacturer'],
                    'manufacturer_thumb'    =>  $manufacturer_thumb,
                    'name'                  =>  $product_info['name'],
                    'short_description'     =>  $product_info['short_description'],
                    'price'                 =>  $price,
                    'disablePurchase'       =>  $disablePurchase,
                    'stock_status'          =>  $product_info['stock_status'],
                    'restock_request_btn'   =>  $product_info['restock_request_btn'],
                    'special'               =>  $special,
                    'mark'                  =>  $mark,
                    'tax'                   =>  $tax,
                    'minimum'               =>  $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
                    'rating'                =>  $rating,
                    'points'                =>  isset($product_info['points']) ? (int)$product_info['points'] : 0,
                    'reward'                =>  isset($product_info['reward']) ? (int)$product_info['reward'] : 0,
                    'href'                  =>  $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
                );
            }

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/latest.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/latest.tpl', $data);
			} else {
				return $this->load->view('default/template/module/latest.tpl', $data);
			}
		}
	}
}