<?php
class ModelExtensionModuleFlashDeal extends Model {
	
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "flash_deal` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `module_id` int(11) NOT NULL,
			  `product_id` int(11) NOT NULL,
			  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
			  `end_date` datetime NOT NULL,
			  `sort_order` int(3) NOT NULL DEFAULT '0',
			  `status` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`),
			  KEY `module_id` (`module_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "flash_deal`");
	}

	public function saveProducts($module_id, $products) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "flash_deal` WHERE `module_id` = '" . (int)$module_id . "'");

		foreach ($products as $product) {
			if (isset($product['product_id']) && !empty($product['product_id'])) {
				$this->db->query("
					INSERT INTO `" . DB_PREFIX . "flash_deal` 
					SET `module_id` = '" . (int)$module_id . "',
						`product_id` = '" . (int)$product['product_id'] . "',
						`discount` = '" . (float)$product['discount'] . "',
						`end_date` = '" . $this->db->escape($product['end_date']) . "',
						`sort_order` = '" . (int)$product['sort_order'] . "',
						`status` = '" . (int)$product['status'] . "'
				");
			}
		}
	}

	public function getProducts($module_id) {
		$query = $this->db->query("
			SELECT fd.*, pd.name as product_name
			FROM `" . DB_PREFIX . "flash_deal` fd
			LEFT JOIN `" . DB_PREFIX . "product` p ON (fd.product_id = p.product_id)
			LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.product_id = pd.product_id AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "')
			WHERE fd.`module_id` = '" . (int)$module_id . "' 
			ORDER BY fd.`sort_order` ASC
		");

		$products = array();

		foreach ($query->rows as $row) {
			$products[] = array(
				'id' => $row['id'],
				'product_id' => $row['product_id'],
				'product_name' => $row['product_name'],
				'discount' => $row['discount'],
				'end_date' => $row['end_date'],
				'sort_order' => $row['sort_order'],
				'status' => $row['status']
			);
		}

		return $products;
	}
}














