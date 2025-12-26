<?php
class ControllerModuleFeaturedOffer extends Controller {
	public function index($setting) {
		$this->load->language('module/featured');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['name'] = $setting['name'];
		$data['blurb'] = $setting['blurb'];
        $data['see_all'] = isset($setting['url']) ? $setting['url'] : null;
        $data['class'] = $setting['class'];
        

		$this->load->model('catalog/offer');

		$this->load->model('tool/image');

		$data['offers'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['offer'])) {
            $offers = array_slice($setting['offer'], 0, (int)$setting['limit']);

			foreach ($offers as $offer_id) {
				$offer_info = $this->model_catalog_offer->getOffer($offer_id);

				if ($offer_info) {
					if ($offer_info['image']) {
						$image = $this->model_tool_image->resize($offer_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}
                    

					$data['offers'][] = array(
						'offer_id'  => $offer_info['offer_id'],
						'image'       => $image,
						'title'        => $offer_info['title'],
                        'branch' => $offer_info['branch'],
						'short_description' => $offer_info['short_description'],
                        'date_start' =>date('d M Y', strtotime( $offer_info['date_start'])),
                        'date_end' => date('d M Y', strtotime( $offer_info['date_end'])),
						'href'        => $this->url->link('information/offer/info', 'offer_id=' . $offer_info['offer_id'])
					);
				}
			}
		}

		if ($data['offers']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured_offer.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/featured_offer.tpl', $data);
			} else {
				return $this->load->view('default/template/module/featured_offer.tpl', $data);
			}
		}
	}
}