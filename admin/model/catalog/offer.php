<?php
class ModelCatalogOffer extends Model {
	public function addOffer($data) {
		$this->event->trigger('pre.admin.offer.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "offer SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end'])  . "'");

		$offer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "offer SET image = '" . $this->db->escape($data['image']) . "' WHERE offer_id = '" . (int)$offer_id . "'");
		}

		foreach ($data['offer_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "offer_description SET offer_id = '" . (int)$offer_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', branch = '" . $this->db->escape($value['branch']) . "', short_description = '" . $this->db->escape($value['short_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}

		if(isset($data['offer_link'])) {
            foreach ($data['offer_link'] as $offer_link) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "offer_link SET offer_id = '" . (int)$offer_id . "', name = '" . $this->db->escape($offer_link['name']) . "', href = '" . $this->db->escape($offer_link['href']) . "'");
            }
        }
		$this->event->trigger('post.admin.offer.add', $offer_id);

		return $offer_id;
	}

	public function editOffer($offer_id, $data) {
		$this->event->trigger('pre.admin.offer.edit', $data);

        $this->db->query("UPDATE " . DB_PREFIX . "offer SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end'])  . "' WHERE offer_id = '" . (int)$offer_id . "'");

        if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "offer SET image = '" . $this->db->escape($data['image']) . "' WHERE offer_id = '" . (int)$offer_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "offer_description WHERE offer_id = '" . (int)$offer_id . "'");

        foreach ($data['offer_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "offer_description SET offer_id = '" . (int)$offer_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', branch = '" . $this->db->escape($value['branch']) . "', short_description = '" . $this->db->escape($value['short_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }

		$this->db->query("DELETE FROM " . DB_PREFIX . "offer_link WHERE offer_id = '" . (int)$offer_id . "'");
        if (isset($data['offer_link'])) {
            foreach ($data['offer_link'] as $offer_link) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "offer_link SET offer_id = '" . (int)$offer_id . "', name = '" . $this->db->escape($offer_link['name']) . "', href = '" . $this->db->escape($offer_link['href']) . "'");
            }
        }

		$this->event->trigger('post.admin.offer.edit', $offer_id);
	}

	public function deleteOffer($offer_id) {
		$this->event->trigger('pre.admin.offer.delete', $offer_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "offer WHERE offer_id = '" . (int)$offer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "offer_description WHERE offer_id = '" . (int)$offer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "offer_link WHERE offer_id = '" . (int)$offer_id . "'");
		$this->cache->delete('offer');

		$this->event->trigger('post.admin.offer.delete', $offer_id);
	}

	public function getOffer($offer_id) {
        $sql = "SELECT * FROM " . DB_PREFIX . "offer o LEFT JOIN " . DB_PREFIX . "offer_description od ON (o.offer_id = od.offer_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "' AND o.offer_id = '" . (int)$offer_id . "'";
        $query = $this->db->query($sql);
		return $query->row;
	}

	public function getOffers($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "offer o LEFT JOIN " . DB_PREFIX . "offer_description od ON (o.offer_id = od.offer_id) WHERE od.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_title'])) {
			$sql .= " AND od.title LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
		}

		$sort_data = array(
			'title',
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

	public function getOfferDescriptions($offer_id) {
		$offer_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "offer_description WHERE offer_id = '" . (int)$offer_id . "'");

		foreach ($query->rows as $result) {
			$offer_description_data[$result['language_id']] = array(
				'title'             => $result['title'],
				'branch'            => $result['branch'],
				'short_description' => $result['short_description'],
				'description'       => $result['description']
			);
		}

		return $offer_description_data;
	}

	public function getOfferLinks($offer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "offer_link WHERE offer_id = '" . (int)$offer_id . "'");
        return $query->rows;
	}


	public function getTotalOffers() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "offer");

		return $query->row['total'];
	}
	
}
