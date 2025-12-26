<?php
class ModelShippingFlat extends Model {
	function getQuote($address, $shippings) {

		$this->load->language('shipping/flat');

		$quote_data = array();

		foreach ($shippings as $shipping) {
            $settings = $shipping['setting'];

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $settings['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
            if (!$settings['geo_zone_id']) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }
            if($status) {
                $quote_data[$shipping['shipping_id'] . ""] = array(
                    'code'         => 'flat.' . $shipping['shipping_id'],
                    'title'        => $settings['name'],
                    'cost'         => $settings['cost'],
                    'tax_class_id' => $settings['tax_class_id'],
                    'text'         => $this->currency->format($this->tax->calculate($settings['cost'], $settings['tax_class_id'], $this->config->get('config_tax')))
                );
            }
        }


		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'flat',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => isset($settings['sort_order']) ? $settings['sort_order'] : 1,
				'error'      => false
			);
		}

		return $method_data;
	}
}