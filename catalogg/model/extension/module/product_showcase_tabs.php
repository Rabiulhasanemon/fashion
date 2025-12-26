<?php
class ModelExtensionModuleProductShowcaseTabs extends Model {
	
	public function getTabs($module_id) {
		$query = $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "product_showcase_tabs` 
			WHERE `module_id` = '" . (int)$module_id . "' 
			ORDER BY `sort_order` ASC
		");

		$tabs = array();

		foreach ($query->rows as $row) {
			$tabs[] = array(
				'id' => $row['id'],
				'tab_title' => $row['tab_title'],
				'selection_type' => $row['selection_type'],
				'category_ids' => json_decode($row['category_ids'], true),
				'product_ids' => json_decode($row['product_ids'], true),
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






