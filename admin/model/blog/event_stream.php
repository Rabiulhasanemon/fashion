<?php
class ModelBlogEventStream extends Model {

	public function addEventStream($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "event_stream SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW(), date_added = NOW()");

		$event_stream_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "event_stream SET image = '" . $this->db->escape($data['image']) . "' WHERE event_stream_id = '" . (int)$event_stream_id . "'");
		}

		foreach ($data['event_stream_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "event_stream_description SET event_stream_id = '" . (int)$event_stream_id . "', ".
				"language_id = '" . (int)$language_id . "', ".
				"name = '" . $this->db->escape($value['name']) . "', ".
				"description = '" . $this->db->escape($value['description']) . "', ".
				"meta_title = '" . $this->db->escape($value['meta_title']) . "', ".
				"meta_description = '" . $this->db->escape($value['meta_description']) . "', ".
				"meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}


		if (isset($data['event_stream_store'])) {
			foreach ($data['event_stream_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "event_stream_to_store SET event_stream_id = '" . (int)$event_stream_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX. "url_alias SET query = 'event_stream_id=" . (int)$event_stream_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('event_stream');

        $this->event->trigger('post.admin.event_stream.add', $event_stream_id);

        return $event_stream_id;
	}

	public function editEventStream($event_stream_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "event_stream SET sort_order = '" . (int)$data['sort_order'] . "', ".
			"status = '" . (int)$data['status'] . "', ".
			"date_modified = NOW() WHERE event_stream_id = '" . (int)$event_stream_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "event_stream SET image = '" . $this->db->escape($data['image']) . "' WHERE event_stream_id = '" . (int)$event_stream_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "event_stream_description WHERE event_stream_id = '" . (int)$event_stream_id . "'");

		foreach ($data['event_stream_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "event_stream_description SET event_stream_id = '" . (int)$event_stream_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "event_stream_to_store WHERE event_stream_id = '" . (int)$event_stream_id . "'");

		if (isset($data['event_stream_store'])) {
			foreach ($data['event_stream_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "event_stream_to_store SET event_stream_id = '" . (int)$event_stream_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_stream_id=" . (int)$event_stream_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'event_stream_id=" . (int)$event_stream_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

        $this->event->trigger('post.admin.event_stream.edit', $event_stream_id);
		$this->cache->delete('event_stream');
	}

	public function deleteEventStream($event_stream_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "event_stream WHERE event_stream_id = '" . (int)$event_stream_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "event_stream_description WHERE event_stream_id = '" . (int)$event_stream_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "event_stream_to_store WHERE event_stream_id = '" . (int)$event_stream_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "article_to_event_stream WHERE event_stream_id = '" . (int)$event_stream_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'event_stream_id=" . (int)$event_stream_id . "'");
		$this->cache->delete('event_stream');
	}

	public function getEventStream($event_stream_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT DISTINCT keyword FROM " . DB_PREFIX. "url_alias WHERE query = 'event_stream_id=" . (int)$event_stream_id . "') AS keyword FROM " . DB_PREFIX . "event_stream es LEFT JOIN " . DB_PREFIX . "event_stream_description esd ON (es.event_stream_id = esd.event_stream_id) WHERE es.event_stream_id = '" . (int)$event_stream_id . "' AND esd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getEventStreams($data = array()) {
		$sql = "SELECT * FROM "
			. DB_PREFIX . "event_stream es LEFT JOIN "
			. DB_PREFIX . "event_stream_description esd ON (es.event_stream_id = esd.event_stream_id)"
			. " WHERE esd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
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

	public function getEventStreamDescriptions($event_stream_id) {
		$event_stream_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event_stream_description WHERE event_stream_id = '" . (int)$event_stream_id . "'");

		foreach ($query->rows as $result) {
			$event_stream_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $event_stream_description_data;
	}

	public function getEventStreamStores($event_stream_id) {
		$event_stream_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event_stream_to_store WHERE event_stream_id = '" . (int)$event_stream_id . "'");

		foreach ($query->rows as $result) {
			$event_stream_store_data[] = $result['store_id'];
		}

		return $event_stream_store_data;
	}

	public function getTotalEventStreams() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "event_stream");

		return $query->row['total'];
	}
}
