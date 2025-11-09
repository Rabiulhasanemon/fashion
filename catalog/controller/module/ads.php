<?php
class ControllerModuleAds extends Controller {
	public function index($setting) {


        $sql = "SELECT * FROM " . DB_PREFIX . "ads WHERE status = '1' and ads_position_id = '" . (int) $setting['ads_position_id'] . "' AND device_type = '0' ORDER BY RAND() LIMIT 1";
        $query = $this->db->query($sql);
        if($query->row) {
            $data = array(
                'ads_position_id' => $query->row['ads_position_id'],
                'image' => $this->config->get('config_ssl') . '/image/' . $query->row['image'],
                'width' => $query->row['width'],
                'height' => $query->row['height'],
                'title' => $query->row['title'],
                'url' => $query->row['url'],
            );
        } else {
            $data = $setting;
        }

        $data['class'] = $setting['class'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/ads.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/ads.tpl', $data);
		} else {
			return $this->load->view('default/template/module/ads.tpl', $data);
		}
	}
}