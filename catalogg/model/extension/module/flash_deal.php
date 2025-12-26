<?php
class ModelExtensionModuleFlashDeal extends Model {
	
	public function getProducts($module_id) {
		$query = $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "flash_deal` 
			WHERE `module_id` = '" . (int)$module_id . "' 
			AND `status` = '1'
			ORDER BY `sort_order` ASC
		");

		return $query->rows;
	}
}














