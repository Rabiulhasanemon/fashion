<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer($data) {
		// Start logging immediately
		$log_file = DIR_LOGS . 'manufacturer_error.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== addManufacturer() CALLED ==========' . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Data keys: ' . implode(', ', array_keys($data)) . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Data: ' . print_r($data, true) . PHP_EOL, FILE_APPEND);
		
		// Validate required data
		if (!isset($data['name']) || empty(trim($data['name']))) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Manufacturer name is required' . PHP_EOL, FILE_APPEND);
			throw new Exception('Manufacturer name is required');
		}
		
		// CRITICAL: Remove any manufacturer_id from data to prevent using a provided ID
		// We always calculate the next ID ourselves
		if (isset($data['manufacturer_id'])) {
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
		
		// CRITICAL: Verify next_id is valid before building SQL
		if ($next_id <= 0) {
			throw new Exception('CRITICAL: Invalid next_id calculated: ' . $next_id . '. Cannot proceed with insert.');
		}
		
		// CRITICAL: Decode HTML entities in image paths before escaping
		$image = isset($data['image']) ? html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8') : '';
		$thumb = isset($data['thumb']) ? html_entity_decode($data['thumb'], ENT_QUOTES, 'UTF-8') : '';
		
		// ALWAYS use AUTO_INCREMENT - never specify manufacturer_id explicitly
		// This avoids all issues with explicit IDs and duplicate key errors
		if ($has_auto_increment) {
			// Ensure AUTO_INCREMENT is set correctly
			$target_ai = $next_id;
			$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . $target_ai);
			
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Using AUTO_INCREMENT insert. Expected ID: ' . $next_id . PHP_EOL, FILE_APPEND);
			
			// Insert without specifying manufacturer_id - let AUTO_INCREMENT handle it
			$insert_sql = "INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($image) . "', thumb = '" . $this->db->escape($thumb) . "', sort_order = " . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - SQL: ' . $insert_sql . PHP_EOL, FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Image length: ' . strlen($image) . ', Thumb length: ' . strlen($thumb) . PHP_EOL, FILE_APPEND);
			
			$insert_result = $this->db->query($insert_sql);
			
			if ($insert_result) {
				$manufacturer_id = $this->db->getLastId();
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Insert succeeded. getLastId() returned: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
				
				// If getLastId() returns 0 or invalid, query the database
				if (!$manufacturer_id || $manufacturer_id <= 0) {
					$find = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE name = '" . $this->db->escape($data['name']) . "' ORDER BY manufacturer_id DESC LIMIT 1");
					if ($find && $find->num_rows) {
						$manufacturer_id = (int)$find->row['manufacturer_id'];
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retrieved manufacturer_id from database: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
					} else {
						$log_file = DIR_LOGS . 'manufacturer_error.log';
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: Could not retrieve manufacturer_id after insert!' . PHP_EOL, FILE_APPEND);
						$insert_result = false;
					}
				}
			} else {
				$log_file = DIR_LOGS . 'manufacturer_error.log';
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - AUTO_INCREMENT insert failed!' . PHP_EOL, FILE_APPEND);
			}
		} else {
			// No AUTO_INCREMENT - use explicit ID (fallback)
			$manufacturer_id_value = (int)$next_id;
			$insert_sql = "INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = " . $manufacturer_id_value . ", name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($image) . "', thumb = '" . $this->db->escape($thumb) . "', sort_order = " . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0);
			$manufacturer_id = $manufacturer_id_value;
			$insert_result = $this->db->query($insert_sql);
		}
		
		// If AUTO_INCREMENT insert failed, try fallback with explicit ID
		if (!$insert_result && $has_auto_increment) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - AUTO_INCREMENT insert failed, trying with explicit ID...' . PHP_EOL, FILE_APPEND);
			
			// Clean up any ID 0 that might have been created
			$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
			
			// Recalculate next_id in case something changed
			$max_check_fallback = $this->db->query("SELECT MAX(manufacturer_id) as max_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id > 0");
			$max_id_fallback = 0;
			if ($max_check_fallback && $max_check_fallback->num_rows && isset($max_check_fallback->row['max_id']) && $max_check_fallback->row['max_id'] !== null) {
				$max_id_fallback = (int)$max_check_fallback->row['max_id'];
			}
			$next_id_fallback = max($max_id_fallback + 1, 1);
			
			// Set AUTO_INCREMENT higher than our target to allow explicit insert
			$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . ($next_id_fallback + 1));
			
			// Insert with explicit ID
			$insert_sql2 = "INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = " . (int)$next_id_fallback . ", name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($image) . "', thumb = '" . $this->db->escape($thumb) . "', sort_order = " . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Fallback SQL: ' . $insert_sql2 . PHP_EOL, FILE_APPEND);
			$insert_result = $this->db->query($insert_sql2);
			
			if ($insert_result) {
				$manufacturer_id = $next_id_fallback;
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Explicit ID insert succeeded, manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			}
		}
		
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
				
				// Retry with new ID - use integer directly, not quoted
				$retry_id = (int)$next_id_retry;
				$insert_sql = "INSERT INTO " . DB_PREFIX . "manufacturer SET manufacturer_id = " . $retry_id . ", name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($image) . "', thumb = '" . $this->db->escape($thumb) . "', sort_order = " . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Retry SQL: ' . $insert_sql . PHP_EOL, FILE_APPEND);
				$insert_result = $this->db->query($insert_sql);
				$manufacturer_id = $retry_id;
				
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
		
		// CRITICAL: Verify manufacturer_id is valid before proceeding
		if ($manufacturer_id <= 0) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: manufacturer_id is invalid after insert: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			throw new Exception('Invalid manufacturer_id after insert: ' . $manufacturer_id);
		}
		
		// CRITICAL: Verify the insert actually succeeded by checking the database
		// MySQLi might return false but the insert could have partially succeeded
		$verify_insert = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id . " LIMIT 1");
		if (!$verify_insert || !$verify_insert->num_rows) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: Record not found after insert. manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			
			// Check if a record with ID 0 was created instead
			$check_zero_created = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0 LIMIT 1");
			if ($check_zero_created && $check_zero_created->num_rows > 0) {
				$log_file = DIR_LOGS . 'manufacturer_error.log';
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: A record with manufacturer_id = 0 was created instead!' . PHP_EOL, FILE_APPEND);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Zero record details: ' . print_r($check_zero_created->row, true) . PHP_EOL, FILE_APPEND);
				// Delete it
				$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
				throw new Exception('CRITICAL: Record with manufacturer_id = 0 was created instead of ' . $manufacturer_id . '. This indicates a database-level issue.');
			}
			
			// Check if maybe the insert failed but MySQL didn't report it correctly
			// Try to get the actual MySQL error
			$mysql_error = '';
			$mysql_errno = 0;
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
							$mysql_error = $link->error;
						}
						if (method_exists($link, 'errno')) {
							$mysql_errno = $link->errno;
						}
					}
				}
			} catch (Exception $e) {
				// Ignore
			}
			
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - MySQL error after failed verify: ' . $mysql_error . ' (Code: ' . $mysql_errno . ')' . PHP_EOL, FILE_APPEND);
			
			throw new Exception('Failed to insert manufacturer - record not found after insert (manufacturer_id: ' . $manufacturer_id . '). MySQL Error: ' . $mysql_error);
		}
		
		$log_file = DIR_LOGS . 'manufacturer_error.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ Main manufacturer insert verified successfully. manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
		
		// Update AUTO_INCREMENT to be higher than the inserted ID to keep it in sync
		if ($has_auto_increment) {
			$next_ai = $manufacturer_id + 1;
			$this->db->query("ALTER TABLE " . DB_PREFIX . "manufacturer AUTO_INCREMENT = " . $next_ai);
		}
		
		// CRITICAL: Final validation before related table inserts
		// Ensure manufacturer_id is still valid (not 0)
		if ($manufacturer_id <= 0) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: manufacturer_id became 0 before related table inserts!' . PHP_EOL, FILE_APPEND);
			throw new Exception('CRITICAL: manufacturer_id is 0 before related table inserts. Cannot proceed.');
		}
		
		// CRITICAL: Clear any MySQL errors from previous queries before proceeding
		// MySQLi can have "sticky" errors that show up on the next query
		try {
			$reflection = new ReflectionClass($this->db);
			$db_property = $reflection->getProperty('db');
			$db_property->setAccessible(true);
			$db_driver = $db_property->getValue($this->db);
			if (is_object($db_driver) && property_exists($db_driver, 'link')) {
				$link_reflection = new ReflectionProperty($db_driver, 'link');
				$link_reflection->setAccessible(true);
				$link = $link_reflection->getValue($db_driver);
				if (is_object($link) && method_exists($link, 'more_results')) {
					// Clear any pending results/errors
					while ($link->more_results() && $link->next_result()) {
						if ($result = $link->store_result()) {
							$result->free();
						}
					}
				}
			}
		} catch (Exception $e) {
			// Ignore reflection errors
		}
		
		// Double-check the manufacturer record exists before proceeding
		$final_verify = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id . " LIMIT 1");
		if (!$final_verify || !$final_verify->num_rows) {
			$log_file = DIR_LOGS . 'manufacturer_error.log';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: Manufacturer record not found before related table inserts! manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			throw new Exception('CRITICAL: Manufacturer record not found before related table inserts. manufacturer_id: ' . $manufacturer_id);
		}

		// CRITICAL: Delete any existing records for this manufacturer_id first (in case of retry or orphaned data)
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = " . (int)$manufacturer_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = " . (int)$manufacturer_id);

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
			// CRITICAL: Validate manufacturer_id before insert
			if ($manufacturer_id <= 0) {
				$log_file = DIR_LOGS . 'manufacturer_error.log';
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: manufacturer_id is 0 before url_alias insert!' . PHP_EOL, FILE_APPEND);
				throw new Exception('CRITICAL: manufacturer_id is 0 before url_alias insert. Cannot proceed.');
			}
			
			$keyword = trim($data['keyword']);
			// Check if keyword already exists
			$existing = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' LIMIT 1");
			if (!$existing->num_rows) {
				$log_file = DIR_LOGS . 'manufacturer_error.log';
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Inserting url_alias with manufacturer_id = ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
				$url_result = $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
				if (!$url_result) {
					$log_file = DIR_LOGS . 'manufacturer_error.log';
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: url_alias insert failed!' . PHP_EOL, FILE_APPEND);
					// Don't throw - url_alias is optional
				}
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
		$log_file = DIR_LOGS . 'manufacturer_error.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== editManufacturer() CALLED ==========' . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Data keys: ' . implode(', ', array_keys($data)) . PHP_EOL, FILE_APPEND);
		
		// CRITICAL: Clean up any ID 0 records before editing
		// These can cause "Duplicate entry '0'" errors
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = 0");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=0'");
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Cleaned up any manufacturer_id = 0 records' . PHP_EOL, FILE_APPEND);
		
		// Validate manufacturer_id
		$manufacturer_id = (int)$manufacturer_id;
		if ($manufacturer_id <= 0) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Invalid manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			throw new Exception('Invalid manufacturer ID: ' . $manufacturer_id);
		}

		// Verify manufacturer exists before updating
		$check_query = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id . " LIMIT 1");
		if (!$check_query->num_rows) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Manufacturer not found: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			throw new Exception('Manufacturer with ID ' . $manufacturer_id . ' does not exist');
		}

		// Validate required data
		if (!isset($data['name']) || empty(trim($data['name']))) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Manufacturer name is required' . PHP_EOL, FILE_APPEND);
			throw new Exception('Manufacturer name is required');
		}

		// CRITICAL: Decode HTML entities in image paths before escaping (same as addManufacturer)
		$image = isset($data['image']) ? html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8') : '';
		$thumb = isset($data['thumb']) ? html_entity_decode($data['thumb'], ENT_QUOTES, 'UTF-8') : '';
		
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Updating manufacturer with image length: ' . strlen($image) . ', thumb length: ' . strlen($thumb) . PHP_EOL, FILE_APPEND);
		
		$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', image = '" . $this->db->escape($image) . "', thumb = '" . $this->db->escape($thumb) . "', sort_order = " . (int)(isset($data['sort_order']) ? $data['sort_order'] : 0) . " WHERE manufacturer_id = " . (int)$manufacturer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = " . (int)$manufacturer_id);

		if (isset($data['manufacturer_description']) && is_array($data['manufacturer_description'])) {
			foreach ($data['manufacturer_description'] as $language_id => $value) {
				$language_id = (int)$language_id;
				if ($language_id > 0 && $manufacturer_id > 0) {
					// Check if exists first to avoid duplicate key errors
					$check_desc = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = " . (int)$manufacturer_id . " AND language_id = " . (int)$language_id . " LIMIT 1");
					if (!$check_desc || !$check_desc->num_rows) {
						$desc_result = $this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = " . (int)$manufacturer_id . ", language_id = " . (int)$language_id . ", description = '" . $this->db->escape(isset($value['description']) ? $value['description'] : '') . "', meta_title = '" . $this->db->escape(isset($value['meta_title']) ? $value['meta_title'] : '') . "', meta_description = '" . $this->db->escape(isset($value['meta_description']) ? $value['meta_description'] : '') . "', meta_keyword = '" . $this->db->escape(isset($value['meta_keyword']) ? $value['meta_keyword'] : '') . "'");
						if (!$desc_result) {
							file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: manufacturer_description insert failed for language_id: ' . $language_id . PHP_EOL, FILE_APPEND);
						}
					}
				}
			}
		}

		// CRITICAL: Validate manufacturer_id before inserting into related tables
		if ($manufacturer_id <= 0) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - CRITICAL: Invalid manufacturer_id (' . $manufacturer_id . ') before inserting into related tables.' . PHP_EOL, FILE_APPEND);
			throw new Exception('CRITICAL: Invalid manufacturer_id (' . $manufacturer_id . ') before inserting into related tables.');
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id);

		if (isset($data['manufacturer_store']) && is_array($data['manufacturer_store']) && !empty($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$store_id = (int)$store_id;
				if ($store_id >= 0) {
					// Check if exists first to avoid duplicate key errors
					$check_store = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id . " AND store_id = " . (int)$store_id . " LIMIT 1");
					if (!$check_store || !$check_store->num_rows) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = " . (int)$manufacturer_id . ", store_id = " . (int)$store_id);
					}
				}
			}
		} else {
			// Default to store 0 if no stores specified
			$check_default = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id . " AND store_id = 0 LIMIT 1");
			if (!$check_default || !$check_default->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = " . (int)$manufacturer_id . ", store_id = 0");
			}
		}

		// Handle SEO keyword (url_alias) - delete existing first, then insert new one
		// This ensures the keyword is always updated correctly
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deleted existing url_alias for manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);

		// Check if keyword is provided (even if empty string, we should handle it)
		if (isset($data['keyword'])) {
			$keyword = trim($data['keyword']);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Keyword provided: "' . $keyword . '" (length: ' . strlen($keyword) . ')' . PHP_EOL, FILE_APPEND);
			
			if (!empty($keyword)) {
				// Check if keyword already exists for a different manufacturer/product/category
				$existing = $this->db->query("SELECT url_alias_id, query FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "' LIMIT 1");
				if ($existing && $existing->num_rows) {
					$existing_query = isset($existing->row['query']) ? $existing->row['query'] : '';
					if ($existing_query != 'manufacturer_id=' . (int)$manufacturer_id) {
						// Keyword exists for different entity - delete it first to avoid conflicts
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Keyword exists for different entity (' . $existing_query . '), deleting it first' . PHP_EOL, FILE_APPEND);
						$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
				
				// Use REPLACE INTO to ensure the keyword is always saved, even if there's a duplicate
				// REPLACE INTO will delete the existing row if there's a duplicate on PRIMARY KEY or UNIQUE index, then insert
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Using REPLACE INTO for url_alias: query=manufacturer_id=' . $manufacturer_id . ', keyword=' . $keyword . PHP_EOL, FILE_APPEND);
				$url_result = $this->db->query("REPLACE INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
				
				if (!$url_result) {
					// Get MySQL error
					$error_msg = 'Unknown error';
					$error_code = 0;
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
									$error_msg = $link->error;
								}
								if (method_exists($link, 'errno')) {
									$error_code = $link->errno;
								}
							}
						}
					} catch (Exception $e) {
						// Ignore
					}
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: url_alias REPLACE failed! Error: ' . $error_msg . ' (Code: ' . $error_code . ')' . PHP_EOL, FILE_APPEND);
					// Try INSERT as fallback
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Trying INSERT as fallback...' . PHP_EOL, FILE_APPEND);
					$url_result = $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					if (!$url_result) {
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: url_alias INSERT also failed!' . PHP_EOL, FILE_APPEND);
						// Don't throw - url_alias is optional, but log the error
					} else {
						file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ url_alias INSERT succeeded (fallback)' . PHP_EOL, FILE_APPEND);
					}
				} else {
					file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ url_alias REPLACE succeeded' . PHP_EOL, FILE_APPEND);
				}
			} else {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Keyword is empty, not inserting url_alias' . PHP_EOL, FILE_APPEND);
			}
		} else {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - No keyword field in data array' . PHP_EOL, FILE_APPEND);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = " . (int)$manufacturer_id);

		if (isset($data['manufacturer_layout']) && is_array($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				$layout_id = (int)$layout_id;
				if ($layout_id > 0) {
					$store_id = (int)$store_id;
					// Check if exists first to avoid duplicate key errors
					$check_layout = $this->db->query("SELECT manufacturer_id FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = " . (int)$manufacturer_id . " AND store_id = " . (int)$store_id . " LIMIT 1");
					if (!$check_layout || !$check_layout->num_rows) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_layout SET manufacturer_id = " . (int)$manufacturer_id . ", store_id = " . (int)$store_id . ", layout_id = " . (int)$layout_id);
					}
				}
			}
		}

		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ editManufacturer() completed successfully for manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
		$this->cache->delete('manufacturer');
	}

	public function deleteManufacturer($manufacturer_id) {
		$log_file = DIR_LOGS . 'manufacturer_error.log';
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' ========== deleteManufacturer() CALLED ==========' . PHP_EOL, FILE_APPEND);
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
		
		// Validate manufacturer_id
		$manufacturer_id = (int)$manufacturer_id;
		if ($manufacturer_id <= 0) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Invalid manufacturer_id: ' . $manufacturer_id . PHP_EOL, FILE_APPEND);
			throw new Exception('Invalid manufacturer ID: ' . $manufacturer_id);
		}
		
		// Verify manufacturer exists before deleting
		$check_query = $this->db->query("SELECT manufacturer_id, name FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id . " LIMIT 1");
		if (!$check_query->num_rows) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Manufacturer not found: ' . $manufacturer_id . ' (may already be deleted)' . PHP_EOL, FILE_APPEND);
			// Don't throw error - just clean up related records if they exist
		} else {
			$manufacturer_name = isset($check_query->row['name']) ? $check_query->row['name'] : 'Unknown';
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deleting manufacturer: ' . $manufacturer_name . ' (ID: ' . $manufacturer_id . ')' . PHP_EOL, FILE_APPEND);
		}
		
		// CRITICAL: Unlink products from this manufacturer before deleting
		// This prevents foreign key constraint issues and allows deletion
		$product_check = $this->db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "product WHERE manufacturer_id = " . (int)$manufacturer_id);
		$product_count = $product_check->row['count'] ?? 0;
		if ($product_count > 0) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Found ' . $product_count . ' products linked to this manufacturer, unlinking them...' . PHP_EOL, FILE_APPEND);
			try {
				$this->db->query("UPDATE " . DB_PREFIX . "product SET manufacturer_id = 0 WHERE manufacturer_id = " . (int)$manufacturer_id);
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ Unlinked ' . $product_count . ' products from manufacturer' . PHP_EOL, FILE_APPEND);
			} catch (Exception $e) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Error unlinking products: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
				// Continue anyway - try to delete manufacturer
			}
		}
		
		// Delete in order: related tables first, then main table
		// This prevents foreign key constraint issues
		try {
			// Delete url_alias first
			$url_result = $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'manufacturer_id=" . (int)$manufacturer_id . "'");
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deleted url_alias records' . PHP_EOL, FILE_APPEND);
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Error deleting url_alias: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
		}
		
		try {
			// Delete manufacturer_to_layout
			$layout_result = $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_layout WHERE manufacturer_id = " . (int)$manufacturer_id);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deleted manufacturer_to_layout records' . PHP_EOL, FILE_APPEND);
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Error deleting manufacturer_to_layout: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
		}
		
		try {
			// Delete manufacturer_to_store
			$store_result = $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE manufacturer_id = " . (int)$manufacturer_id);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deleted manufacturer_to_store records' . PHP_EOL, FILE_APPEND);
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Error deleting manufacturer_to_store: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
		}
		
		try {
			// Delete manufacturer_description
			$desc_result = $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_description WHERE manufacturer_id = " . (int)$manufacturer_id);
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Deleted manufacturer_description records' . PHP_EOL, FILE_APPEND);
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - WARNING: Error deleting manufacturer_description: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
		}
		
		try {
			// Finally delete the main manufacturer record
			$main_result = $this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = " . (int)$manufacturer_id);
			if ($main_result) {
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ Main manufacturer record deleted successfully' . PHP_EOL, FILE_APPEND);
			} else {
				// Get MySQL error
				$error_msg = 'Unknown error';
				$error_code = 0;
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
								$error_msg = $link->error;
							}
							if (method_exists($link, 'errno')) {
								$error_code = $link->errno;
							}
						}
					}
				} catch (Exception $e) {
					// Ignore
				}
				file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Failed to delete main manufacturer record! Error: ' . $error_msg . ' (Code: ' . $error_code . ')' . PHP_EOL, FILE_APPEND);
				throw new Exception('Failed to delete manufacturer: ' . $error_msg);
			}
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ERROR: Exception deleting main manufacturer record: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
			throw $e;
		}

		$this->cache->delete('manufacturer');
		file_put_contents($log_file, date('Y-m-d H:i:s') . ' - ✓ deleteManufacturer() completed successfully' . PHP_EOL, FILE_APPEND);
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


