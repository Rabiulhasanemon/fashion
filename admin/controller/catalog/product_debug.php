<?php
class ControllerCatalogProductDebug extends Controller {
	
	public function index() {
		// Check if user is logged in
		if (!$this->user->isLogged() || !$this->user->hasPermission('modify', 'catalog/product')) {
			$this->response->redirect($this->url->link('common/login', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}
		
		$this->load->language('catalog/product');
		
		$data = array();
		$data['heading_title'] = 'Product Debug Tool';
		$data['token'] = $this->session->data['token'];
		
		// Get product_id from request if provided
		$product_id = isset($this->request->get['product_id']) ? (int)$this->request->get['product_id'] : 0;
		$action = isset($this->request->get['action']) ? $this->request->get['action'] : '';
		
		// Handle cleanup action
		if ($action == 'cleanup' && $this->user->hasPermission('modify', 'catalog/product')) {
			$cleanup_result = $this->performCleanup();
			$data['cleanup_result'] = $cleanup_result;
		}
		
		// Get debug information
		try {
			$data['debug_info'] = $this->getDebugInfo($product_id);
		} catch (Exception $e) {
			$data['debug_info'] = array();
			$data['error'] = 'Error getting debug info: ' . $e->getMessage();
		}
		$data['current_product_id'] = $product_id;
		
		// Ensure debug_info is always an array
		if (!isset($data['debug_info']) || !is_array($data['debug_info'])) {
			$data['debug_info'] = array();
		}
		
		// Initialize empty arrays if not set
		if (!isset($data['debug_info']['zero_records'])) {
			$data['debug_info']['zero_records'] = array();
		}
		if (!isset($data['debug_info']['duplicate_models'])) {
			$data['debug_info']['duplicate_models'] = array();
		}
		if (!isset($data['debug_info']['duplicate_skus'])) {
			$data['debug_info']['duplicate_skus'] = array();
		}
		if (!isset($data['debug_info']['recent_errors'])) {
			$data['debug_info']['recent_errors'] = array();
		}
		
		// Load template
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('catalog/product_debug.tpl', $data));
	}
	
	private function getDebugInfo($product_id = 0) {
		$info = array(
			'zero_records' => array(),
			'duplicate_models' => array(),
			'duplicate_skus' => array(),
			'recent_errors' => array(),
			'auto_increment' => array()
		);
		
		// 1. Check for product_id = 0 records
		$info['zero_records'] = array();
		$tables_to_check = array(
			'product',
			'product_description',
			'product_to_store',
			'product_to_category',
			'product_image',
			'product_option',
			'product_option_value',
			'product_filter',
			'product_attribute',
			'product_discount',
			'product_special',
			'product_reward',
			'product_related',
			'product_compatible',
			'product_to_layout',
			'product_to_download'
		);
		
		foreach ($tables_to_check as $table) {
			$check_table = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
			if ($check_table && $check_table->num_rows) {
				$check_column = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE 'product_id'");
				if ($check_column && $check_column->num_rows) {
					$count_query = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
					if ($count_query && $count_query->num_rows && $count_query->row['count'] > 0) {
						$info['zero_records'][$table] = (int)$count_query->row['count'];
					}
				}
			}
		}
		
		// 2. Check for duplicate models
		$info['duplicate_models'] = array();
		$dup_models = $this->db->query("SELECT model, COUNT(*) as count, GROUP_CONCAT(product_id) as product_ids 
			FROM " . DB_PREFIX . "product 
			WHERE model != '' AND model IS NOT NULL 
			GROUP BY model 
			HAVING count > 1");
		if ($dup_models && $dup_models->num_rows > 0) {
			foreach ($dup_models->rows as $row) {
				$info['duplicate_models'][] = array(
					'model' => $row['model'],
					'count' => $row['count'],
					'product_ids' => $row['product_ids']
				);
			}
		}
		
		// 3. Check for duplicate SKUs
		$info['duplicate_skus'] = array();
		$dup_skus = $this->db->query("SELECT sku, COUNT(*) as count, GROUP_CONCAT(product_id) as product_ids 
			FROM " . DB_PREFIX . "product 
			WHERE sku != '' AND sku IS NOT NULL 
			GROUP BY sku 
			HAVING count > 1");
		if ($dup_skus && $dup_skus->num_rows > 0) {
			foreach ($dup_skus->rows as $row) {
				$info['duplicate_skus'][] = array(
					'sku' => $row['sku'],
					'count' => $row['count'],
					'product_ids' => $row['product_ids']
				);
			}
		}
		
		// 4. Get product information if product_id provided
		if ($product_id > 0) {
			$product_info = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '" . $product_id . "'");
			if ($product_info && $product_info->num_rows) {
				$info['product'] = $product_info->row;
				
				// Check for related records
				$info['product_relations'] = array();
				foreach ($tables_to_check as $table) {
					if ($table == 'product') continue;
					$check_table = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
					if ($check_table && $check_table->num_rows) {
						$check_column = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE 'product_id'");
						if ($check_column && $check_column->num_rows) {
							$count_query = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . $table . " WHERE product_id = '" . $product_id . "'");
							if ($count_query && $count_query->num_rows) {
								$count = (int)$count_query->row['count'];
								if ($count > 0) {
									$info['product_relations'][$table] = $count;
								}
							}
						}
					}
				}
			} else {
				$info['product'] = null;
				$info['product_error'] = 'Product with ID ' . $product_id . ' not found';
			}
		}
		
		// 5. Get recent error logs
		$info['recent_errors'] = array();
		$error_log_file = DIR_LOGS . 'product_insert_error.log';
		if (file_exists($error_log_file)) {
			$log_content = file_get_contents($error_log_file);
			$log_lines = explode("\n", $log_content);
			// Get last 50 lines
			$info['recent_errors'] = array_slice(array_filter($log_lines), -50);
		}
		
		// 6. Database structure info
		$info['db_structure'] = array();
		$structure_query = $this->db->query("SHOW CREATE TABLE " . DB_PREFIX . "product");
		if ($structure_query && $structure_query->num_rows) {
			$info['db_structure']['product'] = $structure_query->row['Create Table'];
		}
		
		// 7. Check for auto_increment issues
		$auto_inc = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "product'");
		if ($auto_inc && $auto_inc->num_rows) {
			$info['auto_increment'] = array(
				'auto_increment' => isset($auto_inc->row['Auto_increment']) ? $auto_inc->row['Auto_increment'] : 'N/A',
				'rows' => isset($auto_inc->row['Rows']) ? $auto_inc->row['Rows'] : 'N/A'
			);
		}
		
		return $info;
	}
	
	private function performCleanup() {
		$result = array(
			'success' => true,
			'messages' => array(),
			'cleaned_tables' => 0
		);
		
		$cleanup_tables = array(
			'product_description',
			'product_to_store',
			'product_to_category',
			'product_image',
			'product_option',
			'product_option_value',
			'product_filter',
			'product_attribute',
			'product_discount',
			'product_special',
			'product_reward',
			'product_related',
			'product_compatible',
			'product_to_layout',
			'product_to_download',
			'product'
		);
		
		foreach ($cleanup_tables as $table) {
			$check_table = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . $table . "'");
			if ($check_table && $check_table->num_rows) {
				$check_column = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE 'product_id'");
				if ($check_column && $check_column->num_rows) {
					try {
						$delete_query = $this->db->query("DELETE FROM " . DB_PREFIX . $table . " WHERE product_id = 0");
						if ($delete_query) {
							$result['cleaned_tables']++;
							$result['messages'][] = "Cleaned " . $table;
						}
					} catch (Exception $e) {
						$result['messages'][] = "Error cleaning " . $table . ": " . $e->getMessage();
					}
				}
			}
		}
		
		// Clean up url_alias
		try {
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=0'");
			$result['messages'][] = "Cleaned url_alias";
		} catch (Exception $e) {
			$result['messages'][] = "Error cleaning url_alias: " . $e->getMessage();
		}
		
		return $result;
	}
}

