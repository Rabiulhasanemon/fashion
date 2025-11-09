<?php

class ModelPaymentAamarpay extends Model {
	public function getMaxEmiTenure() {
		return 12;
	}

  	public function getMethod($address, $total, $isEMICart) {
		$this->load->language('payment/aamarpay');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('aamarpay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('aamarpay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('aamarpay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true; 
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status && ($this->config->get('aamarpay_emi') || !$isEMICart)) {
      		$method_data = array( 
        		'name'       => 'aamarpay',
        		'code'       => 'aamarpay',
				'bank'       => 'z',
				'terms'      => '',
        		'title'      => $isEMICart ? $this->language->get('text_emi_title') : $this->language->get('text_title'),
				'sort_order' => $this->config->get('aamarpay_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>
