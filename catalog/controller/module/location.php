<?php
class ControllerModuleLocation extends Controller {
	public function index($setting) {
		$this->load->language('module/location');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['name'] = $setting['name'];
		$data['blurb'] = $setting['blurb'];
        $data['see_all'] = isset($setting['url']) ? $setting['url'] : null;
        $data['class'] = $setting['class'];


		$this->load->model('localisation/location');

		$this->load->model('tool/image');

		$data['products'] = array();


		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['location'])) {
			$locations = array_slice($setting['location'], 0, (int)$setting['limit']);

			foreach ($locations as $location_id) {
				$location_info = $this->model_localisation_location->getLocation($location_id);

				if ($location_info) {
					if ($location_info['image']) {
						$image = $this->model_tool_image->resize($location_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					$data['locations'][] = array(
						'location_id'  => $location_info['location_id'],
						'image'       => $image,
						'name'        => $location_info['name'],
						'address'        => $location_info['address'],
						'geocode'        => $location_info['geocode'],
						'telephone'        => $location_info['telephone'],
						'fax'        => $location_info['fax'],
						'open'        => $location_info['open'],
						'comment'        => $location_info['comment'],
					);
				}
			}
		}


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/location.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/location.tpl', $data);
        } else {
            return $this->load->view('default/template/module/location.tpl', $data);
        }
	}
}