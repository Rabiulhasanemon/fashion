<?php
class ModelBlogCategory extends Model {

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) ".
			"LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.category_id = '" . (int)$category_id . "' ".
			"AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row;
	}

    public function getCategoryPath($category_id) {
        $query = $this->db->query("SELECT GROUP_CONCAT(path_id ORDER BY `level` SEPARATOR '_') as path FROM " . DB_PREFIX . "blog_category_path WHERE category_id = '" . (int) $category_id . "' GROUP BY category_id");
        return $query->row['path'];
    }

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) ".
			"LEFT JOIN " . DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' ".
			"AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  ".
			"AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}

	public function getTotalCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "blog_category c LEFT JOIN " . $this->DB_PREFIX . "blog_category_to_store c2s ON (c.category_id = c2s.category_id) ".
			"WHERE c.parent_id = '" . (int)$parent_id . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND c.status = '1'");

		return $query->row['total'];
	}

    public function getCategoryLayoutId($category_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . (int)$category_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function getCategoryTree() {
        $category_tree = $this->cache->get("blog_category.tree");
        if($category_tree) { return $category_tree; }

        $category_tree = array();

        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cp.path_id ORDER BY cp.level SEPARATOR '_') AS path, cd1.name, c1.parent_id, c1.image FROM sr_blog_category_path cp"
            . " LEFT JOIN sr_blog_category c1 ON (cp.category_id = c1.category_id)"
            . " LEFT JOIN sr_blog_category_description cd1 ON (cp.category_id = cd1.category_id)"
            . " WHERE c1.status = '1' AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id ORDER BY c1.sort_order";

        $categories = $this->db->query($sql)->rows;

        foreach ($categories as $category) {
            if(!isset($category_tree[$category['parent_id']])) {
                $category_tree[$category['parent_id']] = array();
            }
            $category_tree[$category['parent_id']][] = array(
                'category_id'  => $category['category_id'],
                'name'  => $category['name'],
                'image'  => $category['image'],
                'href'  => $this->url->link('blog/category', 'blog_category_id=' . $category['category_id'])
            );
        }


        $this->cache->set("blog_category.tree", $category_tree);
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
}