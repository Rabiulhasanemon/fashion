<?php
#[AllowDynamicProperties]
class Cart {
	private $config;
	private $db;
	private $data = array();

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
			$this->session->data['cart'] = array();
		}
	}

    public function getProducts() {
        if (!$this->data) {
            $stock_checkout = $this->config->get('config_stock_checkout');
            $emi_cart = $this->isEMI();
            foreach ($this->session->data['cart'] as $key => $quantity) {
                $product = unserialize(base64_decode($key));

                $product_id = $product['product_id'];

                $stock = true;
                $available_stock = false;

                // Options
                if (!empty($product['option'])) {
                    $options = $product['option'];
                } else {
                    $options = array();
                }


                $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

                if (!$product_query->num_rows) {
                    $this->remove($key);
                    continue;
                }

                //region Product Option
                $variation_price = 0;
                $option_data = array();

                foreach ($options as $product_option_id => $option_value_id) {
                    $option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                    if ($option_query->num_rows) {
                        $option_value_query = $this->db->query("SELECT ov.option_value_id, ovd.name FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_value_id = '" . (int)$option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
                        if ($option_value_query->num_rows) {
                            $option_data[] = array(
                                'product_option_id'       => $product_option_id,
                                'option_id'               => $option_query->row['option_id'],
                                'option_value_id'         => $option_value_query->row['option_value_id'],
                                'name'                    => $option_query->row['name'],
                                'value'                   => $option_value_query->row['name'],
                                'type'                    => $option_query->row['type'],
                            );
                        }
                    }
                }

                if($options) {
                    $variation = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_variation WHERE product_id = '" . (int)$product_id . "' AND `key` = '" . $this->db->escape(implode("-", array_values($options))) . " '")->row;
                } else {
                    $variation = null;
                }

                if($variation) {
                    if ($variation['price_prefix'] == '+') {
                        $variation_price += $variation['price'];
                    } elseif ($variation['price_prefix'] == '-') {
                        $variation_price -= $variation['price'];
                    }
                }
                //endregion

                $price = $product_query->row['price'];

                //region Reward Points
                $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

                if ($product_reward_query->num_rows) {
                    $reward = $product_reward_query->row['points'];
                } else {
                    $reward = 0;
                }
                //endregion

                // region Product Discounts
                $discount_quantity = 0;

                foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
                    $product_2 = (array)unserialize(base64_decode($key_2));

                    if ($product_2['product_id'] == $product_id) {
                        $discount_quantity += $quantity_2;
                    }
                }

                $on_sale = false;
                $discount = 0;

                $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start IS NULL OR date_start < NOW()) AND (date_end IS NULL OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

                if ($product_discount_query->num_rows) {
                    $discount = $price - $product_discount_query->row['price'];
                    $price = $product_discount_query->row['price'];
                    $reward = 0;
                    $on_sale = true;
                }
                // endregion

                //region Product Specials
                $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start IS NULL OR date_start < NOW()) AND (date_end IS NULL OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");
                if ($product_special_query->num_rows) {
                    $price = $product_special_query->row['price'];
                    $reward = 0;
                    $discount = 0;
                    $on_sale = true;
                }

                if($emi_cart && isset($product['emi']) && $product['emi']) {
                    $price = max($price, $product_query->row['regular_price']);
                    $reward = 0;
                    $discount = 0;
                }
                // endregion

                // If Order Edit From backend
                if(isset($product['order_product_id'])) {
                    $order_product_id = $product['order_product_id'];
                    $product_special_query = $this->db->query("SELECT price, reward FROM " . DB_PREFIX . "order_product WHERE product_id = '" . (int)$product_id . "' AND order_product_id = '" . (int)$product['order_product_id'] . "' LIMIT 1");
                } else {
                    $order_product_id = null;
                    $product_special_query = null;
                }

                if ($product_special_query && $product_special_query->num_rows) {
                    if($product_special_query->row['price'] < $price) {
                        $on_sale = true;
                    }
                    $price = $product_special_query->row['price'];
                    $reward = $product_special_query->row['reward'] / $quantity;
                }

                // Downloads
                $download_data = array();

                $download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                foreach ($download_query->rows as $download) {
                    $download_data[] = array(
                        'download_id' => $download['download_id'],
                        'name'        => $download['name'],
                        'filename'    => $download['filename'],
                        'mask'        => $download['mask']
                    );
                }

                // Stock & SKU
                if($options) {
                    $sku = $variation['sku'] ?? '';
                    $product_quantity = $variation['quantity'] ?? 0;
                } else {
                    $sku = $product_query->row['sku'];
                    $product_quantity = $product_query->row['quantity'];
                }

                if (!$product_quantity || $product_quantity < 1 || ($product_quantity < $quantity)) {
                    $stock = false;
                }

                if($stock_checkout && $available_stock === false && $product_query->row['subtract']) {
                    $available_stock = $product_quantity ?: 0;
                }

                $this->data[$key] = array(
                    'key'             => $key,
                    'order_product_id'=> $order_product_id,
                    'product_id'      => $product_query->row['product_id'],
                    'manufacturer_id' => $product_query->row['manufacturer_id'],
                    'name'            => $product_query->row['name'],
                    'model'           => $product_query->row['model'],
                    'sku'             => $sku,
                    'short_description'=> $product_query->row['short_description'],
                    'shipping'        => $product_query->row['shipping'],
                    'image'           => $product_query->row['image'],
                    'option'          => $option_data,
                    'emi'             => $product_query->row['emi'] && isset($product['emi']) && $product['emi'],
                    'download'        => $download_data,
                    'quantity'        => $quantity,
                    'minimum'         => $product_query->row['minimum'],
                    'subtract'        => $product_query->row['subtract'],
                    'stock'           => $stock,
                    'available_stock' => $available_stock,
                    'on_sale'         => $on_sale,
                    'price'           => ($price + $variation_price),
                    'total'           => ($price + $variation_price) * $quantity,
                    'discount'        => $discount,
                    'reward'          => $reward * $quantity,
                    'tax_class_id'    => $product_query->row['tax_class_id'],
                    'weight'          => $product_query->row['weight'] * $quantity,
                    'weight_class_id' => $product_query->row['weight_class_id'],
                    'length'          => $product_query->row['length'],
                    'width'           => $product_query->row['width'],
                    'height'          => $product_query->row['height'],
                    'length_class_id' => $product_query->row['length_class_id'],
                );
            }
        }

        return $this->data;
    }



    public function isEMI() {
        if(count($this->session->data['cart']) == 0) return false;
        $isEMI = true;
        foreach ($this->session->data['cart'] as $key => $quantity) {
            $product = unserialize(base64_decode($key));
            $isEMI = $isEMI && isset($product['emi']) && $product['emi'];
        }
        return $isEMI;
    }

    public function getQuantity($product_id, $option = array(), $emi = false) {
        $product['product_id'] = (int)$product_id;

        if ($option) {
            $product['option'] = $option;
        }

        if($emi) {
            $product['emi'] = true;
        }

        $key = base64_encode(serialize($product));

        if (!isset($this->session->data['cart'][$key])) {
            return 0;
        } else {
            return $this->session->data['cart'][$key];
        }

    }

    public function add($product_id, $qty = 1, $option = array(), $emi = false) {
		$this->data = array();

		$product['product_id'] = (int)$product_id;

		if ($option) {
			$product['option'] = $option;
		}

		if($emi) {
		    $product['emi'] = true;
        }

		$key = base64_encode(serialize($product));

		if ((int)$qty && ((int)$qty > 0)) {
			if (!isset($this->session->data['cart'][$key])) {
				$this->session->data['cart'][$key] = (int)$qty;
			} else {
				$this->session->data['cart'][$key] += (int)$qty;
			}
		}
	}

	public function update($key, $qty) {
		$this->data = array();

		if ((int)$qty && ((int)$qty > 0) && isset($this->session->data['cart'][$key])) {
			$this->session->data['cart'][$key] = (int)$qty;
		} else {
			$this->remove($key);
		}
	}

	public function remove($key) {
		$this->data = array();

		unset($this->session->data['cart'][$key]);
	}

	public function clear() {
		$this->data = array();

		$this->session->data['cart'] = array();
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	public function hasProducts() {
		return count($this->session->data['cart']);
	}

	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

    public function hasStock() {
        $stock = true;

        foreach ($this->getProducts() as $product) {
            if (!$product['stock']) {
                $stock = false;
            } elseif ($product['minimum'] > $product['quantity']) {
                $stock = false;
            }
        }

        return $stock;
    }

	public function hasShipping() {
		$shipping = false;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$shipping = true;

				break;
			}
		}

		return $shipping;
	}

	public function hasDownload() {
		$download = false;

		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				$download = true;

				break;
			}
		}

		return $download;
	}
}