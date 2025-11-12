<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->event->trigger('pre.admin.category.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', `filter_profile_id` = '" . (int)$data['filter_profile_id'] . "', view = '" . $this->db->escape($data['view']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$category_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		if (isset($data['thumb'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET thumb = '" . $this->db->escape($data['thumb']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		if (isset($data['icon'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET icon = '" . $this->db->escape($data['icon']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', blurb = '" . $this->db->escape($value['blurb']) . "', intro = '" . $this->db->escape($value['intro']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword']) && !empty($data['keyword'])) {
			// First, delete any existing entry for this category
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
			
			// Delete any entry with the same keyword to prevent conflicts
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($data['keyword']) . "'");
			
			// Use REPLACE INTO which will automatically handle any duplicates
			// REPLACE INTO deletes the existing row if there's a duplicate on PRIMARY KEY or UNIQUE index, then inserts
			$this->db->query("REPLACE INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		// Save category modules
		error_log('addCategory - Checking for category_module in data');
		error_log('addCategory - Data keys: ' . implode(', ', array_keys($data)));
		if (isset($data['category_module'])) {
			error_log('addCategory - category_module found, calling saveCategoryModules');
			$this->saveCategoryModules($category_id, $data['category_module']);
		} else {
			error_log('addCategory - category_module NOT found in data');
		}

		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.add', $category_id);

		return $category_id;
	}

	public function editCategory($category_id, $data) {
		$this->event->trigger('pre.admin.category.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', `filter_profile_id` = '" . (int)$data['filter_profile_id'] . "', view = '" . $this->db->escape($data['view']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['icon'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET icon = '" . $this->db->escape($data['icon']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		if (isset($data['thumb'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET thumb = '" . $this->db->escape($data['thumb']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', blurb = '" . $this->db->escape($value['blurb']) . "', intro = '" . $this->db->escape($value['intro']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		if (isset($data['keyword']) && !empty($data['keyword'])) {
			// Delete any existing entry with this keyword (if used by another item)
			$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($data['keyword']) . "' AND query != 'category_id=" . (int)$category_id . "'");
			
			// Use REPLACE to handle any duplicates gracefully
			$this->db->query("REPLACE INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		// Save category modules
		error_log('editCategory - Checking for category_module in data');
		error_log('editCategory - Data keys: ' . implode(', ', array_keys($data)));
		if (isset($data['category_module'])) {
			error_log('editCategory - category_module found, calling saveCategoryModules');
			$this->saveCategoryModules($category_id, $data['category_module']);
		} else {
			error_log('editCategory - category_module NOT found in data');
		}

		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.edit', $category_id);
	}

	public function deleteCategory($category_id) {
		$this->event->trigger('pre.admin.category.delete', $category_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int)$category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_path WHERE path_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_manufacturer_description WHERE category_manufacturer_id in (select category_manufacturer_id from " . DB_PREFIX."category_manufacturer cm where cm.category_id = '" . (int)$category_id. "')");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_manufacturer WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");

		$this->cache->delete('category');

		$this->event->trigger('post.admin.category.delete', $category_id);
	}

	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' > ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' > ') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if (!isset($data['start']) || $data['start'] < 0) {
				$data['start'] = 0;
			}

			if (!isset($data['limit']) || $data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name' => $result['name'],
				'blurb' => $result['blurb'],
				'intro' => $result['intro'],
				'meta_title' => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword' => $result['meta_keyword'],
				'description' => $result['description']
			);
		}

		return $category_description_data;
	}

	public function getCategoryFilters($category_id) {
		$category_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_filter WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_filter_data[] = $result['filter_id'];
		}

		return $category_filter_data;
	}

	public function getCategoryStores($category_id) {
		$category_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_store_data[] = $result['store_id'];
		}

		return $category_store_data;
	}

	public function getCategoryLayouts($category_id) {
		$category_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $category_layout_data;
	}

	public function getTotalCategories($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	// Category Modules Methods (for admin)
	public function getCategoryModules($category_id) {
		$category_module_data = array();

		// Check if table exists first
		$table_check = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "category_module'");
		if (!$table_check->num_rows) {
			return $category_module_data; // Return empty array if table doesn't exist
		}

		try {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$category_id . "' ORDER BY sort_order ASC");

			foreach ($query->rows as $result) {
				$setting = json_decode($result['setting'], true);
				if (json_last_error() !== JSON_ERROR_NONE) {
					$setting = array();
				}

				$category_module_data[] = array(
					'category_module_id' => $result['category_module_id'],
					'module_id' => isset($result['module_id']) ? $result['module_id'] : 0,
					'code' => $result['code'],
					'setting' => $setting,
					'description' => isset($result['description']) ? $result['description'] : '',
					'sort_order' => isset($result['sort_order']) ? $result['sort_order'] : 0,
					'status' => isset($result['status']) ? $result['status'] : 1
				);
			}
		} catch (Exception $e) {
			// Return empty array on error
			error_log('Error loading category modules: ' . $e->getMessage());
			return $category_module_data;
		}

		return $category_module_data;
	}

	public function saveCategoryModules($category_id, $modules) {
		// Check if table exists first
		$table_check = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "category_module'");
		if (!$table_check->num_rows) {
			error_log('Category module table does not exist');
			return; // Exit if table doesn't exist
		}

		try {
			// Delete existing modules for this category
			$this->db->query("DELETE FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$category_id . "'");

			error_log('saveCategoryModules called for category_id: ' . $category_id);
			error_log('Modules data received: ' . print_r($modules, true));

			if (isset($modules) && is_array($modules) && !empty($modules)) {
				$saved_count = 0;
				foreach ($modules as $index => $module) {
					// Skip empty modules (no code selected)
					if (!isset($module['code']) || empty(trim($module['code']))) {
						error_log('Skipping module at index ' . $index . ' - no code: ' . print_r($module, true));
						continue;
					}
					
					error_log('Processing module at index ' . $index . ': code=' . $module['code']);
					
					$module_id = isset($module['module_id']) ? (int)$module['module_id'] : 0;
					$code = $this->db->escape(trim($module['code']));
					
					// Handle settings - can be array or JSON string (keep for backward compatibility)
					$setting_data = array();
					if (isset($module['setting'])) {
						if (is_array($module['setting'])) {
							$setting_data = $module['setting'];
						} elseif (is_string($module['setting']) && !empty(trim($module['setting']))) {
							$decoded = json_decode(trim($module['setting']), true);
							if (json_last_error() === JSON_ERROR_NONE) {
								$setting_data = $decoded;
							} else {
								// If JSON decode fails, try to use as-is (might be malformed JSON)
								error_log('Warning: Invalid JSON in module setting for code: ' . $code);
							}
						}
					}
					$setting = $this->db->escape(json_encode($setting_data));
					
					// Handle description
					$description = isset($module['description']) ? $this->db->escape(trim($module['description'])) : '';
					
					$sort_order = isset($module['sort_order']) ? (int)$module['sort_order'] : 0;
					$status = isset($module['status']) ? (int)$module['status'] : 1;

					$this->db->query("INSERT INTO " . DB_PREFIX . "category_module SET category_id = '" . (int)$category_id . "', module_id = '" . $module_id . "', code = '" . $code . "', setting = '" . $setting . "', description = '" . $description . "', sort_order = '" . $sort_order . "', status = '" . $status . "'");
					
					$saved_count++;
				}
				error_log('Saved ' . $saved_count . ' category modules for category_id: ' . $category_id);
			} else {
				error_log('No modules array provided or modules is not an array');
			}
		} catch (Exception $e) {
			error_log('Error saving category modules: ' . $e->getMessage());
			error_log('Stack trace: ' . $e->getTraceAsString());
			// Don't throw exception - allow category to save even if modules fail
		}
	}
}
