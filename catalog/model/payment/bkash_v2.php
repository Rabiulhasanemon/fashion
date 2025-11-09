<?php
class ModelPaymentBkashV2 extends Model {
	public function getMethod($address, $total, $isEMICart) {
		$this->load->language('payment/bkash_v2');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('bkash_v2_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('bkash_v2_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if (!$isEMICart && $status) {
			$method_data = array(
                'name'       => 'bkash_v2',
                'code'       => 'bkash_v2',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('bkash_v2_sort_order')
			);
		}

		return $method_data;
	}
}