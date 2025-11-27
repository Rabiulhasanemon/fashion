<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {
		// Validate required data
		if (!isset($data['name']) || empty(trim($data['name']))) {
			throw new Exception('Manufacturer name is required');
		}
		
		// CRITICAL: Remove any manufacturer_id from data to prevent using a provided ID
		// We always calculate the next ID ourselves
		if (isset($data['manufacturer_id'])) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: manufacturer_id provided in data (' . $data['manufacturer_id'] . '), ignoring it' . PHP_EOL, FILE_APPEND);
			unset($data['manufacturer_id']);
		}

		// CRITICAL: Multiple cleanup attempts to ensure no manufacturer_id = 0 exists
		$cleanup_attempts = 0;
		$max_cleanup_attempts = 3;
		
		while ($cleanup_attempts < $max_cleanup_attempts) {
			// Delete any manufacturer with manufacturer_id = 0
			$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
			$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = 0");
			$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = 0");
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=0'");
			
			// Verify cleanup
			$verify_zero = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
			$zero_count = 0;
			if ($verify_zero && $verify_zero->num_rows) {
				$zero_count = (int)$verify_zero->row['count'];
			}
			
			if ($zero_count == 0) {
				break; // Cleanup successful
			}
			
			$cleanup_attempts++;
		}
		
		// If still have zero records after cleanup, this is a critical issue
		if ($zero_count > 0) {
			// Try aggressive cleanup
			$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id <= 0");
			$verify_final = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
			$final_zero = 0;
			if ($verify_final && $verify_final->num_rows) {
				$final_zero = (int)$verify_final->row['count'];
			}
			if ($final_zero > 0) {
				throw new Exception('CRITICAL: Cannot remove record with manufacturer_id = 0. Database may be corrupted. Please check manually.');
			}
		}
		
		// Ensure AUTO_INCREMENT is set correctly
		$max_check = $this->db->query("SELECT MAX(manufacturer_id) as max_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id > 0");
		$max_id = 0;
		if ($max_check && $max_check->num_rows && isset($max_check->row['max_id']) && $max_check->row['max_id'] !== null) {
			$max_id = (int)$max_check->row['max_id'];
		}
		$next_id = max($max_id + 1, 1);
		
		// CRITICAL: Double-check next_id is valid - if it's 0 or negative, something is wrong
		if ($next_id <= 0) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: next_id is invalid (' . $next_id . '). max_id was: ' . $max_id . PHP_EOL, FILE_APPEND);
			// Force it to at least 1
			$next_id = 1;
			// But check if 1 already exists
			$check_one = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 1 LIMIT 1");
			if ($check_one && $check_one->num_rows) {
				// If 1 exists, use max + 1 again, but ensure it's positive
				$next_id = max($max_id + 1, 1);
				if ($next_id <= 0) {
					throw new Exception('CRITICAL: Cannot calculate valid next_id. max_id: ' . $max_id . ', next_id: ' . $next_id);
				}
			}
		}
		
		// Check if AUTO_INCREMENT is available on the column
		$column_check = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "manufacturer WHERE Field = 'manufacturer_id'");
		$has_auto_increment = false;
		if ($column_check && $column_check->num_rows) {
			$extra = isset($column_check->row['Extra']) ? $column_check->row['Extra'] : '';
			$has_auto_increment = (stripos($extra, 'auto_increment') !== false);
		}
		
		// If AUTO_INCREMENT is not available, we need to use explicit ID insertion
		if (!$has_auto_increment) {
			// Try to add AUTO_INCREMENT to the column
			$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer MODIFY manufacturer_id int(11) NOT NULL AUTO_INCREMENT");
			
			// Verify it was added
			$column_check2 = $this->db->query("SHOW COLUMNS FROM " . DB_PREFIX . "manufacturer WHERE Field = 'manufacturer_id'");
			if ($column_check2 && $column_check2->num_rows) {
				$extra2 = isset($column_check2->row['Extra']) ? $column_check2->row['Extra'] : '';
				$has_auto_increment = (stripos($extra2, 'auto_increment') !== false);
			}
		}
		
		// Set AUTO_INCREMENT value if it's available
		if ($has_auto_increment) {
			$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . $next_id);
		}
		
		// Final verification: ensure no ID 0 exists - do this RIGHT before insert
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
		$final_check = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0 LIMIT 1");
		if ($final_check && $final_check->num_rows > 0) {
			throw new Exception('CRITICAL: Record with manufacturer_id = 0 still exists after cleanup. Cannot proceed with insert.');
		}
		
		// CRITICAL: Always ensure AUTO_INCREMENT is set correctly right before insert
		if ($has_auto_increment) {
			// Double-check AUTO_INCREMENT value and force it if needed
			$ai_status = $this->db->query("SHOW TABLE STATUS LIKE '" . DB_PREFIX . "manufacturer'");
			if ($ai_status && $ai_status->num_rows) {
				$current_ai = isset($ai_status->row['Auto_increment']) ? $ai_status->row['Auto_increment'] : (isset($ai_status->row['AUTO_INCREMENT']) ? $ai_status->row['AUTO_INCREMENT'] : null);
				if ($current_ai === null || (int)$current_ai <= $max_id) {
					// Force AUTO_INCREMENT to next_id
					$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . $next_id);
				}
			}
		}
		
		// ALWAYS use explicit ID insertion to avoid AUTO_INCREMENT issues
		// This ensures we never get ID 0, even if AUTO_INCREMENT has problems
		
		// CRITICAL: Verify next_id is valid before building SQL
		if ($next_id <= 0) {
			throw new Exception('CRITICAL: Invalid next_id calculated: ' . $next_id . '. Cannot proceed with insert.');
		}
		
		$insert_sql = "INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = '" . (int)$next_id . "', name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "'";
		
		// CRITICAL: Verify SQL doesn't contain manufacturer_id = 0
		if (strpos($insert_sql, "manufacturer_id = '0'") !== false || strpos($insert_sql, "manufacturer_id = 0") !== false) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: SQL contains manufacturer_id = 0! SQL: ' . $insert_sql . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - next_id value: ' . $next_id . PHP_EOL, FILE_APPEND);
			throw new Exception('CRITICAL: SQL contains manufacturer_id = 0! This should never happen. next_id: ' . $next_id);
		}
		
		$manufacturer_id = $next_id; // Set expected ID
		
		// Log the insert attempt
		$log_file = DIR_LOGS . 'manufacturer_error.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Attempting insert with manufacturer_id = ' . $next_id . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - SQL: ' . $insert_sql . PHP_EOL, FILE_APPEND);
		
		$insert_result = $this->db->query($insert_sql);
		
		// If insert failed, it might be because the ID already exists (race condition)
		// In that case, recalculate next_id and retry
		if (!$insert_result) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Insert failed! Checking error details...' . PHP_EOL, FILE_APPEND);
			
			// Get detailed error information
			$error_details = 'Unknown database error';
			$errno = 0;
			
			// Try to get MySQL error through reflection
			try {
				$reflection = new ReflectionClass($this->db);
				$db_property = $reflection->getProperty('db');
				$db_property->setAccessible(true);
				$db_driver = $db_property->getValue($this->db);
				
				if (is_object($db_driver) && property_exists($db_driver, 'link')) {
					$link_reflection = new ReflectionProperty($db_driver, 'link');
					$link_reflection->setAccessible(true);
					$link = $link_reflection->getValue($db_driver);
					
					if (is_object($link)) {
						if (method_exists($link, 'error')) {
							$error_details = $link->error;
						}
						if (method_exists($link, 'errno')) {
							$errno = $link->errno;
						}
					}
				}
			} catch (Exception $e) {
				// Reflection failed
			}
			
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - MySQL Error: ' . $error_details . ' (Code: ' . $errno . ')' . PHP_EOL, FILE_APPEND);
			
			// Check if a record with ID 0 was created
			$check_zero_created = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0 LIMIT 1");
			if ($check_zero_created && $check_zero_created->num_rows > 0) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: A record with manufacturer_id = 0 was created!' . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Record details: ' . print_r($check_zero_created->row, true) . PHP_EOL, FILE_APPEND);
			}
			
			// Check if the ID we tried to use already exists
			$check_id_exists = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$next_id . "' LIMIT 1");
			if ($check_id_exists && $check_id_exists->num_rows > 0) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ID ' . $next_id . ' already exists. Recalculating...' . PHP_EOL, FILE_APPEND);
				
				// Get the current max ID again
				$max_check_retry = $this->db->query("SELECT MAX(manufacturer_id) as max_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id > 0");
				$max_id_retry = 0;
				if ($max_check_retry && $max_check_retry->num_rows && isset($max_check_retry->row['max_id']) && $max_check_retry->row['max_id'] !== null) {
					$max_id_retry = (int)$max_check_retry->row['max_id'];
				}
				$next_id_retry = max($max_id_retry + 1, 1);
				
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retrying with ID: ' . $next_id_retry . PHP_EOL, FILE_APPEND);
				
				// Clean up any ID 0 that might have been created
				$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
				
				// Retry with new ID
				$insert_sql = "INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = '" . (int)$next_id_retry . "', name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "'";
				$insert_result = $this->db->query($insert_sql);
				$manufacturer_id = $next_id_retry;
				
				if ($insert_result) {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retry successful with ID: ' . $next_id_retry . PHP_EOL, FILE_APPEND);
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retry also failed!' . PHP_EOL, FILE_APPEND);
				}
			}
		}
		
		if (!$insert_result) {
			// Get detailed error information
			$error_details = 'Unknown database error';
			$errno = 0;
			
			// Try to get MySQL error through reflection
			try {
				$reflection = new ReflectionClass($this->db);
				$db_property = $reflection->getProperty('db');
				$db_property->setAccessible(true);
				$db_driver = $db_property->getValue($this->db);
				
				if (is_object($db_driver) && property_exists($db_driver, 'link')) {
					$link_reflection = new ReflectionProperty($db_driver, 'link');
					$link_reflection->setAccessible(true);
					$link = $link_reflection->getValue($db_driver);
					
					if (is_object($link)) {
						if (method_exists($link, 'error')) {
							$error_details = $link->error;
						}
						if (method_exists($link, 'errno')) {
							$errno = $link->errno;
						}
					}
				}
			} catch (Exception $e) {
				// Reflection failed, use generic message
			}
			
			// Check if there's still a record with ID 0
			$check_zero_after = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
			$zero_count_after = 0;
			if ($check_zero_after && $check_zero_after->num_rows) {
				$zero_count_after = (int)$check_zero_after->row['count'];
			}
			
			$error_msg = 'Duplicate entry for key PRIMARY';
			if ($zero_count_after > 0) {
				$error_msg .= ' (Record with manufacturer_id = 0 exists - ' . $zero_count_after . ' record(s))';
			}
			$error_msg .= ' - MySQL Error: ' . $error_details . ' (Code: ' . $errno . ')';
			
			throw new Exception($error_msg);
		}
		
		// manufacturer_id was set explicitly, verify it's correct
		// No need to retrieve it since we set it explicitly
		
		// Verify the insert was successful
		if ($manufacturer_id <= 0) {
			throw new Exception('Invalid manufacturer_id after insert: ' . $manufacturer_id);
		}
		
		// Verify the record actually exists
		$verify_insert = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "' LIMIT 1");
		if (!$verify_insert || !$verify_insert->num_rows) {
			throw new Exception('Failed to insert manufacturer - record not found after insert (manufacturer_id: ' . $manufacturer_id . ')');
		}
		
		// Update AUTO_INCREMENT to be higher than the inserted ID to keep it in sync
		if ($has_auto_increment) {
			$next_ai = $manufacturer_id + 1;
			$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . $next_ai);
		}

		// CRITICAL: Delete any existing records for this manufacturer_id first (in case of retry or orphaned data)
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0) {
					// Check if exists first to avoid duplicate key errors
					$check_desc = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "' AND language_id = '" . $language_id . "' LIMIT 1");
					if (!$check_desc || !$check_desc->num_rows) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . $language_id . "', description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
					}
				}
			}
		} else {
			// Insert default description if none provided
			$default_language_id = 1; // Default to 1 if config not available
			if (isset($this->config) && method_exists($this->config, 'get')) {
				$config_lang_id = $this->config->get('config_language_id');
				if ($config_lang_id) {
					$default_language_id = (int)$config_lang_id;
				}
			}
			// Check if exists first
			$check_default = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "' AND language_id = '" . (int)$default_language_id . "' LIMIT 1");
			if (!$check_default || !$check_default->num_rows) {
				$desc_result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . (int)$default_language_id . "', description = '', meta_title = '" . $this->db->escape($data['name']) . "', meta_description = '', meta_keyword = ''");
				if (!$desc_result) {
					// Log but don't fail - description is optional
					$error = '';
					if (property_exists($this->db, 'link') && is_object($this->db->link) && property_exists($this->db->link, 'error')) {
						$error = $this->db->link->error;
					}
					error_log('Warning: Failed to insert manufacturer description: ' . $error);
				}
			}
		}

		if (isset($data['manufacturer_store']) && is_array($data['manufacturer_store']) && !empty($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					// Check if exists first
					$check_store = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "' AND store_id = '" . $store_id . "' LIMIT 1");
					if (!$check_store || !$check_store->num_rows) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "'");
					}
				}
			}
		} else {
			// Default to store 0 if none specified
			$check_store_default = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "' AND store_id = '0' LIMIT 1");
			if (!$check_store_default || !$check_store_default->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '0'");
			}
		}

		if (isset($data['keyword']) && !empty(trim($data['keyword']))) {
			$keyword = trim($data['keyword']);
			// Check if keyword already exists
			$existing = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' LIMIT 1");
			if (!$existing->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . $manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
			}
		}

		if (isset($data['manufacturer_layout']) && is_array($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$layout_id = (int)$layout_id;
				if ($layout_id > 0) {
					$store_id = (int)$store_id;
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "', layout_id = '" . $layout_id . "'");
				}
			}
		}

		$this->cache->delete('manufacturer');

		return $manufacturer_id;
	}

	public function editManufacturer($manufacturer_id, $data) {
		// Validate manufacturer_id
		$manufacturer_id = (int)$manufacturer_id;
		if ($manufacturer_id <= 0) {
			throw new Exception('Invalid manufacturer ID: ' . $manufacturer_id);
		}

		// Verify manufacturer exists before updating
		$check_query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . $manufacturer_id . "' LIMIT 1");
		if (!$check_query->num_rows) {
			throw new Exception('Manufacturer with ID ' . $manufacturer_id . ' does not exist');
		}

		// Validate required data
		if (!isset($data['name']) || empty(trim($data['name']))) {
			throw new Exception('Manufacturer name is required');
		}

		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape(isset($data['image']) ? $data['image'] : '') . "', thumb = '" . $this->db->escape(isset($data['thumb']) ? $data['thumb'] : '') . "', sort_order = '" . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . "' WHERE manufacturer_id = '" . $manufacturer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . $manufacturer_id . "', language_id = '" . $language_id . "', description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_store']) && is_array($data['manufacturer_store']) && !empty($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "'");
				}
			}
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . $manufacturer_id . "', store_id = '0'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . $manufacturer_id . "'");

		if (isset($data['keyword']) && !empty(trim($data['keyword']))) {
			$keyword = trim($data['keyword']);
			// Check if keyword already exists for a different manufacturer
			$existing = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' AND query != 'manufacturer_id=" . $manufacturer_id . "' LIMIT 1");
			if (!$existing->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . $manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . $manufacturer_id . "'");

		if (isset($data['manufacturer_layout']) && is_array($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$layout_id = (int)$layout_id;
				if ($layout_id > 0) {
					$store_id = (int)$store_id;
					$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = '" . $manufacturer_id . "', store_id = '" . $store_id . "', layout_id = '" . $layout_id . "'");
				}
			}
		}

		$this->cache->delete('manufacturer');
	}

	public function deleteManufacturer($manufacturer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');
	}

	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "' LIMIT 1) AS keyword FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalManufacturers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getManufacturerDescriptions($manufacturer_id) {
		$manufacturer_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_description_data[$result['language_id']] = array(
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $manufacturer_description_data;
	}

	public function getManufacturerStores($manufacturer_id) {
		$manufacturer_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getManufacturerLayouts($manufacturer_id) {
		$manufacturer_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $manufacturer_layout_data;
	}
}


