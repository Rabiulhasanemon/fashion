<?php
class ModelExtensionModuleProductShowcaseTabs extends Model {
	
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_showcase_tabs` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `module_id` int(11) NOT NULL,
			  `tab_title` varchar(255) NOT NULL,
			  `selection_type` enum('category','product') NOT NULL DEFAULT 'category',
			  `category_ids` text,
			  `product_ids` text,
			  `sort_order` int(3) NOT NULL DEFAULT '0',
			  `status` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`),
			  KEY `module_id` (`module_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_showcase_tabs`");
	}

	public function saveTabs($module_id, $tabs) {
		// Delete existing tabs for this module
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_showcase_tabs` WHERE `module_id` = '" . (int)$module_id . "'");

		// Insert new tabs
		foreach ($tabs as $tab) {
			$category_ids = '';
			$product_ids = '';

			if ($tab['selection_type'] == 'category' && isset($tab['category'])) {
				$category_ids = json_encode($tab['category']);
			}

			if ($tab['selection_type'] == 'product' && isset($tab['product'])) {
				$product_ids = json_encode($tab['product']);
			}

			$this->db->query("
				INSERT INTO `" . DB_PREFIX . "product_showcase_tabs` 
				SET `module_id` = '" . (int)$module_id . "',
					`tab_title` = '" . $this->db->escape($tab['tab_title']) . "',
					`selection_type` = '" . $this->db->escape($tab['selection_type']) . "',
					`category_ids` = '" . $this->db->escape($category_ids) . "',
					`product_ids` = '" . $this->db->escape($product_ids) . "',
					`sort_order` = '" . (int)$tab['sort_order'] . "',
					`status` = '" . (int)$tab['status'] . "'
			");
		}
	}

	public function getTabs($module_id) {
		$query = $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "product_showcase_tabs` 
			WHERE `module_id` = '" . (int)$module_id . "' 
			ORDER BY `sort_order` ASC
		");

		$tabs = array();

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		foreach ($query->rows as $row) {
			// Process categories
			$categories = array();
			if (!empty($row['category_ids'])) {
				$category_ids = json_decode($row['category_ids'], true);
				if (is_array($category_ids)) {
					foreach ($category_ids as $cat_id) {
						$cat_info = $this->model_catalog_category->getCategory($cat_id);
						if ($cat_info) {
							$categories[] = array(
								'category_id' => $cat_id,
								'name' => $cat_info['name']
							);
						}
					}
				}
			}
			
			// Process products
			$products = array();
			if (!empty($row['product_ids'])) {
				$product_ids = json_decode($row['product_ids'], true);
				if (is_array($product_ids)) {
					foreach ($product_ids as $prod_id) {
						$prod_info = $this->model_catalog_product->getProduct($prod_id);
						if ($prod_info) {
							$products[] = array(
								'product_id' => $prod_id,
								'name' => $prod_info['name']
							);
						}
					}
				}
			}
			
			$tabs[] = array(
				'id' => $row['id'],
				'tab_title' => $row['tab_title'],
				'selection_type' => $row['selection_type'],
				'category' => $categories,
				'product' => $products,
				'sort_order' => $row['sort_order'],
				'status' => $row['status']
			);
		}

		return $tabs;
	}

	public function getTabById($tab_id) {
		$query = $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "product_showcase_tabs` 
			WHERE `id` = '" . (int)$tab_id . "'
		");

		if ($query->num_rows) {
			return array(
				'id' => $query->row['id'],
				'module_id' => $query->row['module_id'],
				'tab_title' => $query->row['tab_title'],
				'selection_type' => $query->row['selection_type'],
				'category_ids' => json_decode($query->row['category_ids'], true),
				'product_ids' => json_decode($query->row['product_ids'], true),
				'sort_order' => $query->row['sort_order'],
				'status' => $query->row['status']
			);
		}

		return false;
	}
}

