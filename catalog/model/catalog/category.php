<?php
class ModelCatalogCategory extends Model {
	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}

    public function getCategoryPath($category_id) {
        $query = $this->db->query("SELECT GROUP_CONCAT(path_id ORDER BY `level` SEPARATOR '_') as path FROM " . DB_PREFIX . "category_path WHERE category_id = '" . (int) $category_id . "' GROUP BY category_id");
        return $query->row['path'];
    }

	public function getCategories($parent_id = 0, $limit = 0) {
	    $sql = "SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)";

	    $implode = array();
	    if($parent_id !== null) {
            $implode[] = "c.parent_id = '" . (int) $parent_id . "'";
	    }
	    $implode[] = "cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1'";

        $sql .= " WHERE " . implode(" AND ", $implode) . " ORDER BY c.sort_order, LCASE(cd.name)";

	    if($limit) {
            $sql .= " LIMIT 0," . (int)$limit;
        }
	    $query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCategoryFilters($filter_profile_id) {
        $filter_group_query = $this->db->query("SELECT DISTINCT fg.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter_group_to_profile fgp LEFT JOIN " . DB_PREFIX. "filter_group fg ON (fgp.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fgp.filter_profile_id = '" . (int) $filter_profile_id ."' AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY fg.sort_order");
        $filter_group_data = array();
        foreach ($filter_group_query->rows as $filter_group) {
            $filter_data = array();

            $filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");

            foreach ($filter_query->rows as $filter) {
                $filter_data[] = array(
                    'filter_id' => $filter['filter_id'],
                    'name'      => $filter['name']
                );
            }

            if ($filter_data) {
                $filter_group_data[] = array(
                    'filter_group_id' => $filter_group['filter_group_id'],
                    'name'            => $filter_group['name'],
                    'filter'          => $filter_data
                );
            }
        }
		return $filter_group_data;
	}

	public function getCategoryLayoutId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row['total'];
	}

	public function getCategoryTree() {
        $this->load->model('catalog/category_manufacturer');
        $category_tree = $this->cache->get("category.tree");
        if($category_tree) { return $category_tree; }

	    $category_tree = array();

        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cp.path_id ORDER BY cp.level SEPARATOR '_') AS path, cd1.name, c1.parent_id, c1.thumb FROM sr_category_path cp"
        . " LEFT JOIN sr_category c1 ON (cp.category_id = c1.category_id)"
        . " LEFT JOIN sr_category_description cd1 ON (cp.category_id = cd1.category_id)"
        . " WHERE c1.status = '1' AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id ORDER BY c1.sort_order";

	    $categories = $this->db->query($sql)->rows;

	    foreach ($categories as $category) {
	        if(!isset($category_tree[$category['parent_id']])) {
                $category_tree[$category['parent_id']] = array();
            }
            $category_tree[$category['parent_id']][] = array(
                'category_id'  => $category['category_id'],
                'name'  => $category['name'],
                'thumb'  => $category['thumb'],
                'href'  => $this->url->link('product/category', 'category_id=' . $category['category_id'])
            );
        }

        $category_manufacturers = $this->model_catalog_category_manufacturer->getCategoryManufacturers(array());
        foreach ($category_manufacturers as $category_manufacturer) {
            if(!isset($category_tree[$category_manufacturer['category_id']])) {
                $category_tree[$category_manufacturer['category_id']] = array();
            }
            $category_tree[$category_manufacturer['category_id']][] = array(
                'name'  => $category_manufacturer['manufacturer_name'],
                'thumb'  => $category_manufacturer['thumb'],
                'href'  => $this->url->link('product/category', 'category_id=' . $category_manufacturer['category_id'] . '&manufacturer_id=' . $category_manufacturer['manufacturer_id'])
            );
        }

        $this->cache->set("category.tree", $category_tree);
        return $category_tree;
    }

    public function getChildren($parent_id) {
        $category_tree = $this->getCategoryTree();
        if(isset($category_tree[$parent_id])) {
            return $category_tree[$parent_id];
        } else {
            return array();
        }
    }

	// Category Modules Methods (for frontend)
	public function getCategoryModules($category_id) {
		$category_module_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_module WHERE category_id = '" . (int)$category_id . "' AND status = '1' ORDER BY sort_order ASC");

		foreach ($query->rows as $result) {
			$setting = json_decode($result['setting'], true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				$setting = array();
			}

			$category_module_data[] = array(
				'module_id' => $result['module_id'],
				'code' => $result['code'],
				'setting' => $setting,
				'sort_order' => $result['sort_order'],
				'status' => $result['status']
			);
		}

		return $category_module_data;
	}
}