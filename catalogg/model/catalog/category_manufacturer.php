<?php
class ModelCatalogCategoryManufacturer extends Model {

    public function getCategoryManufacturer($category_id, $manufacturer_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category_manufacturer cm LEFT JOIN " . DB_PREFIX . "category_manufacturer_description cmd ON (cm.category_manufacturer_id = cmd.category_manufacturer_id) WHERE cm.category_id = '" . (int)$category_id . "' AND cm.manufacturer_id = '" . (int)$manufacturer_id . "' AND cmd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

	public function getCategoryManufacturers($data) {
        $sql = "SELECT *, (SELECT name FROM " . DB_PREFIX ."manufacturer m WHERE m.manufacturer_id = cm.manufacturer_id) manufacturer_name FROM " . DB_PREFIX . "category_manufacturer cm";

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

}