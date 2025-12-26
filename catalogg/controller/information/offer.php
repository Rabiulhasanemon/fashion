<?php
class ControllerInformationOffer extends Controller {
	public function index() {
        $this->load->language('information/offer');

		$this->document->setTitle(sprintf($this->language->get('heading_title'), $this->config->get('config_name')));
		$this->document->setDescription(sprintf($this->language->get('heading_title'), $this->config->get('config_name')));
		$this->document->setKeywords("");
		$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/offer.css');

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}

		$this->load->model("catalog/offer");

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_offer'),
            'href' =>  $this->url->link('information/offer')
        );


        $data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

        $data['offers'] = array();
        $results =  $this->model_catalog_offer->getOffers();
        foreach ($results as $result) {
            // Set date_end to end of day (23:59:59) so offer is valid for entire end date
            $date_end_timestamp = strtotime($result['date_end'] . ' 23:59:59');
            $data['offers'][] = array(
                'offer_id' => $result['offer_id'],
                'title' => $result['title'],
                'branch' => $result['branch'],
                'short_description' => $result['short_description'],
                'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
                'links' => $result['links'],
                'date_start' => date('d M Y', strtotime($result['date_start'])),
                'date_end' => date('d M Y', strtotime($result['date_end'])),
                'date_start_timestamp' => strtotime($result['date_start']),
                'date_end_timestamp' => $date_end_timestamp,
                'image' => $this->config->get('config_ssl') . '/image/' . $result['image'],
                'href' => $this->url->link("information/offer/info", 'offer_id=' . $result['offer_id'])
            );
        }

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['after_header'] = $this->load->controller('common/after_header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/offer.tpl', $data));
	}

    public function info() {
        $this->load->language('information/offer');

        $this->load->model('catalog/offer');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_offer'),
            'href' => $this->url->link('information/offer')
        );

        if (isset($this->request->get['offer_id'])) {
            $offer_id = (int)$this->request->get['offer_id'];
        } else {
            $offer_id = 0;
        }

        $offer = $this->model_catalog_offer->getOffer($offer_id);

        if ($offer) {
            $this->document->setTitle($offer['title']);
            $this->document->setDescription($offer['short_description']);
            $this->document->setKeywords('');

            $data['breadcrumbs'][] = array(
                'text' => $offer['title'],
                'href' => $this->url->link('information/offer/info', 'offer_id=' .  $offer_id)
            );

            $data['heading_title'] = $offer['title'];

            $data['button_continue'] = $this->language->get('button_continue');

            $data ['image'] = 'image/'.$offer['image'];
            $data ['date_start'] = date('d M Y', strtotime( $offer['date_start']));
            $data ['date_end'] = date('d M Y', strtotime( $offer['date_end']));
            $data ['branch'] = $offer['branch'];
            $data['description'] = html_entity_decode($offer['description'], ENT_QUOTES, 'UTF-8');
            $data['links'] = $offer['links'];

            $data['continue'] = $this->url->link('common/home');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/offer_info.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/offer_info.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/information/offer_info.tpl', $data));
            }
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_error'),
                'href' => $this->url->link('information/information', 'information_id=' . $offer_id)
            );

            $this->document->setTitle($this->language->get('text_error'));

            $data['heading_title'] = $this->language->get('text_error');

            $data['text_error'] = $this->language->get('text_error');

            $data['button_continue'] = $this->language->get('button_continue');

            $data['continue'] = $this->url->link('common/home');

            $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
            }
        }
    }
}