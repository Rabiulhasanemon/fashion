<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['text_information'] = $this->language->get('text_information');
		$data['text_service'] = $this->language->get('text_service');
		$data['text_extra'] = $this->language->get('text_extra');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_sitemap'] = $this->language->get('text_sitemap');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_voucher'] = $this->language->get('text_voucher');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_special'] = $this->language->get('text_special');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$this->load->model('catalog/information');
		$this->load->model('catalog/category');

		// Organize footer information pages into groups
		$data['about_ruplexa'] = array();
		$data['my_ruplexa'] = array();
		$data['help'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$info_item = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
				
				// Group by sort_order ranges
				// About Ruplexa: sort_order 1-5
				// My Ruplexa: sort_order 6-9
				// Help: sort_order 10+
				if ($result['sort_order'] >= 1 && $result['sort_order'] <= 5) {
					$data['about_ruplexa'][] = $info_item;
				} elseif ($result['sort_order'] >= 6 && $result['sort_order'] <= 9) {
					$data['my_ruplexa'][] = $info_item;
				} else {
					$data['help'][] = $info_item;
				}
			}
		}
		
		// Add account-related links to My Ruplexa section
		$data['my_ruplexa'][] = array(
			'title' => $data['text_special'],
			'href' => $this->url->link('product/special')
		);
		$data['my_ruplexa'][] = array(
			'title' => $data['text_wishlist'],
			'href' => $this->url->link('account/wishlist', '', 'SSL')
		);
		$data['my_ruplexa'][] = array(
			'title' => $data['text_order'],
			'href' => $this->url->link('account/order', '', 'SSL')
		);
		$data['my_ruplexa'][] = array(
			'title' => $data['text_account'],
			'href' => $this->url->link('account/account', '', 'SSL')
		);
		
		// Keep old informations array for backward compatibility
		$data['informations'] = array_merge($data['about_ruplexa'], $data['my_ruplexa'], $data['help']);

		// Get categories for footer - Show 7 demo cosmetics categories if no categories exist
		$data['categories'] = array();
		if (isset($this->model_catalog_category)) {
			$categories = $this->model_catalog_category->getCategories(0);
			if ($categories && count($categories) > 0) {
				$count = 0;
				foreach ($categories as $category) {
					if ($count < 7) {
						$data['categories'][] = array(
							'name' => isset($category['name']) ? $category['name'] : '',
							'href' => $this->url->link('product/category', 'category_id=' . $category['category_id'])
						);
						$count++;
					}
				}
			}
		}
		
		// If no categories or less than 7, add demo cosmetics categories
		if (count($data['categories']) < 7) {
			$demo_categories = array(
				'Skin Care',
				'Makeup',
				'Hair Care',
				'Fragrance',
				'Body Care',
				'Beauty Tools',
				'Men\'s Grooming'
			);
			
			$demo_count = 0;
			foreach ($demo_categories as $demo_name) {
				if (count($data['categories']) >= 7) break;
				$data['categories'][] = array(
					'name' => $demo_name,
					'href' => $this->url->link('product/category', 'category_id=0')
				);
				$demo_count++;
			}
		}

		// Get social media links from config
		$data['facebook_url'] = $this->config->get('config_facebook') ? $this->config->get('config_facebook') : '#';
		$data['twitter_url'] = $this->config->get('config_twitter') ? $this->config->get('config_twitter') : '#';
		$data['instagram_url'] = $this->config->get('config_instagram') ? $this->config->get('config_instagram') : '#';
		$data['youtube_url'] = $this->config->get('config_youtube') ? $this->config->get('config_youtube') : '#';
		
		// Newsletter subscription URL
		$data['newsletter_action'] = $this->url->link('account/newsletter', '', 'SSL');

        if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
            $data['logo'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_logo');
        } else {
            $data['logo'] = '';
        }

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['image'] = $this->config->get('config_ssl') . '/image/' . $this->config->get('config_image');
		} else {
			$data['image'] = '';
		}

		$data["is_mobile"] = isset($this->mobile_detect) && method_exists($this->mobile_detect, 'isMobile') ? $this->mobile_detect->isMobile() : false;
		$data['contact'] = $this->url->link('information/contact');
		$data['offer'] = $this->url->link('information/offer');
		$data['manufacturer'] = $this->url->link('brand');
		$data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['special'] = $this->url->link('product/special');
        $data['item_count'] = $this->cart->countProducts();

        $data['logged'] = $this->customer->isLogged();
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['register'] = $this->url->link('account/register', '', 'SSL');
        $data['login'] = $this->url->link('account/login', '', 'SSL');
        $data['home'] = $this->url->link('common/home');
        $data['address'] = nl2br($this->config->get("config_address"));
        $data['telephone'] = $this->config->get("config_telephone");
        $data['email'] = $this->config->get("config_email");
        $data['config_name'] = $this->config->get('config_name');

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/footer.tpl', $data);
		} else {
			return $this->load->view('default/template/common/footer.tpl', $data);
		}
	}
}