<?php
class ModelCatalogNavigation extends Model {
	public function addNavigation($data) {
		$this->event->trigger('pre.admin.navigation.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "navigation SET parent_id = '" . (int)$data['parent_id'] . "', url = '" . $this->db->escape($data['url'])  . "',  `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$navigation_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "navigation SET image = '" . $this->db->escape($data['image']) . "' WHERE navigation_id = '" . (int)$navigation_id . "'");
		}


		foreach ($data['navigation_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "navigation_description SET navigation_id = '" . (int)$navigation_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', blurb = '" . $this->db->escape($value['blurb']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "navigation_path` SET `navigation_id` = '" . (int)$navigation_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "navigation_path` SET `navigation_id` = '" . (int)$navigation_id . "', `path_id` = '" . (int)$navigation_id . "', `level` = '" . (int)$level . "'");


		$this->cache->delete('navigation');

		$this->event->trigger('post.admin.navigation.add', $navigation_id);

		return $navigation_id;
	}

	public function editNavigation($navigation_id, $data) {
		$this->event->trigger('pre.admin.navigation.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "navigation SET parent_id = '" . (int)$data['parent_id'] . "',  url = '" . $this->db->escape($data['url']) . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "',  sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE navigation_id = '" . (int)$navigation_id . "'");


		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "navigation SET image = '" . $this->db->escape($data['image']) . "' WHERE navigation_id = '" . (int)$navigation_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "navigation_description WHERE navigation_id = '" . (int)$navigation_id . "'");

		foreach ($data['navigation_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "navigation_description SET navigation_id = '" . (int)$navigation_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', blurb = '" . $this->db->escape($value['blurb']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "navigation_path` WHERE path_id = '" . (int)$navigation_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $navigation_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$navigation_path['navigation_id'] . "' AND level < '" . (int)$navigation_path['level'] . "'");

				$path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$navigation_path['navigation_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "navigation_path` SET navigation_id = '" . (int)$navigation_path['navigation_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$navigation_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "navigation_path` SET navigation_id = '" . (int)$navigation_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "navigation_path` SET navigation_id = '" . (int)$navigation_id . "', `path_id` = '" . (int)$navigation_id . "', level = '" . (int)$level . "'");
		}

        
		$this->cache->delete('navigation');

		$this->event->trigger('post.admin.navigation.edit', $navigation_id);
	}

	public function deleteNavigation($navigation_id) {
		$this->event->trigger('pre.admin.navigation.delete', $navigation_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "navigation_path WHERE navigation_id = '" . (int)$navigation_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "navigation_path WHERE path_id = '" . (int)$navigation_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteNavigation($result['navigation_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "navigation WHERE navigation_id = '" . (int)$navigation_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "navigation_description WHERE navigation_id = '" . (int)$navigation_id . "'");

		$this->cache->delete('navigation');

		$this->event->trigger('post.admin.navigation.delete', $navigation_id);
	}

	public function repairNavigations($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "navigation WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $navigation) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$navigation['navigation_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "navigation_path` WHERE navigation_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "navigation_path` SET navigation_id = '" . (int)$navigation['navigation_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "navigation_path` SET navigation_id = '" . (int)$navigation['navigation_id'] . "', `path_id` = '" . (int)$navigation['navigation_id'] . "', level = '" . (int)$level . "'");

			$this->repairNavigations($navigation['navigation_id']);
		}
	}

	public function getNavigation($navigation_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(nd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "navigation_path np LEFT JOIN " . DB_PREFIX . "navigation_description nd1 ON (np.path_id = nd1.navigation_id AND np.navigation_id != np.path_id) WHERE np.navigation_id = n.navigation_id AND nd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY np.navigation_id) AS path FROM " . DB_PREFIX . "navigation n LEFT JOIN " . DB_PREFIX . "navigation_description nd2 ON (n.navigation_id = nd2.navigation_id) WHERE n.navigation_id = '" . (int)$navigation_id . "' AND nd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}

	public function getNavigations($data = array()) {
		$sql = "SELECT np.navigation_id AS navigation_id, GROUP_CONCAT(nd1.name ORDER BY np.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name,n1.url, n1.parent_id, n1.sort_order FROM " . DB_PREFIX . "navigation_path np LEFT JOIN " . DB_PREFIX . "navigation n1 ON (np.navigation_id = n1.navigation_id) LEFT JOIN " . DB_PREFIX . "navigation n2 ON (np.path_id = n2.navigation_id) LEFT JOIN " . DB_PREFIX . "navigation_description nd1 ON (np.path_id = nd1.navigation_id) LEFT JOIN " . DB_PREFIX . "navigation_description nd2 ON (np.navigation_id = nd2.navigation_id) WHERE nd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND nd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND nd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY np.navigation_id";

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

	public function getNavigationDescriptions($navigation_id) {
		$navigation_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "navigation_description WHERE navigation_id = '" . (int)$navigation_id . "'");

		foreach ($query->rows as $result) {
			$navigation_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'blurb'             => $result['blurb'],
			);
		}

		return $navigation_description_data;
	}

	public function getTotalNavigations($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "navigation");

		return $query->row['total'];
	}

}
