<?php
class ControllerModuleTopSeller extends Controller {
	public function index($setting) {
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('tool/image');


		if(empty($setting['category'])) {
            $setting['category'] = [0];
        }

		$data['name'] = $setting['name'];
		$data['blurb'] = $setting['blurb'];
		$data['see_all'] = $this->url->link('product/category');
		$data['shop_all_url'] = $this->url->link('product/category');
		$data['categories'] = array();

		foreach ($setting['category'] as $category_id) {
            $category_data = array(
                'category_id' => $category_id
            );

            $category_info = $this->model_catalog_category->getCategory($category_id);
            if($category_info) {
                $category_data['name'] = $category_info['name'];
                $category_data['href'] = $this->url->link('product/category', 'category_id=' . $category_info['category_id']);
            } else {
                $category_data['name'] = "";
                $category_data['href'] = "";

            }

            $category_data['products'] = array();


            $results = $this->model_catalog_product->getBestSellerProducts($setting['limit'], $category_id);
            foreach ($results as $result) {
                if ($result['image']) {
                    $image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
                } else {
                    $image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
                }

                if ($results['featured_image']) {
                    $featured_image = $this->model_tool_image->resize($results['featured_image'], $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));
                } else {
                    $featured_image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_featured_image_width'), $this->config->get('config_featured_image_height'));;
                }
                $disablePurchase = false;
                if ($result['quantity'] <= 0 && $result['stock_status'] != "In Stock") {
                    $disablePurchase = true;
                }

                if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
                    $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $price = false;
                }

                if ((float)$result['special']) {
                    $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
                } else {
                    $special = false;
                }


                $category_data['products'][] = array(
                    'product_id'        => $result['product_id'],
                    'cat_href'          => $category_data['href'],
                    'thumb'             => $image,
                    'featured_image'    => $featured_image,
                    'name'              => $result['name'],
                    'price'             => $price,
                    'special'           => $special,
                    'disablePurchase'   => $disablePurchase,
                    'minimum'           => $result['minimum'] > 0 ? $result['minimum'] : 1,
                    'href'              => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                );

            }
            $data['categories'][] = $category_data;
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/topseller.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/topseller.tpl', $data);
        } else {
            return $this->load->view('default/template/module/topseller.tpl', $data);
        }
	}
}