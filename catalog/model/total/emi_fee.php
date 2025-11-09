<?php
class ModelTotalEMIFee extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/emi_fee');
		
		$emi_tenure = isset($this->session->data['emi_tenure']) ? (int) $this->session->data['emi_tenure'] : 0;
		if(!$this->cart->isEMI() || !$emi_tenure) {
		    return;
        }

        $fee = (float) $this->config->get('emi_fee_' . $emi_tenure . '_month');

		$emi_fee = $total * ($fee / 100);

		$total_data[] = array(
			'code'       => 'emi_fee',
			'title'      => $this->language->get('text_emi_fee'),
			'value'      => $emi_fee,
			'sort_order' => $this->config->get('emi_fee_sort_order')
		);

		$total += $emi_fee;
	}
}