<?php
class ModelSettingComponent extends Model {
	public function addComponent($data) {
		$this->event->trigger('pre.admin.component.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "component SET name = '" . $data['name']."', depends_on = '" . (int)$data['depends_on'] . "', `filter_profile_id` = '" . (int)$data['filter_profile_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', is_required = '" . (int)$data['is_required'] . "', date_added = NOW()");

		$component_id = $this->db->getLastId();

		if (isset($data['component_category'])) {
			foreach ($data['component_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "component_category SET component_id = '" . (int)$component_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
        if (isset($data['component_excluded_product'])) {
            foreach ($data['component_excluded_product'] as $excluded_product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "component_excluded_product SET component_id = '" . (int)$component_id . "', excluded_product_id = '" . (int)$excluded_product_id . "'");
            }
        }

        if (isset($data['thumb'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "component SET thumb = '" . $this->db->escape($data['thumb']) . "' WHERE component_id = '" . (int)$component_id . "'");
        }
		return $component_id;
	}

	public function editComponent($component_id, $data) {
		$this->event->trigger('pre.admin.component.edit', $data);
        $this->db->query("UPDATE " . DB_PREFIX . "component SET name = '" . $data['name']."', depends_on = '" . (int)$data['depends_on'] . "', `filter_profile_id` = '" . (int)$data['filter_profile_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', is_required = '" . (int)$data['is_required'] . "', date_added = NOW() WHERE component_id= '" . (int) $component_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "component_category WHERE component_id = '" . (int)$component_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "component_excluded_product WHERE component_id = '" . (int)$component_id . "'");

		if (isset($data['component_category'])) {
			foreach ($data['component_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "component_category SET component_id = '" . (int)$component_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
        if (isset($data['component_excluded_product'])) {
            foreach ($data['component_excluded_product'] as $excluded_product_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "component_excluded_product SET component_id = '" . (int)$component_id . "', excluded_product_id = '" . (int)$excluded_product_id . "'");
            }
        }

        if (isset($data['thumb'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "component SET thumb = '" . $this->db->escape($data['thumb']) . "' WHERE component_id = '" . (int)$component_id . "'");
        }

		$this->event->trigger('post.admin.component.edit', $component_id);
	}

	public function deleteComponent($component_id) {
		$this->event->trigger('pre.admin.component.delete', $component_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "component WHERE component_id = '" . (int)$component_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "component_category WHERE component_id = '" . (int)$component_id . "'");
		$this->event->trigger('post.admin.component.delete', $component_id);
	}


	public function getComponent($component_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "component c WHERE c.component_id =  '" . (int) $component_id . "'");
		return $query->row;
	}

	public function getComponents($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "component c";

		if (!empty($data['category_name'])) {
			$sql .= "WHERE c.name LIKE '" . $this->db->escape($data['category_name']) . "%'";
		}

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

	public function getComponentCategories($component_id) {
		$component_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "component_category WHERE component_id = '" . (int)$component_id . "'");

		foreach ($query->rows as $result) {
			$component_category_data[] = $result['category_id'];
		}

		return $component_category_data;
	}

    public function getComponentExcludeProducts($component_id) {
        $excluded_product_data = array();
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "component_excluded_product WHERE component_id = '" . (int)$component_id . "'");
        foreach ($query->rows as $result) {
            $excluded_product_data[] = $result['excluded_product_id'];
        }
        return $excluded_product_data;
    }


	public function getTotalComponents() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "component");

		return $query->row['total'];
	}
}
