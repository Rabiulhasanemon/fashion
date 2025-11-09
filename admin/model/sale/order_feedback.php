<?php
class ModelSaleOrderFeedback extends Model {
	public function addOrderFeedback($data) {
		$this->event->trigger('pre.admin.order_feedback.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "order_feedback SET "
            . "order_id = '" . (int)$data['order_id']
            . "', comment = '" . $this->db->escape(strip_tags($data['comment']))
            . "', response = '" . (int)$data['response']
            . "', support_agent = '" . (int)$data['support_agent']
            . "', delivery_service = '" . (int)$data['delivery_service']
            . "', reorder = '" . (int)$data['reorder']
            . "', user_id = '" . $this->user->getId()
            . "', date_added = NOW()");

		$order_feedback_id = $this->db->getLastId();

		$this->event->trigger('post.admin.order_feedback.add', $order_feedback_id);

		return $order_feedback_id;
	}

	public function editOrderFeedback($order_feedback_id, $data) {
		$this->event->trigger('pre.admin.order_feedback.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "order_feedback SET "
            . "comment = '" . $this->db->escape(strip_tags($data['comment']))
            . "', response = '" . (int)$data['response']
            . "', support_agent = '" . (int)$data['support_agent']
            . "', delivery_service = '" . (int)$data['delivery_service']
            . "', reorder = '" . (int)$data['reorder']
            . "', user_id = '" . $this->user->getId()
            . "' WHERE order_feedback_id = '" . (int)$order_feedback_id . "'");


		$this->event->trigger('post.admin.order_feedback.edit', $order_feedback_id);
	}

	public function deleteOrderFeedback($order_feedback_id) {
		$this->event->trigger('pre.admin.order_feedback.delete', $order_feedback_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "order_feedback WHERE order_feedback_id = '" . (int)$order_feedback_id . "'");

		$this->event->trigger('post.admin.order_feedback.delete', $order_feedback_id);
	}

	public function getOrderFeedback($order_feedback_id) {
		$query = $this->db->query("SELECT *, CONCAT(o.firstname, ' ', o.lastname) AS customer_name, f.comment FROM " . DB_PREFIX . "order_feedback f LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = f.order_id) WHERE f.order_feedback_id = '" . (int)$order_feedback_id . "'");
		return $query->row;
	}

	public function getOrderFeedbacks($data = array()) {
		$sql = "SELECT *, CONCAT(o.firstname, ' ', o.lastname) AS customer_name FROM " . DB_PREFIX . "order_feedback f LEFT JOIN " . DB_PREFIX . "order o ON (o.order_id = f.order_id)";

		$implode = array();

		if (!empty($data['order_id'])) {
		    $implode[] = "f.order_id = '" . (int) $data['order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "((CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%' OR o.email LIKE '" . $this->db->escape($data['filter_customer']) . "%' OR o.telephone LIKE '" . $this->db->escape($data['filter_customer']) . "%')";
		}

		if (isset($data['filter_reorder']) && !is_null($data['filter_reorder'])) {
			$implode[] = "f.reorder = '" . (int)$data['filter_reorder'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(f.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

		$sort_data = array(
			'f.rating',
			'f.reorder',
			'f.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY f.date_added";
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

	public function getTotalOrderFeedbacks($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_feedback f LEFT JOIN " . DB_PREFIX . "order o ON (f.order_id = o.order_id)";

        $implode = array();

        if (!empty($data['filter_order_id'])) {
            $implode[] = "f.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $implode[] = "(CONCAT(o.firstname, ' ', o.lastname) LIKE '" . $this->db->escape($data['filter_customer']) . "%' OR o.email LIKE '" . $this->db->escape($data['filter_customer']) . "%' OR o.telephone LIKE '" . $this->db->escape($data['filter_customer']) . "%')";
        }

        if (isset($data['filter_reorder']) && !is_null($data['filter_reorder'])) {
            $implode[] = "f.reorder = '" . (int)$data['filter_reorder'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(f.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }
		$query = $this->db->query($sql);

		return $query->row['total'];
	}


}