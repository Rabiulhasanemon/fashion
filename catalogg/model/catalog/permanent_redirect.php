<?php
class ModelCatalogPermanentRedirect extends Model {
    public function getPermanentRedirect($old_url) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "permanent_redirect WHERE old_url = '" . $this->db->escape($old_url) . "'");
        return $query->row;
    }

}