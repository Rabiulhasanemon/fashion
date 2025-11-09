<?php
class ModelLocalisationRegion extends Model {
	public function addRegion($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "region SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', zone_id = '" . (int)$data['zone_id'] . "'");

		$this->cache->delete('region');
	}

	public function editRegion($region_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "region SET status = '" . (int)$data['status'] . "', name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', zone_id = '" . (int)$data['zone_id'] . "' WHERE region_id = '" . (int)$region_id . "'");

		$this->cache->delete('region');
	}

	public function deleteRegion($region_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "region WHERE region_id = '" . (int)$region_id . "'");

		$this->cache->delete('region');
	}

	public function getRegion($region_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "region WHERE region_id = '" . (int)$region_id . "'");

		return $query->row;
	}

	public function getRegions($data = array()) {
		$sql = "SELECT *, z.name, c.name AS zone FROM " . DB_PREFIX . "region z LEFT JOIN " . DB_PREFIX . "zone c ON (z.zone_id = c.zone_id)";

		$sort_data = array(
			'c.name',
			'z.name',
			'z.code'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.name";
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

	public function getRegionsByZoneId($zone_id) {
		$region_data = $this->cache->get('region.' . (int)$zone_id);

		if (!$region_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "region WHERE zone_id = '" . (int)$zone_id . "' AND status = '1' ORDER BY name");

			$region_data = $query->rows;

			$this->cache->set('region.' . (int)$zone_id, $region_data);
		}

		return $region_data;
	}

	public function getTotalRegions() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "region");

		return $query->row['total'];
	}

	public function getTotalRegionsByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "region WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}
}