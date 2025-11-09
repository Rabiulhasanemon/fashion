<?php
/**
 * This file is part of FreeCart.
 *
 * @copyright  sv2109 <sv2109@gmail.com>
 * @link http://freecart.pro
*/

class ModelCatalogSearchSuggestion extends Model {
	
	public function install () {
		
		$this->load->model('design/layout');

        $results = $this->model_design_layout->getLayouts();
		
		foreach ($results as $result) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET layout_id = '" . (int)$result['layout_id'] . "', code = 'search_suggestion', position = 'content_top', sort_order = '0'");
		}
	}

	/**
	 * @return array
	 */
	public function getDefaultOptions() {
		return array(
			'key' => '',
			'search_order' => 'name',
			'search_order_dir' => 'asc',
			'search_logic' => 'and',
			'search_limit' => 7,
			'search_cache' => 1
		);
	}
}