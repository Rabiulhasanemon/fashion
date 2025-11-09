<?php
class ModelTotalReward extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['reward'])) {
			$this->load->language('total/reward');

			$points = $this->customer->getRewardPoints();
			if ($this->session->data['reward'] <= $points) {

                if ($this->session->data['reward'] > $total) {
                    $discount_total = $total;
                } else {
                    $discount_total = $this->session->data['reward'];
                }

				$total_data[] = array(
					'code'       => 'reward',
					'title'      => sprintf($this->language->get('text_reward'), $this->session->data['reward']),
					'value'      => -$discount_total,
					'sort_order' => $this->config->get('reward_sort_order')
				);

				$total -= $discount_total;
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$this->load->language('total/reward');

		$points = 0;

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$points = substr($order_total['title'], $start, $end - $start);
		}

		if ($points) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $this->db->escape(sprintf($this->language->get('text_order_id'), (int)$order_info['order_id'])) . "', points = '" . (float)-$points . "', date_added = NOW()");
		}
	}

	public function unconfirm($order_id, $order_total) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points < 0");
	}
}