<?php
class ModelCatalogUrlAlias extends Model {
	public function getUrlAlias($keyword) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($keyword) . "'");

		return $query->row;
	}

	public function add($query, $keyword) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($query) . "', keyword = '" .  $this->db->escape($keyword) . "'");
    }

    public function delete($query) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = '" .  $this->db->escape($query) . "'");
    }
}