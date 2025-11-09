<?php
class ModelSalePayment extends Model {

	public function getPayments($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "payment p";

        $implode = array();

		if (!empty($data['filter_payment_id'])) {
            $implode[] =  "p.payment_id = '" . (int) $data['filter_payment_id'] . "'";
		}

		if (!empty($data['filter_order_id'])) {
            $implode[] =  "p.order_id = '" . (int) $data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_total'])) {
            $implode[] =  "p.total = '" . (float) $data['filter_total'] . "'";
		}

		if (!empty($data['filter_status'])) {
            $implode[] =  "p.status =  '" . $this->db->escape($data['filter_status']) . "'";
		}

		if (!empty($data['filter_transaction_id'])) {
            $implode[] =  "(p.status LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%' OR p.payer_info LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%' OR p.comment LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%' OR p.tracking_no LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%')";
		}

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

		$sort_data = array(
			'p.payment_id',
			'p.order_id',
			'p.total',
			'p.status',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.date_added";
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

	public function getTotalPayments($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "payment p";

        $implode = array();

        if (!empty($data['filter_payment_id'])) {
            $implode[] =  "p.payment_id = '" . (int) $data['filter_payment_id'] . "'";
        }

        if (!empty($data['filter_order_id'])) {
            $implode[] =  "p.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_total'])) {
            $implode[] =  "p.total = '" . (float) $data['filter_total'] . "'";
        }

        if (!empty($data['filter_status'])) {
            $implode[] =  "p.status =  '" . $this->db->escape($data['filter_status']) . "'";
        }

        if (!empty($data['filter_transaction_id'])) {
            $implode[] =  "(p.status LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%' OR p.payer_info LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%' OR p.comment LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%' OR p.tracking_no LIKE  '%" . $this->db->escape($data['filter_transaction_id']) . "%')";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(p.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}