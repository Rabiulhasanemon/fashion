<?php
class ModelTotalCoupon extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['coupon'])) {
			$this->load->language('total/coupon');

			$this->load->model('checkout/coupon');

			$coupon_info = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon_info) {
				$discount_total = 0;

				if (!$coupon_info['product']) {
					$sub_total = $this->cart->getSubTotal();
				} else {
					$sub_total = 0;

					foreach ($this->cart->getProducts() as $product) {
						if (array_key_exists($product['product_id'], $coupon_info['product'])) {
							$sub_total += $product['total'];
						}
					}
				}

				if ($coupon_info['type'] == 'F') {
					$coupon_info['discount'] = min($coupon_info['discount'], $sub_total);
				}

				foreach ($this->cart->getProducts() as $product) {
					$discount_amount = 0;
                    $product_discount_info = null;
					if (!$coupon_info['product']) {
						$status = true;
					} else {
						if (array_key_exists($product['product_id'], $coupon_info['product'])) {
						    $product_discount_info = $coupon_info['product'][$product['product_id']];
							$status = true;
						} else {
							$status = false;
						}
					}

					if ($status) {
					    if($product_discount_info && $product_discount_info["discount"]) {
					        $type = $product_discount_info['type'];
					        $discount = $product_discount_info["discount"];
                        } else {
                            $type = $coupon_info['type'];
                            $discount = $coupon_info["discount"];
                        }
						if ($type == 'F') {
					        if($product_discount_info) {
					            $discount_amount = min($discount, $product['total']);
                            } else {
                                $discount_amount = $discount * ($product['total'] / $sub_total);
                            }

						} elseif ($type == 'P') {
							$discount_amount = $product['total'] / 100 * $discount;
						}

						if ($product['tax_class_id']) {
							$tax_rates = $this->tax->getRates($product['total'] - ($product['total'] - $discount_amount), $product['tax_class_id']);

							foreach ($tax_rates as $tax_rate) {
								if ($tax_rate['type'] == 'P') {
									$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
								}
							}
						}
					}

					$discount_total += $discount_amount;
				}

				if ($coupon_info['shipping'] && isset($this->session->data['shipping_method'])) {
					if (!empty($this->session->data['shipping_method']['tax_class_id'])) {
						$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							if ($tax_rate['type'] == 'P') {
								$taxes[$tax_rate['tax_rate_id']] -= $tax_rate['amount'];
							}
						}
					}

					$discount_total += $this->session->data['shipping_method']['cost'];
				}

				// If discount greater than total
				if ($discount_total > $total) {
					$discount_total = $total;
				}

                if ($coupon_info['max_total'] && $discount_total > $coupon_info['max_total']) {
                    $discount_total = $coupon_info['max_total'];
                }

				$total_data[] = array(
					'code'       => 'coupon',
					'title'      => sprintf($this->language->get('text_coupon'), $this->session->data['coupon']),
					'value'      => -$discount_total,
					'sort_order' => $this->config->get('coupon_sort_order')
				);

				$total -= $discount_total;
			}
		}
	}

	public function confirm($order_info, $order_total) {
		$code = '';

		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');

		if ($start && $end) {
			$code = substr($order_total['title'], $start, $end - $start);
		}

		$this->load->model('checkout/coupon');

		$coupon_info = $this->model_checkout_coupon->getCoupon($code);

		if ($coupon_info) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "coupon_history` SET coupon_id = '" . (int)$coupon_info['coupon_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', customer_id = '" . (int)$order_info['customer_id'] . "', amount = '" . (float)$order_total['value'] . "', date_added = NOW()");
		}
	}

	public function unconfirm($order_id, $order_total) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_history` WHERE order_id = '" . (int)$order_id . "'");
	}
}