<?php
class ModelCheckoutRestockRequest extends Model {
	public function addRestockRequest($product_id, $customer_id) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX . "restock_request WHERE customer_id = '" . (int) $customer_id . "' AND product_id = '" . (int) $product_id. "' AND status = 0");
		if($query->row) {
		    return $query->row["restock_request_id"];
        }
        $this->db->query("INSERT INTO " . DB_PREFIX . "restock_request SET customer_id = '" . (int) $customer_id . "' , product_id = '" . (int) $product_id. "' , status = 0, date_added = now()");

		return $this->db->getLastId();
	}
}