<?php
class ModelFormEventParticipant extends Model {

	public function deleteEventParticipant($event_participant_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "event_participant WHERE event_participant_id = '" . (int)$event_participant_id . "'");
	}

	public function getEventParticipant($event_participant_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "event_participant WHERE event_participant_id = '" . (int)$event_participant_id . "'");

		return $query->row;
	}

	public function getEventParticipantByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "event_participant WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getEventParticipants($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "event_participant c";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "c.full_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'c.full_name',
			'c.email',
			'c.phone',
			'c.university',
			'c.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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

    public function getTotalEventParticipants($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "event_participant";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "c.full_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}