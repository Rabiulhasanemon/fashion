<?php
class ControllerModuleReviewView extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->language('module/review_view');
		$this->load->model('catalog/review');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['title'] = isset($setting['title']) ? $setting['title'] : '';
		$data['layout'] = isset($setting['layout']) ? $setting['layout'] : 'grid';
		$data['show_rating'] = isset($setting['show_rating']) ? $setting['show_rating'] : 1;
		$data['show_date'] = isset($setting['show_date']) ? $setting['show_date'] : 1;
		$data['show_product'] = isset($setting['show_product']) ? $setting['show_product'] : 1;

		$data['reviews'] = array();

		if (isset($setting['review_ids']) && is_array($setting['review_ids']) && !empty($setting['review_ids'])) {
			$limit = isset($setting['limit']) ? (int)$setting['limit'] : 5;
			
			foreach ($setting['review_ids'] as $review_id) {
				$review_info = $this->model_catalog_review->getReview($review_id);
				
				if ($review_info && $review_info['status']) {
					// Get product info
					$product_info = $this->model_catalog_product->getProduct($review_info['product_id']);
					
					$product_image = '';
					$product_href = '';
					
					if ($product_info) {
						if ($product_info['image']) {
							$product_image = $this->model_tool_image->resize($product_info['image'], 100, 100);
						}
						$product_href = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);
					}

					$data['reviews'][] = array(
						'review_id'   => $review_info['review_id'],
						'author'      => $review_info['author'],
						'text'        => utf8_substr(strip_tags(html_entity_decode($review_info['text'], ENT_QUOTES, 'UTF-8')), 0, 150) . '..',
						'rating'      => $review_info['rating'],
						'date_added'  => date($this->language->get('date_format_short'), strtotime($review_info['date_added'])),
						'product_name' => $review_info['product'] ? $review_info['product'] : 'N/A',
						'product_image' => $product_image,
						'product_href' => $product_href
					);
					
					if (count($data['reviews']) >= $limit) {
						break;
					}
				}
			}
		}

		$data['module'] = $module++;

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/review_view.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/review_view.tpl', $data);
		} else {
			return $this->load->view('default/template/module/review_view.tpl', $data);
		}
	}
}

