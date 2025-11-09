<?php
class ModelCatalogCategoryManufacturer extends Model {
	public function addCategoryManufacturer($data) {
		$this->event->trigger('pre.admin.category.manufacturer.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "category_manufacturer SET category_id = '" . (int) $data['category_id'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', filter_profile_id = '" . (int)$data['filter_profile_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', sort_order = '" . (int) $data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");

		$category_manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category_manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");
		}

        foreach ($data['category_manufacturer_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_manufacturer_description SET category_manufacturer_id = '" . (int)$category_manufacturer_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

		$this->cache->delete('category_manufacturer');

		$this->event->trigger('post.admin.category.manufacturer.add', $category_manufacturer_id);

		return $category_manufacturer_id;
	}

	public function editCategoryManufacturer($category_manufacturer_id, $data) {
		$this->event->trigger('pre.admin.category_manufacturer.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "category_manufacturer SET  category_id = '" . (int) $data['category_id'] . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', filter_profile_id = '" . (int)$data['filter_profile_id'] . "', `top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', sort_order = '" . (int) $data['sort_order'] . "', date_modified = NOW() WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "category_manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");
		}

        $this->db->query("DELETE FROM " . DB_PREFIX . "category_manufacturer_description WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");
        foreach ($data['category_manufacturer_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "category_manufacturer_description SET category_manufacturer_id = '" . (int)$category_manufacturer_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

		$this->cache->delete('category_manufacturer');
		$this->event->trigger('post.admin.category.manufacturer.edit', $category_manufacturer_id);
	}

	public function deleteCategoryManufacturer($category_manufacturer_id) {
		$this->event->trigger('pre.admin.category.manufacturer.delete', $category_manufacturer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_manufacturer WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_manufacturer_description WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");

		$this->cache->delete('category_manufacturer');

		$this->event->trigger('post.admin.category.manufacturer.delete', $category_manufacturer_id);
	}

	public function getCategoryManufacturer($category_manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (select cd.name FROM " . DB_PREFIX. "category_description cd WHERE cd.category_id = cm.category_id) as category, (SELECT name FROM " . DB_PREFIX ."manufacturer m WHERE m.manufacturer_id = cm.manufacturer_id) manufacturer FROM " . DB_PREFIX . "category_manufacturer cm WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");

		return $query->row;
	}

    public function getCategoryManufacturerDescriptions($category_manufacturer_id) {
        $category_manufacturer_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_manufacturer_description WHERE category_manufacturer_id = '" . (int)$category_manufacturer_id . "'");

        foreach ($query->rows as $result) {
            $category_manufacturer_description_data[$result['language_id']] = array(
                'name'       => $result['name'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'description'      => $result['description']
            );
        }

        return $category_manufacturer_description_data;
    }

	public function getCategoryManufacturers($data = array()) {
		$sql = "SELECT *, (select GROUP_CONCAT(cd.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') from sr_category_path cp LEFT JOIN sr_category_description cd on cp.path_id = cd.category_id where cp.category_id = cm.category_id) as category, (SELECT name FROM " . DB_PREFIX ."manufacturer m WHERE m.manufacturer_id = cm.manufacturer_id) manufacturer FROM " . DB_PREFIX . "category_manufacturer cm";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

        $implode = array();
        if (isset($data['filter_category_id'])) {
            $implode[] = "cm.category_id = '" . $data['filter_category_id'] . "'";
        }
        
        if (isset($data['filter_manufacturer_id'])) {
            $implode[] = "cm.manufacturer_id = '" . $data['filter_manufacturer_id'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

		$sort_data = array(
			'category',
			'manufacturer'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY category";
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


	public function getTotalCategoryManufacturers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_manufacturer");

		return $query->row['total'];
	}
}
