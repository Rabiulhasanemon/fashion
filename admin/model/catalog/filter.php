<?php
class ModelCatalogFilter extends Model {
	public function addFilter($data) {
		$this->event->trigger('pre.admin.filter.add', $data);

		try {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order']. "', label = '" . $this->db->escape($data['label']) . "'");

			$filter_group_id = $this->db->getLastId();
		} catch (Exception $e) {
			error_log("Filter Add Error: " . $e->getMessage());
			return false;
		}

		foreach ($data['filter_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['filter'])) {
			foreach ($data['filter'] as $filter) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");

				$filter_id = $this->db->getLastId();

				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
				}
			}
		}

        if (isset($data['group_filter_profile'])) {
            foreach ($data['group_filter_profile'] as $filter_profile_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_to_profile SET filter_group_id = '" . (int)$filter_group_id . "', filter_profile_id = '" . (int)$filter_profile_id . "'");
            }
        }

		$this->event->trigger('post.admin.filter.add', $filter_group_id);

		return $filter_group_id;
	}

	public function editFilter($filter_group_id, $data) {
		$this->event->trigger('pre.admin.filter.edit', $data);

		try {
			$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET sort_order = '" . (int)$data['sort_order'] . "', label = '" . $this->db->escape($data['label']) . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		} catch (Exception $e) {
			error_log("Filter Edit Error: " . $e->getMessage());
			return false;
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		foreach ($data['filter_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "filter WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_to_profile WHERE filter_group_id = '" . (int)$filter_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_group_id = '" . (int)$filter_group_id . "'");

		if (isset($data['filter'])) {
			foreach ($data['filter'] as $filter) {
				if (!empty($filter['filter_id'])) {
					// Use existing filter_id
					$filter_id = (int)$filter['filter_id'];
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_id = '" . $filter_id . "', filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
				} else {
					// Create new filter
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter SET filter_group_id = '" . (int)$filter_group_id . "', sort_order = '" . (int)$filter['sort_order'] . "'");
					$filter_id = $this->db->getLastId();
				}

				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter_group_id . "', name = '" . $this->db->escape($filter_description['name']) . "'");
				}
			}
		}

        if (isset($data['group_filter_profile'])) {
            foreach ($data['group_filter_profile'] as $filter_profile_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_to_profile SET filter_group_id = '" . (int)$filter_group_id . "', filter_profile_id = '" . (int)$filter_profile_id . "'");
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
