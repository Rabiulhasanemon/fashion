<?php
class ModelPaymentIpdc extends Model {
	public function getMethod($address, $total, $isEMICart) {
		$this->load->language('payment/ipdc');


		if ($this->config->get('ipdc_total') > 0 && $this->config->get('ipdc_total') > $total) {
			$status = false;
		} elseif (!$isEMICart) {
			$status = false;
		} else {
			$status = true;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'name'       => 'ipdc',
				'code'       => 'ipdc',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('ipdc_sort_order')
			);
		}

		return $method_data;
	}
}