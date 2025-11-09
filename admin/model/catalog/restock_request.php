<?php
class ModelCatalogRestockRequest extends Model {

	public function deleteRestockRequest($restock_request_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "restock_request WHERE restock_request_id = '" . (int)$restock_request_id . "'");

		$this->cache->delete('product');

	}

	public function getRestockRequest($restock_request_id) {
        $sql = "SELECT r.restock_request_id, r.product_id, r.customer_id,  pd.name, CONCAT(c.firstname, ' ', c.lastname) AS customer_name, c.email, c.telephone, p.quantity, r.status as status, p.sort_order, p.stock_status_id, r.date_added FROM "
            . DB_PREFIX . "restock_request r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN "
            . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX ."customer c ON (r.customer_id = c.customer_id) WHERE r.restock_request_id = '" . $restock_request_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($sql);
        return $query->row;
	}

	public function getRestockRequests($data = array()) {
		$sql = "SELECT r.restock_request_id, pd.name, CONCAT(c.firstname, ' ', c.lastname) AS customer_name, c.telephone, p.quantity, r.status as status, p.stock_status_id, r.date_added FROM "
            . DB_PREFIX . "restock_request r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN "
            . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX ."customer c ON (r.customer_id = c.customer_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_product_id'])) {
            $sql .= " AND p.product_id = '" . (int)$data['filter_product_id'] . "%'";
        }

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= "AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

        if (!empty($data['filter_status'])) {
            $sql .= " AND r.status = '" . (int)$data['filter_status']  . "'";
        }
        
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'pd.name',
			'customer_name',
			'r.status',
			'r.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY r.date_added";
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

	public function getTotalRestockRequests($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "restock_request r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN "
            . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX ."customer c ON (r.customer_id = c.customer_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_product'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
        }

        if (!empty($data['filter_status'])) {
            $sql .= " AND r.status = '" . (int)$data['filter_status']  . "'";
        }
        
        if (!empty($data['filter_customer'])) {
            $sql .= "AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

    public function notifyRequest($request) {
        $sms_args = array(
            '%product' => $request["name"]
        );
        $sms_text = str_replace(array_keys($sms_args), array_values($sms_args), $this->config->get("config_restock_alert"));
        if($sms_text) $this->sms->send($request["telephone"], $sms_text);
        $this->db->query("UPDATE ". DB_PREFIX . "restock_request SET status = 1 WHERE restock_request_id = '" . $request["restock_request_id"] . "'");
    }

	public function notifyRequests($restock_requests) {
        foreach ($restock_requests as $restock_request_id) {
            $request = $this->getRestockRequest($restock_request_id);
            if($request["status"]) continue;
            $this->notifyRequest($request);
        }
    }

    public function notifyRequestByProduct($product_id) {
        $restock_requests = $this->getRestockRequests(array(
            'filter_product_id' => $product_id,
            'filter_status' => 0
        ));

        foreach ($restock_requests as $restock_request) {
            $request = $this->getRestockRequest($restock_request['restock_request_id']);
            if($request["status"]) continue;
            $this->notifyRequest($request);
        }
    }
}