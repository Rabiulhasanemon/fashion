<?php
class ModelCatalogNavigation extends Model {
	public function getNavigation($navigation_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "navigation n LEFT JOIN " . DB_PREFIX . "navigation_description nd ON (n.navigation_id = nd.navigation_id) WHERE n.navigation_id = '" . (int)$navigation_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1'");
		return $query->row;
	}

    public function getNavigationPath($navigation_id) {
        $query = $this->db->query("SELECT GROUP_CONCAT(path_id ORDER BY `level` SEPARATOR '_') as path FROM " . DB_PREFIX . "navigation_path WHERE navigation_id = '" . (int) $navigation_id . "' GROUP BY navigation_id");
        return $query->row['path'];
    }

    public function getNavigations($parent_id = 0, $limit = 0) {
        $sql = "SELECT * FROM " . DB_PREFIX . "navigation n LEFT JOIN " . DB_PREFIX . "navigation_description nd ON (n.navigation_id = nd.navigation_id)";

        $implode = array();
        if($parent_id !== null) {
            $implode[] = "n.parent_id = '" . (int)$parent_id . "'";
        }
        $implode[] = "nd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
                  AND n.status = '1'";

        $sql .= " WHERE " . implode(" AND ", $implode) . " 
              ORDER BY n.sort_order, LCASE(nd.name)";

        if($limit) {
            $sql .= " LIMIT 0," . (int)$limit;
        }
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCategoriesByNavigationId($parent_id = 0) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "navigation c WHERE c.parent_id = '" . (int)$parent_id . "' AND c.status = '1'");
        return $query->row['total'];
    }

	public function getNavigationTree() {
        $navigation_tree = $this->cache->get("navigation.tree");
        if($navigation_tree) { return $navigation_tree; }

	    $navigation_tree = array();

        $sql = "SELECT np.navigation_id AS navigation_id, GROUP_CONCAT(np.path_id ORDER BY np.level SEPARATOR '_') AS path, nd1.name, n1.parent_id, n1.image, n1.url FROM sr_navigation_path np"
        . " LEFT JOIN sr_navigation n1 ON (np.navigation_id = n1.navigation_id)"
        . " LEFT JOIN sr_navigation_description nd1 ON (np.navigation_id = nd1.navigation_id)"
        . " WHERE n1.status = '1' AND nd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY np.navigation_id ORDER BY n1.sort_order";

	    $navigations = $this->db->query($sql)->rows;

	    foreach ($navigations as $navigation) {
	        if(!isset($navigation_tree[$navigation['parent_id']])) {
                $navigation_tree[$navigation['parent_id']] = array();
            }
            $navigation_tree[$navigation['parent_id']][] = array(
                'navigation_id'  => $navigation['navigation_id'],
                'name'  => $navigation['name'],
                'thumb'  => $navigation['image'],
                'href'  => $navigation['url'],
            );
        }
        

        $this->cache->set("navigation.tree", $navigation_tree);
        return $navigation_tree;
    }

    public function getChildren($parent_id) {
        $navigation_tree = $this->getnavigationTree();
        if(isset($navigation_tree[$parent_id])) {
            return $navigation_tree[$parent_id];
        } else {
            return array();
        }
    }
}