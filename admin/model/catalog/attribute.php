<?php
class ModelCatalogAttribute extends Model {
	public function addAttribute($data) {
		$this->event->trigger('pre.admin.attribute.add', $data);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET sort_order = '" . (int)$data['sort_order'] . "', attribute_profile_id = '" . (int)$data['attribute_profile_id'] . "'");

		$attribute_group_id = $this->db->getLastId();

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['attribute'])) {
			foreach ($data['attribute'] as $attribute) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$attribute_group_id . "', sort_order = '" . (int)$attribute['sort_order'] . "'");

				$attribute_id = $this->db->getLastId();

				foreach ($attribute['attribute_description'] as $language_id => $attribute_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', attribute_group_id = '" . (int)$attribute_group_id . "', name = '" . $this->db->escape($attribute_description['name']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.attribute.add', $attribute_group_id);

		return $attribute_group_id;
	}

	public function editAttribute($attribute_group_id, $data) {
		$this->event->trigger('pre.admin.attribute.edit', $data);

		$this->db->query("UPDATE `" . DB_PREFIX . "attribute_group` SET sort_order = '" . (int)$data['sort_order'] . "', attribute_profile_id = '" . (int)$data['attribute_profile_id'] . "' WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		if (isset($data['attribute'])) {
			foreach ($data['attribute'] as $attribute) {
				if ($attribute['attribute_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_id = '" . (int)$attribute['attribute_id'] . "', attribute_group_id = '" . (int)$attribute_group_id . "', sort_order = '" . (int)$attribute['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$attribute_group_id . "', sort_order = '" . (int)$attribute['sort_order'] . "'");
				}

				$attribute_id = $this->db->getLastId();

				foreach ($attribute['attribute_description'] as $language_id => $attribute_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', attribute_group_id = '" . (int)$attribute_group_id . "', name = '" . $this->db->escape($attribute_description['name']) . "'");
				}
			}
		}

		$this->event->trigger('post.admin.attribute.edit', $attribute_group_id);
	}

	public function deleteAttribute($attribute_group_id) {
		$this->event->trigger('pre.admin.attribute.delete', $attribute_group_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group` WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group_description` WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute` WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_description` WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		$this->event->trigger('post.admin.attribute.delete', $attribute_group_id);
	}

	public function getAttributeGroup($attribute_group_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group` fg LEFT JOIN " . DB_PREFIX . "attribute_group_description fgd ON (fg.attribute_group_id = fgd.attribute_group_id) WHERE fg.attribute_group_id = '" . (int)$attribute_group_id . "' AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getAttributeGroups($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "attribute_group` fg LEFT JOIN " . DB_PREFIX . "attribute_group_description fgd ON (fg.attribute_group_id = fgd.attribute_group_id) WHERE fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'fgd.name',
			'fg.sort_order'
		);

        if (!empty($data['filter_name'])) {
            $sql .= " AND fgd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_profile_id'])) {
            $sql .= " AND fg.attribute_profile_id = '" . (int)$data['filter_profile_id'] . "'";
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

	public function getAttributeGroupDescriptions($attribute_group_id) {
		$attribute_group_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		foreach ($query->rows as $result) {
			$attribute_group_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $attribute_group_data;
	}

	public function getAttribute($attribute_id) {
		$query = $this->db->query("SELECT *, (SELECT name FROM " . DB_PREFIX . "attribute_group_description fgd WHERE f.attribute_group_id = fgd.attribute_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "attribute f LEFT JOIN " . DB_PREFIX . "attribute_description fd ON (f.attribute_id = fd.attribute_id) WHERE f.attribute_id = '" . (int)$attribute_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getAttributes($data) {
		$sql = "SELECT *, (SELECT name FROM " . DB_PREFIX . "attribute_group_description fgd WHERE f.attribute_group_id = fgd.attribute_group_id AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM " . DB_PREFIX . "attribute f LEFT JOIN " . DB_PREFIX . "attribute_description fd ON (f.attribute_id = fd.attribute_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['attribute_name'])) {
			$sql .= " AND fd.name LIKE '" . $this->db->escape($data['attribute_name']) . "%'";
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

	public function getAttributeDescriptions($attribute_group_id) {
		$attribute_data = array();

		$attribute_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");

		foreach ($attribute_query->rows as $attribute) {
			$attribute_description_data = array();

			$attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE attribute_id = '" . (int)$attribute['attribute_id'] . "'");

			foreach ($attribute_description_query->rows as $attribute_description) {
				$attribute_description_data[$attribute_description['language_id']] = array('name' => $attribute_description['name']);
			}

			$attribute_data[] = array(
				'attribute_id'          => $attribute['attribute_id'],
				'attribute_description' => $attribute_description_data,
				'sort_order'         => $attribute['sort_order']
			);
		}

		return $attribute_data;
	}

	public function getTotalAttributeGroups($data= array()) {
        $sql = "SELECT  COUNT(*) AS total FROM `" . DB_PREFIX . "attribute_group` fg LEFT JOIN " . DB_PREFIX . "attribute_group_description fgd ON (fg.attribute_group_id = fgd.attribute_group_id) WHERE fgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        if (!empty($data['filter_name'])) {
            $sql .= " AND fgd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_profile_id'])) {
            $sql .= " AND fg.attribute_profile_id = '" . (int)$data['filter_profile_id'] . "'";
        }
        $query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getAttributesByProfile($attribute_profile_id) {
        $sql = "SELECT * FROM  ". DB_PREFIX . "attribute a WHERE a.attribute_group_id in (SELECT attribute_group_id FROM ". DB_PREFIX ."attribute_group ag WHERE ag.attribute_profile_id ='" . (int) $attribute_profile_id ."') ORDER BY a.sort_order";
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function copyAttribute($attribute_group_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "attribute_group ag LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE ag.attribute_group_id = '" . (int)$attribute_group_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        if ($query->num_rows) {
            $data = $query->row;
            $data['attribute_profile_id'] = 0;
            $data['attribute_group_description'] = array();
            $group_descriptions = $this->getAttributeGroupDescriptions($attribute_group_id);
            foreach ($group_descriptions as $language_id => $group_description) {
                $group_description['name'] = $group_description['name'] . " - Copy";
                $data['attribute_group_description'][$language_id] = $group_description;
            }
            $data['attribute'] = $this->getAttributeDescriptions($attribute_group_id);
            $this->addAttribute($data);
        }
    }
}
