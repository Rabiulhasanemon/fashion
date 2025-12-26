<?php
class ModelTotalDiscount extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/discount');
        $discount_total = 100;

        $total_data[] = array(
            'code'       => 'coupon',
            'title'      => sprintf($this->language->get('text_coupon')),
            'value'      => -$discount_total,
            'sort_order' => $this->config->get('coupon_sort_order')
        );

        $total -= $discount_total;
	}
}