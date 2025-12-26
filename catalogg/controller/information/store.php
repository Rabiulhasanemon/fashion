<?php
class ControllerInformationStore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('information/store');

		$this->document->setTitle($this->language->get('heading_title'));


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/store')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_location'] = $this->language->get('text_location');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_open'] = $this->language->get('text_open');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['button_map'] = $this->language->get('button_map');

		$this->load->model('tool/image');

		if ($this->config->get('config_image')) {
			$data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
		} else {
			$data['image'] = false;
		}

		$data['store'] = $this->config->get('config_name');
		$data['address'] = nl2br($this->config->get('config_address'));
		$data['telephone'] = $this->config->get('config_telephone');

		$data['locations'] = array();

		$this->load->model('localisation/location');
		$this->load->model('localisation/zone');
        $locations = $this->model_localisation_location->getLocations();

		foreach($locations as $location_info) {
            if ($location_info['image']) {
                $image = $this->model_tool_image->resize($location_info['image'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
            } else {
                $image = $this->model_tool_image->resize("placeholder.png", $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
            }

            if($location_info['zone_id']){
                $zone_info = $this->model_localisation_zone->getZone($location_info['zone_id']);

                if ($zone_info) {
                    $zone =  $zone_info['name'];
                } else {
                    $zone= '';
                }
            }else {
                $zone = '';
            }

            $data['locations'][] = array(
                'location_id' => $location_info['location_id'],
                'name'        => $location_info['name'],
                'address'     => nl2br($location_info['address']),
                'zone_id'     => $location_info['zone_id'],
                'zone'     => $zone,
                'geocode'     => $location_info['geocode'],
                'telephone'   => $location_info['telephone'],
                'fax'         => $location_info['fax'],
                'image'       => $image,
                'open'        => nl2br($location_info['open']),
                'comment'     => $location_info['comment']
            );
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/store.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/information/store.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/information/store.tpl', $data));
		}
	}

}