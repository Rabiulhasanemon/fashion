<?php
class ModelToolPcBuilder extends Model {
    public function getComponent($component_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "component c WHERE c.component_id =  '" . (int) $component_id . "'");
        return $query->row;
    }

    public function getPcProductsByComponent($pc_id, $component_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "pc_component_product p WHERE pc_id = '" . (int) $pc_id . "' AND component_id = '" . (int)$component_id . "'";
        $query = $this->db->query($sql);
        return $query->rows;
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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getDependentComponents($component_id){
        $sql = "SELECT * FROM " . DB_PREFIX . "component c WHERE c.depends_on ='" . (int) $component_id. "'" ;
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getProductsForComponent($data) {
        $sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p";

        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";
        $implode = array();
        if (isset($data['categories'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id";
            $implode[] = "ptc.category_id in (" . implode(", ", $data['categories'] ). ")";
        }

        if(isset($data['depends_on_product'])) {
            $implode[] = "p.product_id in (SELECT pr.product_id FROM " . DB_PREFIX . "product_compatible pr WHERE pr.compatible_id =  " . (int)$data['depends_on_product'] . ")";
        } else {
            $implode[] = "p.product_id not in (SELECT cep.excluded_product_id FROM " . DB_PREFIX . "component_excluded_product cep WHERE cep.component_id =  " . (int)$data['component_id'] . ")";
        }

        if (!empty($data['filter_filter'])) {
            $filter_groups = explode('_', $data['filter_filter']);
            foreach ($filter_groups as $filter) {
                $filter_list = array();
                $filters = explode(',', $filter);
                foreach ($filters as $filter_id) {
                    $filter_list[] = (int) $filter_id;
                }
                $implode[] = " p.product_id IN (SELECT pf.product_id FROM ". DB_PREFIX. "product_filter pf where pf.filter_id IN (" . implode(',', $filter_list) . "))";
            }
        }

        if (!empty($data['filter_name'])) {
            $name_filter_sql = "(";
            $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
            $is_not_first = false;
            foreach ($words as $word) {
                if($is_not_first) {
                    $name_filter_sql .= " AND ";
                }
                $is_not_first = true;
                $name_filter_sql .= "pd.name LIKE '%" . $this->db->escape($word) . "%'";
            }
            $name_filter_sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $name_filter_sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $name_filter_sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $name_filter_sql .= ")";
            $implode[] = $name_filter_sql;
        }

        if (!empty($data['filter_price_from'])) {
            $implode[] = "p.price >= '" . (float) $data['filter_price_from'] . "'";
        }

        if (!empty($data['filter_price_to'])) {
            $implode[] = "p.price <= '" . (float) $data['filter_price_to'] . "'";
        }


        if ($implode) {
            $sql .=  " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity > 0 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'" .  " AND " . implode(" AND ", $implode);
        }

        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'p.quantity',
            'p.price',
            'rating',
            'p.sort_order',
            'p.date_added'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } elseif ($data['sort'] == 'p.price') {
                $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC, LCASE(pd.name) DESC";
        } else {
            $sql .= " ASC, LCASE(pd.name) ASC";
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
        $product_data = array();
        $this->load->model("catalog/product");
        foreach ($query->rows as $result) {
            $product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
        }
        return $product_data;
    }

    public function getTotalProductsForComponent($data) {
        $sql = "SELECT COUNT(DISTINCT p.product_id) as total FROM " . DB_PREFIX . "product p";
        $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

        $implode = array();
        if (isset($data['categories'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id";
            $implode[] = "ptc.category_id in (" . implode(", ", $data['categories'] ). ")";
        }

        if(isset($data['depends_on_product'])) {
            $implode[] = "p.product_id in (SELECT pr.product_id FROM " . DB_PREFIX . "product_compatible pr WHERE pr.compatible_id =  " . (int)$data['depends_on_product'] . ")";
        } else {
            $implode[] = "p.product_id not in (SELECT cep.excluded_product_id FROM " . DB_PREFIX . "component_excluded_product cep WHERE cep.component_id =  " . (int)$data['component_id'] . ")";
        }

        if (!empty($data['filter_filter'])) {
            $filter_groups = explode('_', $data['filter_filter']);
            foreach ($filter_groups as $filter) {
                $filter_list = array();
                $filters = explode(',', $filter);
                foreach ($filters as $filter_id) {
                    $filter_list[] = (int) $filter_id;
                }
                $implode[] = " p.product_id IN (SELECT pf.product_id FROM ". DB_PREFIX. "product_filter pf where pf.filter_id IN (" . implode(',', $filter_list) . "))";
            }
        }

        if (!empty($data['filter_name'])) {
            $name_filter_sql = "(";
            $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
            $is_not_first = false;
            foreach ($words as $word) {
                if($is_not_first) {
                    $name_filter_sql .= " AND ";
                }
                $is_not_first = true;
                $name_filter_sql .= "pd.name LIKE '%" . $this->db->escape($word) . "%'";
            }
            $name_filter_sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $name_filter_sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $name_filter_sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
            $name_filter_sql .= ")";
            $implode[] = $name_filter_sql;
        }

        if (!empty($data['filter_price_from'])) {
            $implode[] = "p.price >= '" . (float) $data['filter_price_from'] . "'";
        }

        if (!empty($data['filter_price_to'])) {
            $implode[] = "p.price <= '" . (float) $data['filter_price_to'] . "'";
        }

        if ($implode) {
            $sql .=  " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.quantity > 0 AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'" .  " AND " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);
        return $query->row['total'];
    }

    public function isValidComponentProduct($product_id, $depends_on_product, $categories) {
        $sql = "SELECT COUNT(DISTINCT p.product_id) as total FROM " . DB_PREFIX . "product p";

        $implode = array();
        if ($categories) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category ptc ON p.product_id = ptc.product_id";
            $implode[] = "ptc.category_id in (" . implode(", ", $categories ). ")";
        }

        $implode[] = "p.product_id in (SELECT pr.product_id FROM " . DB_PREFIX . "product_compatible pr WHERE pr.compatible_id =  " . (int) $depends_on_product . ")";
        $implode[] = "p.product_id = '" . $product_id . "'";

        if ($implode) {
            $sql .= " WHERE p.status = 1 AND " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);
        return (int)$query->row['total'] > 0;
    }

    public function getComponentCategories($component_id) {
        $component_category_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "component_category WHERE component_id = '" . (int)$component_id . "'");

        foreach ($query->rows as $result) {
            $component_category_data[] = $result['category_id'];
        }

        return $component_category_data;
    }

    public function getCategories($data = array()) {
        $sql = "SELECT c1.category_id, cd1.name, c1.parent_id,c1.thumb, c1.sort_order FROM " . DB_PREFIX . "category c1 LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c1.category_id = cd1.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (isset($data['categories'])) {
            $sql .= " AND c1.category_id in (" . implode(", ", $data['categories'] ). ")";
        }
        $sql .= " ORDER BY sort_order  ASC";
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function savePc($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "pc` SET customer_id = '" . (int) $this->customer->getId()
            . "', name = '" . $this->db->escape($data['name'])
            . "', description = '" . $this->db->escape($data['description'])
            . "', date_added = NOW(), date_modified = NOW()");

        $pc_id = $this->db->getLastId();

        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "pc_component_product SET pc_id = '" . (int)$pc_id . "', product_id = '" . (int)$product['product_id'] . "', component_id = '" . (int)$product['component_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', price = '" . (float)$product['price'] . "', tax = '" . (float)$product['tax'] . "'");
            }
        }
    }

    public function getPc($pc_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "pc p WHERE pc_id = '" . (int) $pc_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function deletePc($pc_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "pc WHERE pc_id = '" . (int) $pc_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "pc_component_product WHERE pc_id = '" . (int) $pc_id . "'");
    }

    public function getPcs($start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $sql = "SELECT * FROM " . DB_PREFIX . "pc p WHERE customer_id = '" . (int) $this->customer->getId() . "' ORDER BY p.pc_id DESC LIMIT " . (int)$start . "," . (int)$limit;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalPc() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pc` WHERE customer_id = '" . (int) $this->customer->getId() . "'");
        return $query->row['total'];
    }

    public function getPcProduct($pc_id, $component_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "pc_component_product p WHERE pc_id = '" . (int) $pc_id . "' AND component_id = '" . (int)$component_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }
}