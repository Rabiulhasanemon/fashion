<?php
class ModelCatalogProductUpdateRequest extends Model {

	public function deleteProductUpdateRequest($product_update_request_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_update_request WHERE product_update_request_id = '" . (int)$product_update_request_id . "'");

		$this->cache->delete('product');

	}

	public function getProductUpdateReques($product_update_request_id) {
        $sql = "SELECT r.product_update_request_id, r.product_id, r.operator_id,  pd.name, CONCAT(o.firstname, ' ', o.lastname) AS operator_name, o.email as operator_email, p.quantity,p.status as status, r.new_status as new_status, p.price, r.new_price, p.regular_price, r.new_regular_price, p.sort_order, r.new_sort_order, p.stock_status_id, r.new_stock_status_id, r.date_added FROM "
            . DB_PREFIX . "product_update_request r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN "
            . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX ."operator o ON (r.operator_id = o.operator_id) WHERE r.product_update_request_id = '" . $product_update_request_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $query = $this->db->query($sql);
        return $query->row;
	}

	public function getProductUpdateRequests($data = array()) {
		$sql = "SELECT r.product_update_request_id, pd.name, CONCAT(o.firstname, ' ', o.lastname) AS operator_name, p.quantity,p.status as status, r.new_status as new_status, p.price, p.regular_price, p.sort_order, r.new_price, r.new_regular_price, p.stock_status_id, r.new_sort_order, r.new_stock_status_id, r.date_added FROM "
            . DB_PREFIX . "product_update_request r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN "
            . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX ."operator o ON (r.operator_id = o.operator_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_product'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
		}

		if (!empty($data['filter_operator'])) {
			$sql .= "AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_operator']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		$sort_data = array(
			'pd.name',
			'operator_name',
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

	public function getTotalProductUpdateRequests($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_update_request r LEFT JOIN " . DB_PREFIX . "product p ON (r.product_id = p.product_id) LEFT JOIN "
            . DB_PREFIX . "product_description pd ON (r.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX ."operator o ON (r.operator_id = o.operator_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_product'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_product']) . "%'";
        }

        if (!empty($data['filter_operator'])) {
            $sql .= "AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_operator']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(r.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

    public function approveRequest($request) {
	    $product_id = (int) $request['product_id'];
        if(!$product_id) { return; }
	    $this->load->model("catalog/product");
        $stock_status_index = array();
        $results = $this->model_catalog_product->getProductStockStatuses();
        foreach ($results as $result) {
            $stock_status_index[$result['stock_status_id']] = $result['name'];
        }
	    $quantity = null;
        if($request['new_stock_status_id']) {
            $quantity = $stock_status_index[$request['new_stock_status_id']] === "In Stock" ? 100 : 0;
        }
        $sql = "UPDATE ". DB_PREFIX ."product SET date_modified = now(), ";
        $implode = array();
        if($quantity !== null) {
            $implode[] = "quantity = '". $quantity ."'";
        }

        if($request['new_stock_status_id']) {
            $implode[] = "stock_status_id = '". $request['new_stock_status_id'] ."'";
        }

        if($request['new_price']) {
            $implode[] = "price = '". $request['new_price'] ."'";
        }

        if($request['new_regular_price']) {
            $implode[] = "regular_price = '". $request['new_regular_price'] ."'";
        }

        if($request['new_status'] !== null) {
            $implode[] = "status = '". $request['new_status'] ."'";
        }

        if($request['new_sort_order'] !== null) {
            $implode[] = "sort_order = '". (int) $request['new_sort_order'] ."'";
        }

        $sql .= implode(", ", $implode) . " WHERE product_id = '" . $product_id . "'";
        $this->db->query($sql);
        $sql = "INSERT INTO ". DB_PREFIX ."product_update_history SET product_id = '" . $request['product_id'] . "', operator_id = '" . $request['operator_id'] . "', stock_status_id = '" . $request['stock_status_id'] . "', status = '" . $request['status'] . "', price = '" . $request['price']. "', sort_order = '" . $request['sort_order'];
        if($request['new_status']) {
            $sql .= "', new_status = '" . $request['new_status'];
        }

        if($request['new_stock_status_id']) {
            $sql .= "', new_stock_status_id = '" . $request['new_stock_status_id'];
        }

        if($request['new_price']) {
            $sql .= "', new_price = '" . $request['new_price'];
        }

        if($request['new_regular_price']) {
            $sql .= "', new_regular_price = '" . $request['new_regular_price'];
        }

        if($request['new_sort_order']) {
            $sql .= "', new_sort_order = '" . $request['new_sort_order'];
        }

        $sql .= "', date_added = now()";
        $this->db->query($sql);
        $this->deleteProductUpdateRequest($request['product_update_request_id']);


        // Mail

        $this->load->language('mail/product_update_request');

        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'), html_entity_decode($request['name'], ENT_QUOTES, 'UTF-8'));

        $message  = $this->language->get('text_changed') . "\n" ;
        $message .= sprintf($this->language->get('text_product'), html_entity_decode($request['name'], ENT_QUOTES, 'UTF-8')) . "\n";
        $message .= sprintf($this->language->get('text_operator'), html_entity_decode($request['operator_name'], ENT_QUOTES, 'UTF-8')) . "\n";

        if($request['status']) {
            $status = (int)$request['quantity'] > 0 ? "In Stock" : (isset($stock_status_index[$request['stock_status_id']]) ? $stock_status_index[$request['stock_status_id']] : "");
        } else {
            $status = "Disabled";
        }

        $message .= sprintf($this->language->get('text_price'), $request['price']) . "\n";
        $message .= sprintf($this->language->get('text_status'), $status) . "\n";
        $message .= sprintf($this->language->get('sort_order'), $request['sort_order']) . "\n";

        $message .= sprintf($this->language->get('text_new_price'), ($request['new_price'] ?: "")) . "\n";
        $message .= sprintf($this->language->get('text_new_regular_price'), ($request['regular_price'] ?: "")) . "\n";
        $message .= sprintf($this->language->get('text_new_status'), ($request['new_status'] != null ? "Delete" : (isset($stock_status_index[$request['new_stock_status_id']]) ? $stock_status_index[$request['new_stock_status_id']] : ""))) . "\n";
        $message .= sprintf($this->language->get('new_sort_order'), $request['new_sort_order']) . "\n";

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($request['operator_email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setText($message);
        $mail->send();
    }

	public function approveRequests($product_update_requests) {
        foreach ($product_update_requests as $product_update_request_id) {
            $request = $this->getProductUpdateReques($product_update_request_id);
            $this->approveRequest($request);
        }
    }
}