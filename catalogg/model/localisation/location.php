<?php
class ModelLocalisationLocation extends Model {

    public function getLocation($location_id) {
        $query = $this->db->query("SELECT location_id, name, address,zone_id, geocode, telephone, fax, image, open, comment FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

        return $query ? $query->row : false;
    }

	public function getLocations() {
		$query = $this->db->query("SELECT location_id, name, address, zone_id, geocode, telephone, fax, image, open, comment  FROM " . DB_PREFIX . "location ORDER BY sort_order");

        return $query ? $query->rows : false;
	}
}