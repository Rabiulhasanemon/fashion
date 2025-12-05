<?php
class ModelCatalogFilter extends Model {
	public function addFilter($data) {
		$log_file = DIR_LOGS . 'filter_debug.log';
		$log_message = date('Y-m-d H:i:s') . " - [FILTER ADD] Starting\n";
		$log_message .= "POST Data: " . print_r($data, true) . "\n";
		file_put_contents($log_file, $log_message, FILE_APPEND);

		try {
			$this->event->trigger('pre.admin.filter.add', $data);
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Event trigger error: " . $e->getMessage() . "\n", FILE_APPEND);
		}

		// Validate required data
		if (empty($data['label'])) {
			$error_msg = "Filter Add Error: Label is required";
			error_log($error_msg);
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] " . $error_msg . "\n", FILE_APPEND);
			return false;
		}

		if (empty($data['filter_group_description']) || !is_array($data['filter_group_description'])) {
			$error_msg = "Filter Add Error: Filter group description is required";
			error_log($error_msg);
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] " . $error_msg . "\n", FILE_APPEND);
			return false;
		}

		// Set default sort_order if not provided
		if (!isset($data['sort_order']) || $data['sort_order'] === '') {
			$data['sort_order'] = 0;
		}

		try {
			$sql = "INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order']. "', label = '" . $this->db->escape($data['label']) . "'";
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Executing SQL: " . $sql . "\n", FILE_APPEND);
			
			$this->db->query($sql);

			$filter_group_id = $this->db->getLastId();
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Got filter_group_id: " . $filter_group_id . "\n", FILE_APPEND);

			if (!$filter_group_id) {
				$error_msg = "Filter Add Error: Failed to get filter_group_id";
				error_log($error_msg);
				file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] " . $error_msg . "\n", FILE_APPEND);
				return false;
			}
		} catch (Exception $e) {
			$error_msg = "Filter Add Error (DB): " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine();
			error_log($error_msg);
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] " . $error_msg . "\n", FILE_APPEND);
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Stack trace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
			return false;
		}

		try {
			foreach ($data['filter_group_description'] as $language_id => $value) {
				if (empty($value['name'])) {
					file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Warning: Filter group name is empty for language_id: " . $language_id . "\n", FILE_APPEND);
					continue;
				}
				$sql = "INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'";
				file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Inserting description for language_id $language_id\n", FILE_APPEND);
				$this->db->query($sql);
			}
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Successfully inserted filter group descriptions\n", FILE_APPEND);
		} catch (Exception $e) {
			$error_msg = "Filter Add Error (Description): " . $e->getMessage() . " | File: " . $e->getFile() . " | Line: " . $e->getLine();
			error_log($error_msg);
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] " . $error_msg . "\n", FILE_APPEND);
			return false;
		}

		if (isset($data['filter']) && is_array($data['filter'])) {
			try {
				foreach ($data['filter'] as $filter) {
					if (empty($filter['sort_order']) || $filter['sort_order'] === '') {
						$filter['sort_order'] = 0;
					}

					$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");

					$filter_id = $this->db->getLastId();

					if (!$filter_id) {
						error_log("Filter Add Error: Failed to get filter_id");
						continue;
					}

					if (isset($filter['filter_description']) && is_array($filter['filter_description'])) {
						foreach ($filter['filter_description'] as $language_id => $filter_description) {
							if (empty($filter_description['name'])) {
								continue;
							}
							$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
						}
					}
				}
			} catch (Exception $e) {
				error_log("Filter Add Error (Filter Items): " . $e->getMessage());
			}
		}

        if (isset($data['group_filter_profile']) && is_array($data['group_filter_profile'])) {
            try {
				foreach ($data['group_filter_profile'] as $filter_profile_id) {
					if (empty($filter_profile_id)) {
						continue;
					}
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_to_profile SET filter_group_id = '" . (int)$filter_group_id . "', filter_profile_id = '" . (int)$filter_profile_id . "'");
				}
			} catch (Exception $e) {
				error_log("Filter Add Error (Profile): " . $e->getMessage());
			}
        }

		try {
			$this->event->trigger('post.admin.filter.add', $filter_group_id);
		} catch (Exception $e) {
			file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Event trigger error (post): " . $e->getMessage() . "\n", FILE_APPEND);
		}

		file_put_contents($log_file, date('Y-m-d H:i:s') . " - [FILTER ADD] Successfully completed. Filter Group ID: " . $filter_group_id . "\n", FILE_APPEND);
		return $filter_group_id;
	}

	public function editFilter($filter_group_id, $data) {
		$this->event->trigger('pre.admin.filter.edit', $data);

		// Validate required data
		if (empty($data['label'])) {
			error_log("Filter Edit Error: Label is required");
			return false;
		}

		if (empty($data['filter_group_description']) || !is_array($data['filter_group_description'])) {
			error_log("Filter Edit Error: Filter group description is required");
			return false;
		}

		// Set default sort_order if not provided
		if (!isset($data['sort_order']) || $data['sort_order'] === '') {
			$data['sort_order'] = 0;
		}

		try {
			$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "', label = '" . $this->db->escape($data['label']) . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		} catch (Exception $e) {
			error_log("Filter Edit Error (DB): " . $e->getMessage());
			return false;
		}

		try {
			$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

			foreach ($data['filter_group_description'] as $language_id => $value) {
				if (empty($value['name'])) {
					error_log("Filter Edit Error: Filter group name is required for language_id: " . $language_id);
					continue;
				}
				$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
			}
		} catch (Exception $e) {
			error_log("Filter Edit Error (Description): " . $e->getMessage());
			return false;
		}

		try {
			$this->db->query("DELETE FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_to_profile WHERE filter_group_id = '" . (int)$filter_group_id . "'");
			$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		} catch (Exception $e) {
			error_log("Filter Edit Error (Delete): " . $e->getMessage());
			return false;
		}

		if (isset($data['filter']) && is_array($data['filter'])) {
			try {
				foreach ($data['filter'] as $filter) {
					if (empty($filter['sort_order']) || $filter['sort_order'] === '') {
						$filter['sort_order'] = 0;
					}

					if (!empty($filter['filter_id'])) {
						// Use existing filter_id
						$filter_id = (int)$filter['filter_id'];
						$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_id = '" . $filter_id . "', filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
					} else {
						// Create new filter
						$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
						$filter_id = $this->db->getLastId();
					}

					if (!$filter_id) {
						error_log("Filter Edit Error: Failed to get filter_id");
						continue;
					}

					if (isset($filter['filter_description']) && is_array($filter['filter_description'])) {
						foreach ($filter['filter_description'] as $language_id => $filter_description) {
							if (empty($filter_description['name'])) {
								continue;
							}
							$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
						}
					}
				}
			} catch (Exception $e) {
				error_log("Filter Edit Error (Filter Items): " . $e->getMessage());
			}
		}

        if (isset($data['group_filter_profile']) && is_array($data['group_filter_profile'])) {
            try {
				foreach ($data['group_filter_profile'] as $filter_profile_id) {
					if (empty($filter_profile_id)) {
						continue;
					}
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_to_profile SET filter_group_id = '" . (int)$filter_group_id . "', filter_profile_id = '" . (int)$filter_profile_id . "'");
				}
			} catch (Exception $e) {
				error_log("Filter Edit Error (Profile): " . $e->getMessage());
			}
        }

		$this->event->trigger('post.admin.filter.edit', $filter_group_id);
	}

	public function deleteFilter($filter_group_id) {
		$this->event->trigger('pre.admin.filter.delete', $filter_group_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		$this->event->trigger('post.admin.filter.delete', $filter_group_id);
	}

	public function getFilterGroup($filter_group_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` fg LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fg.filter_group_id = '" . (int)$filter_group_id . "' AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getFilterGroups($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "filter_group` fg LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'fgd.name',
			'fg.sort_order'
		);

        if (!empty($data['filter_name'])) {
            $sql .= " AND (fgd.name LIKE '" . $this->db->escape($data['filter_name']) . "%' OR fg.label LIKE '" . $this->db->escape($data['filter_name']) . "%')";
        }

        if (!empty($data['filter_profile_id'])) {
            $sql .= " AND fg.filter_group_id in (select filter_group_id from " . DB_PREFIX ."filter_group_to_profile where filter_profile_id = '" . (int) $data['filter_profile_id'] . "')";
        }

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY fgd.name";
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

	public function getFilterGroupDescriptions($filter_group_id) {
		$filter_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($query->rows as $result) {
			$filter_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $filter_group_data;
	}

	public function getFilter($filter_id) {
		$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id = '" . (int)$filter_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getFilters($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "filter_group_description fgd WHERE f.filter_group_id = fgd.filter_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " ORDER BY f.sort_order ASC";

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

	public function getFilterDescriptions($filter_group_id) {
		$filter_data = array();

		$filter_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($filter_query->rows as $filter) {
			$filter_description_data = array();

			$filter_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE filter_id = '" . (int)$filter['filter_id'] . "'");

			foreach ($filter_description_query->rows as $filter_description) {
				$filter_description_data[$filter_description['language_id']] = array('name' => $filter_description['name']);
			}

			$filter_data[] = array(
				'filter_id'          => $filter['filter_id'],
				'filter_description' => $filter_description_data,
				'sort_order'         => $filter['sort_order']
			);
		}

		return $filter_data;
	}

    public function getFilterGroupByLabel($name) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "filter_group r WHERE r.label = '" . $this->db->escape($name) . "'");
        return $query->row;
    }

	public function getTotalFilterGroups($data = array()) {
        $sql = "SELECT  COUNT(*) AS total FROM `" . DB_PREFIX . "filter_group` fg LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        if (!empty($data['filter_name'])) {
            $sql .= " AND (fgd.name LIKE '" . $this->db->escape($data['filter_name']) . "%' OR fg.label LIKE '" . $this->db->escape($data['filter_name']) . "%')";
        }

        if (!empty($data['filter_profile_id'])) {
            $sql .= " AND fg.filter_group_id in (select filter_group_id from " . DB_PREFIX ."filter_group_to_profile where filter_profile_id = '" . (int) $data['filter_profile_id'] . "')";
        }
        $query = $this->db->query($sql);
        return $query->row['total'];
	}

    public function getFiltersByProfiles($filter_profile_ids) {
        $sql = "SELECT * FROM  ". DB_PREFIX . "filter f WHERE f.filter_group_id in (SELECT filter_group_id FROM ". DB_PREFIX ."filter_group_to_profile ag WHERE ag.filter_profile_id in (" . implode(',', $filter_profile_ids) .")) ORDER BY f.sort_order";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getGroupFilterProfiles($filter_group_id) {
        $group_filter_profile_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_to_profile WHERE filter_group_id = '" . (int)$filter_group_id . "'");

        foreach ($query->rows as $result) {
            $group_filter_profile_data[] = $result['filter_profile_id'];
        }

        return $group_filter_profile_data;
    }
}
